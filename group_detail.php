<?php
require 'db.php'; // データベース接続
require 'header.php'; // 共通ヘッダー

// ログインしていない場合はリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// `group_id` を取得
$groupId = $_GET['group_id'] ?? null;
if (!$groupId) {
    echo "グループIDが指定されていません。";
    exit;
}

try {
    // グループ情報を取得
    $sql = "SELECT * FROM `groups` WHERE id = :group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':group_id' => $groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$group) {
        echo "グループが存在しません。";
        exit;
    }

    // グループメンバー情報を取得
    $sql = "SELECT gm.user_id, r.name AS user_name, gm.role 
            FROM group_members gm 
            JOIN registrations r ON gm.user_id = r.id 
            WHERE gm.group_id = :group_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':group_id' => $groupId]);
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ログインユーザーID
    $userId = $_SESSION['user_id'];

    // ログインユーザーがグループメンバーであるか確認
    $isMember = false;
    foreach ($members as $member) {
        if ((int)$member['user_id'] === (int)$userId) {
            $isMember = true;
            break;
        }
    }

    // グループ作成者かどうか
    $isOwner = (int)$userId === (int)$group['created_by'];

} catch (PDOException $e) {
    // エラー発生時にエラーメッセージを表示
    echo "エラーが発生しました: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グループ詳細</title>
    <style>
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #ff7f50;
        }
        .description {
            margin-bottom: 20px;
        }
        .member-list {
            margin-top: 20px;
        }
        .member-card {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .member-card:last-child {
            border-bottom: none;
        }
        .join-btn, .pending-btn {
            display: block;
            background-color: #ff7f50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            margin: 20px auto;
        }
        .join-btn:hover {
            background-color: #ff6347;
        }
        .disabled-btn {
            background-color: grey;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($group['name']); ?> の詳細</h1>
        <p class="description"><?php echo nl2br(htmlspecialchars($group['description'])); ?></p>

        <!-- グループ編集リンク -->
        <?php if ($isOwner): ?>
            <a href="group_edit.php?group_id=<?php echo $group['id']; ?>" class="join-btn">グループを編集する</a>
        <?php endif; ?>

        <!-- イベントページへのリンク -->
        <a href="event_list.php?group_id=<?php echo urlencode($groupId); ?>" class="join-btn">イベント一覧を見る</a>

        <!-- 参加申請ボタンまたはメンバーステータス -->
        <?php if ($isMember): ?>
            <p style="color: green; text-align: center;">あなたはこのグループのメンバーです。</p>
        <?php else: ?>
            <form method="POST" action="send_group_request.php">
                <input type="hidden" name="group_id" value="<?php echo $groupId; ?>">
                <button type="submit" class="join-btn">グループに参加申請を送る</button>
            </form>
        <?php endif; ?>

        <!-- グループメンバー一覧 -->
        <div class="member-list">
            <h2>メンバー一覧</h2>
            <?php foreach ($members as $member): ?>
                <div class="member-card">
                    <strong><?php echo htmlspecialchars($member['user_name']); ?></strong>
                    <?php if ($member['role'] === 'admin'): ?>
                        <span style="color: #ff7f50;">(管理者)</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <p style="text-align: center;"><a href="group_list.php">グループ一覧に戻る</a></p>
        <?php if ($isOwner): ?>
    <a href="invite_users.php?group_id=<?php echo $groupId; ?>" class="join-btn">ユーザーを招待する</a>
<?php endif; ?>

    </div>
    <?php require 'footer.php'; // 共通フッター ?>
</body>
</html>
