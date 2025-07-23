<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $produtos_id
 * @property string $produtos_nome
 * @property float $produtos_preco
 * @property string $produtos_variacaode
 */
class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    protected $primaryKey = 'produtos_id';
    public $timestamps = false;

    protected $fillable = [
        'produtos_nome',
        'produtos_preco',
    ];
}
