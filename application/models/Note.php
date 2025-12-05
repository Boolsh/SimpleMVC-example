<?php
namespace application\models;
/* 
 * class Note
 * 
 * 
 */

class Note extends BaseExampleModel {
    
    public string $tableName = "notes";
    
    public string $orderBy = 'publicationDate ASC';
    
    public ?int $id = null;
    
    public $title = null;
    
    public $content = null;
    
    public $publicationDate = null;
    
    public $user_id = null; 
    
    public $author_name = null; // Добавьте это свойство для JOIN
    
    /**
     * Получить список заметок с именами авторов
     */
    public function getList($numRows = 1000000, $order = null): array
    {
        if ($order) {
            $this->orderBy = $order;
        }
        
        // SQL с JOIN для получения имени автора
        $sql = "SELECT n.*, u.login as author_name 
                FROM $this->tableName n 
                LEFT JOIN users u ON n.user_id = u.id 
                ORDER BY $this->orderBy 
                LIMIT :numRows";
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":numRows", $numRows, \PDO::PARAM_INT);
        $st->execute();
        
        $list = [];
        while ($row = $st->fetch()) {
            $note = new Note();
            $note->id = $row['id'];
            $note->publicationDate = $row['publicationDate'];
            $note->title = $row['title'];
            $note->content = $row['content'];
            $note->user_id = $row['user_id'];
            $note->author_name = $row['author_name'] ?? 'Неизвестный автор';
            $list[] = $note;
        }
        
        // Получаем общее количество строк
        $sqlTotal = "SELECT COUNT(*) as total FROM $this->tableName";
        $stTotal = $this->pdo->query($sqlTotal);
        $totalRows = $stTotal->fetch()['total'];
        
        return [
            "results" => $list,
            "totalRows" => $totalRows
        ];
    }
    
    /**
     * Получить заметку по ID с именем автора
     */
    public function getById($id)
    {
        $sql = "SELECT n.*, u.login as author_name 
                FROM $this->tableName n 
                LEFT JOIN users u ON n.user_id = u.id 
                WHERE n.id = :id";
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, \PDO::PARAM_INT);
        $st->execute();
        
        if ($row = $st->fetch()) {
            $note = new Note();
            $note->id = $row['id'];
            $note->publicationDate = $row['publicationDate'];
            $note->title = $row['title'];
            $note->content = $row['content'];
            $note->user_id = $row['user_id'];
            $note->author_name = $row['author_name'] ?? 'Неизвестный автор';
            return $note;
        }
        
        return null;
    }
    
    public function insert()
    {
        $sql = "INSERT INTO $this->tableName (title, content, publicationDate, user_id) 
                VALUES (:title, :content, :publicationDate, :user_id)"; 
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":publicationDate", (new \DateTime('NOW'))->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $st->bindValue(":title", $this->title, \PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, \PDO::PARAM_STR);
        $st->bindValue(":user_id", $this->user_id, \PDO::PARAM_INT);
        
        $st->execute();
        $this->id = $this->pdo->lastInsertId();
    }
    
    public function update()
    {
        $sql = "UPDATE $this->tableName SET 
                publicationDate = :publicationDate, 
                title = :title, 
                content = :content, 
                user_id = :user_id 
                WHERE id = :id";  
        
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":publicationDate", (new \DateTime('NOW'))->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
        $st->bindValue(":title", $this->title, \PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, \PDO::PARAM_STR);
        $st->bindValue(":user_id", $this->user_id, \PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, \PDO::PARAM_INT);
        
        $st->execute();
    }
    
    /**
     * Получить имя автора заметки (альтернативный метод, если не используется JOIN)
     */
    public function getAuthorName(): string
    {
        if ($this->user_id) {
            $sql = "SELECT login FROM users WHERE id = :user_id";
            $st = $this->pdo->prepare($sql);
            $st->bindValue(":user_id", $this->user_id, \PDO::PARAM_INT);
            $st->execute();
            $result = $st->fetch();
            
            return $result['login'] ?? 'Неизвестный автор';
        }
        
        return 'Неизвестный автор';
    }
}