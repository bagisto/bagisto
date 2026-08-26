<?php

namespace Webkul\Product\Services\Search\Connections;

use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ProductCheckException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Transport\Exception\NoNodeAvailableException;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Facades\ElasticSearch;
use Webkul\Product\Contracts\SearchEngineConnection;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Enums\SearchEngineStatusEnum;

class ElasticConnection implements SearchEngineConnection
{
    /**
     * Where each recorded setting belongs in the Elasticsearch configuration.
     */
    protected const SETTINGS = [
        'hosts' => 'hosts',
        'username' => 'user',
        'password' => 'pass',
        'api_key' => 'key',
        'cloud_id' => 'id',
    ];

    /**
     * Apply the recorded settings to the Elasticsearch configuration.
     *
     * Read straight from the table, because this is settled during boot, before the
     * channel aware reader is available.
     */
    public function configure(): void
    {
        $settings = $this->settings();

        if (empty($settings)) {
            return;
        }

        if (! empty($settings['index_prefix'])) {
            config(['elasticsearch.index_prefix' => $settings['index_prefix']]);
        }

        $name = $this->connectionName($settings);

        config(['elasticsearch.connection' => $name]);

        foreach (self::SETTINGS as $setting => $key) {
            if (empty($settings[$setting])) {
                continue;
            }

            config([
                "elasticsearch.connections.{$name}.{$key}" => $setting === 'hosts'
                    ? array_map('trim', explode(',', $settings[$setting]))
                    : $settings[$setting],
            ]);
        }
    }

    /**
     * Ask the cluster who it is.
     */
    public function probe(): array
    {
        try {
            $info = ElasticSearch::info()->asArray();

            return [
                'status' => SearchEngineStatusEnum::AVAILABLE->value,
                'host' => $this->host(),
                'cluster' => $info['cluster_name'] ?? null,
                'version' => $info['version']['number'] ?? null,
            ];
        } catch (NoNodeAvailableException) {
            return $this->failed(SearchEngineStatusEnum::UNREACHABLE);
        } catch (AuthenticationException) {
            return $this->failed(SearchEngineStatusEnum::UNAUTHORIZED);
        } catch (ProductCheckException) {
            return $this->failed(SearchEngineStatusEnum::INCOMPATIBLE);
        } catch (ClientResponseException $e) {
            return $this->failed(
                in_array($e->getCode(), [401, 403])
                    ? SearchEngineStatusEnum::UNAUTHORIZED
                    : SearchEngineStatusEnum::UNREACHABLE
            );
        } catch (ServerResponseException) {
            return $this->failed(SearchEngineStatusEnum::UNREACHABLE);
        } catch (\InvalidArgumentException) {
            return $this->failed(SearchEngineStatusEnum::MISCONFIGURED);
        } catch (\Throwable $e) {
            report($e);

            return $this->failed(SearchEngineStatusEnum::UNREACHABLE);
        }
    }

    /**
     * Build a verdict for a connection that did not answer.
     */
    protected function failed(SearchEngineStatusEnum $status): array
    {
        return [
            'status' => $status->value,
            'host' => $this->host(),
        ];
    }

    /**
     * Which of the configured connections the recorded settings describe.
     */
    protected function connectionName(array $settings): string
    {
        if (! empty($settings['cloud_id'])) {
            return 'cloud';
        }

        if (! empty($settings['api_key'])) {
            return 'api';
        }

        return 'default';
    }

    /**
     * The recorded settings for this engine, straight from the table.
     */
    protected function settings(): array
    {
        $prefix = sprintf('search_engines.%s.settings.', SearchEngineEnum::ELASTIC->value);

        try {
            return DB::table('core_config')
                ->where('code', 'like', $prefix.'%')
                ->pluck('value', 'code')
                ->mapWithKeys(fn ($value, $code) => [str_replace($prefix, '', $code) => $value])
                ->all();
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * The first host of the configured connection.
     */
    protected function host(): ?string
    {
        $connection = config('elasticsearch.connection');

        $hosts = config("elasticsearch.connections.{$connection}.hosts", []);

        return is_array($hosts) ? ($hosts[0] ?? null) : $hosts;
    }
}
