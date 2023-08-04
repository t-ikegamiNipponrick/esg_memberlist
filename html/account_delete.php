<?php 
$id = $_GET['id'];
require_once 'dbindex.php';

try {
    $pdo->beginTransaction(); 
    $sql = 'DELETE FROM ESG_login WHERE user_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }

}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql = 'DELETE FROM ESG_member_index WHERE employee_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }

}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql ='DELETE FROM ESG_memberid_info WHERE employee_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }
    
}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql ='DELETE FROM ESG_member_dispatched WHERE key_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }
    
}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql ='DELETE FROM ESG_member_picsid WHERE key_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }
    
}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql ='DELETE FROM ESG_member_picscontents WHERE key_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }
    
}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

try {
    $pdo->beginTransaction(); 
    $sql ='DELETE FROM ESG_member_skills WHERE key_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt) {    
        $pdo->commit();
    }
    
}catch(PDOException $e) {
    $pdo->rollback();
    throw $e;
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>削除結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <p>削除が完了しました。<br><a href="account_list.php">戻る</a></p>
    </body>
</html>