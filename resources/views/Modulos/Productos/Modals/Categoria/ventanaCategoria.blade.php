
<div class="mb-1">
    <div style="float:left">
        @can($permisosCrear->name)
        <a href="#" id="btonNuevoCategoria" class="btn btn-primary btonNuevo btn-sm">NUEVA CATEGORIA</a>
      
        @endcan
    </div>
    <form id="filtroCategoria">
        <div class="enviaFiltro" style="float:right">
            <span id="resetFiltroCategoria" class="btn btn-dark ml-1" style="font-size:14px;width:55px">
                <i class="fa-solid fa-rotate-left"></i>
            </span>
            <button type="submit" class="selectActivos" style="width:60px">
                <i class="fa-solid fa-search"></i>
            </button>
        </div>
        <div style="float:right" class="mt-2">
            <select id="activosCategoria" class="form-control activos selectActivos btn-sm">
                <option value="todos">TODOS</option>
                <option value="activos">ACTIVOS</option>
                <option value="inactivos">INACTIVOS</option>
            </select>
        </div>
        <br><br><br>
    </form>
</div>

@section('footer')
<footer>
    <div class="footer-container">
        <div class="footer-content">
            <div class="row">
            <div class="col-md-6">
                    <p class="texto-Footer"><b>Copyright&copy; 2023 </b><a class="garzasoftFooter" href="http://www.garzasoft.com/">Garzasoft</a>. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom">
                        <div class="footer-social">
                            <ul>
                                <li><a target="_blank" href="https://www.facebook.com/Garzasoft"><i class="fa-brands fa-facebook"></i></a></li>
                                <li><a target="_blank" href="https://api.whatsapp.com/send?phone=+51%20979293176&text=%C2%A1Hola!%20Quisiera%20informaci%C3%B3n%20sobre%20los%20servicios%20de%20Garzasoft."><i class="fab fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@stop

@can($permisosEditar->name)
<input type="text" class="d-none" id="permisoEdit" value="editar">
@else
<input type="text" class="d-none" id="permisoEdit" value="noeditar">
@endcan

@can($permisosEliminar->name)
<input type="text" class="d-none" id="permisoElim" value="eliminar">
@else
<input type="text" class="d-none" id="permisoElim" value="noeliminar">
@endcan



