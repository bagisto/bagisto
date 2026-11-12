<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class ImportCompleted extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-folder';

    /**
     * Keep what was imported, how many rows were processed and how many were skipped.
     */
    public function data(mixed $import): array
    {
        return [
            'import_id' => $import->id,
            'type' => $import->type,
            'processed' => (int) $import->processed_rows_count,
            'skipped' => (int) $import->invalid_rows_count,
        ];
    }

    /**
     * The headline of the notification, naming what was imported.
     */
    public function title(array $data): string
    {
        $importer = config('importers.'.($data['type'] ?? '').'.title');

        return trans('admin::app.notifications.types.import-completed.title', [
            'type' => $importer ? trans($importer) : ($data['type'] ?? ''),
        ]);
    }

    /**
     * How many rows were processed, and how many were skipped.
     */
    public function message(array $data): ?string
    {
        return empty($data['skipped'])
            ? trans('admin::app.notifications.types.import-completed.message', ['processed' => $data['processed'] ?? 0])
            : trans('admin::app.notifications.types.import-completed.message-skipped', [
                'processed' => $data['processed'] ?? 0,
                'skipped' => $data['skipped'],
            ]);
    }

    /**
     * The import's page.
     */
    public function url(array $data): ?string
    {
        return route('admin.settings.data_transfer.imports.import', $data['import_id']);
    }

    /**
     * Green for a clean import, amber when rows were skipped.
     */
    public function tone(array $data): string
    {
        return empty($data['skipped']) ? 'green' : 'amber';
    }
}
