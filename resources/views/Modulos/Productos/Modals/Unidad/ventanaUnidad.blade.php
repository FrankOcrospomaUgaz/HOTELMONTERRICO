
<div class="mb-1">
    <div style="float:left">
        @can($permisosCrear->name)
        <a href="#" id="btonNuevoUnidad" class="btn btn-primary btonNuevo btn-sm">NUEVA UNIDAD</a>
      
        @endcan
    </div>
    <form id="filtroUnidad">
        <div class="enviaFiltro" style="float:right">
            <span id="resetFiltroUnidad" class="btn btn-dark ml-1" style="font-size:14px;width:55px">
                <i class="fa-solid fa-rotate-left"></i>
            </span>
            <button type="submit" class="selectActivos" style="width:60px">
                <i class="fa-solid fa-search"></i>
            </button>
        </div>
        <div style="float:right" class="mt-2 btn-sm">
            <select id="activosUnidad" class="form-control activos selectActivos">
                <option value="todos">TODOS</option>
                <option value="activos">ACTIVOS</option>
                <option value="inactivos">INACTIVOS</option>
            </select>
        </div>
        <br><br><br>
    </form>
</div>


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



