<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<body>
<?php
if(!empty($_POST["comment"]))
    if(!empty($_POST["name"])){
        $comment = $_POST["comment"];
        $name = $_POST["name"];
        $pass = $_POST["pass"];
        $postedAt = date("Y年m月d日 H:i:s");
    }


//table create
$sql = "CREATE TABLE IF NOT EXISTS tb"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "pass char(32),"
. "date char(32),"
. "comment TEXT"
.");";
$stmt = $pdo->query($sql);
?>

<?php
//削除フォーム
if(!empty($_POST["dnum"]) && !empty($_POST["delpass"])){
    $delete = $_POST["dnum"];
    $delpass = $_POST["delpass"];
    $sql = 'SELECT * FROM tb';
    $result = $pdo -> query($sql);
    foreach($result as $row){
        if($row['id'] == $delete && $row['pass'] == $delpass){
            $id = $delete;
            $sql = 'delete from tb where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        elseif($row['id'] == $delete && $row['pass'] !== $delpass){
            echo "パスワードが違います";
        }
    }
}

//編集フォームの送信の有無
if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
    $edit = $_POST["edit"];
    $editpass = $_POST["editpass"];
    $sql = 'SELECT * FROM tb';
    $result = $pdo -> query($sql);    
    foreach($result as $row){
        if($row['id'] == $edit){
            $editnum = $row['id'];
            $editname = $row['name'];
            $editcomment = $row['comment'];
            $editpassword = $row['pass'];
        }
    }
}

//編集機能
if(!empty($_POST["num"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
    $num = $_POST["num"];
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $pass = $_POST["pass"];
    $postedAt = date("Y年m月d日 H:i:s");
    $sql = 'SELECT * FROM tb';
    $result = $pdo -> query($sql); 
    foreach($result as $row){
        if($row['id'] == $num){
            //投稿番号の変更
            $id = $num;
            $name = $_POST['name'];
            $comment = $_POST['comment'];
            $sql = 'UPDATE tb SET name=:name, comment=:comment, date=:date, pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindValue(':date', $postedAt, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}
else{
    (!empty($_POST["name"]));
    if(!empty($_POST["comment"]) && !empty($_POST["pass"])){
        $postedAt = date("Y年m月d日 H:i:s");
        $stmt = $pdo->query($sql);
        $sql = $pdo -> prepare("INSERT INTO tb (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql->bindValue(':date', $postedAt, PDO::PARAM_STR);
        $sql->bindParam(':pass', $pass, PDO::PARAM_STR);        
        $sql->execute();
    }
}
?>


<form action="" method="post">
    <div>
        <label>【投稿フォーム】</label><br>
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["editpass"]) && $editpassword == $editpass){echo $editname;}?>"><br>        
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["editpass"]) && $editpassword == $editpass){echo $editcomment;}?>"><br>
        <input hidden="number" name="num" placeholder="投稿番号" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["editpass"]) && $editpassword == $editpass){echo $editnum;}?>">
        <input type="text" name="pass" placeholder="パスワード">            
        <input type="submit" name="submit" value="送信">
    </div>
</form>
<form action="" method="post">
    <div>
        <label>【削除フォーム】</label><br>
        <input type="number" name="dnum" placeholder="削除対象番号"><br>
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="delete" value="削除">
    </div>
</form>
<form action="" method="post">
    <div>
        <label>【編集フォーム】</label><br>
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" value="編集">
    </div>
</form>

<?php
//table echo
$sql = 'SELECT * FROM tb';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo (int)$row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'].'<br>';
    echo "<hr>";
}
?>

</body>
</html>    