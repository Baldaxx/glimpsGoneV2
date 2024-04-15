<?php

namespace GlimpsGoneV2\core;

use GuzzleHttp\Psr7\ServerRequest;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\controller\api\ArtisteDetailApiController;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\GallerieController;
use GlimpsGoneV2\controller\TotoController;
use GlimpsGoneV2\controller\TotoDetailController;
use GlimpsGoneV2\core\model\ControllerWithParam;


class App
{
    /**
     * Instance de la requête PSR7.
     */
    private ServerRequestInterface $request;

    /**
     * Instance unique de la classe App.
     */
    private static App|null $appInstance = null;

    /**
     * Instance unique de PDO.
     */
    private static PDO|null $pdoInstance = null;

    /**
     * Liste de tous les contrôleurs de l'application.
     */
    private array $controllers = [
        "GET /" => TotoController::class,
        "GET /toto" => TotoController::class,
        "GET /toto/{string}" => TotoDetailController::class,
        "GET /artiste/{int}" => ArtisteDetailController::class,
        "GET /api/artiste/{int}" => ArtisteDetailApiController::class,
        "GET /gallerie" => GallerieController::class,
        "POST /gallerie" => GallerieController::class,
        "DELETE /gallerie" => GallerieController::class,
        "PUT /gallerie" => GallerieController::class,
    ];

    /**
     * Constructeur privé de la classe.
     */
    private function __construct()
    {
        $this->request = ServerRequest::fromGlobals();
        var_dump($this->request);
        die();
    }

    /**
     * Retourne l'instance unique de la classe App.
     *
     * @return App L'instance unique de App
     */
    public static function getAppInstance(): App
    {
        if (self::$appInstance === null) {
            self::$appInstance = new App();
        }

        return self::$appInstance;
    }

    /**
     * Retourne l'instance unique de PDO.
     *
     * @return PDO L'instance unique de PDO.
     */
    public function getPDO(): PDO
    {
        if (self::$pdoInstance === null) {
            $db = Config::getDbConfig();
            $dsn = "mysql:dbname=" . $db["name"] . ";host=" . $db["host"];
            self::$pdoInstance = new PDO($dsn, $db["user"], $db["password"]);
        }

        return self::$pdoInstance;
    }

    /**
     * Exécute la requête en déléguant à un contrôleur approprié ou retourne une erreur si aucun contrôleur ne peut gérer la requête.
     */
    public function run(): void
    {
        $controller = $this->getController();
        if ($controller !== null) {
            $response = $controller->instantiate($this->request)->execute();
            $this->sendResponse($response);
        } else {
            $this->fatalError("Page non trouvée");
        }
    }

    #[NoReturn]
    public function fatalError(string $message): void
    {
        echo $message;
        die();
    }

    /**
     * Retourne le contrôleur capable de gérer la requête ou null si aucun contrôleur n'est trouvé.
     *
     * @return ControllerWithParam|null Le contrôleur ou null
     */
    private function getController(): ControllerWithParam|null
    {
        $requestedPath = str_replace("/" . Config::getAppName(), "", $this->request->getUri()->getPath());
        $requestedMethod = $this->request->getMethod();

        foreach ($this->controllers as $controllerPath => $controllerClass) {
            $pattern = $this->getPatternForPath($controllerPath);
            $method = $this->getMethodForPath($controllerPath);

            $paramsMatched = [];
            if (preg_match($pattern, $requestedPath, $paramsMatched) > 0 && $method === $requestedMethod) {
                array_shift($paramsMatched);
                return new ControllerWithParam($controllerClass, $paramsMatched);
            }
        }

        return null;
    }

    /**
     * Retourne le motif regex associé au chemin d'un contrôleur, remplaçant les jetons {string} ou {int} par le motif regex approprié.
     *
     * @param string $path Le chemin à convertir en motif regex
     * @return string Le motif regex
     */
    private function getPatternForPath(string $path): string
    {
        $pattern = str_replace("/", "\\/", $path);
        $pattern = str_replace("{string}", "(.+)", $pattern);
        $pattern = str_replace("{int}", "([0-9]+)", $pattern);
        $pattern = preg_replace('/(GET|POST|PUT|DELETE)\\s/', '', $pattern);

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
