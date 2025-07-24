<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $estoques_id
 * @property int $estoques_produto
 * @property int $estoques_quantidade
 */
class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoques';
    protected $primaryKey = 'estoques_id';
    public $timestamps = false;
}
