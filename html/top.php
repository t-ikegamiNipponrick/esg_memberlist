<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: sign_in.php'); // ログインページにリダイレクト
    exit();
}

require_once 'url_validation.php';

$sessionId = $_SESSION['user_id'];
require_once 'dbindex.php';
$sortOrder = 'ASC';
$sortBy = '';

if(isset($_GET['sort_by']) && isset($_GET['sort_order'])) {
    $sortBy = $_GET['sort_by'];
    $sortOrder = $_GET['sort_order'];
}

$perPageOptions = array(5, 10, 20, 50);
$defaultPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? $_GET['per_page'] : $defaultPerPage;
$offset = ($page - 1) * $perPage;

$sessionId = htmlspecialchars($sessionId, ENT_QUOTES, 'UTF-8');
$sortBy = htmlspecialchars($sortBy, ENT_QUOTES, 'UTF-8');
$sortOrder = htmlspecialchars($sortOrder, ENT_QUOTES, 'UTF-8');
$page = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');
$perPage = htmlspecialchars($perPage, ENT_QUOTES, 'UTF-8');
$offset = htmlspecialchars($offset, ENT_QUOTES, 'UTF-8');


//実行したいSQLを準備する
$sql = 'SELECT * FROM ESG_member_index';

if(!empty($sortBy) && !empty($sortOrder)) {
    $sql .= ' ORDER BY ' . $sortBy . ' ' . $sortOrder;
}

$sql .= ' LIMIT ' . $offset . ', ' . $perPage;

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchall(PDO::FETCH_ASSOC);

$countsql = 'SELECT COUNT(*) AS count FROM ESG_member_index';
$countstmt = $pdo->prepare($countsql);
$countstmt->execute();
$totalCount = $countstmt->fetch(PDO::FETCH_ASSOC)['count'];
$totalPages = ceil($totalCount / $perPage);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>エンジニアリングサービスグループ社員名簿</title>
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
                        print '<a class="nav-link" href="member_inputform.php">社員情報の追加<span class="sr-only">(current)</span></a>';
                        print '</li>';
                        } ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="memberinfo.php?id=<? print($_SESSION['user_id']); ?>">プロフィール<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="sign_out.php">サインアウト<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <figure class="profile-image">
                                <?php print'<img class="headerimage" alt="画像" onclick="changeImage()" src="image.php?id=' . $sessionId . '">' ?> 
                            </figure>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <br>
        <body style="min-width: 600px;">
            <h1>エンジニアリングサービスグループ社員名簿</h1>
            <br>
            <main class="container">
                <section class="row mb-3">
                    <form method="post" action="search.php" class="form-inline form-group col-12 col-md-8">
                        <label class="sr-only" for="kwork">検索ワード</label>
                        <input type="search" name="word" class="form-control form form-control-sm mr-2" placeholder="キーワード" id="kword" requied>
                        <button type="submit" class="btn btn-warning btn-sm pl-3 pr-3">検索</button>
                    </form>
                    <form class="form-inline mb-3">
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
                            <th class="photo"></th>
                            <th scope="col">社員番号 
                                <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=employee_id&sort_order=<?php echo $reverseSortOrder; ?>">▼</a>
                            </th>
                            <th scope="col">社員名
                                <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=member_name&sort_order=<?php echo $reverseSortOrder; ?>">▼</a>
                            </th>
                            <th scope="col">派遣先
                                <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=dispatched&sort_order=<?php echo $reverseSortOrder; ?>">▼</a>
                            </th>
                            <th scope="col">業務内容
                                <?php $reverseSortOrder = ($sortOrder === 'ASC') ? 'DESC' : 'ASC'; ?>
                                <a href="?sort_by=tasks&sort_order=<?php echo $reverseSortOrder; ?>">▼</a>
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
                            <td><?php print'<a href="memberinfo.php?id='. $employeeId .'">' . $employeeId.'</a>'; ?></td>
                            <td><?php print($memberName); ?></td>
                            <td><?php print($dispatched); ?></td>
                            <td><?php print($tasks); ?></td>
                        </tr>
                        <?php } ?>
                    </table>                
                </section>
            </main> 
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?sort_by=' . $sortBy . '&sort_order=' . $sortOrder . '&page=' . $i . '">' . $i . '</a>';
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
</html>