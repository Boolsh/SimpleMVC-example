<style> 
    textarea {
        height: 200%;
        width: 1110px;
        color: #003300;
    }
</style>

<?php 
use ItForFree\SimpleMVC\Config;

$Url = Config::getObject('core.router.class');
$User = Config::getObject('core.user.class');
?>

<?php include('includes/admin-notes-nav.php'); ?>

<h2><?= $addNoteTitle ?></h2>

<form id="addNote" method="post" action="<?= $Url::link("admin/notes/add") ?>">
    
    <h5>Заголовок заметки</h5> 
    <input type="text" name="title" placeholder="Введите заголовок" value="" required><br>
    
    <h5>Содержание заметки</h5>
    <textarea name="content" placeholder="Введите содержание заметки" required></textarea><br>
    
    <!-- ДОБАВЛЕНО: Выбор автора -->
    <h5>Автор</h5>
    <select name="user_id" id="user_id" required>
        <option value="">-- Выберите автора --</option>
        <?php if (!empty($users)): ?>
            <?php foreach($users as $user): ?>
                <option value="<?= $user->id ?>">
                    <?= htmlspecialchars($user->login) ?>
                    <?php if (isset($user->email)): ?>
                        (<?= htmlspecialchars($user->email) ?>)
                    <?php endif; ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <br><br>
    
    <input type="submit" name="saveNewNote" value="Сохранить">
    <input type="submit" name="cancel" value="Назад">
</form>