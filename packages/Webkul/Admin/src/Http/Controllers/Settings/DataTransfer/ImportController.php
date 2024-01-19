<?php

namespace Webkul\Admin\Http\Controllers\Settings\DataTransfer;

use Illuminate\Http\JsonResponse;
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

        Event::dispatch('settings.data_transfer.imports.create.before');

        $import = $this->importRepository->create(
            array_merge([
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

        Event::dispatch('settings.data_transfer.imports.create.before', $import);

        session()->flash('success', trans('admin::app.settings.data-transfer.import.create.create-success'));

        return redirect()->route('admin.settings.data_transfer.imports.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function validateImport(int $id): JsonResponse
    {
        $import = $this->importRepository->find($id);

        $isValid = $this->importHelper
            ->setImport($import)
            ->validate();

        return new JsonResponse([
            'is_valid'             => $isValid,
            'processed_rows_count' => $this->importHelper->getProcessedRowsCount(),
            'invalid_rows_count'   => $this->importHelper->getErrorHelper()->getInvalidRowsCount(),
            'errors_count'         => $this->importHelper->getErrorHelper()->getErrorsCount(),
            'errors'               => $this->importHelper->getErrorHelper()->getAllErrors(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function start(int $id): JsonResponse
    {
        $import = $this->importRepository->find($id);

        $this->importHelper
            ->setImport($import)
            ->start();

        return new JsonResponse([]);
    }
}
