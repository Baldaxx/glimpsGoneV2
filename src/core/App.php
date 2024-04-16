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


/**
 * Core application class. This class is a singleton, the instance can be retrieve with [App::getInstance()].
 */
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

        "GET /gallerie" => GallerieController::class,
        "POST /gallerie" => GallerieController::class,
        "DELETE /gallerie" => GallerieController::class,
        "PUT /gallerie" => GallerieController::class,
    ];

    /**
     * The constructor of the class.
     */
    private function __construct()
    {
        $this->request = ServerRequest::fromGlobals();
    }

    /**
     * Get the unique instance of the App class.
     *
     * @return App The unique instance of App
     */
    public static function getAppInstance(): App
    {
        if (self::$appInstance == null) {
            self::$appInstance = new App();
        }

        return self::$appInstance;
    }

    /**
     * Get the unique instance of the PDO class.
     *
     * @return PDO The unique instance of PDO.
     */
    function getPDO(): PDO
    {
        if (self::$pdoInstance == null) {
            $db = Config::getDbConfig();
            $dsn = "mysql:dbname=" . $db["name"] . ";host=" . $db["host"];
            self::$pdoInstance = new PDO($dsn, $db["user"], $db["password"]);
        }

        return self::$pdoInstance;
    }

    /**
     * Execute the request.
     * This function will delegate this job to the appropriate controller, or return a not found error if no controller
     * can handle the request.
     *
     * @return void
     */
    function run(): void
    {
        $controller = $this->getController();
        if ($controller != null) {
            $response = $controller->instantiate($this->request)->execute();
            $this->sendResponse($response);
        } else {
            $this->fatalError("Not found");
        }
    }

    function fatalError(string $message): void
    {
        echo $message;
        die();
    }

    /**
     * Return the controller able to handle the request or null if no controller are found.
     *
     * @return ControllerWithParam|null the controller or null
     */
    private function getController(): ControllerWithParam|null
    {
        $requestedPath = str_replace("/" . Config::getAppName(), "", $this->request->getUri()->getPath());
        $requestedMethod = $this->request->getMethod();

        foreach ($this->controllers as $controllerPath => $controllerClass) {
            $pattern = $this->getPatternForPath($controllerPath);
            $method = $this->getMethodForPath($controllerPath);

            $paramsMatched = [];
            if (preg_match($pattern, $requestedPath, $paramsMatched) > 0 && $method == $requestedMethod) {
                // First preg_match return is the entire of the path
                array_shift($paramsMatched);

                return new ControllerWithParam($controllerClass, $paramsMatched);
            }
        }

        return null;
    }

    /**
     * Return a regex pattern associate to the path of a controller.
     * Basically replace {string} or {int} token to the appropriate regex pattern, and escape / character.
     *
     * @param string $path the path to convert into pattern
     * @return string the regex pattern
     */
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
