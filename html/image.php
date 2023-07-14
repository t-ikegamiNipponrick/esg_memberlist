<?php
require_once 'dbindex.php';

$employeeId = $_GET['id']; // 取得したい従業員のemployee_id

$stmt = $pdo->prepare("SELECT file_content, file_type FROM ESG_memberPicsB WHERE key_id = :employeeId");
$stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $imageData = $row['file_content'];
    $imageType = $row['file_type'];

    ob_start();

    // echo "画像データの長さ: " . strlen($imageData);
    // echo "画像データの先頭数バイト: " . bin2hex(substr($imageData, 0, 16));

    // Content-Typeヘッダを設定
    header('Content-Type:' . $imageType);
    ob_end_clean();

    // 画像データを出力
    echo $imageData;
} else {
    echo "画像が見つかりませんでした。";
}
?>