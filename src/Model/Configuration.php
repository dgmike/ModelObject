<?php

namespace Model;

class Configuration {
    private $dns;
    private $username;
    private $password;
    private $options;

    public function __construct(string $dns, string $username = null, string $password = null, array $options = null)
    {
        $this->dns = $dns;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
    }

    public function getDns(): string
    {
        return $this->dns;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }
}
