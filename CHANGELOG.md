# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

- **Breaking** — Refactored core search architecture to engine-agnostic design using Strategy + Manager patterns.

  - Added `SearchEngineEnum` and `SearchContextEnum` enums in `Webkul\Product\Enums`.
  - Added `SearchEngine` and `SearchIndexer` contracts in `Webkul\Product\Contracts`.
  - Added `SearchEngineManager` to centralize all search config resolution.
  - Added `DatabaseEngine` and `ElasticSearchEngine` implementations of `SearchEngine`.
  - Added `ElasticSearchIndexer` and `NullIndexer` implementations of `SearchIndexer`.
  - Added engine-agnostic jobs `Jobs\Search\IndexProducts` and `Jobs\Search\DeleteProducts`.
  - Removed `ElasticSearchRepository` — replaced by `ElasticSearchEngine`.
  - Removed `Jobs\ElasticSearch\DeleteIndex` and `Jobs\ElasticSearch\UpdateCreateIndex`.
  - Removed `ProductRepository::setSearchEngine()` — use `setSearchContext(SearchContextEnum)` instead.
  - Removed hardcoded `core()->getConfigData()` search engine checks from controllers, listeners, DataGrid, and validation classes.
  - Updated `DataTransfer\Importer` to use new engine-agnostic jobs.