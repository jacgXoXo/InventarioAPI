<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/productos', function () {
    return view('productos.index');
})->name('web.productos');

Route::get('/proveedores', function () {
    return view('proveedores.index');
})->name('web.proveedores');

Route::get('/clientes', function () {
    return view('clientes.index');
})->name('web.clientes');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
