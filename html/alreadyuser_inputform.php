<?php
 $protocol = empty($_SERVER["HTTPS"]) ? "http://" : "https://";
 $thisurl = $protocol . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 $beforeurl = $_SERVER['HTTP_REFERER'];
 $parse_url_arr = parse_url ($beforeurl);
 parse_str ( $parse_url_arr['query'], $query_arr );
 $thisid = $query_arr['id'];

 // print($thisid);

 session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

require_once 'url_validation.php';

$sessionId = $_SESSION['user_id'];

 require_once 'dbindex.php';

    try{
        $pdo->beginTransaction();
        $dispatchedsql = "SELECT * FROM ESG_member_dispatched WHERE key_id = :id";
        $dispatchedstmt = $pdo->prepare($dispatchedsql);
        $dispatchedstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

        $dispatchedstmt->execute();
        if($dispatchedstmt) {    
            $pdo->commit();
        }

        $dispatchedresult = $dispatchedstmt->fetchall();
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
    $count= count($dispatchedresult);
    // print($count);

    try{
        $pdo->beginTransaction();
        $skillssql = "SELECT * FROM ESG_member_skills WHERE key_id = :id";
        $skillsstmt = $pdo->prepare($skillssql);
        $skillsstmt->bindValue(':id', $thisid,    PDO::PARAM_INT);

        $skillsstmt->execute();
        if($skillsstmt) {    
            $pdo->commit();
        }

        $skillsresult = $skillsstmt->fetchall();
    }catch(PDOException $e) {
        $pdo->rollback();
        throw $e;
    }
    $count1= count($skillsresult);
    // print($count1);

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


            .profile-image {
                margin: 16px 0 0;
                text-align: center;
            }

            .profile-image img {
                width: 150px;
                height: auto;

                border-radius: 50%;
            }

            .headerimage {
                width:40px;
                height:40p;
                border-radius:50%;
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
                padding: 1em 0.5em;
                line-height: 3;
                border: 1px splid #999;
                box-sizing: border-box;
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
                            <?php print'<img class="headerimage" alt="画像" onclick="changeImage()" src="image.php?id=' . $sessionId . '">' ?> 
                        </figure>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <body class="wrap">
        <div class="content">
            <form method="post" action="alreadyuser_insert.php">
                <!-- <h1 class="heading-lv1 text-center">Profile</h1> -->
                <h3 class="heading-lv3 heading-margin text-center"><b>社員情報入力</b></h3>
                <section class="row">
                    <table class="table">
                    <tbody id="dispatchSection">
                        <tr>
                            <th scope="col">これまでの派遣先<?=$count +1 ?></th> 
                            <td>
                                <div>派遣先</div>
                                <input type="text" name="dispatched_sofar[<?=$count ?>]">       
                                <div>業務内容</div>
                                <input type="text" name="tasks_sofar[<?=$count ?>]">
                                <div>期間</div>
                                <input type="date" name="tasks_sofarStart[<?=$count ?>]">
                                <div>~</div>
                                <input type="date" name="tasks_sofarFin[<?=$count ?>]">
                                <div>詳細</div>
                                <textarea name="tasks_detail[<?=$count ?>]" class="detailtxt"></textarea>
                                <p>
                                    <div>&nbsp;</div>
                                <input type="button" class="btn btn-primary" value="派遣先を追加" onclick="addDispatchRow()">
                                </p>
                            </td>
                        </tr>
                        </tbody>
                        <tbody id="skillSection">
                        <tr>
                            <th scope="col">スキル<?=$count1 +1 ?></th>
                            <td>
                                <div>スキル名</div>
                                <input type="text" name="skill_name[<?=$count1 ?>]">
                                <div>年数</div>
                                <input type="text" id="skilldate" name="skill_date[<?=$count1 ?>]">
                                <label for="skilldate">年</label>
                                <p>
                                    <div>&nbsp;</div>
                                <input type="button" class="btn btn-primary" value="スキルを追加" onclick="addSkillRow()">
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>                
                </section>
                <button type="submit" class="btn btn-primary">登録する</button>
                <script>
                    var dispatchCount = <?=$count ?> +1; // 派遣先の追加回数を保持する変数
                    var skillCount = <?=$count1 ?> +1; // スキルの追加回数を保持する変数

                    function addDispatchRow() {
                        var dispatchSection = document.getElementById("dispatchSection");

                        // 新しい行を作成
                        var newRow = document.createElement("tr");

                        // 新しい行のHTMLコードを設定
                        newRow.innerHTML = `
                            <th scope="col">これまでの派遣先${dispatchCount + 1}</th>
                            <td>
                                <div>派遣先</div>
                                <input type="text" name="dispatched_sofar[${dispatchCount}]">       
                                <div>業務内容</div>
                                <input type="text" name="tasks_sofar[${dispatchCount}]">
                                <div>期間</div>
                                <input type="date" name="tasks_sofarStart[${dispatchCount}]">
                                <div>~</div>
                                <input type="date" name="tasks_sofarFin[${dispatchCount}]">
                                <div>&nbsp;</div>
                                <div>詳細</div>
                                <textarea name="tasks_detail[${dispatchCount}]" class="detailtxt"></textarea>
                                <button class="btn btn-primary" onclick="removeDispatchRow()">フォーム削除</button>
                            </td>
                        `;

                        // テーブルに行を追加
                        dispatchSection.appendChild(newRow);

                        dispatchCount++; // 派遣先の追加回数を増やす
                    }

                    function addSkillRow() {
                        var skillSection = document.getElementById("skillSection");

                        // 新しい行を作成
                        var newRow = document.createElement("tr");

                        // 新しい行のHTMLコードを設定
                        newRow.innerHTML = `
                            <th scope="col">スキル${skillCount + 1}</th>
                            <td>
                                <div>スキル名</div>
                                <input type="text" name="skill_name[${skillCount}]" required>
                                <div>年数</div>
                                <input type="text" id="skilldate" name="skill_date[${skillCount}]" required>
                                <label for="skilldate">年</label>
                                <div>&nbsp;</div>
                                <button class="btn btn-primary" onclick="removeSkillRow()">フォーム削除</button>
                            </td>
                        `;

                        // テーブルに行を追加
                        skillSection.appendChild(newRow);

                        skillCount++; // スキルの追加回数を増やす
                    }

                    function removeDispatchRow() {
                        var dispatchSection = document.getElementById("dispatchSection");
                        
                        // 最後の行を削除
                        dispatchSection.removeChild(dispatchSection.lastChild);
                        
                        dispatchCount--; // 派遣先の追加回数を減らす
                    }

                    function removeSkillRow() {
                        var dispatchSection = document.getElementById("skillSection");
                        
                        // 最後の行を削除
                        dispatchSection.removeChild(skillSection.lastChild);
                        
                        skillCount--; // 派遣先の追加回数を減らす
                    }

                    function restrictInput(event) {
                        var input = event.target;
                        var value = input.value;
                        var restrictedValue = value.replace(/[^\x01-\x7E]/g, ''); // 全角文字を削除

                        if (value !== restrictedValue) {
                            input.value = restrictedValue;
                        }
                    }
                </script>
            </form>
        </div>
    </body>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎<?php echo $year;?><a href="https://www.nipponrick.co.jp/" target="_blank"> 日本リック株式会社</a>  developped by Tomohiro Ikegami</p>
        </div>
    </footer>
</html>