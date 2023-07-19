<?php
session_start(); 
session_unset(); 
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>エラーページ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    h1{
            text-align: center;
        }

        .headerimage {
            width:40px;
            height:40px;
            border-radius:50%;
        }

        .thumbnail {
            width:80px;
            height:80px;
            border-radius:50%;
        }

        .photo{
            width:70px;
        }

        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #312d2a;
        }

        .container {
            width: auto;
            max-width: 1000px;
            padding: 0 15px;
        }

        .container .text-muted {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>エラーが発生しました</h1>
    <div>&nbsp;</div>
    <p>不正なURLまたは無効なデータが送信されました。自動でログアウトされます。</p>
    <p><a href="sign_in.php">サインイン</a></p>
</body>
<footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎<?php echo $year;?><a href="https://www.nipponrick.co.jp/" onclick="target_blank"> 日本リック株式会社</a>  developped by Tomohiro Ikegami</p>
        </div>
    </footer>

</html>