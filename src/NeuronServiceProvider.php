<?php
namespace Mappweb\LaravelNeuronAi;

use Illuminate\Support\ServiceProvider;
use Mappweb\LaravelNeuronAi\Console\Commands\AgentMakeCommand;

class NeuronServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/neuron-ai.php', 'neuron-ai'
        );

        // Register the main service
        $this->app->singleton('neuron-ai', function ($app) {
            return new \InspectorApm\NeuronAI\Agent();
        });

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                AgentMakeCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/neuron-ai.php' => config_path('neuron-ai.php'),
            ], 'neuron-ai-config');

            // Publish stubs
            $this->publishes([
                __DIR__.'/Console/stubs' => base_path('stubs/neuron-ai'),
            ], 'neuron-ai-stubs');

            // Publish all assets
            $this->publishes([
                __DIR__.'/../config/neuron-ai.php' => config_path('neuron-ai.php'),
                __DIR__.'/Console/stubs' => base_path('stubs/neuron-ai'),
            ], 'neuron-ai');
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return ['neuron-ai'];
    }
}
