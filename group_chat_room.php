<?php
require 'db.php'; // データベース接続
require 'header.php'; // 共通ヘッダー

// ログインしていない場合はリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$groupId = $_GET['group_id'] ?? null;
if (!$groupId) {
    echo "グループIDが指定されていません。";
    exit;
}

// グループ情報を取得
$sql = "SELECT * FROM groups WHERE id = :group_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':group_id' => $groupId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    echo "グループが存在しません。";
    exit;
}

// グループメッセージを取得
$sql = "SELECT gm.message, gm.created_at, r.name AS sender_name
        FROM group_messages gm
        JOIN registrations r ON gm.sender_id = r.id
        WHERE gm.group_id = :group_id
        ORDER BY gm.created_at ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':group_id' => $groupId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ログインユーザーID
$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($group['name']); ?> のグループチャット</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #fff7e6;
        }
        .chat-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff7f50;
        }
        .chat-box {
            max-height: 400px;
            overflow-y: auto;
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }
        .message {
            margin-bottom: 10px;
        }
        .message .sender {
            font-weight: bold;
            color: #ff7f50;
        }
        .my-message {
            text-align: right;
            color: green;
        }
        .send-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .send-form textarea {
            width: 80%;
            height: 50px;
            resize: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            font-size: 1rem;
        }
        .send-form button {
            width: 15%;
            height: 50px;
            background-color: #ff7f50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        .send-form button:hover {
            background-color: #ff6347;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="header"><?php echo htmlspecialchars($group['name']); ?> のグループチャット</div>
        
        <div class="chat-box" id="chat-box">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?php echo $msg['sender_name'] === $_SESSION['user_name'] ? 'my-message' : ''; ?>">
                    <span class="sender"><?php echo htmlspecialchars($msg['sender_name']); ?>:</span>
                    <span class="text"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></span>
                    <div class="timestamp"><?php echo $msg['created_at']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post" id="send-message-form" class="send-form">
            <textarea name="message" placeholder="メッセージを入力..." required></textarea>
            <button type="submit">送信</button>
        </form>
    </div>

    <script>
        function fetchMessages() {
            const groupId = "<?php echo $groupId; ?>";
            fetch(`fetch_group_messages.php?group_id=${groupId}`)
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = ''; // 既存のメッセージをクリア
                    data.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message ' + (msg.sender_name === "<?php echo $_SESSION['user_name']; ?>" ? 'my-message' : '');
                        messageDiv.innerHTML = `
                            <span class="sender">${msg.sender_name}:</span>
                            <span class="text">${msg.message}</span>
                            <div class="timestamp">${msg.created_at}</div>
                        `;
                        chatBox.appendChild(messageDiv);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight; // 最新メッセージまでスクロール
                });
        }

        // 初期メッセージ取得
        fetchMessages();

        // 定期的にメッセージを取得（3秒ごと）
        setInterval(fetchMessages, 3000);

        // メッセージ送信
        document.getElementById('send-message-form').addEventListener('submit', function(e) {
            e.preventDefault(); // フォーム送信を中断
            const formData = new FormData(this);
            formData.append('group_id', "<?php echo $groupId; ?>");
            fetch('send_group_message.php', {
                method: 'POST',
                body: formData
            }).then(() => {
                fetchMessages(); // 送信後にメッセージ更新
                this.reset(); // フォームをリセット
            });
        });
    </script>
        <?php require 'footer.php'; // 共通フッター ?>
</body>
</html>
