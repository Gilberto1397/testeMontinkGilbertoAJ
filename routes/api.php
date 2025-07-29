<?php

use App\Http\Controllers\CepController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cep/{cep?}', [CepController::class, 'buscarCep']);

Route::prefix('produtos')->group(function () {
    Route::post('', [ProdutoController::class, 'criarProduto']);
    Route::put('', [ProdutoController::class, 'atualizarProduto']);
    Route::get('', [ProdutoController::class, 'listarProdutos']);
});

Route::prefix('carrinho')->group(function () {
    Route::post('', [CarrinhoController::class, 'adicionarAoCarrinho']);
});

Route::prefix('pedidos')->group(function () {
    Route::get('valores-pedido', [PedidoController::class, 'getValoresPedido']);
    Route::post('', [PedidoController::class, 'criarPedido']);
});