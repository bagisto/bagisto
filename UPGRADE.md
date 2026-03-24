# UPGRADE Guide

- [Upgrading To v2.5 From v2.4](#upgrading-to-v25-from-v24)

## High Impact Changes

- [Search Architecture Refactored to Engine-Agnostic Design](#search-architecture-refactored-to-engine-agnostic-design)

## Upgrading To v2.5 From v2.4

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Search Architecture Refactored to Engine-Agnostic Design

**Impact Probability: High**

Bagisto v2.4 replaces the tightly-coupled Elasticsearch search infrastructure in the `Product` package with an engine-agnostic design using the Strategy and Manager patterns. This enables swapping search engines (e.g., Algolia, Pinecone) without modifying core code.

#### Removed Classes

The following classes have been **deleted**:

| Removed | Replacement |
|---------|-------------|
| `Webkul\Product\Repositories\ElasticSearchRepository` | `Webkul\Product\Services\Search\Engines\ElasticSearchEngine` |
| `Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex` | `Webkul\Product\Jobs\Search\IndexProducts` |
| `Webkul\Product\Jobs\ElasticSearch\DeleteIndex` | `Webkul\Product\Jobs\Search\DeleteProducts` |

If your custom code imports any of these classes, update the imports to their replacements.

#### Removed Methods

| Removed | Replacement |
|---------|-------------|
| `ProductRepository::setSearchEngine(string)` | `ProductRepository::setSearchContext(SearchContextEnum)` |

#### New Enums

Two enums replace all hardcoded search-related strings:

**`Webkul\Product\Enums\SearchEngineEnum`** — search driver values:
- `SearchEngineEnum::DATABASE` (`'database'`)
- `SearchEngineEnum::ELASTIC` (`'elastic'`)

**`Webkul\Product\Enums\SearchContextEnum`** — search context values:
- `SearchContextEnum::STOREFRONT` (`'storefront'`)
- `SearchContextEnum::ADMIN` (`'admin'`)

#### New Contracts

Two contracts define the engine abstraction:

- `Webkul\Product\Contracts\SearchEngine` — search operations (`search`, `getSuggestions`, `getMaxPrice`, `findBySlug`)
- `Webkul\Product\Contracts\SearchIndexer` — indexing operations (`indexBatch`, `deleteBatch`, `reindexFull`)

#### New Services

| Class | Purpose |
|-------|---------|
| `Services\Search\SearchEngineManager` | Centralizes all config resolution — single source of truth for which engine/driver is active |
| `Services\Search\Engines\DatabaseEngine` | `SearchEngine` implementation for database mode |
| `Services\Search\Engines\ElasticSearchEngine` | `SearchEngine` implementation for Elasticsearch |
| `Services\Search\Indexers\ElasticSearchIndexer` | `SearchIndexer` implementation wrapping the existing `Helpers\Indexers\ElasticSearch` |
| `Services\Search\Indexers\NullIndexer` | No-op `SearchIndexer` for database mode (eliminates config guards) |

#### Migration Steps

1. **Update search engine config checks:**

   All scattered `core()->getConfigData('catalog.products.search.engine')` checks are now centralized in `SearchEngineManager`. If your custom code checks the search engine config directly, use the manager instead:

   ```diff
   - if (core()->getConfigData('catalog.products.search.engine') == 'elastic') {
   -     $searchEngine = core()->getConfigData('catalog.products.search.storefront_mode');
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::STOREFRONT);
   ```

   For admin context:

   ```diff
   - if (
   -     core()->getConfigData('catalog.products.search.engine') == 'elastic'
   -     && core()->getConfigData('catalog.products.search.admin_mode') == 'elastic'
   - ) {
   -     $searchEngine = 'elastic';
   - }
   - $this->productRepository->setSearchEngine($searchEngine ?? 'database');
   + use Webkul\Product\Enums\SearchContextEnum;
   + $this->productRepository->setSearchContext(SearchContextEnum::ADMIN);
   ```

2. **Update job dispatches:**

   ```diff
   - use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex;
   - use Webkul\Product\Jobs\ElasticSearch\DeleteIndex;
   + use Webkul\Product\Jobs\Search\IndexProducts;
   + use Webkul\Product\Jobs\Search\DeleteProducts;

   - UpdateCreateIndex::dispatch($productIds);
   + IndexProducts::dispatch($productIds);

   - DeleteIndex::dispatch($productIds);
   + DeleteProducts::dispatch($productIds);
   ```

   The new jobs use `SearchEngineManager` internally. When the master engine is `database`, the `NullIndexer` handles the call as a no-op — no config guards needed in your code.

3. **Update `ElasticSearchRepository` usage:**

   ```diff
   - use Webkul\Product\Repositories\ElasticSearchRepository;
   + use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;
   ```

   If you were calling `ElasticSearchRepository` methods directly, use `SearchEngineManager::engine()` instead:

   ```diff
   - $results = $this->elasticSearchRepository->search(...)
   + $engine = app(SearchEngineManager::class)->engine(SearchContextEnum::STOREFRONT);
   + $results = $engine->search($params, $options);
   ```

4. **Update Artisan indexer commands:**

   The `indexer:index` command now uses `search` instead of `elastic` as the type flag:

   ```diff
   - php artisan indexer:index --type=elastic --mode=full
   + php artisan indexer:index --type=search --mode=full
   ```

5. **Update `SearchEngineManager` usage for config checks:**

   If you need to check whether an external engine is enabled:

   ```php
   use Webkul\Product\Services\Search\SearchEngineManager;

   $manager = app(SearchEngineManager::class);

   // Check if external engine is configured
   if ($manager->isExternalEngineEnabled()) {
       // ...
   }

   // Get the resolved driver for a context
   use Webkul\Product\Enums\SearchContextEnum;
   use Webkul\Product\Enums\SearchEngineEnum;

   $driver = $manager->resolveDriver(SearchContextEnum::STOREFRONT);
   if ($driver === SearchEngineEnum::ELASTIC) {
       // ...
   }
   ```

#### Adding a Custom Search Engine

To add a new search engine (e.g., Algolia):

1. Implement `Webkul\Product\Contracts\SearchEngine`:

   ```php
   class AlgoliaEngine implements SearchEngine
   {
       public function search(array $params, array $options): array { /* ... */ }
       public function getSuggestions(?string $query): ?string { /* ... */ }
       public function getMaxPrice(array $params = []): float { /* ... */ }
       public function findBySlug(string $slug): ?int { /* ... */ }
   }
   ```

2. Implement `Webkul\Product\Contracts\SearchIndexer`:

   ```php
   class AlgoliaIndexer implements SearchIndexer
   {
       public function indexBatch(array $products): void { /* ... */ }
       public function deleteBatch(array $productIds): void { /* ... */ }
       public function reindexFull(): void { /* ... */ }
   }
   ```

3. Add a case to `SearchEngineEnum`:

   ```php
   case ALGOLIA = 'algolia';
   ```

4. Register named bindings in your service provider:

   ```php
   $this->app->singleton('product.search.engine.algolia', AlgoliaEngine::class);
   $this->app->singleton('product.search.indexer.algolia', AlgoliaIndexer::class);
   ```

The `SearchEngineManager` will automatically resolve your engine when the config value matches the enum case.
