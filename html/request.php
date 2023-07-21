<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

    session_start();
    $csrfToken = filter_input(INPUT_POST, '_csrf_token');

    if(empty($csrfToken) || empty($_SESSION['_csrf_token']) || $csrfToken !== $_SESSION['_csrf_token']) {
        exit('不正なリクエストが検知されました。');
    }


    function isValidEmail($email) {
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        if (preg_match($pattern, $email)) {
            return true; 
        } else {
            return false;
        }
    } 

    $email = filter_input(INPUT_POST, 'user_email');
        if(isValidEmail($email) == false){
            echo "無効な形式のメールアドレスです。";
        } 

    require_once 'dbindex.php';

    $userverifysql = 'SELECT * FROM ESG_login WHERE user_email = :email';
    $stmt = $pdo->prepare($userverifysql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if(!$user) {
        require_once 'resetpassword_request_form.php';
        exit();
    }

    $tokenchecksql = 'SELECT * FROM ESG_password_resets WHERE user_email = :email';
    $stmt = $pdo->prepare($tokenchecksql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $resetUser = $stmt->fetch(PDO::FETCH_OBJ);

    if(!$resetUser) {
        $resetsql = 'INSERT INTO ESG_password_resets (user_email, token, token_sent_at) VALUES (:email, :token, :token_sent_at)';
    }else{
        $resetsql = 'UPDATE ESG_password_resets SET token = :token, token_sent_at = :token_sent_at WHERE user_email = :email';
    }

    $passwordResetToken = bin2hex(random_bytes(32));

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare($resetsql);
        $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':token', $passwordResetToken, PDO::PARAM_STR);
        $stmt->bindValue(':token_sent_at', (new DateTime())->format('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->execute();

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $mail = new PHPMailer(true);
        $mail->CharSet = 'utf-8';

        $mail->isSMTP();
        $mail->Host = "smtp.office365.com";
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['usermail'];
        $mail->Password = $_ENV['userpass'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $url = "http://localhost:8080/resetpassword_form.php?token={$passwordResetToken}";
        $subject = "パスワードリセット用URLをお送りします";

        $body = <<<EOD
        24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。
        {$url}
EOD;

        $mail->setFrom('t-ikegami@nipponrick.co.jp', '差出人名'); 
        $mail->addAddress($email, '受信者名');   

        $mail->Subject = $subject; 
        $mail->Body    = $body;  

        $mail->send();

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        exit($e->getMessage());
    }

?> 