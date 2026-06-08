<table id="tbCompraProductos" class="table table-striped shadow-lg mt-4" style="width:100%"">
<thead>
        <tr class=" custom-header-bg">
    <th scope=" col">#</th>
    <th scope="col">Número</th>
    <th scope="col">Tipo</th>

    <th scope="col">Proveedor</th>
    <th scope="col">Usuario</th>
    <th scope="col">Situacion</th>
    
    <th scope="col">Total</th><th scope="col">Fecha Creacion</th>
    @can($permisosEditar->name)
    <th scope="col">Acciones</th>
    @endcan
    </tr>
    </thead>

</table>