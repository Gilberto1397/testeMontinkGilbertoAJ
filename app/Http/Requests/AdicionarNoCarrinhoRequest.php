<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * @property int $produtoId
 * @property int $quantidade
 * @property string $nomeProduto
 */
class AdicionarNoCarrinhoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'produtoId' => 'required|integer|exists:produtos,produtos_id',
            'quantidade' => 'required|integer|min:1',
            'nomeProduto' => 'required|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array
     */
    public function messages(): array
    {
        return [
            'produtoId.required' => 'É necessário informar o produto a ser atualizado!',
            'produtoId.integer' => 'O ID do produto deve ser um número inteiro válido!',
            'produtoId.exists' => 'O produto informado não existe no sistema!',

            'quantidade.required' => 'É necessário informar a quantidade do produto!',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro válido!',
            'quantidade.min' => 'A quantidade deve ser de pelo menos uma unidade!',

            'nomeProduto.required' => 'É necessário informar o nome do produto!',
            'nomeProduto.string' => 'O nome do produto deve estar em um formato válido!',
            'nomeProduto.max' => 'O nome do produto não pode exceder 255 caracteres!',
        ];
    }

    /**
     * @param  Validator  $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator): void
    {
        $data = [
            'mensagens' => $validator->errors()->getMessages(),
            'erro' => true
        ];

        throw new ValidationException($validator, new Response($data, 406));
    }
}
