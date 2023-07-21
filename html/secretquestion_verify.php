<?php

$user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
$secretquestion = htmlspecialchars($_POST['secret_question'], ENT_QUOTES, 'UTF-8');
$secretanswer = htmlspecialchars($_POST['secret_answer'], ENT_QUOTES, 'UTF-8');

require_once 'dbindex.php';
$sql = 'SELECT * FROM ESG_login WHERE user_id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
$stmt->execute();
$verifyresult = $stmt->fetch(PDO::FETCH_ASSOC);

if($verifyresult['secret_question'] == $secretquestion) {
    if($verifyresult['secret_answer'] == $secretanswer) {
        header("Location: resetpassword_form.php?id='.$user_id.'");
    }else{
        $errorMessage = '秘密の質問の回答が間違っています。';
    }
}else{
    $errorMessage = '登録された秘密の質問と異なります。';
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>エラー</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
    <font color="red">
    <p><?=$errorMessage ?><br>
    </font>
    <?php print'<a href="sign_in.php">戻る</a>'; ?></p>
    </body>
</html>