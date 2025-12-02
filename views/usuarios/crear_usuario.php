<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../auth.php';
require_once __DIR__ . '/../../permisos.php';
require_login();

$idUsuario = $_SESSION['user']['id'] ?? null;

if (!$idUsuario || !Permisos::tienePermiso("Crear usuario", $idUsuario)) {
    header("Location: /proyecto-prog-web/views/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario - Kynetik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="../../assets/js/usuarios.js" defer></script>
    <script src="../../assets/js/roles.js" defer></script>
</head>

<body>
    <div>
        <?php include __DIR__ . '/../../includes/navbar_permisos.php'; ?>
    </div>
    <div class="auth-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 p-5 mt-5">
                    <div class="auth-card">
                        <div class="row g-0">
                            <!-- Lado izquierdo -->
                            <div
                                class="col-md-6 d-flex align-items-center justify-content-center bg-dark text-white p-5">
                                <div class="text-center">
                                    <i class="fas fa-user-plus display-3 text-warning mb-4"></i>
                                    <h3 class="fw-bold mb-3">Nuevo Usuario</h3>
                                    <p class="text-muted">Agregá un usuario al sistema</p>
                                </div>
                            </div>

                            <!-- Lado derecho: formulario -->
                            <div class="col-md-6 p-5">
                                <div class="text-center mb-4">
                                    <h2 class="fw-bold">Crear Usuario</h2>
                                    <p class="text-muted">Completa los datos del usuario</p>
                                </div>

                                <form id="crearUsuarioForm" method="POST" action="procesar_crear_usuario.php"
                                    novalidate>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required
                                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                                        <div class="invalid-feedback">Ingrese un nombre válido (2–50 letras).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required
                                            pattern="^[a-zA-ZÀ-ÿ\s]{2,50}$">
                                        <div class="invalid-feedback">Ingrese un apellido válido (2–50 letras).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Ingrese un email válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" required
                                            pattern="^[0-9]{7,15}$">
                                        <div class="invalid-feedback">Ingrese un teléfono válido (7–15 dígitos).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni" required
                                            pattern="^[0-9]{7,10}$">
                                        <div class="invalid-feedback">Ingrese un DNI válido (7–10 dígitos).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required minlength="6">
                                        <div class="invalid-feedback">La contraseña debe tener al menos 6 caracteres.
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="rol" class="form-label">Rol</label>
                                        <select id="crearUsuarioRol" class="form-select" name="rol" required>
                                            <option value="">Seleccione un rol</option>
                                        </select>
                                        <div class="invalid-feedback">Seleccione un rol válido.</div>
                                    </div>

                                    <div id="usuarioFeedback" class="mt-2"></div>

                                    <button type="submit" class="btn btn-warning w-100 mb-3" id="btnCrearUsuario">
                                        Crear Usuario
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>