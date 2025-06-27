<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used by your
    | agents when no specific provider is configured. You can change this
    | to any of the supported providers.
    |
    | Supported: "openai", "anthropic", "gemini", "ollama"
    |
    */

    'default_provider' => env('NEURON_AI_DEFAULT_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the settings for each AI provider. You should
    | set your API keys and other provider-specific configurations in your
    | environment file for security.
    |
    */

    'providers' => [

        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4'),
            'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
            'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
            'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 2048),
            'temperature' => env('ANTHROPIC_TEMPERATURE', 0.7),
        ],

        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-pro'),
            'max_tokens' => env('GEMINI_MAX_TOKENS', 2048),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
        ],

        'ollama' => [
            'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
            'model' => env('OLLAMA_MODEL', 'llama2'),
            'temperature' => env('OLLAMA_TEMPERATURE', 0.7),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Agent Settings
    |--------------------------------------------------------------------------
    |
    | These are the default settings that will be applied to all agents
    | unless specifically overridden in the agent class.
    |
    */

    'agents' => [
        'default_instructions' => env('NEURON_AI_DEFAULT_INSTRUCTIONS', 'You are a helpful AI assistant.'),
        'default_namespace' => 'App\\Agents',
        'timeout' => env('NEURON_AI_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how NeuronAI should log interactions and errors.
    |
    */

    'logging' => [
        'enabled' => env('NEURON_AI_LOGGING_ENABLED', true),
        'channel' => env('NEURON_AI_LOG_CHANNEL', 'default'),
        'level' => env('NEURON_AI_LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for AI responses to improve performance and reduce
    | API calls when appropriate.
    |
    */

    'cache' => [
        'enabled' => env('NEURON_AI_CACHE_ENABLED', false),
        'ttl' => env('NEURON_AI_CACHE_TTL', 3600), // 1 hour
        'store' => env('NEURON_AI_CACHE_STORE', 'default'),
        'prefix' => env('NEURON_AI_CACHE_PREFIX', 'neuron_ai'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tools Configuration
    |--------------------------------------------------------------------------
    |
    | Configure available tools and their settings.
    |
    */

    'tools' => [
        'enabled' => env('NEURON_AI_TOOLS_ENABLED', true),
        'timeout' => env('NEURON_AI_TOOLS_TIMEOUT', 10),
    ],

]; 