<!-- MODAL EDITAR CRUD DE OPCIONES -->

<div class="modal fade" id="modalCRUD" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">
                    <strong id="nuevoModalLabel">EDITAR CRUD</strong>
                </h5>
                <button type="button" class="btn btn-dark btnCloseCrud " id="cerrarModalCRUD">X</button>
            </div>
            <div class="modal-body">
                <div class="mb-4 cajaCrud">
                    @include('Modulos.OpcionesMenu.Tables.tablaCRUD')
                </div>
                <input type="hidden" id="idE">
                <br>
            </div>
        </div>
    </div>
</div>