<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    public static $wrap = 'produtos';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->produtos_id,
            'nome' => $this->produtos_nome,
            'preco' => $this->produtos_preco,
            'variacaoDePreco' => $this->produtos_variacaode,
        ];
    }
}
