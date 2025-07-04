<?php

namespace Mappweb\LaravelNeuronAi\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class PromptMakeCommand extends GeneratorCommand
{
    protected $name = 'make:prompt';
    
    protected $description = 'Create a new AI prompt class';
    
    protected $type = 'Prompt';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/prompt.stub';
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $this->option('path') 
            ? $rootNamespace . '\\' . str_replace('/', '\\', $this->option('path'))
            : $rootNamespace . '\\Agents\\Prompts';
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceToStringMethod($stub)
            ->replaceClass($stub, $name);

    }

    /**
     * Replace __toString method implementation.
     */
    protected function replaceToStringMethod(&$stub)
    {
        $content = $this->option('content');
        
        if (!$content) {
            $stub = str_replace(
                'DummyToStringImplementation',
                '// TODO: Implement your prompt string logic here' . PHP_EOL .
                '        // Example: return $this->content;' . PHP_EOL .
                '        return \'\';',
                $stub
            );
        } else {
            $stub = str_replace(
                'DummyToStringImplementation',
                "return '" . addslashes($content) . "';",
                $stub
            );
        }
        
        return $this;
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['content', 'c', InputOption::VALUE_OPTIONAL, 'Default content for the prompt'],
            ['path', null, InputOption::VALUE_OPTIONAL, 'Custom directory path relative to app namespace'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the prompt already exists'],
        ];
    }

    /**
     * Get the desired class name from the input.
     */
    protected function getNameInput(): string
    {
        $name = trim($this->argument('name'));
        
        // Ensure it ends with 'Prompt'
        if (!Str::endsWith($name, 'Prompt')) {
            $name .= 'Prompt';
        }
        
        return $name;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Show information if no options are provided
        if (!$this->hasAnyOptions()) {
            $this->info('Generating prompt with basic structure.');
            $this->comment('Use --content to customize the generated prompt.');
            $this->comment('Example: php artisan make:prompt MyPrompt --content="You are an AI Agent specialized in writing YouTube video summaries."');
        }

        return parent::handle();
    }

    /**
     * Check if any customization options were provided.
     */
    protected function hasAnyOptions(): bool
    {
        return !empty($this->option('content'));
    }
} 