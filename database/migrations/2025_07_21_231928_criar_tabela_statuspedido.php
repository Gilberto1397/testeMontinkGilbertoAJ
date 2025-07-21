<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CriarTabelaStatuspedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuspedido', function (Blueprint $table) {
            $table->smallIncrements('statuspedido_id');
            $table->string('statuspedido_descricao');
        });

        DB::table('statuspedido')->insert([
            ['statuspedido_descricao' => 'Em Preparação'],
            ['statuspedido_descricao' => 'Saiu para Entrega'],
            ['statuspedido_descricao' => 'Entregue'],
            ['statuspedido_descricao' => 'Cancelado']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuspedido');
    }
}
