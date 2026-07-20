@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const apiProveedores = '/api/proveedores';
    const tablaProveedores = document.getElementById('tablaProveedores');
    const alertas = document.getElementById('alertasProveedores');
    const formulario = document.getElementById('formProveedor');
    const proveedorId = document.getElementById('proveedorId');
    const tituloModal = document.getElementById('tituloModalProveedor');
    const botonGuardar = document.getElementById('btnGuardarProveedor');
    const botonEliminar = document.getElementById('btnConfirmarEliminarProveedor');
    const nombreEliminar = document.getElementById('nombreProveedorEliminar');
    const modalProveedor = new window.bootstrap.Modal(document.getElementById('modalProveedor'));
    const modalEliminar = new window.bootstrap.Modal(document.getElementById('modalEliminarProveedor'));
    let idProveedorEliminar = null;

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

    async function cargarProveedores() {
        tablaProveedores.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Cargando proveedores...</td></tr>';

        try {
            const respuesta = await window.axios.get(apiProveedores);
            const proveedores = respuesta.data;

            if (!proveedores.length) {
                tablaProveedores.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">No hay proveedores registrados.</td></tr>';
                return;
            }

            tablaProveedores.innerHTML = proveedores.map(function (proveedor) {
                return `<tr>
                    <td>${escaparHtml(proveedor.nombre)}</td>
                    <td>${escaparHtml(proveedor.telefono || '—')}</td>
                    <td>${escaparHtml(proveedor.correo || '—')}</td>
                    <td>${escaparHtml(proveedor.direccion || '—')}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="${proveedor.id}">Editar</button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${proveedor.id}" data-nombre="${escaparHtml(proveedor.nombre)}">Eliminar</button>
                    </td>
                </tr>`;
            }).join('');
        } catch (error) {
            tablaProveedores.innerHTML = '<tr><td colspan="5" class="text-center text-danger py-4">No fue posible cargar los proveedores.</td></tr>';
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    function abrirNuevoProveedor() {
        formulario.reset();
        proveedorId.value = '';
        tituloModal.textContent = 'Nuevo proveedor';
        modalProveedor.show();
    }

    async function editarProveedor(id) {
        try {
            const respuesta = await window.axios.get(`${apiProveedores}/${id}`);
            const proveedor = respuesta.data;

            formulario.reset();
            proveedorId.value = proveedor.id;
            document.getElementById('nombre').value = proveedor.nombre;
            document.getElementById('telefono').value = proveedor.telefono || '';
            document.getElementById('correo').value = proveedor.correo || '';
            document.getElementById('direccion').value = proveedor.direccion || '';
            tituloModal.textContent = 'Editar proveedor';
            modalProveedor.show();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    async function guardarProveedor(evento) {
        evento.preventDefault();
        const id = proveedorId.value;
        const datos = Object.fromEntries(new FormData(formulario).entries());
        botonGuardar.disabled = true;

        try {
            const respuesta = id
                ? await window.axios.put(`${apiProveedores}/${id}`, datos)
                : await window.axios.post(apiProveedores, datos);

            modalProveedor.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarProveedores();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonGuardar.disabled = false;
        }
    }

    function solicitarEliminacion(id, nombre) {
        idProveedorEliminar = id;
        nombreEliminar.textContent = nombre;
        modalEliminar.show();
    }

    async function eliminarProveedor() {
        if (!idProveedorEliminar) {
            return;
        }

        botonEliminar.disabled = true;

        try {
            const respuesta = await window.axios.delete(`${apiProveedores}/${idProveedorEliminar}`);
            modalEliminar.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarProveedores();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonEliminar.disabled = false;
            idProveedorEliminar = null;
        }
    }

    document.getElementById('btnNuevoProveedor').addEventListener('click', abrirNuevoProveedor);
    formulario.addEventListener('submit', guardarProveedor);
    botonEliminar.addEventListener('click', eliminarProveedor);

    tablaProveedores.addEventListener('click', function (evento) {
        const botonEditar = evento.target.closest('.btn-editar');
        const botonEliminarProveedor = evento.target.closest('.btn-eliminar');

        if (botonEditar) {
            editarProveedor(botonEditar.dataset.id);
        }

        if (botonEliminarProveedor) {
            solicitarEliminacion(botonEliminarProveedor.dataset.id, botonEliminarProveedor.dataset.nombre);
        }
    });

    cargarProveedores();
});
</script>
@endpush
