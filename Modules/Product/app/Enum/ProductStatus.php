<?php

namespace Modules\Product\Enum;

enum ProductStatus: string
{
    case IN_STOCK = 'i'; // Em estoque
    case IN_REPLACEMENT = 'ii'; // Em reposição
    case LACKING = 'iii'; // Em falta
}
