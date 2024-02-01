<?php

namespace Webkul\Admin\Http\Controllers\Settings\DataTransfer;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Admin\DataGrids\Settings\DataTransfer\ImportDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Repositories\ImportRepository;

class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ImportRepository $importRepository,
        protected Import $importHelper
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ImportDataGrid::class)->toJson();
        }

        return view('admin::settings.data-transfer.imports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::settings.data-transfer.imports.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'type'                => 'required|in:category,product,customer',
            'action'              => 'required:in:append,replace,delete',
            'validation_strategy' => 'required:in:stop-on-errors,skip-errors',
            'allowed_errors'      => 'required|integer|min:0',
            'field_separator'     => 'required',
            'file'                => 'required|mimes:csv',
        ]);

        Event::dispatch('data_transfer.imports.create.before');

        $import = $this->importRepository->create(
            array_merge(
                [
                    'file_path' => request()->file('file')->storeAs(
                        'imports',
                        time() . '-' . request()->file('file')->getClientOriginalName(),
                        'private'
                    ),
                ],
                request()->only([
                    'type',
                    'action',
                    'validation_strategy',
                    'validation_strategy',
                    'allowed_errors',
                    'field_separator',
                    'images_directory_path',
                ])
            )
        );

        Event::dispatch('data_transfer.imports.create.before', $import);

        session()->flash('success', trans('admin::app.settings.data-transfer.imports.create-success'));

        return redirect()->route('admin.settings.data_transfer.imports.index');
    }

    /**
     * Show the form for editing a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        return view('admin::settings.data-transfer.imports.edit', compact('import'));
    }

    /**
     * Update a resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        $this->validate(request(), [
            'type'                => 'required|in:category,product,customer',
            'action'              => 'required:in:append,replace,delete',
            'validation_strategy' => 'required:in:stop-on-errors,skip-errors',
            'allowed_errors'      => 'required|integer|min:0',
            'field_separator'     => 'required',
            'file'                => 'required|mimes:csv',
        ]);

        Event::dispatch('data_transfer.imports.update.before');

        $import = $this->importRepository->update(
            array_merge(
                [
                    'file_path'            => request()->file('file')->storeAs(
                        'imports',
                        time() . '-' . request()->file('file')->getClientOriginalName(),
                        'private'
                    ),
                    'state'                => 'pending',
                    'processed_rows_count' => 0,
                    'invalid_rows_count'   => 0,
                    'errors_count'         => 0,
                    'errors'               => null,
                    'error_file_path'      => null,
                ],
                request()->only([
                    'type',
                    'action',
                    'validation_strategy',
                    'validation_strategy',
                    'allowed_errors',
                    'field_separator',
                    'images_directory_path',
                ])
            ), $import->id
        );

        Event::dispatch('data_transfer.imports.update.before', $import);

        session()->flash('success', trans('admin::app.settings.data-transfer.imports.update-success'));

        return redirect()->route('admin.settings.data_transfer.imports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function import(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        $isValid = $this->importHelper
            ->setImport($import)
            ->isValid();

        $state = $import->state == Import::STATE_LINKING
            ? Import::STATE_COMPLETED
            : Import::STATE_PROCESSING;

        $stats = $this->importHelper->stats($state);

        $import->unsetRelations();

        return view('admin::settings.data-transfer.imports.import', compact('import', 'isValid', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function validateImport(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $isValid = $this->importHelper
            ->setImport($import)
            ->validate();

        $import = $this->importHelper->getImport();

        return new JsonResponse([
            'is_valid' => $isValid,
            'import'   => $import->unsetRelations(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function start(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->processed_rows_count) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.nothing-to-import'),
            ], 400);
        }

        $this->importHelper->setImport($import);

        if (! $this->importHelper->isValid()) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.not-valid'),
            ], 400);
        }

        if (config('queue.default') == 'sync') {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.setup-queue-error'),
            ], 400);
        }

        /**
         * Set the import state to processing
         */
        if ($import->state == Import::STATE_VALIDATED) {
            $this->importHelper->started();
        }

        /**
         * Get the first pending batch to import
         */
        $importBatch = $import->batches->where('state', Import::STATE_PENDING)->first();

        if ($importBatch) {
            /**
             * Start the import process
             */
            try {
                if ($import->process_in_queue) {
                    $this->importHelper->start();
                } else {
                    $this->importHelper->start($importBatch);
                }
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }

        } else {
            $this->importHelper->linking();
        }

        return new JsonResponse([
            'import' => $this->importHelper->getImport()->unsetRelations(),
            'stats'  => $this->importHelper->stats(Import::STATE_PROCESSING),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function link(int $id): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        if (! $import->processed_rows_count) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.nothing-to-import'),
            ], 400);
        }

        $this->importHelper->setImport($import);

        if (! $this->importHelper->isValid()) {
            return new JsonResponse([
                'message' => trans('admin::app.settings.data-transfer.imports.not-valid'),
            ], 400);
        }

        /**
         * Set the import state to linking
         */
        if ($import->state == Import::STATE_PROCESSING) {
            $this->importHelper->linking();
        }

        /**
         * Get the first processing batch to link
         */
        $importBatch = $import->batches->where('state', Import::STATE_PROCESSING)->first();

        /**
         * Set the import state to linking/completed
         */
        if ($importBatch) {
            /**
             * Start the resource linking process
             */
            try {
                $this->importHelper->link($importBatch);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], 400);
            }
        } else {
            /**
             * Set the import state to completed
             */
            $this->importHelper->completed();
        }

        return new JsonResponse([
            'import' => $this->importHelper->getImport()->unsetRelations(),
            'stats'  => $this->importHelper->stats(Import::STATE_COMPLETED),
        ]);
    }

    /**
     * Returns import stats
     */
    public function stats(int $id, string $state = Import::STATE_PROCESSING): JsonResponse
    {
        $import = $this->importRepository->findOrFail($id);

        $stats = $this->importHelper
            ->setImport($import)
            ->stats($state);

        return new JsonResponse([
            'import' => $import->unsetRelations(),
            'stats'  => $stats,
        ]);
    }

    /**
     * Download import error report
     */
    public function download(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        return Storage::disk('private')->download($import->file_path);
    }

    /**
     * Download import error report
     */
    public function downloadErrorReport(int $id)
    {
        $import = $this->importRepository->findOrFail($id);

        return Storage::disk('private')->download($import->error_file_path);
    }
}
