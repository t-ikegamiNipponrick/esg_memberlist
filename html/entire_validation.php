<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    if(validateLogin($login_id, $password)) {
      session_start();
      $_SESSION['entity_id'] = $login_id;
      header('Location: sign_in.php');
      exit();
    } else {
        $errorMessage = 'ユーザー名またはパスワードが正しくありません';
    }
}

function validateLogin($login_id, $password) {
  require_once 'dbindex.php';
  $sql = 'SELECT * FROM ESG_validation WHERE login_id = :login_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':login_id', $login_id, PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if($user && password_verify($password, $user['login_password'])) {
    return true;
  } else {
    return false;
  }
}

?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ログイン</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <!-- CSSの設定ファイル -->
  <style>
    html {
      height: 100%;
      margin: 0 auto;
      padding: 0;
      display: table;
    }
    
    body {
      min-height: 100%;
      margin: 0 auto;
      padding: 0;
      display: table-cell;
      vertical-align: middle;
    }

    .form-signin {
      max-width: 330px;
      padding: 1rem;
    }

    .form-signin .form-floating:focus-within {
      z-index: 2;
    }

    .form-signin input[type="id"] {
      margin-bottom: -1px;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
      margin-bottom: 0px;
      border-top-left-radius: 0;
      border-top-right-radius: 0;
    }

    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 60px;
      background-color: #312d2a;
    }

  </style>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <main class="form-signin w-100 m-auto">
    <form class="text-center" method="post">
      <h1 class="h3 mb-3 fw-normal">認証情報を入力してください</h1>
      <font color="red">
        <? print($errorMessage); ?><br>
      </font>
      <div class="form-floating">
        <label for="floatingInput">ユーザーID</label>  
        <input type="text" class="form-control" name="login_id" id="floatingInput" placeholder="ID" required>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingPassword">パスワード</label>
        <input type="password" data-toggle="password" class="form-control" name="password" id="floatingPassword" placeholder="パスワード" required>
      </div>
      <div>&nbsp;</div>
      <button class="btn btn-primary w-100 py-2" type="submit">ログイン</button>
      <div>&nbsp;</div>
      <p class="mt-5 mb-3 text-body-secondary">日本リック株式会社第二事業本部ESG</p>
      <p class="mt-5 mb-3 text-body-secondary">&copy; 2023- developped by T. Ikegami</p>
   </form>
  </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <!-- JavaScriptプラグインの設定など -->
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</html>