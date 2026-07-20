@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Clientes</h1>
            <p class="text-muted mb-0">Administra la información de los clientes.</p>
        </div>

        <button type="button" class="btn btn-primary" id="btnNuevoCliente">Nuevo cliente</button>
    </div>

    <div id="alertasClientes"></div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                @include('clientes.tabla')
            </div>
        </div>
    </div>
</div>

@include('clientes.modal')
@include('clientes.scripts')
@endsection
