<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $cupons_id
 * @property string $cupons_codigo
 * @property float $cupons_valorminimo
 * @property float $cupons_valordesconto
 * @property \DateTime $cupons_expiracao
 */
class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons';
    protected $primaryKey = 'cupons_id';
    public $timestamps = false;
}
