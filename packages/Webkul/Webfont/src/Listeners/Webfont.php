<?php

namespace Webkul\Webfont\Listeners;

class Webfont {

    public function handleFrontFont()
    {
        if (core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_frontend')) {
            Event::listen('bagisto.shop.layout.head', function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('webfont::webfont');
            });
        }
    }

    public function handleBackFont()
    {
        if (core()->getConfigData('general.design.webfont.status') && core()->getConfigData('general.design.webfont.enable_backend')) {
            Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate('webfont::webfont');
            });
        }
    }
}