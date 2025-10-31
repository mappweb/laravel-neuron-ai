<?php

namespace Mappweb\LaravelNeuronAi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static Agent
 * @method static \NeuronAI\Agent make()
 * @method static \NeuronAI\Agent provider(\NeuronAI\Providers\AIProviderInterface $provider)
 * @method static \NeuronAI\Agent instructions(string $instructions)
 * @method static \NeuronAI\Agent tools(array $tools)
 * @method static string chat(string $message)
 * @method static array stream(string $message)
 *
 * @see \NeuronAI\Agent
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