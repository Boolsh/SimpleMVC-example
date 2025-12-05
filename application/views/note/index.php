<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>

<?php include('includes/admin-notes-nav.php'); ?>

<h2>Список заметок</h2>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Заголовок</th>
            <th>Автор</th>
            <th>Содержание</th>
            <th>Дата публикации</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($notes)): ?>
            <?php foreach($notes as $note): ?>
            <tr>
                <td><?= $note->id ?></td>
                <td><?= htmlspecialchars($note->title) ?></td>
                <td><?= htmlspecialchars($note->author_name ?? 'Неизвестный автор') ?></td>
                <td><?= htmlspecialchars(mb_substr($note->content, 0, 100)) . '...' ?></td>
                <td><?= date('j M Y', strtotime($note->publicationDate)) ?></td>
                <td>
                    <a href="<?= $Url::link('admin/notes/edit&id=' . $note->id) ?>">Редактировать</a> | 
                    <a href="<?= $Url::link('admin/notes/delete&id=' . $note->id) ?>">Удалить</a> | 
                    <a href="<?= $Url::link('admin/notes/index&id=' . $note->id) ?>">Просмотреть</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Заметок нет</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>