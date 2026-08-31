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
use Webkul\Product\Enums\ElasticAuthEnum;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Enums\SearchEngineStatusEnum;

class ElasticConnection implements SearchEngineConnection
{
    /**
     * Where each recorded setting belongs, per connection. A connection reads its own
     * parameter names, so the API key is `key` on one and `api_key` on another.
     */
    protected const SETTINGS = [
        'default' => [
            'hosts' => 'hosts',
            'username' => 'user',
            'password' => 'pass',
        ],

        'api' => [
            'hosts' => 'hosts',
            'api_key' => 'key',
        ],

        'cloud' => [
            'cloud_id' => 'id',
            'api_key' => 'api_key',
            'username' => 'user',
            'password' => 'pass',
        ],
    ];

    /**
     * Apply the recorded settings, or the values passed in, to the Elasticsearch configuration.
     * Credentials the chosen authentication does not read are cleared.
     */
    public function configure(array $overrides = []): void
    {
        $settings = array_merge($this->settings(), array_filter(
            $overrides,
            fn ($value) => $value !== null,
        ));

        if (empty($settings)) {
            return;
        }

        if (! empty($settings['index_prefix'])) {
            config(['elasticsearch.index_prefix' => $settings['index_prefix']]);
        }

        $auth = $this->auth($settings);

        $name = $auth->connection();

        config(['elasticsearch.connection' => $name]);

        foreach (self::SETTINGS[$name] as $setting => $key) {
            if (! in_array($setting, $auth->settings())) {
                config(["elasticsearch.connections.{$name}.{$key}" => null]);

                continue;
            }

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
     * Ask the cluster who it is, optionally through settings that are not saved yet.
     */
    public function probe(array $overrides = []): array
    {
        if (! empty($overrides)) {
            $this->configure($overrides);
        }

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
     * Whether the given values describe the settings as they are already recorded.
     */
    public function describesRecorded(array $overrides): bool
    {
        $settings = $this->settings();

        foreach ($overrides as $setting => $value) {
            if ((string) ($settings[$setting] ?? '') !== (string) $value) {
                return false;
            }
        }

        return true;
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
     * How the settings say the cluster is reached and authenticated against.
     * Settings naming none are read the old way, by whichever credential is filled in.
     */
    protected function auth(array $settings): ElasticAuthEnum
    {
        if ($auth = ElasticAuthEnum::tryFrom((string) ($settings['auth_type'] ?? ''))) {
            return $auth;
        }

        if (! empty($settings['cloud_id'])) {
            return empty($settings['api_key'])
                ? ElasticAuthEnum::CLOUD_BASIC
                : ElasticAuthEnum::CLOUD_API_KEY;
        }

        if (! empty($settings['api_key'])) {
            return ElasticAuthEnum::API_KEY;
        }

        return empty($settings['username'])
            ? ElasticAuthEnum::NONE
            : ElasticAuthEnum::BASIC;
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
