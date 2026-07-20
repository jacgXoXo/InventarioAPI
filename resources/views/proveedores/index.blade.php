@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Proveedores</h1>
            <p class="text-muted mb-0">Administra la información de los proveedores.</p>
        </div>

        <button type="button" class="btn btn-primary" id="btnNuevoProveedor">Nuevo proveedor</button>
    </div>

    <div id="alertasProveedores"></div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                @include('proveedores.tabla')
            </div>
        </div>
    </div>
</div>

@include('proveedores.modal')
@include('proveedores.scripts')
@endsection
