<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: sign_in.php'); // ログインページにリダイレクト
        exit();
    }

    $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
    $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $beforeurl = $_SERVER['HTTP_REFERER'];
    $thisid = substr($beforeurl, 40);
    print($thisid);

    require_once 'dbindex.php';

    try {
        $pdo->beginTransaction(); 
        $sql = 'DELETE FROM ESG_memberList WHERE employee_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }

        $result = $stmt->fetchall();
        // print($result);

    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sql ='DELETE FROM ESG_memberInfo WHERE employee_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }
    
        $result = $stmt->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sql ='DELETE FROM ESG_memberInfoB WHERE key_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }
    
        $result = $stmt->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sql ='DELETE FROM ESG_memberPics WHERE key_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }
    
        $result = $stmt->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sql ='DELETE FROM ESG_memberPicsB WHERE key_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }
    
        $result = $stmt->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sql ='DELETE FROM ESG_memberSkills WHERE key_id =' .$thisid;
        print($sql);
        $stmt = $pdo->prepare($sql);
    
        $stmt->execute();

        if($stmt) {    
            $pdo->commit();
        }
    
        $result = $stmt->fetchall();
        // print($result);
        
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
    <p>削除が完了しました。<br><a href="top.php">戻る</a></p>
    </body>
</html>