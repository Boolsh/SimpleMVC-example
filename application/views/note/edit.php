<style> 
    
    textarea{
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

<h2><?= $editNoteTitle ?></h2>

<form id="editNote" method="post" action="<?= $Url::link("admin/notes/edit&id=" . $_GET['id'])?>">
    <h5>Note title</h5> 
    <input type="text" name="title" placeholder="name note" value="<?= htmlspecialchars($viewNotes->title) ?>"><br>
    
    <h5>Note content</h5>
    <textarea type="description" name="content" placeholder="контент"><?= htmlspecialchars($viewNotes->content) ?></textarea><br>
    

    <h5>Автор</h5>
    <select name="user_id" id="user_id">
        <option value="">Выберите автора</option>
        <?php foreach($users as $user): ?>
            <option value="<?= $user->id ?>" 
                <?= ($viewNotes->user_id == $user->id) ? 'selected' : '' ?>>
                <?= htmlspecialchars($user->login) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
    <input type="submit" name="saveChanges" value="Сохранить">
    <input type="submit" name="cancel" value="Назад">
</form>