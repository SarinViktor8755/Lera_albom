<?php
require_once 'config.php';
checkAuth();

// Загрузка новой фотографии
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_photo'])) {
    $chapter_id = (int)$_POST['chapter_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (!empty($title) && $chapter_id > 0 && isset($_FILES['photo'])) {
        $uploadDir = '../img/';
        $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $stmt = $pdo->prepare("INSERT INTO photos (chapter_id, title, description, file_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$chapter_id, $title, $description, './img/' . $fileName]);
            $_SESSION['success'] = "Фотография успешно загружена";
            redirect('photos.php');
        } else {
            $_SESSION['error'] = "Ошибка при загрузке файла";
        }
    }
}

// Удаление фотографии
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Сначала получаем путь к файлу
    $photo = $pdo->query("SELECT file_path FROM photos WHERE id = $id")->fetch();
    
    if ($photo) {
        // Удаляем файл
        $filePath = '../' . ltrim($photo['file_path'], './');
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Удаляем запись из БД
        $pdo->prepare("DELETE FROM photos WHERE id = ?")->execute([$id]);
        $_SESSION['success'] = "Фотография успешно удалена";
    }
    
    redirect('photos.php');
}

// Получение списка фотографий с именами глав
$photos = $pdo->query("
    SELECT p.*, c.title AS chapter_title 
    FROM photos p
    LEFT JOIN chapters c ON p.chapter_id = c.id
    ORDER BY p.weight, p.created_at DESC
")->fetchAll();

// Получение списка глав для выпадающего списка
$chapters = $pdo->query("SELECT id, title FROM chapters ORDER BY title")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление фотографиями</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <div class="col-md-10 main-content">
                <h2>Управление фотографиями</h2>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <!-- Форма добавления фотографии -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Добавить новую фотографию</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="chapter_id">Глава</label>
                                <select class="form-control" id="chapter_id" name="chapter_id" required>
                                    <option value="">-- Выберите главу --</option>
                                    <?php foreach ($chapters as $chapter): ?>
                                        <option value="<?= $chapter['id'] ?>"><?= htmlspecialchars($chapter['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Название фотографии</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="photo">Фотография</label>
                                <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*" required>
                            </div>
                            <button type="submit" name="add_photo" class="btn btn-primary">Загрузить фотографию</button>
                        </form>
                    </div>
                </div>
                
                <!-- Список фотографий -->
                <div class="card">
                    <div class="card-header">
                        <h5>Список фотографий</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Глава</th>
                                    <th>Название</th>
                                    <th>Фото</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($photos as $photo): ?>
                                <tr>
                                    <td><?= $photo['id'] ?></td>
                                    <td><?= htmlspecialchars($photo['chapter_title']) ?></td>
                                    <td><?= htmlspecialchars($photo['title']) ?></td>
                                    <td>
                                        <img src=".<?= htmlspecialchars($photo['file_path']) ?>" alt="<?= htmlspecialchars($photo['title']) ?>" style="max-width: 100px;">
                                    </td>
                                    <td>
                                        <a href="photo_edit.php?id=<?= $photo['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="photos.php?delete=<?= $photo['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>