<?php

namespace Mappweb\LaravelNeuronAi\Tools;

use NeuronAI\Tools\Tool as BaseTool;
use NeuronAI\Tools\ToolPropertyInterface;

class Tool extends BaseTool
{
    /**
     * @return ToolPropertyInterface[]
     */
    protected function properties(): array
    {
        return [];
    }

    protected function callback(): callable
    {
        return function (array $parameters) {};
    }

    public function execute(): void
    {
        if (method_exists($this, 'callback')) {
            $this->callback = $this->callback();
        }
        parent::execute();
    }
}