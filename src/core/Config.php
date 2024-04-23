<?php

namespace GlimpsGoneV2\core;

require_once __DIR__ . '/../../config/config.php';

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
}
