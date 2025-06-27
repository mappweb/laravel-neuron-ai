# Laravel NeuronAI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mappweb/laravel-neuron-ai.svg?style=flat-square)](https://packagist.org/packages/mappweb/laravel-neuron-ai)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mappweb/laravel-neuron-ai/run-tests?label=tests)](https://github.com/mappweb/laravel-neuron-ai/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mappweb/laravel-neuron-ai/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mappweb/laravel-neuron-ai/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mappweb/laravel-neuron-ai.svg?style=flat-square)](https://packagist.org/packages/mappweb/laravel-neuron-ai)

Un wrapper de Laravel para [neuron-ai](https://github.com/inspector-apm/neuron-ai) que facilita la integración del framework de agentes AI en aplicaciones Laravel.


## Documentación oficial

**[Ir a la documentación oficial](https://neuron.inspector.dev/)**

## Guías y tutoriales

CConsulte el archivo de guías técnicas y tutoriales para aprender a crear sus agentes de inteligencia artificial con Neuron.
https://docs.neuron-ai.dev/resources/guides-and-tutorials.

## Características

- 🚀 **Fácil instalación** - Configuración automática con auto-discovery
- 🎨 **Comando Artisan** - Genera clases de agentes AI con `make:agent`
- ⚙️ **Configuración flexible** - Soporte para múltiples proveedores AI
- 🔧 **Facade incluida** - Acceso simple a través de `NeuronAI::`
- 🧪 **Tests incluidos** - Suite completa de pruebas
- 📚 **Documentación completa** - Ejemplos y guías detalladas

## Proveedores AI Soportados

- **OpenAI** (GPT-4, GPT-3.5-turbo)
- **Anthropic** (Claude 3)
- **Google Gemini**
- **Ollama** (Modelos locales)

## Instalación

### Vía Composer

```bash
composer require mappweb/laravel-neuron-ai
```

### Publicar Configuración

```bash
# Publicar archivo de configuración
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai-config"

# Publicar stubs para personalización
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai-stubs"

# Publicar todos los archivos
php artisan vendor:publish --provider="Mappweb\LaravelNeuronAi\NeuronServiceProvider" --tag="neuron-ai"
```

### Configuración

Agrega las siguientes variables a tu archivo `.env`:

```env
# Configuración general
NEURON_AI_DEFAULT_PROVIDER=openai

# OpenAI
OPENAI_API_KEY=your-openai-api-key
OPENAI_MODEL=gpt-4

# Anthropic
ANTHROPIC_API_KEY=your-anthropic-api-key
ANTHROPIC_MODEL=claude-3-sonnet-20240229

# Gemini
GEMINI_API_KEY=your-gemini-api-key

# Ollama (para modelos locales)
OLLAMA_BASE_URL=http://localhost:11434
OLLAMA_MODEL=llama2
```

## Uso

### Generar Agentes con Artisan

```bash
# Agente básico
php artisan make:agent ChatAgent

# Agente con proveedor específico
php artisan make:agent ChatAgent --provider=openai

# Agente con instrucciones personalizadas
php artisan make:agent ChatAgent --instructions="You are a helpful customer support agent"

# Agente con herramientas
php artisan make:agent ChatAgent --tools="WebSearch,EmailSender"

# Agente completo
php artisan make:agent CustomerSupportAgent \
    --provider=anthropic \
    --instructions="You are a customer support agent" \
    --tools="WebSearch,DatabaseQuery"
```

### Ejemplo de Agente Generado

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

### Uso en Controladores

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

### Uso con Facade

```php
use Mappweb\LaravelNeuronAi\Facades\NeuronAI;

// Uso básico
$agent = NeuronAI::make()
    ->provider(OpenAIProvider::make())
    ->instructions('You are a helpful assistant');

$response = $agent->chat('Hello!');

// Con configuración desde config
$agent = NeuronAI::make()
    ->provider(config('neuron-ai.default_provider'))
    ->instructions(config('neuron-ai.agents.default_instructions'));
```

### Configuración Avanzada

El archivo `config/neuron-ai.php` permite configurar:

```php
return [
    // Proveedor por defecto
    'default_provider' => env('NEURON_AI_DEFAULT_PROVIDER', 'openai'),
    
    // Configuración de proveedores
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],
        // ... otros proveedores
    ],
    
    // Configuración de agentes
    'agents' => [
        'default_instructions' => env('NEURON_AI_DEFAULT_INSTRUCTIONS', 'You are a helpful AI assistant.'),
        'default_namespace' => 'App\\Agents',
        'timeout' => env('NEURON_AI_TIMEOUT', 30),
    ],
    
    // Logging y cache
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

### Opciones del Comando `make:agent`

| Opción | Descripción | Ejemplo |
|--------|-------------|---------|
| `--provider` | Proveedor AI a usar | `--provider=openai` |
| `--instructions` | Instrucciones personalizadas | `--instructions="You are helpful"` |
| `--tools` | Herramientas (separadas por comas) | `--tools="WebSearch,Email"` |
| `--path` | Directorio personalizado | `--path="CustomAgents"` |
| `--force` | Sobrescribir archivos existentes | `--force` |

### Proveedores Disponibles

- `openai` → OpenAIProvider
- `anthropic` → AnthropicProvider  
- `gemini` → GeminiProvider
- `ollama` → OllamaProvider

## Testing

```bash
composer test
```

## Changelog

Consulta [CHANGELOG](CHANGELOG.md) para ver los cambios recientes.

## Contribuciones

Las contribuciones son bienvenidas. Por favor revisa [CONTRIBUTING](CONTRIBUTING.md) para más detalles.

## Seguridad

Si descubres vulnerabilidades de seguridad, envía un email a diego.toscanof@gmail.com.

## Créditos
- [neuron-ai.dev](https://github.com/inspector-apm/neuron-ai)
- [Diego Toscano](https://github.com/mappweb)
- [Todos los contribuidores](../../contributors)

## Licencia

La licencia MIT (MIT). Consulta [License File](LICENSE.md) para más información.
