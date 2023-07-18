<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

$sessionId = $_SESSION['user_id'];
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
                padding: 1em 0.5em;
                line-height: 3;
                border: 1px splid #999;
                box-sizing: border-box;
            }

            .headerimage {
                width:40px;
                height:40px;
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
            <form name="register" onsubmit="return validateForm()" method="post" action="create.php" >
                <h3 class="heading-lv3 heading-margin text-center">社員情報入力</h3>
                <section class="row">
                    <table class="table">
                        <tr>
                            <th scope="col">社員番号</th>
                            <td>
                                <input type="text" name="employee_id" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">氏名</th>
                            <td>
                                <input type="text" name="member_name" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">出身地</th>
                            <td>
                                <input type="text" name="member_from" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">入社年月</th>
                            <td>
                                <input type="date" name="DateEntry" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col">現在の派遣先</th>
                            <td>
                                <input type="text" name="dispatched" required>
                            </td>
                        </tr>  
                        <tr>
                            <th scope="col">現在の業務内容</th>
                            <td>
                                <input type="text" name="tasks" required>
                            </td>
                        </tr> 
                        <tbody id="dispatchSection">
                        <tr>
                            <th scope="col">これまでの派遣先1</th> 
                            <td>
                                <div>派遣先</div>
                                <input type="text" name="dispatched_sofar[0]" required>       
                                <div>業務内容</div>
                                <input type="text" name="tasks_sofar[0]" required>
                                <div>期間</div>
                                <input type="date" name="tasks_sofarStart[0]" required>
                                <div>~</div>
                                <input type="date" name="tasks_sofarFin[0]" required>
                                <div>詳細</div>
                                <textarea name="tasks_detail[0]" class="detailtxt"></textarea>
                                <p>
                                    <div>&nbsp;</div>
                                <input type="button" value="派遣先を追加" onclick="addDispatchRow()">
                                </p>
                            </td>
                        </tr>
                        </tbody>
                        <tbody id="skillSection">
                        <tr>
                            <th scope="col">スキル1</th>
                            <td>
                                <div>スキル名</div>
                                <input type="text" name="skill_name[0]">
                                <div>年数</div>
                                <input type="text" name="skill_date[0]">
                                <p>
                                    <div>&nbsp;</div>
                                <input type="button" value="スキルを追加" onclick="addSkillRow()">
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>                
                </section>
                <button type="submit" class="btn btn-primary">登録する</button>
                <script>
                    var dispatchCount = 1; // 派遣先の追加回数を保持する変数
                    var skillCount = 1; // スキルの追加回数を保持する変数

                    function addDispatchRow() {
                        var dispatchSection = document.getElementById("dispatchSection");

                        // 新しい行を作成
                        var newRow = document.createElement("tr");

                        // 新しい行のHTMLコードを設定
                        newRow.innerHTML = `
                            <th scope="col">これまでの派遣先${dispatchCount + 1}</th>
                            <td>
                                <div>派遣先</div>
                                <input type="text" name="dispatched_sofar[${dispatchCount}]" required>       
                                <div>業務内容</div>
                                <input type="text" name="tasks_sofar[${dispatchCount}]" required>
                                <div>期間</div>
                                <input type="date" name="tasks_sofarStart[${dispatchCount}]" required>
                                <div>~</div>
                                <input type="date" name="tasks_sofarFin[${dispatchCount}]" required>
                                <div>詳細</div>
                                <input type="text" name="tasks_detail[${dispatchCount}]" class="detailtxt">
                                <div>&nbsp;</div>
                                <button onclick="removeDispatchRow()">フォーム削除</button>
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
                                <input type="text" name="skill_date[${skillCount}]" required>
                                <div>&nbsp;</div>
                                <button onclick="removeSkillRow()">フォーム削除</button>
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

                    // 実装予定バリデーション
                    function validateForm() {
                        var employeeId = document.forms["register"]["employee_id"].value;
                        var memberName = document.forms["register"]["memeber_name"].value;
                        var memberFrom = document.forms["register"]["member_from"].value;
                        var dateEntry = document.forms["register"]["DateEntry"].value;
                        var dispatched = document.forms["register"]["dispatched"].value;
                        var tasks = document.forms["register"]["tasks"].value;
                        var dispatchedSofar = document.forms["register"]["dispatched_sofar"].value;
                        var tasksSofar = document.forms["register"]["tasks_sofar"].value;
                        var tasksSofatStart = document.forms["register"]["tasks_sofarStart"].value;
                        var tasksSofarFin = document.forms["register"]["tasks_sofarFin"].value;
                        var skillName = document.forms["register"]["skill_name"].value;
                        var skillDate = document.forms["register"]["skill_date"].value;
                        
                        if (employeeId.trim() === "") {
                            alert("社員番号を入力してください");
                            return false;
                        }
                        
                        return true;
                    }
                </script>
            </form>
        </div>
    </body>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎<?php echo $year;?> developped by Tomohiro Ikegami</p>
        </div>
    </footer>
</html>