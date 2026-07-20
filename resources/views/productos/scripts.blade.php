@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const apiProductos = '/api/productos';
    const tablaProductos = document.getElementById('tablaProductos');
    const alertas = document.getElementById('alertasProductos');
    const formulario = document.getElementById('formProducto');
    const productoId = document.getElementById('productoId');
    const tituloModal = document.getElementById('tituloModalProducto');
    const selectorCategorias = document.getElementById('categoria_id');
    const selectorProveedores = document.getElementById('proveedor_id');
    const botonGuardar = document.getElementById('btnGuardarProducto');
    const botonEliminar = document.getElementById('btnConfirmarEliminarProducto');
    const nombreEliminar = document.getElementById('nombreProductoEliminar');
    const modalProducto = new window.bootstrap.Modal(document.getElementById('modalProducto'));
    const modalEliminar = new window.bootstrap.Modal(document.getElementById('modalEliminarProducto'));
    let idProductoEliminar = null;

    function escaparHtml(valor) {
        const elemento = document.createElement('div');
        elemento.textContent = valor ?? '';
        return elemento.innerHTML;
    }

    function mostrarAlerta(tipo, mensaje) {
        alertas.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${escaparHtml(mensaje)}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;
    }

    function mensajeError(error) {
        if (error.response?.data?.errors) {
            return Object.values(error.response.data.errors).flat().join(' ');
        }

        return error.response?.data?.mensaje || error.response?.data?.message || 'Ocurrió un error al procesar la solicitud.';
    }

    async function cargarProductos() {
        tablaProductos.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Cargando productos...</td></tr>';

        try {
            const respuesta = await window.axios.get(apiProductos);
            const productos = respuesta.data;

            if (!productos.length) {
                tablaProductos.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay productos registrados.</td></tr>';
                return;
            }

            tablaProductos.innerHTML = productos.map(function (producto) {
                const precio = Number(producto.precio).toLocaleString('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 2
                });

                return `
                    <tr>
                        <td>${escaparHtml(producto.nombre)}</td>
                        <td>${precio}</td>
                        <td>${escaparHtml(producto.stock)}</td>
                        <td>${escaparHtml(producto.categoria?.nombre || 'Sin categoría')}</td>
                        <td>${escaparHtml(producto.proveedor?.nombre || 'Sin proveedor')}</td>
                        <td class="text-end text-nowrap">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-editar" data-id="${producto.id}">Editar</button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${producto.id}" data-nombre="${escaparHtml(producto.nombre)}">Eliminar</button>
                        </td>
                    </tr>`;
            }).join('');
        } catch (error) {
            tablaProductos.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">No fue posible cargar los productos.</td></tr>';
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    async function cargarOpciones(categoriaSeleccionada = '', proveedorSeleccionado = '') {
        selectorCategorias.innerHTML = '<option value="">Cargando categorías...</option>';
        selectorProveedores.innerHTML = '<option value="">Cargando proveedores...</option>';

        try {
            const [categoriasRespuesta, proveedoresRespuesta] = await Promise.all([
                window.axios.get('/api/categorias'),
                window.axios.get('/api/proveedores')
            ]);

            selectorCategorias.innerHTML = '<option value="">Selecciona una categoría</option>' + categoriasRespuesta.data.map(function (categoria) {
                const seleccionada = String(categoria.id) === String(categoriaSeleccionada) ? ' selected' : '';
                return `<option value="${categoria.id}"${seleccionada}>${escaparHtml(categoria.nombre)}</option>`;
            }).join('');

            selectorProveedores.innerHTML = '<option value="">Selecciona un proveedor</option>' + proveedoresRespuesta.data.map(function (proveedor) {
                const seleccionado = String(proveedor.id) === String(proveedorSeleccionado) ? ' selected' : '';
                return `<option value="${proveedor.id}"${seleccionado}>${escaparHtml(proveedor.nombre)}</option>`;
            }).join('');
        } catch (error) {
            selectorCategorias.innerHTML = '<option value="">No fue posible cargar las categorías</option>';
            selectorProveedores.innerHTML = '<option value="">No fue posible cargar los proveedores</option>';
            mostrarAlerta('danger', mensajeError(error));
            throw error;
        }
    }

    async function abrirNuevoProducto() {
        formulario.reset();
        productoId.value = '';
        tituloModal.textContent = 'Nuevo producto';

        try {
            await cargarOpciones();
            modalProducto.show();
        } catch (error) {
            // La alerta ya se muestra en cargarOpciones.
        }
    }

    async function editarProducto(id) {
        try {
            const respuesta = await window.axios.get(`${apiProductos}/${id}`);
            const producto = respuesta.data;

            formulario.reset();
            productoId.value = producto.id;
            document.getElementById('nombre').value = producto.nombre;
            document.getElementById('precio').value = producto.precio;
            document.getElementById('stock').value = producto.stock;
            tituloModal.textContent = 'Editar producto';

            await cargarOpciones(producto.categoria_id, producto.proveedor_id);
            modalProducto.show();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        }
    }

    async function guardarProducto(evento) {
        evento.preventDefault();
        const id = productoId.value;
        const datos = Object.fromEntries(new FormData(formulario).entries());
        botonGuardar.disabled = true;

        try {
            const respuesta = id
                ? await window.axios.put(`${apiProductos}/${id}`, datos)
                : await window.axios.post(apiProductos, datos);

            modalProducto.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarProductos();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonGuardar.disabled = false;
        }
    }

    function solicitarEliminacion(id, nombre) {
        idProductoEliminar = id;
        nombreEliminar.textContent = nombre;
        modalEliminar.show();
    }

    async function eliminarProducto() {
        if (!idProductoEliminar) {
            return;
        }

        botonEliminar.disabled = true;

        try {
            const respuesta = await window.axios.delete(`${apiProductos}/${idProductoEliminar}`);
            modalEliminar.hide();
            mostrarAlerta('success', respuesta.data.mensaje);
            await cargarProductos();
        } catch (error) {
            mostrarAlerta('danger', mensajeError(error));
        } finally {
            botonEliminar.disabled = false;
            idProductoEliminar = null;
        }
    }

    document.getElementById('btnNuevoProducto').addEventListener('click', abrirNuevoProducto);
    formulario.addEventListener('submit', guardarProducto);
    botonEliminar.addEventListener('click', eliminarProducto);

    tablaProductos.addEventListener('click', function (evento) {
        const botonEditar = evento.target.closest('.btn-editar');
        const botonEliminarProducto = evento.target.closest('.btn-eliminar');

        if (botonEditar) {
            editarProducto(botonEditar.dataset.id);
        }

        if (botonEliminarProducto) {
            solicitarEliminacion(botonEliminarProducto.dataset.id, botonEliminarProducto.dataset.nombre);
        }
    });

    cargarProductos();
});
</script>
@endpush
