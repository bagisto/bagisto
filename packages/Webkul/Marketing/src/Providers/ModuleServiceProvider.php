<?php

namespace Webkul\Marketing\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;
use Webkul\Marketing\Models\SearchSynonym;
use Webkul\Marketing\Models\SearchTerm;
use Webkul\Marketing\Models\Template;
use Webkul\Marketing\Models\URLRewrite;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Campaign::class,
        Event::class,
        SearchSynonym::class,
        SearchTerm::class,
        Template::class,
        URLRewrite::class,
    ];
}
