<?php
require 'db.php';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$groupId = $_GET['group_id'] ?? null;
if (!$groupId) {
    echo "グループIDが指定されていません。";
    exit;
}

$sql = "SELECT * FROM registrations WHERE id != :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invitedUsers = $_POST['invited_users'] ?? [];
    foreach ($invitedUsers as $userId) {
        $inviteSql = "INSERT INTO group_requests (group_id, user_id, status) VALUES (:group_id, :user_id, 'invited')";
        $inviteStmt = $pdo->prepare($inviteSql);
        $inviteStmt->execute([
            ':group_id' => $groupId,
            ':user_id' => $userId,
        ]);
    }
    echo "招待を送信しました。";
    header("Location: group_detail.php?group_id=$groupId");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>グループ招待</title>
</head>
<body>
    <h1>招待リスト</h1>
    <form method="POST" action="">
        <?php foreach ($users as $user): ?>
            <div>
                <label>
                    <input type="checkbox" name="invited_users[]" value="<?php echo $user['id']; ?>">
                    <?php echo htmlspecialchars($user['name']); ?>
                </label>
            </div>
        <?php endforeach; ?>
        <button type="submit">招待を送る</button>
    </form>
</body>
</html>
