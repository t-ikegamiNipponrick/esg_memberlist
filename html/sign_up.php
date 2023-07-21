<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>サインアップページ</title>
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

    .form-signin input[type="email"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
    margin-bottom: 0px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    }

    .form check tecxt-start my-3 {
    text-align:left;
    }
  </style>
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <main class="form-signin w-100 m-auto">
    <form class="text-center" method="post" action="add_user.php">
      <h1 class="h3 mb-3 fw-normal">新規ユーザー登録</h1>
      
      <div class="form-floating">
        <label for="floatingInput">ユーザーID（社員番号）</label>
        <input type="text" class="form-control" name="user_id" id="floatingInput" placeholder="社員番号" required>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingInput">メールアドレス</label>
        <input type="text" class="form-control" name="user_email" id="floatingInput" placeholder="xxx@nipponrick.co.jp" required>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingPassword">パスワード</label>
        <input type="password" data-toggle="password" class="form-control" name="password" id="floatingPassword" placeholder="パスワード" required>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingInput">秘密の質問</label>
        <select class="form-select" name="secret_question" aria-label="Default select example">
          <option selected>質問を選択してください</option>
          <option value="あなたが生まれた場所は？">あなたが生まれた場所は？</option>
          <option value="子供のときの一番の思い出は？">子供のときの一番の思い出は？</option>
          <option value="中学2年生の時の担任の先生の名前は？">中学2年生の時の担任の先生の名前は？</option>
        </select>
      </div>
      <div>&nbsp;</div>
      <div class="form-floating">
        <label for="floatingInput">秘密の質問の答え</label>
        <input type="text" class="form-control" name="secret_answer" id="floatingInput" required>
      </div>
      <p class="form-check text-start my-3">
        <input class="form-check-input" name="checking_admin" value="0" type="checkbox" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          管理者用アカウントで登録する
        </label>
      </p>
      <button class="btn btn-primary w-100 py-2" type="submit">サインアップ</button>
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