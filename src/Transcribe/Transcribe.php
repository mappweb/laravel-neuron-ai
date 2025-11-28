<?php

declare(strict_types=1);

namespace Mappweb\LaravelNeuronAi\Transcribe;

use Mappweb\LaravelNeuronAi\Chat\Enums\SourceType;
use Mappweb\LaravelNeuronAi\Chat\Messages\ContentBlocks\AudioContent;
use Mappweb\LaravelNeuronAi\Exceptions\ContentBlocksException;
use NeuronAI\Agent;

class Transcribe extends Agent
{
    use ResolveTranscribeProvider;

    /**
     * [Source audio] Speech to text.
     *
     * @param AudioContent $audio
     * @return string
     * @throws ContentBlocksException
     */
    public function transcribeAudio(AudioContent $audio): string
    {
        if ($audio->sourceType->value === SourceType::URL) {
            return $this->resolveTranscribeProvider()->trascribe($audio->source);
        }
        throw new ContentBlocksException('Unsupported audio source');
    }
}
