<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = '../img/';
    $uploadFile = uniqid() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $uploadFile);

    $data = json_decode(file_get_contents('data.json'), true);
    $data[] = [
        'src' => $uploadFile,
        'alt' => $_POST['alt'],
        'text' => $_POST['text']
    ];
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header('Location: index.php');
}