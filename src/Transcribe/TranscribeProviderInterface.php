<?php

declare(strict_types=1);

namespace Mappweb\LaravelNeuronAi\Transcribe;

interface TranscribeProviderInterface
{
    public function trascribe(string $filePath): string;
}
