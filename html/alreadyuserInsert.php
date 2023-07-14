<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $thisid = substr($beforeurl, 50);
 // print($thisid);

 session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // ログインページにリダイレクト
    exit();
}

 $S_dispatched = $_POST['dispatched_sofar'];
 $S_tasks = $_POST['tasks_sofar'];
 $tasks_detail = $_POST['tasks_detail'];
 $date_started = $_POST['tasks_sofarStart'];
 $date_finished = $_POST['tasks_sofarFin'];
 $skill_name = $_POST['skill_name'];
 $skill_date = $_POST['skill_date'];
     // print($id. $name. $from. $entry. $dispatched. $tasks. $S_dispatched. $S_tasks. $date_started. $date_finished. $skill_name. $skill_date);

 $lengthpostD = count($S_tasks);
 $lengthpostS = count($skill_name);
 // print($lengthpostS);

 require_once 'dbindex.php';

 try{
    $pdo->beginTransaction();
    $sqlA = "SELECT * FROM ESG_memberInfoB WHERE key_id =" .$thisid;
    $stmtA = $pdo->prepare($sqlA);

    $stmtA->execute();
    if($stmtA) {    
        $pdo->commit();
    }

    $resultA = $stmtA->fetchall();
}catch(PDOException $e) {
    throw $e;
}

try{
    $pdo->beginTransaction();
    $sqlB = "SELECT * FROM ESG_memberSkills WHERE key_id =" .$thisid;
    $stmtB = $pdo->prepare($sqlB);

    $stmtB->execute();
    if($stmtB) {    
        $pdo->commit();
    }

    $resultB = $stmtB->fetchall();
}catch(PDOException $e) {
    throw $e;
}

$columncountA = count($resultA);
// print($columncountA);

$columncountB = count($resultB);
// print($columncountB);

for($i = $columncountA; $i < $columncountA + $lengthpostD; $i++) {
    if(!empty($S_dispatched[$i]) && !empty($S_tasks[$i])) {
        try{
            $sqlC = 'INSERT INTO ESG_memberInfoB (key_id, dispatched_sofar, tasks_sofar, tasks_detail, tasks_sofarStart, tasks_sofarFin) values (:key_id, :dispsof, :tasksof, :detail, :sofStart, :sofFin)';
            $stmtC = $pdo->prepare($sqlC);
            $pdo->beginTransaction();
            
            $stmtC->bindValue(':key_id', $thisid,    PDO::PARAM_INT);
            $stmtC->bindValue(':dispsof', $S_dispatched[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':tasksof', $S_tasks[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':detail', $tasks_detail[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':sofStart', $date_started[$i],    PDO::PARAM_STR);
            $stmtC->bindValue(':sofFin', $date_finished[$i],   PDO::PARAM_STR);

            $stmtC->execute();

            if($stmtC) {
                $pdo->commit();
            }
            
        }catch(PDOException $e) {
            throw $e;
        }
    }
}

print($lengthpostS . $columncountB);

for($i = $columncountB; $i < $columncountB + $lengthpostS; $i++) {
    if(!empty($skill_name[$i]) && !empty($skill_date[$i])) {
        try{
            $sqlD = 'INSERT INTO ESG_memberSkills (key_id, skill_name, skill_date) values (:key_id, :skill_name, :skill_date)';
            $stmtD = $pdo->prepare($sqlD);
            $pdo->beginTransaction();
            
            $stmtD->bindValue(':key_id', $thisid,    PDO::PARAM_INT);
            $stmtD->bindValue(':skill_name', $skill_name[$i],    PDO::PARAM_STR);
            $stmtD->bindValue(':skill_date', $skill_date[$i],    PDO::PARAM_STR);

            $stmtD->execute();

            if($stmtD) {
                $pdo->commit();
            }
            
        }catch(PDOException $e) {
            throw $e;
        }
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
    <p>追加が完了しました。<br>
    <div>&nbsp;</div>
    <a href="top.php">戻る</a></p>
    </body>
</html>