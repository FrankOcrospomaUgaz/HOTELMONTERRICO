<table id="tbUnidad" class="table table-striped shadow-lg mt-4" style="width:100%"">
<thead>
        <tr class="custom-header-bg">
    <th scope=" col">#</th>
    <th scope="col">Nombre</th>
    <th scope="col">Fecha Creacion</th>
    @can($permisosEliminar->name)
    <th scope="col">Estado</th>
    @endcan
    @can($permisosEditar->name)
    <th scope="col">Acciones</th>
    @endcan
    </tr>
    </thead>

</table>