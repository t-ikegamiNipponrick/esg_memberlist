<?php
session_start();
$id = $_POST['user_id'];
$request = filter_input_array(INPUT_POST);
// var_dump($request);
/*
if(empty($request['_csrf_token']) || empty($_SESSION['_csrf_token']) || $request['_csrf_token'] !== $_SESSION['_csrf_token']) {
    exit('不正なリクエストが検知されました。');
}
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirmPassword = $_POST["password_confirmation"];

    if ($password !== $confirmPassword) {
        $_SESSION['errorMessage'] = "パスワードと確認用パスワードが一致しません。";
        header('location: resetpassword_form.php');
    }
}

if(!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
    $_SESSION['errorMessage'] = 'パスワードは半角英数字のみで入力してください。';
    header('location: resetpassword_form.php');
    exit();
} 

if(strlen($password) < 8) {
    $_SESSION['errorMessage'] = "パスワードが短すぎます。半角英数字8文字以上で入力してください。";
    header('location: resetpassword_form.php');
    exit();
}

require_once 'dbindex.php';
/*
$sql = 'SELECT * FROM `ESG_password_resets` WHERE `token` = :token';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':token', $request['password_reset_token'], PDO::PARAM_STR);
$stmt->execute();
$passwordResetuser = $stmt->fetch(PDO::FETCH_OBJ);

if (!$passwordResetuser) exit('無効なURLです');
*/
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
// var_dump($hashedPassword);

try {
    $pdo->beginTransaction();

    $updatesql = 'UPDATE ESG_login SET password = :password WHERE user_id = :id';
    $updatestmt = $pdo->prepare($updatesql);
    $updatestmt->bindValue(':id', $id, PDO::PARAM_INT);
    $updatestmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    // $updatestmt->bindValue(':email', $passwordResetuser->email, PDO::PARAM_STR);
    $updatestmt->execute();
    /*
    $sql = 'DELETE FROM `ESG_password_resets` WHERE user_email = :email';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $passwordResetuser->email, PDO::PARAM_STR);
    $stmt->execute();
    */
    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();

    exit($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>リセット結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <p>パスワードを変更しました。<br><?php print'<a href="sign_in.php">戻る</a>'; ?></p>
    </body>
</html>