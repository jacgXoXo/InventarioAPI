@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Selecciona un módulo para administrar el inventario.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h2 class="h4 card-title">Productos</h2>
                    <p class="card-text text-muted">Consulta y administra los productos del inventario.</p>
                    <a href="{{ route('web.productos') }}" class="btn btn-primary mt-auto">Ir a Productos</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h2 class="h4 card-title">Proveedores</h2>
                    <p class="card-text text-muted">Gestiona la información de los proveedores.</p>
                    <a href="{{ route('web.proveedores') }}" class="btn btn-primary mt-auto">Ir a Proveedores</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <h2 class="h4 card-title">Clientes</h2>
                    <p class="card-text text-muted">Administra los datos de los clientes.</p>
                    <a href="{{ route('web.clientes') }}" class="btn btn-primary mt-auto">Ir a Clientes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
