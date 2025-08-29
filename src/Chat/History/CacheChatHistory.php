<?php

namespace Mappweb\LaravelNeuronAi\Chat\History;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use NeuronAI\Chat\History\AbstractChatHistory;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Exceptions\ChatHistoryException;

class CacheChatHistory extends AbstractChatHistory
{
    /**
     * Instance constructor.
     *
     * @param string $key
     * @param int $contextWindow
     * @param string $prefix
     * @param int $ttl
     * @param string $cacheStore
     */
    public function __construct(
        protected string $key,
        int              $contextWindow = 50000,
        protected string $prefix = 'neuron_chat_',
        protected int    $ttl = 3600, // 1 hora por defecto
        protected string $cacheStore = 'file'
    )
    {
        parent::__construct($contextWindow);
        $this->init();
    }

    /**
     * Inicializa el historial desde el cache
     */
    protected function init(): void
    {
        $cachedData = Cache::store($this->cacheStore)->get($this->getCacheKey(), null);

        if (is_null($cachedData)) {
            Log::debug('No chat history found in cache', ['key' => $this->getCacheKey()]);
            return;
        }

        Log::debug('Chat history loaded from cache', [
            'key' => $this->getCacheKey(),
            'messages_count' => is_array($cachedData) ? count($cachedData) : 0
        ]);

        // Verificar si los datos están en formato serializado (arrays) o ya son objetos Message
        if (!empty($cachedData) && is_array($cachedData)) {
            if (isset($cachedData[0])) {
                // Si el primer elemento es un array, deserializar
                if (is_array($cachedData[0])) {
                    $this->history = $this->deserializeMessages($cachedData);
                } // Si el primer elemento es un objeto Message, usar directamente
                elseif ($cachedData[0] instanceof Message) {
                    $this->history = $cachedData;
                }
            }
        }
    }

    /**
     * Almacena un mensaje en el cache y sincroniza el historial
     */
    protected function storeMessage(Message $message): ChatHistoryInterface
    {
        $this->updateCache();
        return $this;
    }

    /**
     * Elimina un mensaje antiguo del cache y sincroniza el historial
     */
    public function removeOldMessage(int $index): ChatHistoryInterface
    {
        if (isset($this->history[$index])) {
            unset($this->history[$index]);
            $this->history = array_values($this->history); // Reindexar array
            $this->updateCache();
        }

        return $this;
    }

    /**
     * Limpia todo el historial del cache
     */
    protected function clear(): ChatHistoryInterface
    {
        $cacheKey = $this->getCacheKey();
        if (!Cache::store($this->cacheStore)->forget($cacheKey)) {
            throw new ChatHistoryException("Unable to delete cache key '{$cacheKey}'");
        }
        Log::debug('Chat history cleared', ['key' => $cacheKey]);

        return $this;
    }

    /**
     * Obtiene la clave de cache para este historial
     */
    protected function getCacheKey(): string
    {
        return $this->prefix . $this->key;
    }

    /**
     * Actualiza el cache con el historial actual
     * Guarda los mensajes en formato serializado para evitar problemas de deserialización
     */
    protected function updateCache(): void
    {
        $cacheKey = $this->getCacheKey();

        // Serializar los mensajes a arrays para almacenamiento seguro
        $serializedMessages = array_map(function (Message $message) {
            return $message->jsonSerialize();
        }, $this->history);

        //save message
        Cache::store($this->cacheStore)->put($cacheKey, $serializedMessages, $this->ttl);

        Log::debug('Chat history updated in cache', [
            'key' => $cacheKey,
            'messages_count' => count($serializedMessages),
            'ttl' => $this->ttl
        ]);
    }

    /**
     * @return ChatHistoryInterface
     */
    public function removeOldestMessage(): ChatHistoryInterface
    {
        foreach ($this->history as $index => $message) {
            if (isset($this->history[$index])) {
                unset($this->history[$index]);
            }
        }
        $this->history = array_values($this->history); // Reindexar array
        $this->updateCache();

        return $this;
    }

    /**
     * @param Message[] $messages
     */
    public function setMessages(array $messages): ChatHistoryInterface
    {
        $this->history = $messages;
        $this->updateCache();

        return $this;
    }
}