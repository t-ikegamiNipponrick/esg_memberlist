<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];
    
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
    $hobby = htmlspecialchars($_POST['hobby_info'], ENT_QUOTES, 'UTF-8');
    $dispatched = htmlspecialchars($_POST['dispatched'], ENT_QUOTES, 'UTF-8');
    $tasks = htmlspecialchars($_POST['tasks'], ENT_QUOTES, 'UTF-8');
    $member_pr = htmlspecialchars($_POST['member_pr'], ENT_QUOTES, 'UTF-8');
    $S_dispatched = sanitaizeArray($_POST['dispatched_sofar']);
    $S_tasks = sanitaizeArray($_POST['tasks_sofar']);
    $tasks_detail = sanitaizeArray($_POST['tasks_detail']);
    $date_started = sanitaizeArray($_POST['tasks_sofarStart']);
    $date_finished = sanitaizeArray($_POST['tasks_sofarFin']);
    $skill_name = sanitaizeArray($_POST['skill_name']);
    $skill_date = sanitaizeArray($_POST['skill_date']);

    require_once 'dbindex.php';

    try {
        $pdo->beginTransaction(); 
        $indexsql = 'UPDATE ESG_member_index SET employee_id = :id, member_name = :name, member_from = :from, DateEntry = :entry, hobby_info = :hobby, dispatched = :dispatched, tasks = :tasks, member_pr = :member_pr WHERE employee_id = :thisid';
        $indexstmt = $pdo->prepare($indexsql);
        $indexstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
        $indexstmt->bindValue(':id', $id,   PDO::PARAM_INT);
        $indexstmt->bindValue(':name', $name,   PDO::PARAM_STR);
        $indexstmt->bindValue(':from', $from,    PDO::PARAM_STR);
        $indexstmt->bindValue(':entry', $entry,    PDO::PARAM_STR);
        $indexstmt->bindValue(':hobby', $hobby, PDO::PARAM_STR);
        $indexstmt->bindValue(':dispatched', $dispatched,   PDO::PARAM_STR);
        $indexstmt->bindValue(':tasks', $tasks,    PDO::PARAM_STR);
        $indexstmt->bindValue(':member_pr', $member_pr, PDO::PARAM_STR);

        $indexstmt->execute();

        if($indexstmt) {    
            $pdo->commit();
        }

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
        
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $dispatchedsql ='UPDATE ESG_member_dispatched SET key_id = :id, dispatched_sofar = :dispsof, tasks_sofar = :tasksof, tasks_detail = :detail, tasks_sofarStart = :sofsta, tasks_sofarFin = :soffin WHERE employee_id = :thisid';
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
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }

    for($i = 0; $i < $length; $i++) {
        try {
            $pdo->beginTransaction(); 
            $skillssql ='UPDATE ESG_member_skills SET key_id = :id, skill_name = :sname, skill_date = :sdate WHERE employee_id = :thisid';
            $skillsstmt = $pdo->prepare($skillssql);
            $skillsstmt->bindValue(':thisid', $thisid,   PDO::PARAM_INT);
            $skillsstmt->bindValue(':id', $id,   PDO::PARAM_INT);
            $skillsstmt->bindValue(':sname', $skill_name[$i],    PDO::PARAM_STR);
            $skillsstmt->bindValue(':sdate', $skill_date[$i],    PDO::PARAM_STR);
            $skillsstmt->execute();

            if($skillsstmt) {    
                $pdo->commit();
            }
            
        }catch(PDOException $e) {
            $pdo->rollback();
            throw $e;
        }
    }
    
    // 画像のアップデート
    define('UPLOADPASS', './img/');

    require_once 'dbindex.php';
    $sqlQuery = "SELECT COUNT(*) FROM ESG_member_picsid WHERE employee_id = ?";
    $stmtQuery = $pdo->prepare($sqlQuery);
    $stmtQuery->execute([$thisid]);
    $validateQ = $stmtQuery->fetchColumn();
    
    $name = $_FILES['photo']['name'];
    $type = $_FILES['photo']['type'];
    $size = $_FILES['photo']['size'];

    if(!empty($_FILES['photo']['tmp_name'])) {
        $content = file_get_contents($_FILES['photo']['tmp_name']);
    }

    $error = $_FILES['photo']['error'];
    
    $target=UPLOADPASS.$name;

    if(!empty($content)) {
        if($validateQ > 0) {
            if($_SERVER['REQUEST_METHOD']==='POST') {

                try {
                    $pdo->beginTransaction(); 
                    $picscontentsql = 'UPDATE ESG_member_picscontents SET key_id = :id, file_name = :name, file_type = :type, file_content = :content, file_size = :size WHERE key_id =' .$thisid;
                    $picscontentstmt = $pdo->prepare($picscontentsql);

                    $picscontentstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);
                    $picscontentstmt->bindValue(':name', $name,   PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':type', $type,    PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':content', $content,    PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':size', $size,   PDO::PARAM_INT);

                    $picscontentstmt->execute();

                    if($picscontentstmt) {    
                        $pdo->commit();
                    }
                    
                    $resultB = $picscontentstmt->fetchall();

                }catch(PDOException $e) {
                    $pdo->rollback();
                    throw $e;
                }

            }
        } else {
            if($_SERVER['REQUEST_METHOD']==='POST') {
                try {
                    $pdo->beginTransaction(); 
                    $picsidsql = 'INSERT INTO ESG_member_picsid VALUES (:employee_id, :key_id)';
                    $picsidstmt = $pdo->prepare($picsidsql);
            
                    $picsidstmt->bindValue(':employee_id', $thisid,    PDO::PARAM_INT);
                    $picsidstmt->bindValue(':key_id', $thisid,   PDO::PARAM_INT);
            
                    $picsidstmt->execute();
            
                    if($picsidstmt) {    
                        $pdo->commit();
                    }
            
                }catch(PDOException $e) {
                    $pdo->rollback();
                    throw $e;
                }

                try {
                    $pdo->beginTransaction(); 
                    $picscontentsql = 'INSERT INTO ESG_member_picscontents (key_id, file_name, file_type, file_content, file_size) VALUES (:id, :name, :type, :content, :size)';
                    $picscontentstmt = $pdo->prepare($picscontentsql);

                    $picscontentstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);
                    $picscontentstmt->bindValue(':name', $name,   PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':type', $type,    PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':content', $content,    PDO::PARAM_STR);
                    $picscontentstmt->bindValue(':size', $size,   PDO::PARAM_INT);

                    $picscontentstmt->execute();

                    if($picscontentstmt) {    
                        $pdo->commit();
                    }

                }catch(PDOException $e) {
                    $pdo->rollback();
                    throw $e;
                }

            }
        }
    }

    header('Location: memberinfo.php?id=' . $thisid);
?>