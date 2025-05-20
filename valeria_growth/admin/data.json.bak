<?php
$id = intval($_GET['id']);
$data = json_decode(file_get_contents('data.json'), true);
$item = $data[$id];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data[$id]['alt'] = $_POST['alt'];
    $data[$id]['text'] = $_POST['text'];
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: index.php');
}
?>
<form method="post">
    <input type="text" name="alt" value="<?php echo htmlspecialchars($item['alt']); ?>" required>
    <input type="text" name="text" value="<?php echo htmlspecialchars($item['text']); ?>" required>
    <button type="submit">Сохранить</button>
</form>