<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

 session_start();
 if (!isset($_SESSION['user_id'])) {
     header('Location: sign_in.php'); // ログインページにリダイレクト
     exit();
 }

$sessionId = $_SESSION['user_id'];
require_once 'dbindex.php';

//実行したいSQLを準備する
$dispatchedsql = "SELECT * FROM ESG_member_dispatched WHERE key_id = :id ORDER BY tasks_sofarStart ASC";
$dispatchedstmt = $pdo->prepare($dispatchedsql);
$dispatchedstmt->bindValue(':id', $thisid,  PDO::PARAM_INT);

//SQLを実行
$dispatchedstmt->execute();

//データベースの値を取得
$dispatchedresult = $dispatchedstmt->fetchall();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>社員情報</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .wrap {
                padding: 1px 0 64px;
                background-color: #54B1E5;
            }

            .content {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                padding: 10px;
                background-color: #fff;
            }

            .heading-lv1 {
                font-size: 32px;
                font-style: italic;
            }

            .heading-lv2 {
                font-size: 24px;
            }

            .heading-lv3 {
                font-size: 20px;
            }

            .heading-margin {
                margin-top: 32px;
            }

            .text {
                margin: 16px 0 0;
                font-size: 16px;
                line-height: 1.5;
            }

            .text-center {
                text-align: center;
            }

            .profile-image {
                margin: 16px 0 0;
                text-align: center;
            }

            .profile-image img {
                width: 150px;
                height: auto;
                border-radius: 50%;
            }

            a {
                color: #3F82A8;
            }

            a:hover {
                text-decoration: none;
            }

            .headerimage {
                width:40px;
                height:40p;
                border-radius:50%;
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
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark mb-3">
            <a class="navbar-brand" href="top.php">日本リック株式会社ESG</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav4" aria-controls="navbarNav4" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav4">
                <ul class="navbar-nav">
                    <?php if($sessionId == 11400) {
                        print '<li class="nav-item active">';
                        print '<a class="nav-link" href="memberinputForm.php">社員情報の追加<span class="sr-only">(current)</span></a>';
                        print '</li>';
                    } ?>                    
                    <li class="nav-item active">
                        <a class="nav-link" href="sign_out.php">サインアウト<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="memberinfo.php?id=<? print($_SESSION['user_id']); ?>">プロフィール<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <figure>
                            <?php print'<img class="headerimage" alt="画像" onclick="changeImage()" src="image.php?id=' . $sessionId . '">' ?> 
                        </figure>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <br>
    <body class="wrap">
        <form>
        <div class="content">
            <h1 class="heading-lv3 heading-margin text-center">派遣先詳細情報</h3>
            <section class="row">
                <table class="table">
                    <tr>
                        <th scope="col">派遣先</th>
                        <th scope="col">業務内容</th>
                        <th scope="col">期間</th>
                        <th>詳細</th>
                    </tr>
                    <?php foreach($dispatchedresult as $r) {?>
                    <tr>                  
                        <td><?php print($r['dispatched_sofar']); ?></td>
                        <td><?php print($r['tasks_sofar']); ?></td>
                        <td><?php print($r['tasks_sofarStart'])?>~<?php print($rB['tasks_sofarFin'])?></td>
                        <td><?php print($r['tasks_detail']); ?></td>
                    </tr>
                    <?php } ?>
                </table>            
            </section>
        </div>
        </form>
    </body>
    <?php $year = date('Y'); ?>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎<?php echo $year;?> developped by Tomohiro Ikegami</p>
        </div>
    </footer>
</html>