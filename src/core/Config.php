<?php

namespace GlimpsGoneV2\core;

require __DIR__ . '/../../config/config.php';

/**
 * Fonctions utilitaires pour lire le fichier de configuration /config/config.php.
 */
class Config
{
    /**
     * Retourne la configuration de la base de données spécifiée dans le fichier de configuration racine.
     *
     * @return array la configuration de la DB.
     */
    public static function getDbConfig(): array
    {
        return DB;
    }

    /**
     * Retourne le APP_NAME spécifié dans le fichier de configuration racine.
     *
     * @return string le APP_NAME.
     */
    public static function getAppName(): string
    {
        return APP_NAME;
    }
}
