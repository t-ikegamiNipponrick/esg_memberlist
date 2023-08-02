<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

// require_once 'url_validation.php';

$sessionId = $_SESSION['user_id'];
require_once 'dbindex.php';
$sortOrder = 'ASC';
$sortBy = 'user_id';

if(isset($_GET['sort_by']) && isset($_GET['sort_order'])) {
    $sortBy = $_GET['sort_by'];
    $sortOrder = $_GET['sort_order'];
}

$perPageOptions = array(5, 10, 20, 50);
$defaultPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? $_GET['per_page'] : $defaultPerPage;
if (isset($_GET['per_page']) && in_array($_GET['per_page'], $perPageOptions)) {
    $perPage = $_GET['per_page'];
}
$offset = ($page - 1) * $perPage;

$indexsql = 'SELECT * FROM ESG_login';

if(!empty($sortBy) && !empty($sortOrder)) {
    $indexsql .= ' ORDER BY ' . $sortBy . ' ' . $sortOrder;
}

$indexsql .= ' LIMIT ' . $offset . ', ' . $perPage;
$indexstmt = $pdo->prepare($indexsql);
$indexstmt->execute();
$result = $indexstmt->fetchall(PDO::FETCH_ASSOC);

$countsql = 'SELECT COUNT(*) AS count FROM ESG_login';
$countstmt = $pdo->prepare($countsql);
$countstmt->execute();
$totalCount = $countstmt->fetch(PDO::FETCH_ASSOC)['count'];
$totalPages = ceil($totalCount / $perPage);

require_once 'admincheck.php';
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>アカウント一覧</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        h1{
            text-align: center;
            padding-top: 5%;
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
            max-width: 250px;
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
            <h1>登録アカウント一覧</h1>
            <br>
            <main class="container">
                <section class="row mb-3">
                    <form class="form-inline mb-3" method="GET">
                       <input type="hidden" name="sort_by" value="<?php echo $sortBy; ?>">
                       <input type="hidden" name="sort_order" value="<?php echo $sortOrder; ?>">
                       <label class="mr-2">1ページあたりの表示数:</label>
                       <select class="form-control" name="per_page" onchange="this.form.submit()">
                       <?php foreach ($perPageOptions as $option) {
                            echo '<option value="' . $option . '"' . ($option == $perPage ? ' selected' : '') . '>' . $option . '</option>';
                        } ?>
                        </select>
                    </form>
                </section>
                <section class="row">
                    <table class="table">
                        <tr>
                            <th scope="col">社員番号 
                                <?php $reverseSortOrder = ($sortOrder === 'ASC' && $sortBy === 'user_id') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=user_id&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                            </th>
                            <th scope="col">メールアドレス
                                <?php $reverseSortOrder = ($sortOrder === 'ASC' && $sortBy === 'user_email') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=user_email&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                            </th>
                            <th scope="col">秘密の質問
                                <?php $reverseSortOrder = ($sortOrder === 'ASC' && $sortBy === 'secret_question') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=secret_question&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                            </th>
                            <th scope="col">アカウント状態
                                <?php $reverseSortOrder = ($sortOrder === 'ASC' && $sortBy === 'checking_admin') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=checking_admin&sort_order=<?php echo $reverseSortOrder; ?>&per_page=<?php echo $perPage?>">▼</a>
                            </th>
                            <th scope="col">パスワード</th>
                        </tr>
                        <?php foreach($result as $r) { 
                                $user_id = htmlspecialchars($r['user_id'], ENT_QUOTES, 'UTF-8');
                                $user_email = htmlspecialchars($r['user_email'], ENT_QUOTES, 'UTF-8');
                                $secret_question = htmlspecialchars($r['secret_question'], ENT_QUOTES, 'UTF-8');
                                $checking_admin = htmlspecialchars($r['checking_admin'], ENT_QUOTES, 'UTF-8');
                            ?>
                        <tr>
                            <td><?php print'<a href="memberinfo.php?id='. $r['user_id'] .'">' . $r['user_id'].'</a>'; ?></td>
                            <td><?php print($r['user_email']); ?></td>
                            <td><?php print($r['secret_question']); ?></td>
                            <td><?php if($r['checking_admin'] == 0) {
                                print '管理者';
                            }else{
                                print '一般';
                            }; ?></td>
                            <td><a href="resetpassword_form.php?id=<?php print $r['$user_id']; ?>">リセット</a></td>
                        </tr>
                        <?php } ?>
                    </table>                
                </section>
            </main> 
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?sort_by=' . $sortBy . '&sort_order=' . $sortOrder . '&per_page=' . $perPage . '&page=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    } ?>
                </ul>
            </nav>
            <div>&nbsp;</div>
        </body>
    <?php $year = date('Y'); ?>
    <footer class="footer">
        <div class="container text-center">
        <p class="text-muted">©︎<?php echo $year;?><a href="https://www.nipponrick.co.jp/" target="_blank"> 日本リック株式会社</a>  developped by Tomohiro Ikegami</p>
        </div>
    </footer>
    <script>
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
</html>