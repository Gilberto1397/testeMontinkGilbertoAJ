<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $estoque_id
 * @property int $estoque_produto
 * @property int $estoque_quantidade
 */
class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque';
    protected $primaryKey = 'estoque_id';
    public $timestamps = false;
}
