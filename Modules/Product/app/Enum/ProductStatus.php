<?php

namespace Modules\Product\Enum;

enum ProductStatus: string
{
    case IN_STOCK = 'i';
    case IN_REPLACEMENT = 'ii';
    case LACKING = 'iii';
}
