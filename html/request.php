<?php
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

        $url = "http://localhost:8080/resetpassword_form.php?token={$passwordResetToken}";
        $subject = "パスワードリセット用URLをお送りします";

        $body = <<<EOD
        24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。
        {$url}
EOD;

        $headers = "From: esgmemberlist.info@gmail.com\n";
        $headers .= "Content-Type: text/plain";
        $smtpPassword = $_ENV['GMAIL_PASSWORD'];

        $isSent = mb_send_mail($email, $subject, $body, $headers);
        
        if(!$isSent){
            throw new Exception('メールの送信に失敗しました。');
        } 
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        exit($e->getMessage());
    }

?> 