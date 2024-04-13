<?php

namespace Nanicas\LegacyLaravelToolkit\Traits;

trait Configurable
{
    protected array $config = [];

    public function configure(array $data): self
    {
        $this->config = $data;
        return $this;
    }

    public function mergeConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    public function configureIndex($key, $data): self
    {
        $this->config[$key] = $data;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getConfigIndex($key): mixed
    {
        return $this->config[$key] ?? null;
    }

    public function isValidConfig($key): bool
    {
        return (!empty($this->config[$key]));
    }

    public function existsConfigIndex($key): bool
    {
        return (array_key_exists($key, $this->config));
    }
}
