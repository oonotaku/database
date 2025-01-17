<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'] ?? null;
$groupId = htmlspecialchars($_POST['group_id'] ?? '', ENT_QUOTES, 'UTF-8');

// ログインしていない場合はリダイレクト
if (!$userId) {
    header('Location: login.php');
    exit;
}

// 既に参加申請を送っているか確認
$checkRequestSql = "SELECT * FROM group_requests WHERE group_id = :group_id AND user_id = :user_id";
$checkRequestStmt = $pdo->prepare($checkRequestSql);
$checkRequestStmt->execute([':group_id' => $groupId, ':user_id' => $userId]);
$requestExists = $checkRequestStmt->fetch(PDO::FETCH_ASSOC);

if ($requestExists) {
    echo "<p style='color: red; text-align: center;'>既に参加申請を送信済みです。</p>";
    exit;
}

// 参加申請を送る
$sql = "INSERT INTO group_requests (group_id, user_id, status) VALUES (:group_id, :user_id, 'pending')";
$stmt = $pdo->prepare($sql);
$stmt->execute([':group_id' => $groupId, ':user_id' => $userId]);

header("Location: group_detail.php?group_id=$groupId");
exit;
