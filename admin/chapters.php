<?php
require_once 'config.php';
checkAuth();

// Добавление новой главы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_chapter'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $additional_info = $_POST['additional_info'] ?? '';
    
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO chapters (title, description, additional_info) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $additional_info]);
        $_SESSION['success'] = "Глава успешно добавлена";
        redirect('chapters.php');
    }
}

// Удаление главы
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM chapters WHERE id = ?")->execute([$id]);
    $_SESSION['success'] = "Глава успешно удалена";
    redirect('chapters.php');
}

// Получение списка глав
$chapters = $pdo->query("SELECT * FROM chapters ORDER BY weight, title")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление главами</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <div class="col-md-10 main-content">
                <h2>Управление главами</h2>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <!-- Форма добавления главы -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Добавить новую главу</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="title">Название главы</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="additional_info">Дополнительная информация (например, вес и рост)</label>
                                <input type="text" class="form-control" id="additional_info" name="additional_info">
                            </div>
                            <button type="submit" name="add_chapter" class="btn btn-primary">Добавить главу</button>
                        </form>
                    </div>
                </div>
                
                <!-- Список глав -->
                <div class="card">
                    <div class="card-header">
                        <h5>Список глав</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Доп. информация</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($chapters as $chapter): ?>
                                <tr>
                                    <td><?= $chapter['id'] ?></td>
                                    <td><?= htmlspecialchars($chapter['title']) ?></td>
                                    <td><?= htmlspecialchars($chapter['description']) ?></td>
                                    <td><?= htmlspecialchars($chapter['additional_info']) ?></td>
                                    <td>
                                        <a href="chapter_edit.php?id=<?= $chapter['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="chapters.php?delete=<?= $chapter['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">
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