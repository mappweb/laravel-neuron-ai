<?php

namespace Mappweb\LaravelNeuronAi\Tests;

use Mappweb\LaravelNeuronAi\NeuronServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            NeuronServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'NeuronAI' => \Mappweb\LaravelNeuronAi\Facades\NeuronAI::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Define environment setup for tests
        $app['config']->set('neuron-ai.default_provider', 'openai');
        $app['config']->set('neuron-ai.providers.openai.api_key', 'test-key');
    }
} 