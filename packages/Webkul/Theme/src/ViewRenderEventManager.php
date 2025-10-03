<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Event;

class ViewRenderEventManager
{
    /**
     * Contains all templates.
     *
     * @var array
     */
    protected $templates = [];

    /**
     * Event name.
     */
    protected $eventName;

    /**
     * Parameters passed with event.
     *
     * @var array
     */
    protected $params;

    /**
     * Fires event for rendering template.
     *
     * @param  array|null  $params
     * @return self
     */
    public function handleRenderEvent(string $eventName, mixed $params = null)
    {
        $this->templates = [];

        $this->eventName = $eventName;

        $this->params = $params ?? [];

        Event::dispatch($eventName, $this);

        return $this;
    }

    /**
     * Get all templates.
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Get event name.
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Get all params.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get specific param by name.
     *
     * @return mixed
     */
    public function getParam($name)
    {
        return optional($this->params)[$name];
    }

    /**
     * Add templates for render.
     *
     * @param  string  $template
     * @return void
     */
    public function addTemplate($template)
    {
        array_push($this->templates, $template);
    }

    /**
     * Renders templates.
     *
     * @return string
     */
    public function render()
    {
        $string = '';

        foreach ($this->templates as $template) {
            if (view()->exists($template)) {
                $string .= view($template, $this->params)->render();
            } elseif (is_string($template)) {
                $string .= $template;
            }
        }

        $this->resetState();

        return $string;
    }

    /**
     * Reset the manager state.
     *
     * @return void
     */
    protected function resetState()
    {
        $this->templates = [];

        $this->eventName = null;

        $this->params = null;
    }
}
