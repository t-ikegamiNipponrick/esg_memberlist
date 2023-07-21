<?php

$adminsql = 'SELECT checking_admin FROM ESG_login WHERE user_id = :user_id';
$adminstmt = $pdo->prepare($adminsql);
$adminstmt->bindValue(':user_id', $sessionId, PDO::PARAM_INT);
$adminstmt->execute();
$resultadmin = $adminstmt->fetch(PDO::FETCH_ASSOC);

?>