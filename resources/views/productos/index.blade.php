@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Productos</h1>
            <p class="text-muted mb-0">Administra los productos del inventario.</p>
        </div>

        <button type="button" class="btn btn-primary" id="btnNuevoProducto">Nuevo producto</button>
    </div>

    <div id="alertasProductos"></div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                @include('productos.tabla')
            </div>
        </div>
    </div>
</div>

@include('productos.modal')
@include('productos.scripts')
@endsection
