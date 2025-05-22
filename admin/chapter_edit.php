<?php
require_once 'config.php';
checkAuth();

// Получаем ID главы для редактирования
$chapterId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Получаем данные о главе
$chapter = [];
$stmt = $pdo->prepare("SELECT * FROM chapters WHERE id = ?");
$stmt->execute([$chapterId]);
$chapter = $stmt->fetch();

if (!$chapter) {
    $_SESSION['error'] = "Глава не найдена";
    redirect('chapters.php');
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_chapter'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $additionalInfo = $_POST['additional_info'] ?? '';
    
    if (!empty($title)) {
        // Обновляем запись в базе данных
        $stmt = $pdo->prepare("UPDATE chapters SET title = ?, description = ?, additional_info = ? WHERE id = ?");
        $stmt->execute([$title, $description, $additionalInfo, $chapterId]);
        
        $_SESSION['success'] = "Глава успешно обновлена";
        redirect('chapters.php');
    } else {
        $_SESSION['error'] = "Название главы обязательно для заполнения";
        redirect("chapter_edit.php?id=$chapterId");
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование главы</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <div class="col-md-10 main-content">
                <h2>Редактирование главы</h2>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="title">Название главы</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?= htmlspecialchars($chapter['title']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= 
                                    htmlspecialchars($chapter['description']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="additional_info">Дополнительная информация (например, вес и рост)</label>
                                <input type="text" class="form-control" id="additional_info" name="additional_info"
                                       value="<?= htmlspecialchars($chapter['additional_info']) ?>">
                            </div>
                            <button type="submit" name="edit_chapter" class="btn btn-primary">Сохранить изменения</button>
                            <a href="chapters.php" class="btn btn-secondary">Отмена</a>
                        </form>
                    </div>
                </div>

                <!-- Список фотографий в этой главе -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Фотографии в этой главе</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $photos = $pdo->prepare("SELECT * FROM photos WHERE chapter_id = ? ORDER BY weight, created_at");
                        $photos->execute([$chapterId]);
                        $photos = $photos->fetchAll();
                        
                        if (empty($photos)): ?>
                            <p>В этой главе пока нет фотографий</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($photos as $photo): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <img src=".<?= htmlspecialchars($photo['file_path']) ?>" class="card-img-top" 
                                             alt="<?= htmlspecialchars($photo['title']) ?>" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= htmlspecialchars($photo['title']) ?></h6>
                                            <p class="card-text text-muted small"><?= 
                                                htmlspecialchars(mb_substr($photo['description'], 0, 50) . (mb_strlen($photo['description']) > 50 ? '...' : '')) ?>
                                            </p>
                                            <a href="photo_edit.php?id=<?= $photo['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Редактировать
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>