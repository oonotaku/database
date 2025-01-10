<?php
require 'db.php'; // データベース接続ファイル
// session_start(); // セッション開始
require 'header.php'; // 共通ヘッダー

// GETパラメータから 'name' を取得
$name = htmlspecialchars($_GET['name'] ?? '', ENT_QUOTES, 'UTF-8');

// データベースから該当するユーザー情報を取得
$sql = "SELECT * FROM registrations WHERE name = :name";
$stmt = $pdo->prepare($sql);
$stmt->execute([':name' => $name]);
$details = $stmt->fetch(PDO::FETCH_ASSOC);

if ($details):
?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($details['name']); ?> の詳細</title>
        <style>
            .container {
                max-width: 600px;
                margin: 50px auto;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                text-align: center;
            }
            img {
                max-width: 100%;
                border-radius: 8px;
            }
            h1 {
                color: #ff7f50;
                text-align: center;
                margin-bottom: 20px;
            }
            p {
                font-size: 1rem;
                margin: 10px 0;
            }
            nav a {
                display: inline-block;
                margin-top: 20px;
                text-decoration: none;
                color: #ff7f50;
                font-weight: bold;
            }
            .edit-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: #ff7f50;
                font-weight: bold;
                text-decoration: none;
                padding: 10px;
                border: 1px solid #ff7f50;
                border-radius: 4px;
                transition: background-color 0.3s;
            }
            .edit-link:hover {
                background-color: #ff7f50;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1><?php echo htmlspecialchars($details['name']); ?> の詳細</h1>
            <?php if (!empty($details['photo_path'])): ?>
                <img src="<?php echo htmlspecialchars($details['photo_path']); ?>" alt="<?php echo htmlspecialchars($details['name']); ?>">
            <?php endif; ?>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($details['email']); ?></p>
            <p><strong>所属会社:</strong> <?php echo htmlspecialchars($details['company']); ?></p>
            <p><strong>役職:</strong> <?php echo htmlspecialchars($details['position']); ?></p>
            <p><strong>備考:</strong> <?php echo nl2br(htmlspecialchars($details['memo'])); ?></p>

            <!-- 自身の情報の場合のみ編集リンクを表示 -->
            <?php if (isset($_SESSION['user_name']) && $_SESSION['user_name'] === $details['name']): ?>
                <a href="edit.php?id=<?php echo urlencode($details['id']); ?>" class="edit-link">情報を編集する</a>
            <?php endif; ?>

            <nav>
                <a href="index.php">← 登録一覧に戻る</a>
            </nav>
        </div>
        <?php require 'footer.php'; // 共通フッター ?>
    </body>
    </html>
<?php
else:
?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>データが見つかりません</title>
        <style>
            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
            }
            h1 {
                color: #721c24;
                margin-bottom: 20px;
            }
            a {
                text-decoration: none;
                color: #ff7f50;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>該当するデータが見つかりません。</h1>
            <p>データが存在しないか、URLが正しくありません。</p>
            <nav>
                <a href="index.php">← トップページに戻る</a>
            </nav>
        </div>
        <?php require 'footer.php'; // 共通フッター ?>
    </body>
    </html>
<?php
endif;
