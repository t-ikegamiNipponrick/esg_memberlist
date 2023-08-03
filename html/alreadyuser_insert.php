<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

 session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

function sanitaizeArray($array){
    foreach($array as $value) {
        if(is_array($value)) {
            $value = sanitaizeArray($value);
        } else {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    return $array;
}

 $S_dispatched = sanitaizeArray($_POST['dispatched_sofar']);
 $S_tasks = sanitaizeArray($_POST['tasks_sofar']);
 $tasks_detail = sanitaizeArray($_POST['tasks_detail']);
 $date_started = sanitaizeArray($_POST['tasks_sofarStart']);
 $date_finished = sanitaizeArray($_POST['tasks_sofarFin']);
 $skill_name = sanitaizeArray($_POST['skill_name']);
 $skill_date = sanitaizeArray($_POST['skill_date']);

 $lengthpostD = count($S_tasks);
 $lengthpostS = count($skill_name);
 // print($lengthpostS);

 require_once 'dbindex.php';

try{
$pdo->beginTransaction();
$dispatchedsql = "SELECT * FROM ESG_member_dispatched WHERE key_id = :id";
$dispatchedstmt = $pdo->prepare($dispatchedsql);
$dispatchedstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

$dispatchedstmt->execute();
if($dispatchedstmt) {    
    $pdo->commit();
}

    $dispatchedresult = $dispatchedstmt->fetchall();
}catch(PDOException $e) {
    throw $e;
}

try{
    $pdo->beginTransaction();
    $skillssql = "SELECT * FROM ESG_member_skills WHERE key_id = :id";
    $skillsstmt = $pdo->prepare($skillssql);
    $skillsstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

    $skillsstmt->execute();
    if($skillsstmt) {    
        $pdo->commit();
    }

    $skillsresult = $skillsstmt->fetchall();
}catch(PDOException $e) {
    throw $e;
}

$columncountA = count($dispatchedresult);
// print($columncountA);

$columncountB = count($skillsresult);
// print($columncountB);

for($i = $columncountA; $i < $columncountA + $lengthpostD; $i++) {
    if(!empty($S_dispatched[$i]) && !empty($S_tasks[$i])) {
        try{
            $disp_insertsql = 'INSERT INTO ESG_member_dispatched (key_id, dispatched_sofar, tasks_sofar, tasks_detail, tasks_sofarStart, tasks_sofarFin) values (:key_id, :dispsof, :tasksof, :detail, :sofStart, :sofFin)';
            $disp_insertstmt = $pdo->prepare($disp_insertsql);
            $pdo->beginTransaction();
            
            $disp_insertstmt->bindValue(':key_id', $thisid,    PDO::PARAM_INT);
            $disp_insertstmt->bindValue(':dispsof', $S_dispatched[$i],    PDO::PARAM_STR);
            $disp_insertstmt->bindValue(':tasksof', $S_tasks[$i],    PDO::PARAM_STR);
            $disp_insertstmt->bindValue(':detail', $tasks_detail[$i],    PDO::PARAM_STR);
            $disp_insertstmt->bindValue(':sofStart', $date_started[$i],    PDO::PARAM_STR);
            $disp_insertstmt->bindValue(':sofFin', $date_finished[$i],   PDO::PARAM_STR);

            $disp_insertstmt->execute();

            if($disp_insertstmt) {
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
            $skills_insertsql = 'INSERT INTO ESG_member_skills (key_id, skill_name, skill_date) values (:key_id, :skill_name, :skill_date)';
            $skills_insertstmt = $pdo->prepare($skills_insertsql);
            $pdo->beginTransaction();
            
            $skills_insertstmt->bindValue(':key_id', $thisid,    PDO::PARAM_INT);
            $skills_insertstmt->bindValue(':skill_name', $skill_name[$i],    PDO::PARAM_STR);
            $skills_insertstmt->bindValue(':skill_date', $skill_date[$i],    PDO::PARAM_STR);

            $skills_insertstmt->execute();

            if($skills_insertstmt) {
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