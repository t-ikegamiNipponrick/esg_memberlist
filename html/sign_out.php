<?php
session_start(); // セッションを開始

// セッションを破棄してログアウト処理を行う
session_unset(); // セッションの変数を全て解除
session_destroy(); // セッションを破棄

header('Location: sign_in.php'); // ログインページにリダイレクト
exit();
?>
