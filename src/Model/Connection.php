<?php

namespace Model;

use PDO;

class Connection {
    private static $configurations = [];
    private static $connections = [];

    static public function addConfiguration(String $name, Configuration $configuration): void
    {
        if (array_key_exists($name, self::$configurations)) {
            $errorMessage = sprinf('Configruation "%s" is already defined', $name);
            throw new Exception($errorMessage, 1);
        }

        self::$configurations[$name] = $configuration;
    }

    static public function removeConfiguration(string $name): void
    {
        self::getConfiguration($name);
        unset(self::$configurations[$name]);
    }

    static public function setConfiguration($configuration): void
    {
        self::$configurations = $configuration;
    }

    static public function getConfiguration($name): Configuration
    {
        if (array_key_exists($name, self::$configurations)) {
            $errorMessage = sprinf('Configruation "%s" is not defined', $name);
            throw new Exception($errorMessage, 1);
        }

        self::$configurations[$name];
    }

    static public function setConnection(string $name, Configuration $configuration): void
    {
        self::$connections[$name] = new PDO(
            $configuration->getDns(),
            $configuration->getUsername(),
            $configuration->getPassword()
        );
    }

    static public function getConnection($name): PDO
    {
        if (array_key_exists($name, self::$connections)) {
            return self::$connections[$name];
        }

        self::setConfiguration($name, self::getconfiguration($name));

        return self::getConnection($name);
    }

    static public function destroyConnection($name): void
    {
        if (!array_key_exists($name, self::$connections)) {
            $errorMessage = sprinf('Connection "%s" is not defined', $name);
            throw new Exception($errorMessage, 1);
        }

        self::$connections[$name] = null;
        unset(self::$connections[$name]);
    }
}
