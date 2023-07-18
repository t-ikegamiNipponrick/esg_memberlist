<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

define('UPLOADPASS', './img/');

require_once 'dbindex.php';
$sqlQuery = "SELECT COUNT(*) FROM ESG_member_picsid WHERE employee_id = ?";
$stmtQuery = $pdo->prepare($sqlQuery);
$stmtQuery->execute([$thisid]);
$validateQ = $stmtQuery->fetchColumn();

if($validateQ > 0) {
	if($_SERVER['REQUEST_METHOD']==='POST') {
		//var_dump($_FILES);
		$name = $_FILES['photo']['name'];
		$type = $_FILES['photo']['type'];
		$size = $_FILES['photo']['size'];
		$content = file_get_contents($_FILES['photo']['tmp_name']);
		$error = $_FILES['photo']['error'];
		
		$target=UPLOADPASS.$name;

		try {
			$pdo->beginTransaction(); 
			$sqlB = 'INSERT into ESG_member_picscontents (key_id, file_name, file_type, file_content, file_size) values (:id, :name, :type, :content, :size)';
			$stmtB = $pdo->prepare($sqlB);

			$stmtB->bindValue(':id', $thisid,    PDO::PARAM_INT);
			$stmtB->bindValue(':name', $name,   PDO::PARAM_STR);
			$stmtB->bindValue(':type', $type,    PDO::PARAM_STR);
			$stmtB->bindValue(':content', $content,    PDO::PARAM_STR);
			$stmtB->bindValue(':size', $size,   PDO::PARAM_INT);

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

		if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
			print 'OK';
		}else {
			print 'down';
		}

	}
} else {
	if($_SERVER['REQUEST_METHOD']==='POST') {
		//var_dump($_FILES);
		$name = $_FILES['photo']['name'];
		$type = $_FILES['photo']['type'];
		$size = $_FILES['photo']['size'];
		$content = file_get_contents($_FILES['photo']['tmp_name']);
		$error = $_FILES['photo']['error'];
		
		$target=UPLOADPASS.$name;

		try {
			$pdo->beginTransaction(); 
			$sql = 'INSERT into ESG_member_picsid values (:employee_id, :key_id)';
			$stmt = $pdo->prepare($sql);
	
			$stmt->bindValue(':employee_id', $thisid,    PDO::PARAM_INT);
			$stmt->bindValue(':key_id', $thisid,   PDO::PARAM_INT);
	
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
			$sqlB = 'INSERT into ESG_member_picscontents (key_id, file_name, file_type, file_content, file_size) values (:id, :name, :type, :content, :size)';
			$stmtB = $pdo->prepare($sqlB);

			$stmtB->bindValue(':id', $thisid,    PDO::PARAM_INT);
			$stmtB->bindValue(':name', $name,   PDO::PARAM_STR);
			$stmtB->bindValue(':type', $type,    PDO::PARAM_STR);
			$stmtB->bindValue(':content', $content,    PDO::PARAM_STR);
			$stmtB->bindValue(':size', $size,   PDO::PARAM_INT);

			$stmtB->execute();

			if($stmtB) {    
				$pdo->commit();
			}
			
			$resultB = $stmt->fetchall();
			// print($result);

		}catch(PDOException $e) {
			$pdo->rollback();
			throw $e;
		}

		if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
			print 'OK';
		}else {
			print 'down';
		}

	}
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>upload処理</title>
	</head>
	<body>
		<p>アップロード完了！</p>
		<img src="<?php print UPLOADPASS.$name ?>">
		<a href="top.php">戻る</a>
	
	</body>
</html>