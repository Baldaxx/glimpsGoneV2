<?php

namespace GlimpsGoneV2\core;

require_once __DIR__ . '/../../config/config.php';

use PDO;

class Config
{
    public static function getDbConfig(): array
    {
        return DB;
    }

    public static function getAppName(): string
    {
        return APP_NAME;
    }

    public static function getPDO(): PDO
    {
        $db = self::getDbConfig();
        return new PDO("mysql:dbname={$db['name']};host={$db['host']}", $db['user'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
