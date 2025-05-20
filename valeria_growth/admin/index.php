<?php
$data = json_decode(file_get_contents('../admin/data.json'), true);
?>
<h2>Управление фотографиями</h2>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <input type="text" name="alt" placeholder="Alt текст" required>
    <input type="text" name="text" placeholder="Текст под фото" required>
    <button type="submit">Загрузить</button>
</form>
<hr>
<ul>
    <?php foreach ($data as $key => $item): ?>
        <li>
            <img src="../img/<?php echo $item['src']; ?>" width="100">
            <strong><?php echo htmlspecialchars($item['text']); ?></strong>
            <a href="delete.php?id=<?php echo $key; ?>">Удалить</a> |
            <a href="edit.php?id=<?php echo $key; ?>">Редактировать</a>
        </li>
    <?php endforeach; ?>
</ul>