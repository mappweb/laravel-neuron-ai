<?php

declare(strict_types=1);

namespace Mappweb\LaravelNeuronAi\Chat\Messages\ContentBlocks;

use Mappweb\LaravelNeuronAi\Chat\Enums\ContentBlockType;

interface ContentBlock extends \JsonSerializable
{
    public function getType(): ContentBlockType;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
