<?php

namespace GlimpsGoneV2\core;  // Définit l'espace de noms du fichier pour organiser le code.

use GlimpsGoneV2\controller\AccueilController;
use GuzzleHttp\Psr7\ServerRequest;  // Inclut la classe ServerRequest pour gérer les requêtes HTTP.
use Psr\Http\Message\ResponseInterface;  // Interface pour la réponse HTTP.
use Psr\Http\Message\ServerRequestInterface;  // Interface pour la requête HTTP.
use PDO;  // Classe pour gérer la connexion à la base de données.

// Inclut différentes classes de contrôleurs utilisées dans l'application.
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
    private array $controllers = [
        "GET /" => AccueilController::class,
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
        $this->request = ServerRequest::fromGlobals();  // Crée une instance de requête basée sur les globales PHP. Une "global PHP" est une variable utilisable dans tout le script PHP, accessible depuis n'importe où dans le code sans avoir besoin de la déclarer à plusieurs reprises.
    }

    public static function getAppInstance(): App
    {
        if (self::$appInstance === null) {
            self::$appInstance = new self();  // Crée l'instance unique si elle n'existe pas déjà.
        }
        return self::$appInstance;
    }

    public function getPDO(): PDO
    {
        if (self::$pdoInstance === null) {
            $db = Config::getDbConfig();  // Récupère la configuration de la base de données.
            // Crée une instance de PDO avec la configuration de la base de données.
            self::$pdoInstance = new PDO("mysql:dbname={$db['name']};host={$db['host']}", $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$pdoInstance;
    }

    public function run(): void
    {
        $controller = $this->getController();  // Récupère le contrôleur pour l'URL demandée.
        if ($controller !== null) {
            $response = $controller->instantiate($this->request)->execute();  // Exécute la méthode du contrôleur.
            $this->sendResponse($response);  // Envoie la réponse HTTP.
        } else {
            $this->fatalError("Page non trouvée !!!");  // Gère les erreurs de page non trouvée.
        }
    }

    function fatalError(string $message): void
    {
        echo $message;  // Affiche le message d'erreur.
        exit;  // Arrête l'exécution du script.
    }

    private function getController(): ControllerWithParam|null
    {
        $requestedPath = str_replace("/" . Config::getAppName(), "", $this->request->getUri()->getPath());
        $requestedMethod = $this->request->getMethod();  // Méthode HTTP demandée.

        foreach ($this->controllers as $controllerPath => $controllerClass) {
            $pattern = $this->getPatternForPath($controllerPath);
            $method = $this->getMethodForPath($controllerPath);

            $paramsMatched = [];
            if (preg_match($pattern, $requestedPath, $paramsMatched) > 0 && $method == $requestedMethod) {
                array_shift($paramsMatched);  // Supprime le premier élément du tableau des correspondances.

                return new ControllerWithParam($controllerClass, $paramsMatched);
            }
        }
        return null;
    }

    private function getPatternForPath(string $path): string
    {
        $pattern = str_replace("/", "\\/", $path);
        $pattern = str_replace("{string}", "(.+)", $pattern);  // Remplace les placeholders par des regex. Un "placeholder" est un espace réservé dans une chaîne de caractères ou une requête SQL, généralement remplacé par une valeur spécifique lors de l'exécution du programme.
        $pattern = str_replace("{int}", "([0-9]+)", $pattern);
        $pattern = str_replace("GET ", "", $pattern);
        $pattern = str_replace("POST ", "", $pattern);
        $pattern = str_replace("PUT ", "", $pattern);
        $pattern = str_replace("DELETE ", "", $pattern);

        return "#^$pattern$#";  // Retourne le pattern final pour les correspondances d'URL.
    }

    private function getMethodForPath(string $path): string
    {
        return explode(" ", $path)[0];  // Retourne la méthode HTTP pour un chemin donné.
    }

    private function sendResponse(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);  // Envoie les en-têtes HTTP.
            }
        }
        http_response_code($response->getStatusCode());  // Définit le code de statut de la réponse HTTP.
        echo $response->getBody();  // Affiche le corps de la réponse.
    }
}
