<?php
require_once 'config.php';
checkAuth();
?>

<?php include 'header.php'; ?>

<!-- Основной контент страницы -->
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h2>Добро пожаловать в админку</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Быстрые действия</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="chapters.php" class="list-group-item list-group-item-action">
                                            <i class="fas fa-book mr-2"></i> Управление главами
                                        </a>
                                        <a href="photos.php" class="list-group-item list-group-item-action">
                                            <i class="fas fa-images mr-2"></i> Управление фотографиями
                                        </a>
                                        <a href="../" target="_blank" class="list-group-item list-group-item-action">
                                            <i class="fas fa-external-link-alt mr-2"></i> Посмотреть сайт
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Статистика</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $chaptersCount = $pdo->query("SELECT COUNT(*) FROM chapters")->fetchColumn();
                                    $photosCount = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
                                    ?>
                                    <div class="stat-item mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-book mr-2"></i> Всего глав:</span>
                                            <span class="badge bg-primary rounded-pill"><?= $chaptersCount ?></span>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-images mr-2"></i> Всего фотографий:</span>
                                            <span class="badge bg-primary rounded-pill"><?= $photosCount ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>