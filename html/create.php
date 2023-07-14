<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php'); // ログインページにリダイレクト
        exit();
    }

    $id = $_POST['employee_id'];
    $name = $_POST['member_name'];
    $from = $_POST['member_from'];
    $entry = $_POST['DateEntry'];
    $dispatched = $_POST['dispatched'];
    $tasks = $_POST['tasks'];
    $S_dispatched = $_POST['dispatched_sofar'];
    $S_tasks = $_POST['tasks_sofar'];
    $date_started = $_POST['tasks_sofarStart'];
    $date_finished = $_POST['tasks_sofarFin'];
    $tasks_detail = $_POST['tasks_detail'];
    $skill_name = $_POST['skill_name'];
    $skill_date = $_POST['skill_date'];

    // print($id. $name. $from. $entry. $dispatched. $tasks. $S_dispatched[1]. $S_tasks[1]. $date_started[1]. $date_finished[1]. $skill_name[1]. $skill_date[1]);

    $length = count($S_dispatched);
    // print($length);

    require_once 'dbindex.php';

    try {
        $sqlA = 'INSERT into ESG_memberList values (:id, :name, :from, :entry, :dispatched, :tasks)';
        $stmtA = $pdo->prepare($sqlA);
        $pdo->beginTransaction(); 

        $stmtA->bindValue(':id', $id,    PDO::PARAM_INT);
        $stmtA->bindValue(':name', $name,   PDO::PARAM_STR);
        $stmtA->bindValue(':from', $from,    PDO::PARAM_STR);
        $stmtA->bindValue(':entry', $entry,    PDO::PARAM_STR);
        $stmtA->bindValue(':dispatched', $dispatched,   PDO::PARAM_STR);
        $stmtA->bindValue(':tasks', $tasks,    PDO::PARAM_STR);

        $stmtA->execute();

        if($stmtA) {    
            $pdo->commit();
        }
        
        $resultA = $stmtA->fetchall();
        // print($result);

    }catch(PDOException $e) {
        throw $e;
    }

    try {
        $sqlB ='INSERT into ESG_memberInfo values (?, ?)';
        $stmtB = $pdo->prepare($sqlB);
        $pdo->beginTransaction();
    
        $stmtB->bindValue(1, $id,    PDO::PARAM_INT);
        $stmtB->bindValue(2, $id,   PDO::PARAM_INT);

        $stmtB->execute();

        if($stmtB) {    
            $pdo->commit();
        }
    
        $resultB = $stmtB->fetchall();
        
        // print($result);
        
    }catch(PDOException $e) {
        throw $e;
    }

    for($i = 0; $i < $length; $i++) {
        try{
            $sqlc = 'INSERT INTO ESG_memberInfoB (key_id, dispatched_sofar, tasks_sofar, tasks_detail, tasks_sofarStart, tasks_sofarFin) values (?, ?, ?, ?, ?, ?)';
            $stmtC = $pdo->prepare($sqlc);
            $pdo->beginTransaction();
            
            $stmtC->bindValue(1, $id,    PDO::PARAM_INT);
            $stmtC->bindValue(2, $S_dispatched[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(3, $S_tasks[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(4, $tasks_detail[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(5, $date_started[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(6, $date_finished[$i],   PDO::PARAM_STR);

            $stmtC->execute();

            if($stmtC) {
                $pdo->commit();
            }

            $resultC = $stmtC->fetchall();
            
        }catch(PDOException $e) {
            throw $e;
        }
    }

    for($i = 0; $i < $length; $i++) {
        try{
            $sqlc = 'INSERT INTO ESG_memberSkills (key_id, skill_name, skill_date) values (?, ?, ?)';
            $stmtC = $pdo->prepare($sqlc);
            $pdo->beginTransaction();

            $stmtC->bindValue(1, $id,    PDO::PARAM_INT);
            $stmtC->bindValue(2, $skill_name[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(3, $skill_date[$i],    PDO::PARAM_STR);

            $stmtC->execute();

            if($stmtC) {
                $pdo->commit();
            }

            $resultC = $stmtC->fetchall();
            
        }catch(PDOException $e) {
            throw $e;
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>登録結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <p>登録が完了しました。<br><a href="top.php">戻る</a></p>
    </body>
</html>