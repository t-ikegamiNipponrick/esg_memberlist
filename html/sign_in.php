<?php
session_start();
if (!isset($_SESSION['entity_id'])) {
    header('Location: error_page.php'); // 認証ページにリダイレクト
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    if(validateLogin($user_id, $password)) {
      if(isUserExists($user_id)) {
        session_start();
        $_SESSION['user_id'] = $user_id;
        header('LOCATION: top.php');
        exit();
      } else {
        session_start();
        $_SESSION['user_id'] = $user_id;
        header('LOCATION: member_inputform.php');
        exit();
      } 
    } else {
        $errorMessage = 'ユーザー名またはパスワードが正しくありません';
    }
}

function isUserExists($user_id) {
  require_once 'dbindex.php';
  $pdo = connect_db();
  $countsql = 'SELECT COUNT(*) FROM ESG_memberid_info WHERE employee_id = :employee_id';
  $countstmt = $pdo->prepare($countsql);
  $countstmt->bindValue(':employee_id', $user_id, PDO::PARAM_INT);
  $countstmt->execute();
  $usercount = $countstmt->fetchColumn();
  if($usercount > 0){
    return true;
  } else {
    return false;
  }
}

function validateLogin($user_id, $password) {
  require_once 'dbindex.php';
  $sql = 'SELECT * FROM ESG_login WHERE user_id = :user_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if($user && password_verify($password, $user['password'])) {
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
  <title>サインインページ</title>
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
      <h1 class="h3 mb-3 fw-normal">サインインして下さい</h1>
      <font color="red">
        <? print($errorMessage); ?><br>
      </font>
      <div class="form-floating">
        <label for="floatingInput">ユーザーID（社員番号）</label>  
        <input type="text" class="form-control" name="user_id" id="floatingInput" placeholder="社員番号" required>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingPassword">パスワード</label>
        <input type="password" data-toggle="password" class="form-control" name="password" id="floatingPassword" placeholder="パスワード" required>
      </div>

      <div class="form-check text-start my-3">
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          状態を記憶する
        </label>
      </div>
      <button class="btn btn-primary w-100 py-2" type="submit">サインイン</button>
      <a href="secretquestion_inputform.php">パスワードを忘れましたか？</a>
      <div>&nbsp;</div>
      <a href="sign_up.php" class="btn btn-primary btn-lg">新規登録はこちら</a>
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