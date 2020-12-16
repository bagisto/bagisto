<?php

namespace Webkul\Communication\Datagrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Communication\Repositories\NewsletterTemplateRepository;

class NewsletterTemplateDataGrid extends DataGrid
{
    /**
     * Newsletter queue repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterTemplateRepository
     */
    protected $newsletterTemplateRepository;

    /**
     * Main index key.
     *
     * @var string
     */
    protected $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Constructor.
     *
     * @param  Webkul\Communication\Repositories\NewsletterTemplateRepository $newsletterTemplateRepository
     * @return void
     */
    public function __construct(
        NewsletterTemplateRepository $newsletterTemplateRepository
    )
    {
        parent::__construct();
        $this->newsletterTemplateRepository = $newsletterTemplateRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->newsletterTemplateRepository->query()
            ->addSelect([
                'id', 'template_name', 'template_subject', 'sender_name', 'sender_email'
            ]);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('communication::app.newsletter-templates.template-form.template-id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'template_name',
            'label'      => trans('communication::app.newsletter-templates.template-form.template-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'template_subject',
            'label'      => trans('communication::app.newsletter-templates.template-form.template-subject'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sender_name',
            'label'      => trans('communication::app.newsletter-templates.template-form.sender-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sender_email',
            'label'      => trans('communication::app.newsletter-templates.template-form.sender-email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('communication::app.newsletter-queue.queue-newsletter'),
            'method' => 'GET',
            'route'  => 'communication.newsletter-queue.create',
            'icon'   => 'icon list-icon',
        ]);

        $this->addAction([
            'title'  => trans('communication::app.newsletter-templates.template-form.edit-template'),
            'method' => 'GET',
            'route'  => 'communication.newsletter-templates.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('communication::app.newsletter-templates.template-form.delete-template'),
            'method'       => 'DELETE',
            'route'        => 'communication.newsletter-templates.delete',
            'icon'         => 'icon trash-icon',
        ]);
    }
}