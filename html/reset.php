<?php
session_start();

$request = filter_input_array(INPUT_POST);
// var_dump($request);

if(empty($request['_csrf_token']) || empty($_SESSION['_csrf_token']) || $request['_csrf_token'] !== $_SESSION['_csrf_token']) {
    exit('不正なリクエストが検知されました。');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirmPassword = $_POST["password_confirmation"];

    if ($password !== $confirmPassword) {
        $_SESSION['errorMessage'] = "パスワードと確認用パスワードが一致しません。";
        header('location: resetpassword_form.php');
    }
}

require_once 'dbindex.php';

$sql = 'SELECT * FROM `ESG_password_resets` WHERE `token` = :token';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':token', $request['password_reset_token'], PDO::PARAM_STR);
$stmt->execute();
$passwordResetuser = $stmt->fetch(PDO::FETCH_OBJ);

if (!$passwordResetuser) exit('無効なURLです');

$hashedPassword = password_hash($request['password'], PASSWORD_BCRYPT);

try {
    $pdo->beginTransaction();

    $sql = 'UPDATE ESG_login SET password = :password WHERE email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':password', $hashedPassword, \PDO::PARAM_STR);
    $stmt->bindValue(':email', $passwordResetuser->email, \PDO::PARAM_STR);
    $stmt->execute();

    $sql = 'DELETE FROM `ESG_password_resets` WHERE `email` = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $passwordResetuser->email, \PDO::PARAM_STR);
    $stmt->execute();

    $pdo->commit();

} catch (\Exception $e) {
    $pdo->rollBack();

    exit($e->getMessage());
}

echo 'パスワードの変更が完了しました。';