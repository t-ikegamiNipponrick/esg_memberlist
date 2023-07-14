<?php
    $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
    $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    $beforeurl = $_SERVER['HTTP_REFERER'];
    $thisid = substr($beforeurl, 46);
    print($thisid);

    $id = $_POST['employee_id'];
    $name = $_POST['member_name'];
    $from = $_POST['member_from'];
    $entry = $_POST['DateEntry'];
    $dispatched = $_POST['dispatched'];
    $tasks = $_POST['tasks'];
    $S_dispatched = $_POST['dispatched_sofar'];
    $S_tasks = $_POST['tasks_sofar'];
    $tasks_detail = $_POST['tasks_detail'];
    $date_started = $_POST['tasks_sofarStart'];
    $date_finished = $_POST['tasks_sofarFin'];
    $skill_name = $_POST['skill_name'];
    $skill_date = $_POST['skill_date'];

    // print($id. $name. $from. $entry. $dispatched. $tasks. $S_dispatched. $S_tasks. $date_started. $date_finished. $skill_name. $skill_date);

    require_once 'dbindex.php';

    try {
        $pdo->beginTransaction(); 
        $sqlA = 'UPDATE ESG_memberList SET employee_id = :id, member_name = :name, member_from = :from, DateEntry = :entry, dispatched = :dispatched, tasks = :tasks WHERE employee_id =' .$thisid;
        print($sqlA);
        $stmtA = $pdo->prepare($sqlA);
        
        $stmtA->bindValue(':id', $id,   PDO::PARAM_INT);
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
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $sqlB ='UPDATE ESG_memberInfo SET employee_id = :id, key_id = :key_id WHERE employee_id =' .$thisid;
        print($sqlB);
        $stmtB = $pdo->prepare($sqlB);
        $stmtB->bindValue(':id', $id,   PDO::PARAM_INT);
        $stmtB->bindValue(':key_id', $id,   PDO::PARAM_INT);
        $stmtB->execute();

        if($stmtB) {    
            $pdo->commit();
        }
    
        $resultB = $stmtB->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $sqlC ='UPDATE ESG_memberInfoB SET key_id = :id, dispatched_sofar = :dispsof, tasks_sofar = :tasksof, tasks_detail = :detail, tasks_sofarStart = :sofsta, tasks_sofarFin = :soffin WHERE employee_id =' .$thisid;
            print($sqlC);
            $stmtC = $pdo->prepare($sqlC);
            $stmtC->bindValue(':id', $id,   PDO::PARAM_INT);
            $stmtC->bindValue(':dispsof', $S_dispatched[$i],   PDO::PARAM_STR);
            $stmtC->bindValue(':tasksof', $S_tasks[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':detail', $tasks_detail[$i],  PDO::PARAM_STR);
            $stmtC->bindValue(':sofsta', $date_started[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':soffin', $date_finished[$i],   PDO::PARAM_STR);
            $stmtC->execute();

            if($stmtC) {    
                $pdo->commit();
            }
        
            $resultC = $stmtC->fetchall();
            // print($result);
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $sqlD ='UPDATE ESG_memberSKills SET key_id = :id, skill_name = :sname, skill_date = :sdate WHERE employee_id =' .$thisid;
            print($sqlD);
            $stmtD = $pdo->prepare($sqlD);
            $stmtD->bindValue(':id', $id,   PDO::PARAM_INT);
            $stmtD->bindValue(':sname', $skill_name[$i],    PDO::PARAM_STR);
            $stmtD->bindValue(':sdate', $skill_date[$i],    PDO::PARAM_STR);
            $stmtD->execute();

            if($stmtD) {    
                $pdo->commit();
            }
        
            $resultD = $stmtD->fetchall();
            // print($result);
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>更新結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <p>更新が完了しました。<br><a href="top.php">戻る</a></p>
    </body>
</html>