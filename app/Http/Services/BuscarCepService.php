<?php

namespace App\Http\Services;

use App\Http\Helpers\OrganizaRespostaRequisicao;
use GuzzleHttp\Client;

class BuscarCepService
{
    const URL_API = 'https://viacep.com.br/ws/';

    /**
     * Busca informações de um CEP utilizando a API ViaCEP.
     * @param int $cep
     * @return object
     */
    public function buscarCep(int $cep): object
    {
        $client = new Client();
        $response = $client->getAsync(self::URL_API . $cep . '/json')->wait();

        return new OrganizaRespostaRequisicao(200, '', (object)json_decode($response->getBody()));
    }
}