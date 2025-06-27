<?php

namespace Mappweb\LaravelNeuronAi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \InspectorApm\NeuronAI\Agent make()
 * @method static \InspectorApm\NeuronAI\Agent provider(\InspectorApm\NeuronAI\Providers\AIProviderInterface $provider)
 * @method static \InspectorApm\NeuronAI\Agent instructions(string $instructions)
 * @method static \InspectorApm\NeuronAI\Agent tools(array $tools)
 * @method static string chat(string $message)
 * @method static array stream(string $message)
 *
 * @see \InspectorApm\NeuronAI\Agent
 */
class NeuronAI extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'neuron-ai';
    }
} 