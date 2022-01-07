<?php

namespace Webkul\Sales\Generators;

use Webkul\Sales\Contracts\Sequencer as SequencerContract;

class Sequencer implements SequencerContract
{
    /**
     * Length.
     *
     * @var string
     */
    public $length;

    /**
     * Prefix.
     *
     * @var string
     */
    public $prefix;

    /**
     * Suffix.
     *
     * @var string
     */
    public $suffix;

    /**
     * Generator class.
     *
     * @var string
     */
    public $generatorClass;

    /**
     * Last id.
     *
     * @var int
     */
    public $lastId = 0;

    /**
     * Set length from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setLength($configKey)
    {
        $this->length = core()->getConfigData($configKey);
    }

    /**
     * Set prefix from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setPrefix($configKey)
    {
        $this->prefix = core()->getConfigData($configKey);
    }

    /**
     * Set suffix from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setSuffix($configKey)
    {
        $this->suffix = core()->getConfigData($configKey);
    }

    /**
     * Set generator class from the core config.
     *
     * @param  string  $configKey
     * @return void
     */
    public function setGeneratorClass($configKey)
    {
        $this->generatorClass = core()->getConfigData($configKey);
    }

    /**
     * Resolve generator class.
     *
     * @return string
     */
    public function resolveGeneratorClass()
    {
        if (
            $this->generatorClass !== ''
            && class_exists($this->generatorClass)
            && in_array(SequencerContract::class, class_implements($this->generatorClass), true)
        ) {
            return app($this->generatorClass)->generate();
        }

        return $this->generate();
    }

    /**
     * Create and return the next sequence number for e.g. an order.
     *
     * @return string
     */
    public function generate(): string
    {
        if ($this->length && ($this->prefix || $this->suffix)) {
            $number = ($this->prefix) . sprintf(
                "%0{$this->length}d",
                ($this->lastId + 1)
            ) . ($this->suffix);
        } else {
            $number = $this->lastId + 1;
        }

        return $number;
    }
}
