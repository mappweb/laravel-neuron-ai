<?php

namespace Mappweb\LaravelNeuronAi\Tests;

use Illuminate\Support\Facades\File;

class PromptMakeCommandTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_basic_prompt()
    {
        $this->artisan('make:prompt', ['name' => 'TestPrompt'])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        // Verify it's created in App\Agents\Prompts
        $this->assertTrue(File::exists(app_path('Agents/Prompts/TestPrompt.php')));
        
        $content = File::get(app_path('Agents/Prompts/TestPrompt.php'));
        $this->assertStringContainsString('namespace App\Agents\Prompts;', $content);
        $this->assertStringContainsString('class TestPrompt implements PromptInterface', $content);
        $this->assertStringContainsString('TODO: Define your constructor parameters', $content);
        $this->assertStringContainsString('TODO: Implement your prompt string logic', $content);
    }

    /** @test */
    public function it_can_generate_prompt_with_custom_parameters()
    {
        $this->artisan('make:prompt', [
            'name' => 'CustomPrompt',
            '--parameters' => 'string $title = "Default", array $items = []'
        ])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/Prompts/CustomPrompt.php'));
        $this->assertStringContainsString('namespace App\Agents\Prompts;', $content);
        $this->assertStringContainsString('public string $title = "Default",', $content);
        $this->assertStringContainsString('public array $items = [],', $content);
    }

    /** @test */
    public function it_can_generate_prompt_with_content()
    {
        $this->artisan('make:prompt', [
            'name' => 'ContentPrompt',
            '--content' => 'You are a helpful assistant. Please help with: {$this->task}'
        ])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/Prompts/ContentPrompt.php'));
        $this->assertStringContainsString('namespace App\Agents\Prompts;', $content);
        $this->assertStringContainsString("return 'You are a helpful assistant. Please help with: {\$this->task}';", $content);
    }

    /** @test */
    public function it_can_generate_prompt_with_custom_path()
    {
        $this->artisan('make:prompt', [
            'name' => 'CustomPathPrompt',
            '--path' => 'Custom\\MyPrompts'
        ])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(app_path('Custom/MyPrompts/CustomPathPrompt.php')));
        
        $content = File::get(app_path('Custom/MyPrompts/CustomPathPrompt.php'));
        $this->assertStringContainsString('namespace App\Custom\MyPrompts;', $content);
    }

    /** @test */
    public function it_can_generate_prompt_with_all_options()
    {
        $this->artisan('make:prompt', [
            'name' => 'CompletePrompt',
            '--parameters' => 'string $role = "assistant", string $task = "help"',
            '--content' => 'You are a {$this->role}. Your task is to {$this->task}.'
        ])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/Prompts/CompletePrompt.php'));
        $this->assertStringContainsString('namespace App\Agents\Prompts;', $content);
        $this->assertStringContainsString('public string $role = "assistant",', $content);
        $this->assertStringContainsString('public string $task = "help",', $content);
        $this->assertStringContainsString("return 'You are a {\$this->role}. Your task is to {\$this->task}.';", $content);
    }

    /** @test */
    public function it_automatically_adds_prompt_suffix()
    {
        $this->artisan('make:prompt', ['name' => 'Chat'])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(app_path('Agents/Prompts/ChatPrompt.php')));
        
        $content = File::get(app_path('Agents/Prompts/ChatPrompt.php'));
        $this->assertStringContainsString('class ChatPrompt implements PromptInterface', $content);
    }

    /** @test */
    public function it_can_use_short_parameter_options()
    {
        $this->artisan('make:prompt', [
            'name' => 'ShortOptionsPrompt',
            '-p' => 'string $name = "Default"',
            '-c' => 'Hello {$this->name}!'
        ])
            ->expectsOutput('Prompt created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/Prompts/ShortOptionsPrompt.php'));
        $this->assertStringContainsString('public string $name = "Default",', $content);
        $this->assertStringContainsString("return 'Hello {\$this->name}!';", $content);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the Agents/Prompts directory exists
        if (!File::exists(app_path('Agents/Prompts'))) {
            File::makeDirectory(app_path('Agents/Prompts'), 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Clean up generated files
        if (File::exists(app_path('Agents'))) {
            File::deleteDirectory(app_path('Agents'));
        }
        
        if (File::exists(app_path('Custom'))) {
            File::deleteDirectory(app_path('Custom'));
        }

        parent::tearDown();
    }
} 