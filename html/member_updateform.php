<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: entire_validation.php'); // ログインページにリダイレクト
    exit();
}

require_once 'url_validation.php';

require_once 'dbindex.php';
$sessionId = $_SESSION['user_id'];

//データベースと接続して、PDOインスタンスを取得
$pdo = connect_db();

//実行したいSQLを準備する
$id = $_GET['id'];
$indexsql = "SELECT * FROM ESG_member_index WHERE employee_id = :id";
$indexstmt = $pdo->prepare($indexsql);
$indexstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

//SQLを実行
$indexstmt->execute();

//データベースの値を取得
$indexresult = $indexstmt->fetchall();

//実行したいSQLを準備する
$dispatchedsql = "SELECT * FROM ESG_member_dispatched WHERE key_id = :id";
$dispatchedstmt = $pdo->prepare($dispatchedsql);
$dispatchedstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

//SQLを実行
$dispatchedstmt->execute();

//データベースの値を取得
$dispatchedresult = $dispatchedstmt->fetchall();

$skillssql = "SELECT * FROM ESG_member_skills WHERE key_id = :id";
$skillsstmt = $pdo->prepare($skillssql);
$skillsstmt->bindValue(':id', $thisid,  PDO::PARAM_INT);

//SQLを実行
$skillsstmt->execute();

//データベースの値を取得
$skillsresult = $skillsstmt->fetchall();
$columnCountB = count($dispatchedresult);
$columnCountC = count($skillsresult);

$picscountsqlQuery = "SELECT COUNT(*) FROM ESG_member_picsid WHERE employee_id = ?";
$picscountstmtQuery = $pdo->prepare($picscountsqlQuery);
$picscountstmtQuery->execute([$thisid]);
$validateQ = $picscountstmtQuery->fetchColumn();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

            .heading-lv1 {
                font-size: 32px;
                font-style: italic;
            }

            .heading-lv2 {
                font-size: 24px;
            }

            .heading-lv3 {
                padding-top: 10%;
                font-size: 20px;
            }

            .navbar {
                position: fixed; 
                top: 0; 
                width: 100%; 
                z-index: 100; 
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

            .headerimage {
                width:40px;
                height:40px;
                border-radius:50%;
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

            .detailtxt {
                display: inline-block;
                width: 100%;
                padding: 10px;
                line-height: 1.2em;
                height: calc(1.2em * 6);
                border: 1px splid #999;
                box-sizing: border-box;
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
    <body class="wrap">
        <div class="content">
            <form enctype="multipart/form-data" method="post" action="update.php">
                <!-- <h1 class="heading-lv1 text-center">Profile</h1> -->

                <h3 class="heading-lv3 heading-margin text-center">社員情報更新</h3>
                <section class="row">
                    <table class="table">
                    <?php foreach($indexresult as $rI) {
                        $employeeId = htmlspecialchars($rI['employee_id'], ENT_QUOTES, 'UTF-8');
                        $memberName = htmlspecialchars($rI['member_name'], ENT_QUOTES, 'UTF-8');
                        $memberFrom = htmlspecialchars($rI['member_from'], ENT_QUOTES, 'UTF-8');
                        $dateEntry = htmlspecialchars($rI['DateEntry'], ENT_QUOTES, 'UTF-8');
                        $Dispatched = htmlspecialchars($rI['dispatched'], ENT_QUOTES, 'UTF-8');
                        $tasks = htmlspecialchars($rI['tasks'], ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr>
                            <th scope="col">社員番号</th>
                            <td>
                                <input type="text" name="employee_id" value="<?= $rI['employee_id']; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">氏名</th>
                            <td>
                                <input type="text" name="member_name" value="<?= $rI['member_name']; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">出身地</th>
                            <td>
                                <input type="text" name="member_from" value="<?= $rI['member_from']; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">入社年月</th>
                            <td>
                                <input type="date" name="DateEntry" value="<?= $rI['DateEntry']; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">趣味</th>
                            <td>
                                <input type="text" name="hobby_info" value="<?= $rI['hobby_info']; ?>" >
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">現在の就業先</th>
                            <td>
                                <input type="text" name="dispatched" value="<?= $rI['dispatched']; ?>" required>
                            </td>
                        </tr>  
                        <tr>
                            <th scope="col">現在の業務内容</th>
                            <td>
                                <input type="text" name="tasks" value="<?= $rI['tasks']; ?>" required>
                            </td>
                        </tr> 
                        <tr>
                            <th scope="col">自己紹介</th>
                            <td>
                                <textarea name="member_pr" class="detailtxt"><?=$rD['member_pr'] ?></textarea>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <?php $i=0;
                            foreach($dispatchedresult as $rD) { 
                                $dispatchedSofar = htmlspecialchars($rD['dispatched_sofar'], ENT_QUOTES, 'UTF-8');
                                $tasksSofar = htmlspecialchars($rD['tasks_sofar'], ENT_QUOTES, 'UTF-8');
                                $tasksSofarStart = htmlspecialchars($rD['tasks_sofarStart'], ENT_QUOTES, 'UTF-8');
                                $tasksSofarFin = htmlspecialchars($rD['tasks_sofarFin'], ENT_QUOTES, 'UTF-8');
                                ?>
                        <tr>
                            <th scope="col">これまでの就業先<?=$i+1?></th> 
                            <td>
                                <div>就業先</div>   
                                <input type="text" name=<?="dispatched_sofar[$i]" ?> value="<?= $rD['dispatched_sofar']; ?>" required>   
                                <div>&nbsp;</div>    
                                <div>業務内容</div>
                                <input type="text" name=<?="tasks_sofar[$i]"?> value="<?= $rD['tasks_sofar']; ?>" required>
                                <div>&nbsp;</div>
                                <div>期間</div>
                                <input type="date" name=<?="tasks_sofarStart[$i]"?> value="<?= $rD['tasks_sofarStart']; ?>" required>
                                <div>~</div>
                                <input type="date" name=<?="tasks_sofarFin[$i]"?> value="<?= $rD['tasks_sofarFin']; ?>" required>
                                <div>&nbsp;</div>
                                <div>詳細</div>
                                <textarea name=<?="tasks_detail[$i]" ?> class="detailtxt"><?=$rD['tasks_detail']; ?></textarea>
                            </td>
                        </tr>
                            <?php $i++; }
                            $j = 0;
                            foreach($skillsresult as $rS) {
                                $skillName = htmlspecialchars($rS['skill_name'], ENT_QUOTES, 'UTF-8');
                                $skillDate = htmlspecialchars($rS['skill_date'], ENT_QUOTES, 'UTF-8');
                            ?>
                        <tr>
                            <th scope="col">スキル<?=$j+1?></th>
                            <td>
                                <div>スキル名</div>
                                <input type="text" name=<?="skill_name[$j]"?> value="<?= $rS['skill_name']; ?>" required>
                                <div>&nbsp;</div>
                                <div>年数</div>
                                <input type="text" id="skilldate" oninput="restrictInput(event)" name=<?="skill_date[$j]"?> value="<?= $rS['skill_date']; ?>" required>
                                <label for="skilldate">年</label>
                            </td>
                        </tr>
                            <?php $j++; 
                            } ?>
                        <tr>
                            <th scope="col">プロフィール画像</th>
                            <td>
                                <input type="file" name="photo"> 
                            </td>
                        </tr>
                    </table>
                    <div>&nbsp;</div>            
                </section>  
                <div>&nbsp;</div>
                <input type="submit" class="btn btn-primary" value="更新する">&emsp;
                <input type="button" class="btn btn-primary" value="新規情報を追加する" onclick="location.href='<?php print('alreadyuser_inputform.php?id='. $rI['employee_id'] .'') ?>'">
            </form>
        </div>
    </body>
    <script>
        function showPopup(imgId) {
            let popup = document.getElementById('popup-' + imgId);
            popup.style.display = 'block';
        }

        function hidePopup(imgId) {
            let popup = document.getElementById('popup-' + imgId);
            popup.style.display = 'none';
        }
    </script>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎ 2023<a href="https://www.nipponrick.co.jp/" target="_blank"> 日本リック株式会社</a>  developped by Tomohiro Ikegami</p>
        </div>
    </footer>
</html>