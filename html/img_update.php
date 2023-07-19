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
			$picsidsql = 'UPDATE ESG_member_picsid SET employee_id = :employee_id, key_id = :key_id';
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
		<p>画像のアップデートが完了しました。</p>
		<img src="<?php print UPLOADPASS.$name ?>">
		<a href="top.php">戻る</a>
	
	</body>
</html>