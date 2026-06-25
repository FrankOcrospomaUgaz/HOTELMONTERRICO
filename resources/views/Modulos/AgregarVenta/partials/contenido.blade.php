<section class="venta-rapida" data-num-habitacion="{{ $numFil }}">
    <input type="hidden" id="numHabitacion" name="numHabitacion" value="{{ $numFil }}">
    <input type="hidden" id="idMovimiento">
    <input type="hidden" id="modoModal" value="{{ !empty($modoModal) ? 1 : 0 }}">

    <header class="venta-rapida__header">
        <div class="venta-rapida__summary" id="resumenHabitacion">
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Habitaci&oacute;n</span>
                <strong class="venta-rapida__value" id="numeroI">N&deg; {{ $numFil }}</strong>
            </div>
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Tipo</span>
                <strong class="venta-rapida__value" id="tipoI">-</strong>
            </div>
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Cliente</span>
                <strong class="venta-rapida__value" id="nombreClienteI">-</strong>
            </div>
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Ingreso</span>
                <strong class="venta-rapida__value" id="fechaingresoI">-</strong>
            </div>
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Salida</span>
                <strong class="venta-rapida__value" id="fechasalidaI">-</strong>
            </div>
            <div class="venta-rapida__item">
                <span class="venta-rapida__label">Estado</span>
                <strong class="venta-rapida__value" id="estadoI">-</strong>
            </div>
        </div>

        <div class="venta-rapida__actions cajaAccionesVenta d-none">
            <a href="javascript:void(0)" id="btonCambiarHabitacion" onclick="cambiarHabitacion('{{ $numFil }}')"
                class="btn btn-sm venta-rapida__btn venta-rapida__btn-primary">
                <i class="fa-solid fa-bed"></i>
                Cambiar
            </a>
            <a href="#" id="btonEliminarMovimientoAtencion" class="btn btn-sm venta-rapida__btn venta-rapida__btn-danger">
                <i class="fa-solid fa-trash"></i>
                Anular
            </a>
        </div>
    </header>

    <section class="venta-rapida__checkin cajaCliente d-none">
        <div class="venta-rapida__section-head">
            <div>
                <h3 class="venta-rapida__title">Registrar check-in</h3>
                <p class="venta-rapida__subtitle">Selecciona r&aacute;pido al cliente y crea la atenci&oacute;n.</p>
            </div>
        </div>

        <form id="registroNuevoMovimientoAtencion" class="venta-rapida__checkin-grid">
            @csrf
            <div class="venta-rapida__field">
                <label for="clientes">Cliente</label>
                <div class="venta-rapida__field-inline">
                    <select name="clientes" class="form-control selectTwo" id="clientes"></select>
                    <button type="button" id="agregarCliente" class="btn btn-light venta-rapida__mini-btn" title="Nuevo cliente">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>
                </div>
            </div>
            <div class="venta-rapida__checkin-action">
                <button type="submit" id="enviaCheckInBtn" class="btn btn-primary venta-rapida__btn-block">
                    Registrar check-in
                </button>
            </div>
        </form>
    </section>

    <section id="panelOperacion" class="venta-rapida__workspace d-none">
        <div class="venta-rapida__grid">
            <article class="venta-rapida__panel">
                <div class="venta-rapida__section-head">
                    <div>
                        <h3 class="venta-rapida__title">Productos</h3>
                        <p class="venta-rapida__subtitle">Vende desde la habitaci&oacute;n o mueve stock desde almac&eacute;n general en el mismo paso.</p>
                    </div>
                </div>

                <div class="venta-rapida__quick-form">
                    <div class="venta-rapida__field venta-rapida__field-producto">
                        <label for="productoRapido">Producto</label>
                        <select id="productoRapido" class="form-control selectTwo"></select>
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-origen">
                        <label for="fuenteProductoRapido">Origen</label>
                        <div class="venta-rapida__segmentado" id="fuenteProductoRapido" role="radiogroup" aria-label="Origen">
                            <input type="radio" name="fuenteProductoRapido" id="fuenteProductoHabitacion" value="habitacion">
                            <label for="fuenteProductoHabitacion">
                                <i class="fa-solid fa-bed"></i>
                                Habitaci&oacute;n
                            </label>
                            <input type="radio" name="fuenteProductoRapido" id="fuenteProductoGeneral" value="general" checked>
                            <label for="fuenteProductoGeneral">
                                <i class="fa-solid fa-warehouse"></i>
                                Almac&eacute;n general
                            </label>
                        </div>
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-sm">
                        <label for="cantidadProductoRapido">Cantidad</label>
                        <input type="number" id="cantidadProductoRapido" class="form-control" min="1" value="1">
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-sm">
                        <label for="precioProductoRapido">Precio</label>
                        <input type="text" id="precioProductoRapido" class="form-control" readonly>
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-action">
                        <button type="button" id="btnAgregarProductoRapido" class="btn btn-primary venta-rapida__btn-block">
                            <i class="fa-solid fa-plus"></i>
                            Agregar
                        </button>
                    </div>
                </div>

                <div class="venta-rapida__stock-bar">
                    <div class="venta-rapida__stock-pill">
                        <span>Hab.</span>
                        <strong id="stockHabitacionRapido">0</strong>
                    </div>
                    <div class="venta-rapida__stock-pill">
                        <span>General</span>
                        <strong id="stockGeneralRapido">0</strong>
                    </div>
                    <div class="venta-rapida__stock-pill venta-rapida__stock-pill-wide">
                        <span id="ayudaProductoRapido">Selecciona un producto para empezar.</span>
                    </div>
                </div>

                <div class="formatoTabla venta-rapida__tabla">
                    <table class="table table-sm mb-0">
                        <thead id="theadProductos">
                            <tr>
                                <th>Acciones</th>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Comentario</th>
                                <th>Desc. (%)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="listaProductos">
                            <tr>
                                <td colspan="7" class="text-center text-muted">Sin productos registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="venta-rapida__panel venta-rapida__panel-side">
                <div class="venta-rapida__section-head">
                    <div>
                        <h3 class="venta-rapida__title">Servicios</h3>
                        <p class="venta-rapida__subtitle">Agrega cargos adicionales sin salir de esta vista.</p>
                    </div>
                </div>

                <div class="venta-rapida__quick-form venta-rapida__quick-form-side">
                    <div class="venta-rapida__field">
                        <label for="servicioRapido">Servicio</label>
                        <select id="servicioRapido" class="form-control selectTwo"></select>
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-sm">
                        <label for="cantidadServicioRapido">Cantidad</label>
                        <input type="number" id="cantidadServicioRapido" class="form-control" min="1" value="1">
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-sm">
                        <label for="precioServicioRapido">Precio</label>
                        <input type="number" id="precioServicioRapido" class="form-control" step="0.01">
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-full">
                        <label for="comentarioServicioRapido">Comentario</label>
                        <input type="text" id="comentarioServicioRapido" class="form-control" value="-">
                    </div>
                    <div class="venta-rapida__field venta-rapida__field-action venta-rapida__field-full">
                        <button type="button" id="btnAgregarServicioRapido" class="btn btn-outline-primary venta-rapida__btn-block">
                            <i class="fa-solid fa-plus"></i>
                            Agregar servicio
                        </button>
                    </div>
                </div>

                <div class="formatoTabla venta-rapida__tabla venta-rapida__tabla-side">
                    <table class="table table-sm mb-0">
                        <thead id="theadServicios">
                            <tr>
                                <th>Acciones</th>
                                <th>Servicio</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Comentario</th>
                                <th>Desc. (%)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody class="listaServicios">
                            <tr>
                                <td colspan="7" class="text-center text-muted">Sin servicios registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </article>
        </div>

        <footer class="venta-rapida__footer">
            <div class="venta-rapida__total">
                <span>Total a pagar</span>
                <strong class="totalPagar" id="totalMasIgv">S/0.00</strong>
            </div>
            <div class="venta-rapida__footer-actions">
                @if (!empty($modoModal))
                    <a id="cerrarVentaRapida" class="btn btn-light" href="javascript:void(0)" role="button">Cerrar</a>
                @else
                    <a id="irVistaPrincipal" class="btn btn-light" href="vistaPrincipal" role="button">Vista Principal</a>
                @endif
                <a id="irDetalleHabitacion" class="btn btn-danger"
                    href="detalleHabitacion?id={{ $numFil }}" role="button" @if (!empty($modoModal)) target="_top" @endif>
                    Pagar habitaci&oacute;n
                </a>
            </div>
        </footer>
    </section>
</section>

@include('Modulos.AgregarVenta.Modals.modalCambiarHabitacion')
@include('Modulos.AgregarVenta.Modals.modalCrearUsuario')
