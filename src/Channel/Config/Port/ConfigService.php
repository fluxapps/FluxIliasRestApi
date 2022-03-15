<?php

namespace FluxIliasRestApi\Channel\Config\Port;

use FluxIliasRestApi\Channel\Config\Command\DeleteConfigCommand;
use FluxIliasRestApi\Channel\Config\Command\GetConfigCommand;
use FluxIliasRestApi\Channel\Config\Command\SetConfigCommand;

class ConfigService
{

    private function __construct()
    {

    }


    public static function new() : /*static*/ self
    {
        return new static();
    }


    public function deleteConfig() : void
    {
        DeleteConfigCommand::new()
            ->deleteConfig();
    }


    public function getConfig(string $key)/* : mixed*/
    {
        return GetConfigCommand::new()
            ->getConfig(
                $key
            );
    }


    public function setConfig(string $key, /*mixed*/ $value) : void
    {
        SetConfigCommand::new()
            ->setConfig(
                $key,
                $value
            );
    }
}
