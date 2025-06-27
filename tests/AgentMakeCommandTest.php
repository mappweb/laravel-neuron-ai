<?php

namespace Mappweb\LaravelNeuronAi\Tests;

use Illuminate\Support\Facades\File;

class AgentMakeCommandTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_basic_agent()
    {
        $this->artisan('make:agent', ['name' => 'TestAgent'])
            ->expectsOutput('Agent created successfully.')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(app_path('Agents/TestAgent.php')));
        
        $content = File::get(app_path('Agents/TestAgent.php'));
        $this->assertStringContainsString('class TestAgent extends Agent', $content);
        $this->assertStringContainsString('TODO: Configure your AI provider here', $content);
    }

    /** @test */
    public function it_can_generate_agent_with_provider()
    {
        $this->artisan('make:agent', [
            'name' => 'OpenAIAgent',
            '--provider' => 'openai'
        ])
            ->expectsOutput('Agent created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/OpenAIAgent.php'));
        $this->assertStringContainsString('use NeuronAI\Providers\OpenAIProvider;', $content);
        $this->assertStringContainsString('return OpenAIProvider::make();', $content);
    }

    /** @test */
    public function it_can_generate_agent_with_instructions()
    {
        $this->artisan('make:agent', [
            'name' => 'CustomAgent',
            '--instructions' => 'You are a helpful assistant'
        ])
            ->expectsOutput('Agent created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/CustomAgent.php'));
        $this->assertStringContainsString("return 'You are a helpful assistant';", $content);
    }

    /** @test */
    public function it_can_generate_agent_with_tools()
    {
        $this->artisan('make:agent', [
            'name' => 'ToolAgent',
            '--tools' => 'WebSearch,EmailSender'
        ])
            ->expectsOutput('Agent created successfully.')
            ->assertExitCode(0);

        $content = File::get(app_path('Agents/ToolAgent.php'));
        $this->assertStringContainsString('use NeuronAI\Tools\WebSearchTool;', $content);
        $this->assertStringContainsString('use NeuronAI\Tools\EmailSenderTool;', $content);
        $this->assertStringContainsString('WebSearchTool::make(),', $content);
        $this->assertStringContainsString('EmailSenderTool::make(),', $content);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the Agents directory exists
        if (!File::exists(app_path('Agents'))) {
            File::makeDirectory(app_path('Agents'), 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Clean up generated files
        if (File::exists(app_path('Agents'))) {
            File::deleteDirectory(app_path('Agents'));
        }

        parent::tearDown();
    }
} 