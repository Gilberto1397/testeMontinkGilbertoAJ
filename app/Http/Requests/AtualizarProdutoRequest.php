<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * @property int produtoId
 * @property string nome
 * @property double preco
 * @property int estoque
 * @property array produtoVariacao
 */
class AtualizarProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'produtoId' => ['required', 'integer', 'exists:produtos,produtos_id'],
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
            'estoque' => ['required', 'integer', 'min:0'],
            'produtoVariacao' => ['array'],
            'produtoVariacao.*.nome' => ['required', 'string', 'max:255'],
            'produtoVariacao.*.estoque' => ['required', 'integer', 'min:0'],
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

            'nome.required' => 'É necessário informar um nome para o produto!',
            'nome.string' => 'O nome do produto não está em um formato adequado!',
            'nome.max' => 'O nome do produto deve ter no máximo 255 caracteres.',

            'preco.required' => 'É necessário informar um preço para o produto!',
            'preco.numeric' => 'O preço do produto deve ser um número válido!',
            'preco.min' => 'O preço do produto não pode ser inferior a zero.',

            'estoque.required' => 'É necessário informar a quantidade em estoque do produto!',
            'estoque.integer' => 'A quantidade em estoque do produto deve ser um número inteiro válido!',
            'estoque.min' => 'A quantidade em estoque do produto não pode ser inferior a zero.',

            'produtoVariacao.array' => 'As variações do produto não foram informadas corretamente!',
            'produtoVariacao.*.nome.required' => 'É necessário informar um nome para cada variação do produto!',
            'produtoVariacao.*.nome.string' => 'O nome da variação do produto não está em um formato adequado!',
            'produtoVariacao.*.nome.max' => 'O nome da variação do produto deve ter no máximo 255 caracteres.',

            'produtoVariacao.*.estoque.required' => 'É necessário informar a quantidade em estoque para cada variação do produto!',
            'produtoVariacao.*.estoque.integer' => 'A quantidade em estoque da variação do produto deve ser um número inteiro válido!',
            'produtoVariacao.*.estoque.min' => 'A quantidade em estoque da variação do produto não pode ser inferior a zero.',
        ];
    }

    /**
     * Prepare the data for validation.
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nome' => strip_tags($this->nome),
        ]);
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
