<?php

define('UPLOADPASS', './img/');

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

require_once 'url_validation.php';

require_once 'dbindex.php';

$sessionId = $_SESSION['user_id'];
// print($sessionId);
$id = $_GET['id'];
// print($id);
$indexsql = "SELECT * FROM ESG_member_index WHERE employee_id = :id";
$indexstmt = $pdo->prepare($indexsql);
$indexstmt->bindValue(':id', $id,    PDO::PARAM_INT);

$indexstmt->execute();

$indexresult = $indexstmt->fetchall();

$dispatchedsql = "SELECT * FROM ESG_member_dispatched WHERE key_id = :id ORDER BY tasks_sofarStart ASC";
$dispatchedstmt = $pdo->prepare($dispatchedsql);
$dispatchedstmt->bindValue(':id', $id,    PDO::PARAM_INT);

$dispatchedstmt->execute();

$dispatchedresult = $dispatchedstmt->fetchall();

$skillssql = "SELECT * FROM ESG_member_skills WHERE key_id = :id ORDER BY skill_name ASC";
$skillsstmt = $pdo->prepare($skillssql);
$skillsstmt->bindValue(':id', $id,  PDO::PARAM_INT);

$skillsstmt->execute();

$skillsresult = $skillsstmt->fetchall();

require_once 'admincheck.php';
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
                padding: 16px;
                background-color: #fff;
            }

            .navbar {
                position: fixed; 
                top: 0; 
                width: 100%; 
                z-index: 100; 
            }

            .heading-lv1 {
                padding-top: 10%;
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

            .headerimage {
                width:40px;
                height:40px;
                border-radius:50%;
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

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }

            #myModal img {
                display: block;
                margin: auto;
                max-width: 30%; 
                max-height: 100%; 
                padding: 0;
                object-fit: contain;
            }

            .close {
                color: #fff;
                position: absolute;
                top: 10px;
                right: 25px;
                font-size: 35px;
                font-weight: bold;
                transition: 0.3s;
                cursor: pointer;
            }

            .close:hover,
            .close:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }

            .popup {
                position: absolute;
                background-color: #f9f9f9;
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
                display: none;
                top: 100%;
                left: 85%;
                transform: translateX(-50%);
                max-width: 200px;
                white-space: nowrap;
            }

            .popup ul {
                padding: 0;
                margin: 0;
            }

            .popup li {
                list-style-type: none;
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
                    <?php if($resultadmin['checking_admin'] == 0) {
                        print '<li class="nav-item active" onclick="toggleSublist()">';
                        print '<a class="nav-link" href="#">管理者メニュー<span class="sr-only">(current)</span></a>';
                        print '<ul id="sublist" class="popup" style="display: none;">';
                        print '<li><a href="member_inputform.php">新規メンバーの追加</a></li>';
                        print '<li><a href="account_list.php">アカウント一覧</a></li>';
                        print '</ul>';
                        print '</li>';
                    }else{
                        print '<li class="nav-item active">';
                        print '<a class="nav-link" href="resetpassword_form.php?id=' . $_SESSION['user_id'] . '">パスワードのリセット</a>';
                    } ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="memberinfo.php?id=<? print($_SESSION['user_id']); ?>">プロフィール<span class="sr-only">(current)</span></a>
                    </li>                    
                    <li class="nav-item active">
                        <a class="nav-link" href="sign_out.php">サインアウト<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item active">
                        <figure>
                            <?php print'<img class="headerimage" alt="画像" onmouseover="showPopup(' . $sessionId . ')" onmouseout="hidePopup(' . $sessionId . ')" onclick="showPopup(' . $sessionId . ')" src="image.php?id=' . $sessionId . '">'; ?> 
                        </figure>
                    </li>
                </ul>
            </div>
            <div id="popup-<?php echo $sessionId; ?>" class="popup">
                <p>社員番号: <?php echo $sessionId ?></p>
                <?php if($resultadmin['checking_admin'] == 0) {
                    echo 'アカウントの状態：管理者';
                } else {
                    echo 'アカウントの状態：一般';
                }
                ?>
            </div>
        </nav>
    </header>
    <br>
    <body class="wrap">
        <form>
        <div class="content">
            <h1 class="heading-lv1 text-center">Profile</h1>
            <figure class="profile-image">
                <?php  print'<img id="myImage" alt="画像" onclick="openModal()" src="image.php?id=' . $id . '">' ?> 
            </figure>

            <h3 class="heading-lv3 heading-margin text-center">社員情報</h3>
            <section class="row">
            <?php foreach($indexresult as $rA) {
                $employeeId = htmlspecialchars($rA['employee_id'], ENT_QUOTES, 'UTF-8');
                $memberName = htmlspecialchars($rA['member_name'], ENT_QUOTES, 'UTF-8');
                $memberFrom = htmlspecialchars($rA['member_from'], ENT_QUOTES, 'UTF-8');
                $dateEntry = htmlspecialchars($rA['DateEntry'], ENT_QUOTES, 'UTF-8');
                $Dispatched = htmlspecialchars($rA['dispatched'], ENT_QUOTES, 'UTF-8');
                $tasks = htmlspecialchars($rA['tasks'], ENT_QUOTES, 'UTF-8');
            ?>
                <table class="table">
                    <tr>
                        <th scope="col">社員番号</th>
                        <td><?php print($rA['employee_id']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">氏名</th>
                        <td><?php print($rA['member_name']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">出身地</th>
                        <td><?php print($rA['member_from']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">入社年月</th>
                        <td><?php print($rA['DateEntry']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">現在の就業先</th>
                        <td><?php print($rA['dispatched']); ?></td>
                    </tr>  
                    <tr>
                        <th scope="col">現在の業務内容</th>
                        <td><?php print($rA['tasks']); ?></td>
                    </tr>
                    <?php } ?>
                </table>                
            </section>
            <h3 class="heading-1v3 heading-margin text-center">これまでの就業先</h3>
            <section class="row">
                <table class="table">
                    <tr>
                        <th scope="col">就業先</th>
                        <th scope="col">業務内容</th>
                        <th scope="col">期間</th>
                    </tr>
                    <?php foreach($dispatchedresult as $rB) {
                        $dispatchedSofar = htmlspecialchars($rB['dispatched_sofar'], ENT_QUOTES, 'UTF-8');
                        $tasksSofar = htmlspecialchars($rB['tasks_sofar'], ENT_QUOTES, 'UTF-8');
                        $tasksSofarStart = htmlspecialchars($rB['tasks_sofarStart'], ENT_QUOTES, 'UTF-8');
                        $tasksSofarFin = htmlspecialchars($rB['tasks_sofarFin'], ENT_QUOTES, 'UTF-8');
                        ?>
                    <tr>
                        <td><?php print($rB['dispatched_sofar']); ?></td>
                        <td><?php print($rB['tasks_sofar']); ?></td>
                        <td><?php print($rB['tasks_sofarStart'])?>~<?php print($rB['tasks_sofarFin'])?></td>
                    </tr>
                    <?php } ?>
                </table>            
            </section>
            <?php print'<div style=text-align:center;><a href="memberinfo_detail.php?id='. $rB['key_id'] .'">就業先の詳細はこちら</a></div>'; ?>
            <h3 class="heading-1v3 heading-margin text-center">スキル</h3>
            <section class="row">
                <table class="table">
                    <tr>
                        <th scope="col">スキル</th>
                        <th scope="col">年数</th>
                    </tr>
                    <?php foreach($skillsresult as $rC) {
                        $skillName = htmlspecialchars($rC['skill_name'], ENT_QUOTES, 'UTF-8');
                        $skillDate = htmlspecialchars($rC['skill_date'], ENT_QUOTES, 'UTF-8');
                        ?>
                    <tr>
                        <td><?php print($rC['skill_name']); ?></td>
                        <td><?php print($rC['skill_date']); ?>年</td>
                    </tr>
                    <?php } ?>
                </table>                
            </section>
            <?php if($sessionId == $id || $sessionId == 11400){
            print '<input type="button" class="btn btn-primary" name="update" onclick="updateLink()" value="修正">&emsp;';
            } ?>
        </div>
        </form>
        <script>
            function updateLink() {
                location.href = 'member_updateform.php?id=<?= $id?>';
            }

            function openModal() {
                // モーダルを取得
                let modal = document.getElementById('myModal');

                // 画像を取得
                let img = document.getElementById('myImage');
                let modalImg = document.getElementById("img01");

                // 画像をモーダルに表示
                modal.style.display = "block";
                modalImg.src = img.src;
            }

            function closeModal() {
                // モーダルを取得
                let modal = document.getElementById('myModal');
                
                // モーダルを非表示にする
                modal.style.display = "none";
            }

            function showPopup(imgId) {
                let popup = document.getElementById('popup-' + imgId);
                popup.style.display = 'block';
            }

            function hidePopup(imgId) {
                let popup = document.getElementById('popup-' + imgId);
                popup.style.display = 'none';
            }

            function toggleSublist() {
                let sublist = document.getElementById('sublist');
                sublist.style.display = sublist.style.display === 'none' ? 'block' : 'none';
            }
        </script>
    </body>
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="img01">
    </div>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎ 2023<a href="https://www.nipponrick.co.jp/" target="_blank"> 日本リック株式会社</a>  developped by Tomohiro Ikegami</p>
        </div>
    </footer>
</html>