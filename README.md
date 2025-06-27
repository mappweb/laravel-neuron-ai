# Laravel NeuronAI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mappweb/laravel-neuron-ai.svg?style=flat-square)](https://packagist.org/packages/mappweb/laravel-neuron-ai)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mappweb/laravel-neuron-ai/run-tests?label=tests)](https://github.com/mappweb/laravel-neuron-ai/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mappweb/laravel-neuron-ai/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mappweb/laravel-neuron-ai/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mappweb/laravel-neuron-ai.svg?style=flat-square)](https://packagist.org/packages/mappweb/laravel-neuron-ai)

A Laravel wrapper for [neuron-ai](https://github.com/inspector-apm/neuron-ai) that facilitates the integration of the AI agents framework into Laravel applications.

## Official Documentation

**[Go to official documentation](https://neuron.inspector.dev/)**

## Guides and Tutorials

Check out the technical guides and tutorials file to learn how to create your artificial intelligence agents with Neuron.
https://docs.neuron-ai.dev/resources/guides-and-tutorials.

## Features

- 🚀 **Easy installation** - Automatic configuration with auto-discovery
- 🎨 **Artisan commands** - Generate AI agent classes with `make:agent` and prompt classes with `make:prompt`
- ⚙️ **Flexible configuration** - Support for multiple AI providers
- 🔧 **Facade included** - Simple access through `NeuronAI::`
- 📝 **Flexible prompts** - Create custom prompt classes with any structure you need
- 🧪 **Tests included** - Complete test suite
- 📚 **Complete documentation** - Detailed examples and guides

## Supported AI Providers

- **OpenAI** (GPT-4, GPT-3.5-turbo)
- **Anthropic** (Claude 3)
- **Google Gemini**
- **Ollama** (Local models)

## Installation

### Via Composer

```bash
composer require mappweb/laravel-neuron-ai
```

### Publish Configuration

```bash
# Publish configuration file
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai-config"

# Publish stubs for customization
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai-stubs"

# Publish all files
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai"
```

### Configuration

Add the following variables to your `.env` file:

```env
# General configuration
NEURON_AI_DEFAULT_PROVIDER=openai

# OpenAI
OPENAI_API_KEY=your-openai-api-key
OPENAI_MODEL=gpt-4

# Anthropic
ANTHROPIC_API_KEY=your-anthropic-api-key
ANTHROPIC_MODEL=claude-3-sonnet-20240229

# Gemini
GEMINI_API_KEY=your-gemini-api-key

# Ollama (for local models)
OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_MODEL=llama2
```

## Usage

### Generate Agents with Artisan

```bash
# Basic agent
php artisan make:agent ChatAgent

# Agent with specific provider
php artisan make:agent ChatAgent --provider=openai

# Agent with custom instructions
php artisan make:agent ChatAgent --instructions="You are a helpful customer support agent"

# Agent with tools
php artisan make:agent ChatAgent --tools="WebSearch,EmailSender"

# Complete agent
php artisan make:agent CustomerSupportAgent \
    --provider=anthropic \
    --instructions="You are a customer support agent" \
    --tools="WebSearch,DatabaseQuery"
```

### Generate Prompts with Artisan

```bash
# Basic prompt
php artisan make:prompt ChatPrompt

# Prompt with custom parameters
php artisan make:prompt BlogPrompt --parameters="string $topic = 'Technology', int $wordCount = 500"

# Prompt with content
php artisan make:prompt GreetingPrompt --content="Hello! I'm {$this->name}, your assistant."

# Complete prompt
php artisan make:prompt ContentPrompt \
    --parameters="string $title = 'Default', array $sections = []" \
    --content="Create content about {$this->title} with sections: {implode(', ', $this->sections)}"

# Prompt with custom path
php artisan make:prompt CustomPrompt --path="Custom\\Prompts"
```

### Generated Agent Example

```php
<?php

declare(strict_types=1);

namespace App\Agents;

use NeuronAI\Agent;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\OpenAIProvider;

class ChatAgent extends Agent
{
    public function provider(): AIProviderInterface
    {
        return OpenAIProvider::make();
    }

    public function instructions(): string
    {
        return 'You are a helpful AI assistant.';
    }
}
```

### Usage in Controllers

```php
<?php

namespace App\Http\Controllers;

use App\Agents\ChatAgent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $agent = new ChatAgent();
        
        $response = $agent->chat($request->input('message'));
        
        return response()->json([
            'response' => $response
        ]);
    }
}
```

### Usage with Facade

```php
use Mappweb\LaravelNeuronAi\Facades\NeuronAI;

// Basic usage
$agent = NeuronAI::make()
    ->provider(OpenAIProvider::make())
    ->instructions('You are a helpful assistant');

$response = $agent->chat('Hello!');

// With configuration from config
$agent = NeuronAI::make()
    ->provider(config('neuron-ai.default_provider'))
    ->instructions(config('neuron-ai.agents.default_instructions'));
```

### Advanced Configuration

The `config/neuron-ai.php` file allows you to configure:

```php
return [
    // Default provider
    'default_provider' => env('NEURON_AI_DEFAULT_PROVIDER', 'openai'),
    
    // Providers configuration
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],
        // ... other providers
    ],
    
    // Agents configuration
    'agents' => [
        'default_instructions' => env('NEURON_AI_DEFAULT_INSTRUCTIONS', 'You are a helpful AI assistant.'),
        'default_namespace' => 'App\\Agents',
        'timeout' => env('NEURON_AI_TIMEOUT', 30),
    ],
    
    // Logging and cache
    'logging' => [
        'enabled' => env('NEURON_AI_LOGGING_ENABLED', true),
        'channel' => env('NEURON_AI_LOG_CHANNEL', 'default'),
    ],
    
    'cache' => [
        'enabled' => env('NEURON_AI_CACHE_ENABLED', false),
        'ttl' => env('NEURON_AI_CACHE_TTL', 3600),
    ],
];
```

### Generated Prompt Example

```php
<?php

declare(strict_types=1);

namespace App\Agents\Prompts;

use Mappweb\LaravelNeuronAi\Prompts\PromptInterface;

class BlogPrompt implements PromptInterface
{
    public function __construct(
        public string $topic = 'Technology',
        public int $wordCount = 500,
        public array $keywords = [],
    ) {
        //
    }

    public function __toString(): string
    {
        return "Write a {$this->wordCount}-word blog post about {$this->topic}. Include keywords: " . implode(', ', $this->keywords);
    }
}
```

### `make:agent` Command Options

| Option | Description | Example |
|--------|-------------|---------|
| `--provider` | AI provider to use | `--provider=openai` |
| `--instructions` | Custom instructions | `--instructions="You are helpful"` |
| `--tools` | Tools (comma-separated) | `--tools="WebSearch,Email"` |
| `--path` | Custom directory | `--path="CustomAgents"` |
| `--force` | Overwrite existing files | `--force` |

### `make:prompt` Command Options

| Option | Description | Example |
|--------|-------------|---------|
| `--parameters` (`-p`) | Constructor parameters | `--parameters="string $title = 'Default'"` |
| `--content` (`-c`) | Default content/template | `--content="Hello {$this->name}!"` |
| `--path` | Custom directory | `--path="Custom\\Prompts"` |
| `--force` (`-f`) | Overwrite existing files | `--force` |

### Available Providers

- `openai` → OpenAIProvider
- `anthropic` → AnthropicProvider  
- `gemini` → GeminiProvider
- `ollama` → OllamaProvider

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Contributing

Contributions are welcome. Please review [CONTRIBUTING](CONTRIBUTING.md) for more details.

## Security

If you discover security vulnerabilities, please send an email to diego.toscanof@gmail.com.

## Credits

- [Neuron-ai.dev](https://github.com/inspector-apm/neuron-ai)
- [Diego Toscano](https://github.com/mappweb)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
