<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

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
        $indexsql = 'UPDATE ESG_member_index SET employee_id = :id, member_name = :name, member_from = :from, DateEntry = :entry, dispatched = :dispatched, tasks = :tasks WHERE employee_id = :thisid';
        // print($sqlA);
        $indexstmt = $pdo->prepare($indexsql);
        $indexstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
        $indexstmt->bindValue(':id', $id,   PDO::PARAM_INT);
        $indexstmt->bindValue(':name', $name,   PDO::PARAM_STR);
        $indexstmt->bindValue(':from', $from,    PDO::PARAM_STR);
        $indexstmt->bindValue(':entry', $entry,    PDO::PARAM_STR);
        $indexstmt->bindValue(':dispatched', $dispatched,   PDO::PARAM_STR);
        $indexstmt->bindValue(':tasks', $tasks,    PDO::PARAM_STR);

        $indexstmt->execute();

        if($indexstmt) {    
            $pdo->commit();
        }

        $indexresult = $indexstmt->fetchall();
        // print($result);

    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    try {
        $pdo->beginTransaction(); 
        $memberidsql ='UPDATE ESG_memberid_info SET employee_id = :id, key_id = :key_id WHERE employee_id = :thisid';
        $memberidstmt = $pdo->prepare($memberidsql);
        $memberidstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
        $memberidstmt->bindValue(':id', $id,   PDO::PARAM_INT);
        $memberidstmt->bindValue(':key_id', $id,   PDO::PARAM_INT);
        $memberidstmt->execute();

        if($memberidstmt) {    
            $pdo->commit();
        }
    
        $memberidresult = $memberidstmt->fetchall();
        // print($result);
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $dispatchedsql ='UPDATE ESG_member_dispatched SET key_id = :id, dispatched_sofar = :dispsof, tasks_sofar = :tasksof, tasks_detail = :detail, tasks_sofarStart = :sofsta, tasks_sofarFin = :soffin WHERE employee_id = :thisid';
            // print($sqlC);
            $dispatchedstmt = $pdo->prepare($dispatchedsql);
            $dispatchedstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
            $dispatchedstmt->bindValue(':id', $id,   PDO::PARAM_INT);
            $dispatchedstmt->bindValue(':dispsof', $S_dispatched[$i],   PDO::PARAM_STR);
            $dispatchedstmt->bindValue(':tasksof', $S_tasks[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(':detail', $tasks_detail[$i],  PDO::PARAM_STR);
            $dispatchedstmt->bindValue(':sofsta', $date_started[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(':soffin', $date_finished[$i],   PDO::PARAM_STR);
            $dispatchedstmt->execute();

            if($dispatchedstmt) {    
                $pdo->commit();
            }
        
            $dispatchedresult = $dispatchedstmt->fetchall();
            // print($result);
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $skillssql ='UPDATE ESG_memberS_skills SET key_id = :id, skill_name = :sname, skill_date = :sdate WHERE employee_id = :thisid';
            // print($sqlD);
            $skillsstmt = $pdo->prepare($skillssql);
            $skillsstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
            $skillsstmt->bindValue(':id', $id,   PDO::PARAM_INT);
            $skillsstmt->bindValue(':sname', $skill_name[$i],    PDO::PARAM_STR);
            $skillsstmt->bindValue(':sdate', $skill_date[$i],    PDO::PARAM_STR);
            $skillsstmt->execute();

            if($skillsstmt) {    
                $pdo->commit();
            }
        
            $skillsresult = $skillsstmt->fetchall();
            // print($result);
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }
    header('Location: memberinfo.php?id=' . $thisid);
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
    <p>更新が完了しました。<br><?php print'<a href="memberinfo.php?id=' . $thisid . '">戻る</a>'; ?></p>
    </body>
</html>