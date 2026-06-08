<table id="tbHabitacion" class="table table-striped shadow-lg mt-4" style="width:100%"">
    <thead>
        <tr class="custom-header-bg">
    <th scope=" col">#</th>
    <th scope="col">Numero</th>
    <th scope="col">Tipo</th>
    <th scope="col">Situación</th>
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