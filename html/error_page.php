<?php
session_start(); 
session_unset(); 
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>エラーページ</title>
</head>
<body>
    <h1>エラーが発生しました</h1>
    <p>不正なURLまたは無効なデータが送信されました。自動でログアウトされます。</p>
    <p><a href="sign_in.php">サインイン</a></p>
</body>
</html>