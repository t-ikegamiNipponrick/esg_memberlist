<?php

$protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
$thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$beforeurl = $_SERVER['HTTP_REFERER'];
$thisid = substr($beforeurl, 47);
// print($thisid);

define('UPLOADPASS', './img/');

require_once 'dbindex.php';
$sqlQuery = "SELECT COUNT(*) FROM ESG_memberPics WHERE employee_id = ?";
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
			$sqlB = 'UPDATE ESG_memberPicsB SET key_id = :id, file_name = :name, file_type = :type, file_content = :content, file_size = :size WHERE key_id =' .$thisid;
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
			$sql = 'UPDATE ESG_memberPics SET employee_id = :employee_id, key_id = :key_id';
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
			$sqlB = 'UPDATE ESG_memberPicsB SET key_id = :id, file_name = :name, file_type = :type, file_content = :content, file_size = :size WHERE key_id =' .$thisid;
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
		<form enctype="multipart/form-data" action="upload_test.php" method="POST">
			<input type="file" name="photo">
			<input type="submit" value="画像をアップロード">
		</form>
		<p>
		<img src="<?php print UPLOADPASS.$name ?>">
		<a href="top.php">戻る</a>
	
	</body>
</html>