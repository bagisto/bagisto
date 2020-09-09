<?php

namespace Webkul\Core;

use Illuminate\Support\Str;
use Konekt\Concord\Contracts\Convention;
use Konekt\Concord\Conventions\BaseConvention;

class CoreConvention extends BaseConvention implements Convention
{
    /**
     * @inheritdoc
     */
    public function modulesFolder(): string
    {
        return 'Modules';
    }

    /**
     * @inheritDoc
     */
    public function modelsFolder(): string
    {
        return 'Models';
    }

    /**
     * @inheritDoc
     */
    public function contractsFolder(): string
    {
        return 'Contracts';
    }

    /**
     * @inheritDoc
     */
    public function controllersFolder(): string
    {
        return 'Http/Controllers';
    }

    /**
     * @inheritDoc
     */
    public function requestsFolder(): string
    {
        return 'Http/Requests';
    }

    /**
     * @inheritDoc
     */
    public function resourcesFolder(): string
    {
        return 'Http/Resources';
    }

    /**
     * @inheritDoc
     */
    public function contractForRequest(string $requestClass): string
    {
        return sprintf(
            '%s\\Contracts\\Requests\\%s',
            $this->oneLevelUp($this->oneLevelUp($this->getNamespace($requestClass))),
            class_basename($requestClass)
        );
    }

    /**
     * @inheritDoc
     */
    public function contractForModel(string $modelClass): string
    {
        return sprintf(
            '%s\\Contracts\\%s',
                $this->oneLevelUp($this->getNamespace($modelClass)),
                class_basename($modelClass)
        );
    }

    /**
     * @inheritDoc
     */
    public function modelForRepository(string $repositoryClass): string
    {
        return Str::replaceLast('Repository', '', $repositoryClass);
    }

    /**
     * @inheritDoc
     */
    public function modelForProxy(string $proxyClass): string
    {
        return Str::replaceLast('Proxy', '', $proxyClass);
    }

    /**
     * @inheritDoc
     */
    public function repositoryForModel(string $modelClass): string
    {
        return $modelClass . 'Repository';
    }

    /**
     * @inheritDoc
     */
    public function proxyForModel(string $modelClass): string
    {
        return $modelClass . 'Proxy';
    }

    /**
     * @inheritDoc
     */
    public function manifestFile(): string
    {
        return 'resources/manifest.php';
    }

    /**
     * @inheritDoc
     */
    public function configFolder(): string
    {
        return 'resources/config';
    }

    /**
     * @inheritDoc
     */
    public function migrationsFolder(): string
    {
        return 'Database/Migrations';
    }

    /**
     * @inheritDoc
     */
    public function viewsFolder(): string
    {
        return 'resources/views';
    }

    /**
     * @inheritDoc
     */
    public function routesFolder(): string
    {
        return 'resources/routes';
    }

    /**
     * @inheritDoc
     */
    public function providersFolder(): string
    {
        return 'Providers';
    }

    /**
     * @inheritDoc
     */
    public function enumsFolder(): string
    {
        return 'Models';
    }

    /**
     * @inheritDoc
     */
    public function contractForEnum(string $enumClass): string
    {
        // Enums are in the same folder as models, so we use the existing method
        return $this->contractForModel($enumClass);
    }

    /**
     * @inheritDoc
     */
    public function proxyForEnum(string $enumClass): string
    {
        // Identical with model proxies
        return $this->proxyForModel($enumClass);
    }

    /**
     * @inheritDoc
     */
    public function enumForProxy(string $proxyClass): string
    {
        // Identical with model proxies
        return $this->modelForProxy($proxyClass);
    }

    /**
     * @inheritdoc
     */
    public function proxyForEnumContract(string $enumContract)
    {
        return $this->proxyForEnum(
                    $this->defaultEnumClassForContract($enumContract)
                );
    }

    /**
     * @inheritdoc
     */
    public function proxyForModelContract(string $modelContract): string
    {
        return $this->proxyForModel(
            $this->defaultModelClassForContract($modelContract)
        );
    }

    /**
     * Returns the convention's default enum class for an enum contract
     *
     * @param $enumContract
     *
     * @return string
     */
    protected function defaultEnumClassForContract($enumContract)
    {
        return
            $this->oneLevelUp($this->getNamespace($enumContract))
            . '\\' . $this->enumsFolder()
            . '\\' . class_basename($enumContract);
    }

    /**
     * Returns the convention's default model class for a model contract
     *
     * @param $modelContract
     *
     * @return string
     */
    protected function defaultModelClassForContract($modelContract)
    {
        return
            $this->oneLevelUp($this->getNamespace($modelContract))
            . '\\' . $this->modelsFolder()
            . '\\' . class_basename($modelContract);
    }
}