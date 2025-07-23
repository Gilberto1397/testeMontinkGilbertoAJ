<?php

namespace App\Http\Helpers;

class OrganizaRespostaRequisicao
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $mensagem;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var bool
     */
    protected $erro;

    /**
     * @param mixed $data
     * @param string $mensagem
     * @param int $statusCode
     */
    public function __construct(int $statusCode, string $mensagem = '', $data = null)
    {
        $this->data = $data;
        $this->mensagem = $mensagem;
        $this->statusCode = $statusCode;
        $this->erro = $this->isError($statusCode);
    }

    /**
     * @param int $statusCode
     * @return bool
     */
    protected function isError($statusCode): bool
    {
        return $statusCode < 200 || $statusCode >= 300;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getMensagem(): string
    {
        return $this->mensagem;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return bool
     */
    public function getErro(): bool
    {
        return $this->erro;
    }
}