<?php

namespace GlimpsGoneV2\core;

use GlimpsGoneV2\core\model\ControllerWithParam;
use GuzzleHttp\Psr7\ServerRequest;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /**
     * @var ServerRequestInterface Instance de requête PSR7.
     */
    private ServerRequestInterface $request;

    /**
     * @var App|null Instance unique de la classe App.
     */
    private static App|null $appInstance = null;

    /**
     * @var PDO|null Instance unique de la classe PDO pour la connexion DB.
     */
    private static PDO|null $pdoInstance = null;

    /**
     * @var array|string[] Liste des contrôleurs de l'application.
     */
    private array $controllers = [];

    // Crée une instance avec la requête HTTP actuelle

    private function __construct()
    {
        $this->request = ServerRequest::fromGlobals();
    }

    // Retourne une instance unique de la classe App, en la créant si nécessaire
    public static function getAppInstance(): App
    {
        if (self::$appInstance === null) {
            self::$appInstance = new self();
            include __DIR__ . '/../router.php';
        }
        return self::$appInstance;
    }

    // Renvoie une instance de PDO pour la connexion à la base de données, en la créant si elle n'existe pas déjà
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

    // Gère la requête en appelant le contrôleur approprié et en envoyant sa réponse
    public function run(): void
    {
        $controller = $this->getController();
        if ($controller !== null) {
            $response = $controller->instantiate($this->request)->execute();
            $this->sendResponse($response);
        } else {
            $this->fatalError("cette page n'existe pas frérot !!!");
        }
    }

    function fatalError(string $message): void
    {
        header("Content-Type: application/json");
        http_response_code(500);
        echo json_encode(["error" => $message]);
        exit;
    }

    public function get(string $url, string $controller)
    {
        $this->controllers["GET $url"] = $controller;
    }

    public function post(string $url, string $controller)
    {
        $this->controllers["POST $url"] = $controller;
    }

    public function put(string $url, string $controller)
    {
        $this->controllers["PUT $url"] = $controller;
    }

    public function delete(string $url, string $controller)
    {
        $this->controllers["DELETE $url"] = $controller;
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
                // preg_match rajoute l'element recherché complet (le pattern) en premier element de $paramsMatched, donc on supprime le premier element de $paramsMatched
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

    // Extrait et retourne la méthode HTTP d'une route spécifiée
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
