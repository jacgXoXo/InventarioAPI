@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const apiClientes = '/api/clientes';
    const tablaClientes = document.getElementById('tablaClientes');
    const alertas = document.getElementById('alertasClientes');
    const formulario = document.getElementById('formCliente');
    const clienteId = document.getElementById('clienteId');
    const tituloModal = document.getElementById('tituloModalCliente');
    const botonGuardar = document.getElementById('btnGuardarCliente');
    const botonEliminar = document.getElementById('btnConfirmarEliminarCliente');
    const nombreEliminar = document.getElementById('nombreClienteEliminar');
    const modalCliente = new window.bootstrap.Modal(document.getElementById('modalCliente'));
    const modalEliminar = new window.bootstrap.Modal(document.getElementById('modalEliminarCliente'));
    let idClienteEliminar = null;

    function escaparHtml(valor) {
        const elemento = document.createElement('div');
        elemento.textContent = valor ?? '';
        return elemento.innerHTML;
    }

    function mostrarAlerta(tipo, mensaje) {
        alertas.innerHTML = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">${escaparHtml(mensaje)}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button></div>`;
    }

    function mensajeError(error) {
        if (error.response?.data?.errors) {
            return Object.values(error.response.data.errors).flat().join(' ');
        }

        return error.response?.data?.mensaje || error.response?.data?.message || 'Ocurrió un error al procesar la solicitud.';
    }

    async function cargarClientes() {
        tablaClientes.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Cargando clientes...</td></tr>';

        try {
            const respuesta = await window.axios.get(apiClientes);
            const clientes = respuesta.data;

            if (!clientes.length) {
                tablaClientes.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No hay clientes registrados.</td></tr>';
                return;
            }

            tablaClientes.innerHTML = clientes.map(function (cliente) {
                return `<tr>
                    <td>${escaparHtml(cliente.nombre)}</td>
                    <td>${escaparHtml(cliente.apellido)}</td>
                    <td>${escaparHtml(cliente.telefono || '—')}</td>
                    <td>${escaparHtml(cliente.correo)}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="${cliente.id}">Editar</button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${cliente.id}" data-nombre="${escaparHtml(`${cliente.nombre} ${cliente.apellido}`)}">Eliminar</button>
                    </td>
                </tr>`;
            }).join('');
        } catch (error) {
            tablaClientes.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4">No fue posible cargar los clientes.</td></tr>';
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    function abrirNuevoCliente() {
        formulario.reset();
        clienteId.value = '';
        tituloModal.textContent = 'Nuevo cliente';
        modalCliente.show();
    }

    async function editarCliente(id) {
        try {
            const respuesta = await window.axios.get(`${apiClientes}/${id}`);
            const cliente = respuesta.data;

            formulario.reset();
            clienteId.value = cliente.id;
            document.getElementById('nombre').value = cliente.nombre;
            document.getElementById('apellido').value = cliente.apellido;
            document.getElementById('telefono').value = cliente.telefono || '';
            document.getElementById('correo').value = cliente.correo;
            tituloModal.textContent = 'Editar cliente';
            modalCliente.show();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    async function guardarCliente(evento) {
        evento.preventDefault();
        const id = clienteId.value;
        const datos = Object.fromEntries(new FormData(formulario).entries());
        botonGuardar.disabled = true;

        try {
            const respuesta = id
                ? await window.axios.put(`${apiClientes}/${id}`, datos)
                : await window.axios.post(apiClientes, datos);

            modalCliente.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarClientes();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonGuardar.disabled = false;
        }
    }

    function solicitarEliminacion(id, nombre) {
        idClienteEliminar = id;
        nombreEliminar.textContent = nombre;
        modalEliminar.show();
    }

    async function eliminarCliente() {
        if (!idClienteEliminar) {
            return;
        }

        botonEliminar.disabled = true;

        try {
            const respuesta = await window.axios.delete(`${apiClientes}/${idClienteEliminar}`);
            modalEliminar.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarClientes();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonEliminar.disabled = false;
            idClienteEliminar = null;
        }
    }

    document.getElementById('btnNuevoCliente').addEventListener('click', abrirNuevoCliente);
    formulario.addEventListener('submit', guardarCliente);
    botonEliminar.addEventListener('click', eliminarCliente);

    tablaClientes.addEventListener('click', function (evento) {
        const botonEditar = evento.target.closest('.btn-editar');
        const botonEliminarCliente = evento.target.closest('.btn-eliminar');

        if (botonEditar) {
            editarCliente(botonEditar.dataset.id);
        }

        if (botonEliminarCliente) {
            solicitarEliminacion(botonEliminarCliente.dataset.id, botonEliminarCliente.dataset.nombre);
        }
    });

    cargarClientes();
});
</script>
@endpush
