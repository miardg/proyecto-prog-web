<?php
require_once __DIR__ . '/../permisos.php';

$idUsuario = $_SESSION['user']['id'] ?? null;

//funcion para no repetir toda la sentencia para consultar los permisos
function can(string $permiso, ?int $idUsuario): bool
{
    return $idUsuario && Permisos::tienePermiso($permiso, $idUsuario);
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/proyecto-prog-web/views/dashboard.php">
            <i class="fas fa-dumbbell text-warning me-2"></i>KynetikGym
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Inicio siempre visible en privadas -->
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'index' ? 'active' : '' ?>"
                        href="/proyecto-prog-web/views/dashboard.php">Inicio</a>
                </li>

                <!-- Clases -->
                <?php if (
                    can('Crear clases', $idUsuario) ||
                    can('Modificar clases', $idUsuario) ||
                    can('Ver clases', $idUsuario) ||
                    can('Cancelar clase', $idUsuario) ||
                    can('Ver clases asignadas', $idUsuario) ||
                    can('Ver inscriptos', $idUsuario) ||
                    can('Confirmar asistencia', $idUsuario) ||
                    can('Cancelar inscripción a clase', $idUsuario)
                ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($currentPage ?? '') === 'clases' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Clases
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (can('Cancelar inscripción a clase', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="/proyecto-prog-web/views/clases/clases_gestion.php">Ver mis
                                        clases</a></li>
                            <?php endif; ?>

                            <?php if (can('Crear clases', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="/proyecto-prog-web/views/clases/crear_clase.php">Crear
                                        clase</a></li>
                            <?php endif; ?>

                            <?php if (can('Modificar clases', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="clases_gestion.php">Modificar clase</a></li>
                            <?php endif; ?>

                            <?php if (can('Cancelar clase', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="clases_cancelar.php">Cancelar clase</a></li>
                            <?php endif; ?>

                            <?php if (can('Ver clases asignadas', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="clases_asignadas.php">Mis clases asignadas</a></li>
                            <?php endif; ?>

                            <?php if (can('Ver inscriptos', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="clases_inscriptos.php">Ver inscriptos</a></li>
                            <?php endif; ?>

                            <?php if (can('Confirmar asistencia', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="clases_asistencia.php">Confirmar asistencia</a></li>
                            <?php endif; ?>

                            <?php if (can('Ver clases', $idUsuario)): ?>
                                <li><a class="dropdown-item"
                                        href="/proyecto-prog-web/views/clases/clases_disponibles.php">Ver clases disponibles</a></li>
                            <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Planes -->
                <?php if (can('Ver planes', $idUsuario) || can('Modificar planes', $idUsuario) || can('Ver plan actual', $idUsuario)): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($currentPage ?? '') === 'planes' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Planes
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (can('Ver planes', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="/proyecto-prog-web/views/planes/ver_planes.php">Ver
                                        planes</a></li>
                            <?php endif; ?>

                            <?php if (can('Ver plan actual', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="/proyecto-prog-web/views/planes/mi_plan_actual.php">Mi plan
                                        actual</a></li>
                            <?php endif; ?>

                            <?php if (can('Modificar planes', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="planes_gestion.php">Gestionar/Modificar planes</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Usuarios -->
                <?php if (can('Crear usuario', $idUsuario) || can('Modificar usuario', $idUsuario) || can('Eliminar usuario', $idUsuario)): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($currentPage ?? '') === 'usuarios' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Usuarios
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (can('Crear usuario', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="usuarios_crear.php">Crear usuario</a></li>
                            <?php endif; ?>

                            <?php if (can('Modificar usuario', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="usuarios_gestion.php">Modificar usuario</a></li>
                            <?php endif; ?>

                            <?php if (can('Eliminar usuario', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="usuarios_eliminar.php">Eliminar usuario</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Socios -->
                <?php if (
                    can('Asignar plan a socio', $idUsuario) ||
                    can('Cambiar plan de socio', $idUsuario) ||
                    can('Dar de baja socio', $idUsuario) ||
                    can('Aprobar nuevos socios', $idUsuario)
                ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($currentPage ?? '') === 'socios' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Socios
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (can('Asignar plan a socio', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="socios_asignar_plan.php">Asignar plan</a></li>
                            <?php endif; ?>

                            <?php if (can('Cambiar plan de socio', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="socios_cambiar_plan.php">Cambiar plan</a></li>
                            <?php endif; ?>

                            <?php if (can('Aprobar nuevos socios', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="socios_aprobar.php">Aprobar nuevos socios</a></li>
                            <?php endif; ?>

                            <?php if (can('Dar de baja socio', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="socios_baja.php">Dar de baja socio</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Beneficios -->
                <?php if (can('Ver beneficios', $idUsuario) || can('Canjear beneficio', $idUsuario)): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($currentPage ?? '') === 'beneficios' ? 'active' : '' ?>"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Beneficios
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (can('Ver beneficios', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="beneficios.php">Ver beneficios</a></li>
                            <?php endif; ?>

                            <?php if (can('Canjear beneficio', $idUsuario)): ?>
                                <li><a class="dropdown-item" href="beneficios_canjear.php">Canjear beneficio</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Usuario y sesión -->
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    Hola, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuario') ?>
                </span>
                <a href="/proyecto-prog-web/logout.php" class="btn btn-outline-warning me-2">Salir</a>
            </div>
        </div>
    </div>
</nav>