<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: sign_in.php'); // ログインページにリダイレクト
        exit();
    }

    function sanitaizeArray($array){
        foreach($array as $value) {
            if(is_array($value)) {
                $value = sanitaizeArray($value);
            }else{
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $array;
    }

    $id = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($_POST['member_name'], ENT_QUOTES, 'UTF-8');
    $from = htmlspecialchars($_POST['member_from'], ENT_QUOTES, 'UTF-8');
    $entry = htmlspecialchars($_POST['DateEntry'], ENT_QUOTES, 'UTF-8');
    $dispatched = htmlspecialchars($_POST['dispatched'], ENT_QUOTES, 'UTF-8');
    $tasks = htmlspecialchars($_POST['tasks'], ENT_QUOTES, 'UTF-8');
    $S_dispatched = sanitaizeArray($_POST['dispatched_sofar']);
    $S_tasks = sanitaizeArray($_POST['tasks_sofar']);
    $date_started = sanitaizeArray($_POST['tasks_sofarStart']);
    $date_finished = sanitaizeArray($_POST['tasks_sofarFin']);
    $tasks_detail = sanitaizeArray($_POST['tasks_detail']);
    $skill_name = sanitaizeArray($_POST['skill_name']);
    $skill_date = sanitaizeArray($_POST['skill_date']);

    if($id == null || $name == null || $from == null || $entry == null || $dispatched == null || $tasks == null || $S_dispatched == null
     || $S_tasks == null || $date_started == null || $date_finished == null || $tasks_detail == null || $skill_name == null || $skill_date == null) {
        header('Location: member_inputform.php');
        $errorMessage = '未入力の項目があります。';
    }

    // print($id. $name. $from. $entry. $dispatched. $tasks. $S_dispatched[1]. $S_tasks[1]. $date_started[1]. $date_finished[1]. $skill_name[1]. $skill_date[1]);

    $length = count($S_dispatched);
    // print($length);

    require_once 'dbindex.php';

    try {
        $indexsql = 'INSERT into ESG_member_index values (:id, :name, :from, :entry, :dispatched, :tasks)';
        $indexstmt = $pdo->prepare($indexsql);
        $pdo->beginTransaction(); 

        $indexstmt->bindValue(':id', $id,    PDO::PARAM_INT);
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
        throw $e;
    }

    try {
        $memberidsql ='INSERT into ESG_memberid_info values (?, ?)';
        $memberidstmt = $pdo->prepare($memberidsql);
        $pdo->beginTransaction();
    
        $memberidstmt->bindValue(1, $id,   PDO::PARAM_INT);
        $memberidstmt->bindValue(2, $id,   PDO::PARAM_INT);

        $memberidstmt->execute();

        if($memberidstmt) {    
            $pdo->commit();
        }
        
    }catch(PDOException $e) {
        throw $e;
    }

    for($i = 0; $i < $length; $i++) {
        try{
            $dispatchedsql = 'INSERT INTO ESG_member_dispatched (key_id, dispatched_sofar, tasks_sofar, tasks_detail, tasks_sofarStart, tasks_sofarFin) values (?, ?, ?, ?, ?, ?)';
            $dispatchedstmt = $pdo->prepare($dispatchedsql);
            $pdo->beginTransaction();
            
            $dispatchedstmt->bindValue(1, $id,    PDO::PARAM_INT);
            $dispatchedstmt->bindValue(2, $S_dispatched[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(3, $S_tasks[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(4, $tasks_detail[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(5, $date_started[$i],    PDO::PARAM_STR);
            $dispatchedstmt->bindValue(6, $date_finished[$i],   PDO::PARAM_STR);

            $dispatchedstmt->execute();

            if($dispatchedstmt) {
                $pdo->commit();
            }
            
        }catch(PDOException $e) {
            throw $e;
        }
    }

    for($i = 0; $i < $length; $i++) {
        try{
            $skillssql = 'INSERT INTO ESG_member_skills (key_id, skill_name, skill_date) values (?, ?, ?)';
            $skillsstmt = $pdo->prepare($skillssql);
            $pdo->beginTransaction();

            $skillsstmt->bindValue(1, $id,    PDO::PARAM_INT);
            $skillsstmt->bindValue(2, $skill_name[$i],    PDO::PARAM_STR);
            $skillsstmt->bindValue(3, $skill_date[$i],    PDO::PARAM_STR);

            $skillsstmt->execute();

            if($skillsstmt) {
                $pdo->commit();
            }
            
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