<?php

namespace Webkul\Communication\Datagrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Communication\Repositories\NewsletterQueueRepository;

class NewsletterQueueDataGrid extends DataGrid
{
    /**
     * Newsletter queue repository.
     *
     * @var Webkul\Communication\Repositories\NewsletterQueueRepository
     */
    protected $newsletterQueueRepository;

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
     * @param  Webkul\Communication\Repositories\NewsletterQueueRepository $newsletterQueueRepository
     * @return void
     */
    public function __construct(
        NewsletterQueueRepository $newsletterQueueRepository
    )
    {
        parent::__construct();
        $this->newsletterQueueRepository = $newsletterQueueRepository;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->newsletterQueueRepository->query()
            ->addSelect([
                'id', 'subject', 'sender_name', 'sender_email', 'queue_datetime'
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
            'index'      => 'subject',
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

        $this->addColumn([
            'index'      => 'queue_datetime',
            'label'      => trans('communication::app.newsletter-queue.queue-form.queue-date'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
    }
}