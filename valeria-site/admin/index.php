<?php
require_once '../includes/auth.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit;
}

// Обработка загрузки фото
if (isset($_POST['upload'])) {
    $result = uploadImage($_FILES['photo'], $_POST['category']);
    if ($result['success']) {
        $success = "Фото успешно загружено";
    } else {
        $error = $result['message'];
    }
}

// Обработка удаления фото
if (isset($_GET['delete'])) {
    $result = deleteImage($_GET['delete']);
    if ($result['success']) {
        $success = "Фото успешно удалено";
    } else {
        $error = $result['message'];
    }
    header('Location: index.php');
    exit;
}

$photos = getAllPhotos();
$categories = ['perv' => 'Предыстория', 'gl1' => 'Глава 1'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администраторская панель</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .photo-thumbnail {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .photo-card {
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .photo-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Админ-панель</a>
            <div class="ml-auto">
                <a href="?logout" class="btn btn-outline-light">Выйти</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Добавить новое фото</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select class="form-control" id="category" name="category" required>
                            <?php foreach ($categories as $value => $label): ?>
                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="photo">Фото</label>
                        <input type="file" class="form-control-file" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="caption">Описание</label>
                        <input type="text" class="form-control" id="caption" name="caption" placeholder="Необязательное описание">
                    </div>
                    <button type="submit" name="upload" class="btn btn-primary">Загрузить</button>
                </form>
            </div>
        </div>

        <h4 class="mb-3">Все фотографии</h4>
        <div class="row">
            <?php foreach ($photos as $photo): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card photo-card">
                        <img src="../<?php echo UPLOAD_DIR . htmlspecialchars($photo['filename']); ?>" class="card-img-top photo-thumbnail" alt="<?php echo htmlspecialchars($photo['caption']); ?>">
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($photo['caption']); ?></p>
                            <small class="text-muted"><?php echo $categories[$photo['category']] ?? $photo['category']; ?></small>
                        </div>
                        <div class="card-footer">
                            <a href="?delete=<?php echo $photo['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Удалить это фото?')">
                                <i class="fas fa-trash"></i> Удалить
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>