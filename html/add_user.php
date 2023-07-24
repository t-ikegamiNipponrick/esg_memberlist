<?php
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
    $user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $secretquestion = htmlspecialchars($_POST['secret_question'], ENT_QUOTES, 'UTF-8');
    $secretanswer = htmlspecialchars($_POST['secret_answer'], ENT_QUOTES, 'UTF-8');
    $checking_admin = htmlspecialchars($_POST['checking_admin'], ENT_QUOTES, 'UTF-8');

    if(!preg_match('/^[0-9]+$/', $user_id)) {
        $_SESSION['$errorMessage'] = "社員番号は数字のみで入力してください";
        header('Location: sign_up.php');
        exit();
    }
    
    if(!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $_SESSION['errorMessage'] = 'パスワードは半角英数字のみで入力してください。';
        header('Location: sign_up.php');
        exit();
    } 

    if(empty($checking_admin)){
        $checking_admin = 1;
    }

    if(isValidEmail($user_email) == false){
        $_SESSION['errorMessage'] = 'メールアドレスの形式が無効です。';
        header('Location: sign_up.php');
        exit();
    } 


    function isValidEmail($email) {
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        if (preg_match($pattern, $email)) {
            return true; 
        } else {
            return false;
        }
    } 

    require_once 'dbindex.php';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $signinsql = 'INSERT INTO ESG_login VALUES (:user_id, :user_email, :password, :secret_question, :secret_answer, :checking_admin)';
    $signinstmt = $pdo->prepare($signinsql);
    $signinstmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $signinstmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $signinstmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    $signinstmt->bindValue(':secret_question', $secretquestion, PDO::PARAM_STR);
    $signinstmt->bindValue(':secret_answer', $secretanswer, PDO::PARAM_STR);
    $signinstmt->bindValue(':checking_admin', $checking_admin, PDO::PARAM_INT);
    $signinstmt->execute();

    $success = 'ユーザーの追加が完了しました。';
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>追加結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <p><?=print($success);?></p>
     <a href="sign_in.php">サインインページへ</a>
    </body>
</html>
