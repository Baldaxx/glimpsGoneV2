<?php

namespace GlimpsGoneV2\core;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\ArtisteDetailApiController;
use GlimpsGoneV2\controller\GallerieController;
use GlimpsGoneV2\controller\TotoController;
use GlimpsGoneV2\controller\TotoDetailController;
use GlimpsGoneV2\controller\AjouterController;
use GlimpsGoneV2\controller\FaqController;
use GlimpsGoneV2\controller\InfosController;
use PDO;

class App
{
    private ServerRequestInterface $request;
    private static ?App $appInstance = null;
    private static ?PDO $pdoInstance = null;

    private array $controllers = [
        "GET /" => TotoController::class,
        "GET /toto" => TotoController::class,
        "GET /toto/{string}" => TotoDetailController::class,
        "GET /artiste/{int}" => ArtisteDetailController::class,
        "GET /api/artiste/{int}" => ArtisteDetailApiController::class,
        "GET /ajouter" => AjouterController::class,
        "GET /faq" => FaqController::class,
        "GET /infos" => InfosController::class,
        "GET /gallerie" => GallerieController::class,
        "POST /gallerie" => GallerieController::class,
        "DELETE /gallerie" => GallerieController::class,
        "PUT /gallerie" => GallerieController::class,
    ];

    private function __construct()
    {
        $this->request = ServerRequest::fromGlobals();
        $configDB = Config::getDbConfig();
        $nomApp = Config::getAppName();
    }

    public static function getAppInstance(): App
    {
        if (self::$appInstance === null) {
            self::$appInstance = new self();
        }
        return self::$appInstance;
    }

    public function getPDO(): PDO
    {
        if (self::$pdoInstance === null) {
            $db = Config::getDbConfig();
            self::$pdoInstance = new PDO("mysql:dbname={$db['name']};host={$db['host']}", $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION  // Configurer PDO pour qu'il lance des exceptions en cas d'erreur
            ]);
        }
        return self::$pdoInstance;
    }

    public function run(): void
    {
        $controller = $this->getController();
        if ($controller !== null) {
            $response = $controller->instantiate($this->request)->execute();
            $this->sendResponse($response);
        } else {
            $this->fatalError("Page non trouvée !!!");
        }
    }

    private function fatalError(string $message): void
    {
        echo $message;
        exit;
    }

    private function getController()
    {
        $requestedPath = $this->request->getUri()->getPath();
        $requestedMethod = $this->request->getMethod();

        foreach ($this->controllers as $controllerPath => $controllerClass) {
            $pattern = $this->getPatternForPath($controllerPath);
            if (preg_match($pattern, $requestedPath) && $requestedMethod === explode(" ", $controllerPath)[0]) {
                return new $controllerClass($this->request, []);
            }
        }
        return null;
    }

    private function getPatternForPath(string $path): string
    {
        $method = explode(" ", $path)[0];
        $uriPattern = substr($path, strlen($method) + 1);
        $uriPattern = preg_replace("/{int}/", '(\d+)', $uriPattern);
        $uriPattern = preg_replace("/{string}/", '([^/]+)', $uriPattern);
        return "#^" . str_replace("/", "\\/", $uriPattern) . "$#";
    }

    private function sendResponse(ResponseInterface $response): void
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
        echo $response->getBody();
    }
}
