<?php

declare(strict_types=1);

namespace Mappweb\LaravelNeuronAi\Chat\Enums;

enum SourceType: string
{
    case URL = 'url';
    case BASE64 = 'base64';
}
