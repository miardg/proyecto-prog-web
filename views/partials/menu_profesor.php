<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Panel profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link href="../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="../assets/js/menu_profesor.js" defer></script>
</head>

<body>
   

    <!-- Contenido -->
    <main class="container">
        <br>
        <div class="row g-4">
            <div class="col-md-12">
                <div class="card text-center p-4">
                    <h5 class="mb-3">Ver Calendario</h5>
                    <a href="clases/calendario_profesor.php" class="btn btn-warning w-100">Ir al calendario</a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card text-center p-4">
                    <h5 class="mb-3">Cancelar Clase</h5>
                    <button class="btn btn-outline-warning w-100" data-bs-toggle="modal"
                        data-bs-target="#modalCancelarClase">
                        Cancelar una clase
                    </button>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card text-center p-4">
                    <h5 class="mb-3">Tomar Asistencia</h5>
                    <button id="btnSeleccionarClase" class="btn btn-outline-warning w-100" data-bs-toggle="modal"
                        data-bs-target="#modalTomarAsistencia">
                        Seleccionar clase del día
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal cancelar clase -->
    <div class="modal fade" id="modalCancelarClase" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label for="motivoCancelacion" class="form-label">Motivo de la cancelación</label>
                        <textarea id="motivoCancelacion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mt-3 scroll-clases">
                        <ul id="listaClasesCancelar" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal tomar asistencia -->
    <div class="modal fade" id="modalTomarAsistencia">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Tomar asistencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Paso 1: lista de clases del día -->
                    <ul id="listaClasesAsistencia" class="list-group mb-3"></ul>

                    <!-- Paso 2: lista editable de inscriptos -->
                    <div id="contenedorAsistencia" class="d-none">
                        <h6 class="mb-3">Inscriptos</h6>
                        <ul id="listaInscriptosAsistencia" class="list-group"></ul>
                        <div class="text-end mt-3">
                            <button id="btnGuardarAsistencia" class="btn btn-primary">Guardar asistencia</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Toasts -->
    <div class="position-fixed bottom-0 end-0 p-3">
        <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body" id="toastMsg">Acción completada con éxito</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Modal de confirmacion -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacionTitulo">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmacionMensaje">¿Estás seguro de que querés realizar esta acción?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Volver</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarAccion">Confirmar</button>
                </div>
            </div>
        </div>
    </div>




</body>

</html>