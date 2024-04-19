<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Product\Enum\ProductStatus;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    #[ArrayShape([
        'name' => "string",
        'description' => "string",
        'price' => "string",
        'status' => "array",
        'stock_quantity' => "string"
    ])]
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'status' => [
                'required',
                'string',
                Rule::in([
                    ProductStatus::IN_STOCK->value,
                    ProductStatus::IN_REPLACEMENT->value,
                    ProductStatus::LACKING->value,
                ]),
            ],
            'stock_quantity' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome.',
            'name.string' => 'Informe o nome corretamente.',
            'name.max' => 'Limite de caracteres excedido. (máx.: 255).',
            'name.unique' => 'Nome já cadastrado.',
            'description.string' => 'Informe a descrição corretamente.',
            'description.max' => 'Limite de caracteres excedido. (máx.: 500).',
            'price.required' => 'Informe o preço.',
            'price.numeric' => 'Informe o preço corretamente.',
            'price.min' => 'Preço não pode ser menor que 0.',
            'status.required' => 'Informe o status',
            'status.string' => 'Informe o status corretamente.',
            'status.in' => 'Informe o status corretamente.',
            'stock_quantity.required' => 'Informe a quantidade de estoque.',
            'stock_quantity.numeric' => 'Informe a quantidade de estoque corretamente.',
            'stock_quantity.min' => 'Informe a quantidade de estoque corretamente.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
