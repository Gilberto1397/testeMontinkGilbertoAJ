<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * @property string $cupomCodigo
 * @property float $valorMinimo
 * @property float $valorDesconto
 * @property \DateTime $dataExpiracao
 */
class CriarCupomRequest extends FormRequest
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
            'cupomCodigo' => 'required|string|max:255',
            'valorMinimo' => 'required|numeric|min:0',
            'valorDesconto' => 'required|numeric|min:1',
            'dataExpiracao' => 'required|date_format:d/m/Y'
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'cupomCodigo.required' => "Necessário informar o código do cupom!",
            'cupomCodigo.string' => "O código informado é inválido!",
            'cupomCodigo.max' => "O código não pode ter mais de 255 caracteres!",

            'valorMinimo.required' => "Necessário informar o valor mínimo do cupom!",
            'valorMinimo.numeric' => "O valor mínimo deve ser um número!",
            'valorMinimo.min' => "O valor mínimo deve ser maior ou igual a zero!",

            'valorDesconto.required' => "Necessário informar o valor de desconto do cupom!",
            'valorDesconto.numeric' => "O valor de desconto deve ser um número!",
            'valorDesconto.min' => "O valor de desconto deve ser maior ou igual a 1!",

            'dataExpiracao.required' => "Necessário informar a data de expiração do cupom!",
            'dataExpiracao.date_format' => "A data de expiração deve estar no formato dia/mês/ano!",
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'cupomCodigo' => strip_tags($this->cupomCodigo)
        ]);
    }

    /**
     * @param  Validator  $validator
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        $data = [
            'mensagens' => $validator->errors()->getMessages(),
            'error' => true
        ];

        throw new ValidationException($validator, new Response($data, 406));
    }
}
