<?php

namespace GlimpsGoneV2\core;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PDO;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\api\ArtisteDetailApiController;
use GlimpsGoneV2\controller\GallerieController;
use GlimpsGoneV2\controller\TotoController;
use GlimpsGoneV2\controller\TotoDetailController;
use GlimpsGoneV2\controller\AjouterController;
use GlimpsGoneV2\controller\FaqController;
use GlimpsGoneV2\controller\InfosController;
use GlimpsGoneV2\core\model\ControllerWithParam;

class App
{
    /**
     * @var ServerRequestInterface PSR7 request instance.
     */
    private ServerRequestInterface $request;

    /**
     * @var App|null The single instance of the class.
     */
    private static App|null $appInstance = null;

    /**
     * @var PDO|null the single instance of PDO.
     */
    private static PDO|null $pdoInstance = null;

    /**
     * @var array|string[] List of all controllers of the application.
     */
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
        // var_dump($this->request);
        // die();
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
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
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

 function fatalError(string $message): void
    {
        echo $message;
        exit;
    }

    private function getController(): ControllerWithParam|null
    {
        $requestedPath = str_replace("/" . Config::getAppName(), "", $this->request->getUri()->getPath());
        $requestedMethod = $this->request->getMethod();

        foreach ($this->controllers as $controllerPath => $controllerClass) {
            $pattern = $this->getPatternForPath($controllerPath);
            $method = $this->getMethodForPath($controllerPath);

            $paramsMatched = [];
            if (preg_match($pattern, $requestedPath, $paramsMatched) > 0 && $method == $requestedMethod) {
                array_shift($paramsMatched);

                return new ControllerWithParam($controllerClass, $paramsMatched);
            }
        }
        return null;
    }

    private function getPatternForPath(string $path): string
    {
        $pattern = str_replace("/", "\\/", $path);
        $pattern = str_replace("{string}", "(.+)", $pattern);
        $pattern = str_replace("{int}", "([0-9]+)", $pattern);
        $pattern = str_replace("GET ", "", $pattern);
        $pattern = str_replace("POST ", "", $pattern);
        $pattern = str_replace("PUT ", "", $pattern);
        $pattern = str_replace("DELETE ", "", $pattern);

        return "#^$pattern$#";
    }

    private function getMethodForPath(string $path): string
    {
        return explode(" ", $path)[0];
    }

    private function sendResponse(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        http_response_code($response->getStatusCode());
        echo $response->getBody();
    }
}
