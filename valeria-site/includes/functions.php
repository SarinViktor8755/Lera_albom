<?php
require_once 'config.php';

// Функция для проверки аутентификации
function isAuthenticated() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Функция для загрузки изображения
function uploadImage($file, $category) {
    global $pdo;
    
    // Проверка ошибок загрузки
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Ошибка загрузки файла'];
    }
    
    // Проверка типа файла
    if (!in_array($file['type'], ALLOWED_TYPES)) {
        return ['success' => false, 'message' => 'Недопустимый тип файла'];
    }
    
    // Проверка размера файла
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Файл слишком большой'];
    }
    
    // Генерация уникального имени файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    // Перемещение файла
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Ошибка при сохранении файла'];
    }
    
    // Сохранение в базу данных
    $caption = $_POST['caption'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO photos (filename, caption, category) VALUES (?, ?, ?)");
    $stmt->execute([$filename, $caption, $category]);
    
    return ['success' => true, 'filename' => $filename];
}

// Функция для удаления изображения
function deleteImage($id) {
    global $pdo;
    
    // Получаем информацию о файле
    $stmt = $pdo->prepare("SELECT filename FROM photos WHERE id = ?");
    $stmt->execute([$id]);
    $photo = $stmt->fetch();
    
    if (!$photo) {
        return ['success' => false, 'message' => 'Фото не найдено'];
    }
    
    // Удаляем файл
    $filepath = UPLOAD_DIR . $photo['filename'];
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    
    // Удаляем запись из базы данных
    $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
    $stmt->execute([$id]);
    
    return ['success' => true];
}

// Функция для получения всех фотографий по категории
function getPhotosByCategory($category) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE category = ? ORDER BY upload_date DESC");
    $stmt->execute([$category]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для получения всех фотографий
function getAllPhotos() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM photos ORDER BY category, upload_date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>