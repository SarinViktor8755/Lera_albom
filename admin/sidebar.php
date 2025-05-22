<div class="col-md-2 sidebar p-0">
    <div class="p-3">
        <h4 class="text-white">Админпанель</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" href="index.php">
                <i class="fas fa-home mr-2"></i> Главная
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'chapters.php' ? 'active' : '' ?>" href="chapters.php">
                <i class="fas fa-book mr-2"></i> Главы
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'photos.php' ? 'active' : '' ?>" href="photos.php">
                <i class="fas fa-images mr-2"></i> Фотографии
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">
                <i class="fas fa-sign-out-alt mr-2"></i> Выход
            </a>
        </li>
    </ul>
</div>