<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fas fa-dumbbell text-warning me-2"></i>KynetikGym
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage ?? '') === 'index' ? 'active' : '' ?>" href="index.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage ?? '') === 'planes' ? 'active' : '' ?>" href="planes.php">Planes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($currentPage ?? '') === 'servicios' ? 'active' : '' ?>" href="#servicios">Servicios</a>
        </li>
      </ul>
      <div class="d-flex">
        <a href="login.php" class="btn btn-outline-warning me-2">Iniciar Sesión</a>
        <a href="registro.php" class="btn btn-warning">Registrarse</a>
      </div>
    </div>
  </div>
</nav>