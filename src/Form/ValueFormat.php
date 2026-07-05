<?php

declare(strict_types=1);

namespace Nowo\TagInputBundle\Form;

enum ValueFormat: string
{
    case ARRAY  = 'array';
    case STRING = 'string';
}
