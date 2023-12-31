<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

require_once 'url_validation.php';

$word = trim(htmlspecialchars($_GET['query'],ENT_QUOTES));
$word = str_replace("　","",$word);
define('WORD', $word);

$sessionId = $_SESSION['user_id'];
$sortOrder = 'ASC';
$sortBy = '';

if(isset($_GET['sort_by']) && isset($_GET['sort_order'])) {
    $sortBy = $_GET['sort_by'];
    $sortOrder = $_GET['sort_order'];
}

require_once 'dbindex.php';
$perPageOptions = array(5, 10, 20, 50);
$defaultPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? $_GET['per_page'] : $defaultPerPage;
if (isset($_GET['per_page']) && in_array($_GET['per_page'], $perPageOptions)) {
    $perPage = $_GET['per_page'];
}
$offset = ($page - 1) * $perPage;

$countsql = 'SELECT COUNT(*) AS count FROM ESG_member_index WHERE CONCAT(employee_id, member_name, member_from, DateEntry, dispatched, tasks) LIKE :word';
$countstmt = $pdo->prepare($countsql);
$countstmt->bindValue(':word','%' . WORD . '%', PDO::PARAM_STR);
$countstmt->execute();
$totalCount = $countstmt->fetch(PDO::FETCH_ASSOC)['count'];
$totalPages = ceil($totalCount / $perPage);

require_once 'admincheck.php';

try{
    //実行したいSQLを準備する
    $sql = 'SELECT * FROM ESG_member_index WHERE CONCAT(employee_id, member_name, member_from, DateEntry, dispatched, tasks) LIKE :word';
        if(!empty($sortBy) && !empty($sortOrder)) {
            $sql .= ' ORDER BY ' . $sortBy . ' ' . $sortOrder;
        }
    $sql .= ' LIMIT ' . $offset . ', ' . $perPage;
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':word','%' . WORD . '%', PDO::PARAM_STR);

    //SQLを実行
    $stmt->execute();

    $result = $stmt->fetchall();
}catch(PDOException $e){
    $pdo->rollback();
        throw $e;
}
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>検索結果</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
            h1 {
                text-align: center;
                padding-top: 5%;
            }
            html {
                position: relative;
                min-height: 100%;
                overflow-x: hidden;
            }

            body {
                margin-bottom: 60px;
                overflow-x: hidden;
            }

            .headerimage {
                width:40px;
                height:40px;
                border-radius:50%;
            }

            .navbar {
                position: fixed; 
                top: 0; 
                width: 100%; 
                z-index: 100; 
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

            .photo{
                width:70px;
            }

            .thumbnail {
                width:80px;
                height:80px;
                border-radius:50%;
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
                        <figure class="profile-image">
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
    <body style="min-width: 600px;">
        <h1>「<?php echo WORD ?>」の検索結果</h1>
        <div>&nbsp;</div>
        <main class="container">
            <section class="row">
                <form method="get" action="search.php" class="form-inline form-group col-12 col-md-8">
                    <label class="sr-only" for="kwork">検索ワード</label>
                    <input type="search" name="query" class="form-control form form-control-sm mr-2" placeholder="キーワード" id="kword" value="<?php echo WORD ?>" requied>
                    <input type="hidden" name="sort_by" value="<?php echo $sortBy; ?>">
                    <input type="hidden" name="sort_order" value="<?php echo $sortOrder; ?>">
                <button type="submit" class="btn btn-warning btn-sm pl-3 pr-3">検索</button>
                </form>
                <form class="form-inline mb-3">
                    <input type="hidden" name="sort_by" value="<?php echo $sortBy; ?>">
                    <input type="hidden" name="sort_order" value="<?php echo $sortOrder; ?>">
                    <input type="hidden" name="query" class="form-control form form-control-sm mr-2" id="kword" value="<?php echo WORD ?>" requied>
                    <label class="mr-2">1ページあたりの表示数:</label>
                    <select class="form-control" name="per_page" onchange="this.form.submit()">
                    <?php foreach ($perPageOptions as $option) {
                        echo '<option value="' . $option . '"' . ($option == $perPage ? ' selected' : '') . '>' . $option . '</option>';
                    } ?>
                    </select>
                </form>
                <p class="bg-primary text-white rounded-pill p-2"><?php echo $totalCount ?>件見つかりました</p>
                <div>&nbsp;</div>
                <table class="table">
                    <tr>
                        <th class="photo"></th>
                        <th scope="col">社員番号 
                            <?php $reverseSortOrder = ($sortOrder === 'ASC' && $sortBy === 'employee_id') ? 'DESC' : 'ASC'; 
                            $sortBy = "employee_id" ?>
                            <a href="?query=<?php echo WORD?>&sort_by=<?php echo $sortBy?>&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                        </th>
                        <th scope="col">社員名
                            <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; 
                            $sortBy = "member_name"; ?>
                            <a href="?query=<?php echo WORD?>&sort_by=member_name&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                        </th>
                        <th scope="col">派遣先
                            <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; 
                            $sortBy = "dispatched"; ?>
                            <a href="?query=<?php echo WORD?>&sort_by=dispatched&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                        </th>
                        <th scope="col">業務内容
                            <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC';
                            $sortBy = "tasks"; ?>
                            <a href="?query=<?php echo WORD?>&sort_by=tasks&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                        </th>
                    </tr>
                    <?php foreach($result as $r) { 
                        $employeeId = htmlspecialchars($r['employee_id'], ENT_QUOTES, 'UTF-8');
                        $memberName = htmlspecialchars($r['member_name'], ENT_QUOTES, 'UTF-8');
                        $dispatched = htmlspecialchars($r['dispatched'], ENT_QUOTES, 'UTF-8');
                        $tasks = htmlspecialchars($r['tasks'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td><figure class="profile-image">
                            <?php print'<img class="thumbnail" alt="画像" onclick="changeImage()" src="image.php?id=' . $r['employee_id'] . '">' ?> 
                            </figure></td>
                        <td><?php print'<a href="memberinfo.php?id='. $employeeId .'">' . $employeeId .'</a>'; ?></td>
                        <td><?php print($r['member_name']); ?></td>
                        <td><?php print($r['dispatched']); ?></td>
                        <td><?php print($r['tasks']); ?></td>
                    </tr>
                    <?php } ?>
                </table>                
            </section>
        </main> 
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?query=' . WORD . '&sort_by=' . $sortBy . '&sort_order=' . $sortOrder . '&per_page=' . $perPage . '&page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                } ?>
            </ul>
        </nav>
            <div>&nbsp;</div>
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