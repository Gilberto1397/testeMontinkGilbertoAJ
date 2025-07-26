<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $pedidos_id
 * @property int $pedidos_statuspedido
 * @property float $pedidos_valor
 * @property \DateTime $pedidos_data
 */
class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'pedidos_id';
    public $timestamps = false;
}
