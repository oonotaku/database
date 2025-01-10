<?php
// session_start();
require 'header.php'; // 共通ヘッダー
require 'db.php'; // データベース接続

// 検索キーワードを取得
$keyword = htmlspecialchars($_GET['keyword'] ?? '', ENT_QUOTES, 'UTF-8');

// SQLクエリを動的に生成
$sql = "SELECT name, photo_path, company FROM registrations";
$params = [];

if (!empty($keyword)) {
    $sql .= " WHERE company LIKE :keyword"; // 所属会社で部分一致検索
    $params[':keyword'] = '%' . $keyword . '%';
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Peatix風サイト</title>
    <style>
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #fff7e6;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #ff7f50;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    color: white;
}

.navbar a {
    text-decoration: none;
    color: white;
    font-weight: bold;
    margin-left: 15px;
}

.navbar a:hover {
    text-decoration: underline;
}

.logo {
    font-size: 1.5rem;
    font-weight: bold;
}

.center-message {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 70vh;
    text-align: center;
}

.center-message h1 {
    font-size: 3rem;
    color: #ff7f50;
}

.cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

.card {
    width: calc(100% / 3 - 32px);
    max-width: 320px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease-in-out;
    text-align: center; /* カード内の内容を中央寄せ */
}

.card:hover {
    transform: translateY(-10px);
}

.card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card h3 {
    color: #ff7f50;
    font-size: 1.2rem;
    margin: 12px 0;
}

.card p {
    font-size: 0.9rem;
    color: #666;
    padding: 0 12px 12px;
}
    </style>
</head>
<body>

<!-- <header class="navbar">
    <a href="#" class="logo">My Peatix風サイト</a>
    <div class="nav-right">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="detail.php?name=<?php echo urlencode($_SESSION['user_name']); ?>" class="username-link">
                ようこそ、<?php echo htmlspecialchars($_SESSION['user_name']); ?> さん
            </a>
            <a href="logout.php" class="logout-link">ログアウト</a>
        <?php else: ?>
            <a href="login.php" class="login-link">ログイン</a>
            <a href="touroku.php" class="register-link" style="margin-left: 10px;">新規登録</a>
        <?php endif; ?>
    </div>
</header> -->

<main>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- ログインしていない場合 -->
        <div class="center-message">
            <h1>ようこそ！</h1>
        </div>
    <?php else: ?>
        <!-- ログインしている場合 -->
        <h1 style="text-align: center; margin-top: 20px;">登録一覧</h1>
        <!-- 検索フォーム -->
        <div class="search-container" style="text-align: center; margin: 20px 0;">
            <form method="get" action="index.php">
                <input type="text" name="keyword" placeholder="会社名で検索" value="<?php echo htmlspecialchars($keyword); ?>" style="width: 300px; padding: 10px; font-size: 1rem; border: 1px solid #ccc; border-radius: 4px;">
                <button type="submit" style="padding: 10px 20px; background-color: #ff7f50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">検索</button>
            </form>
        </div>

        <div class="cards">
            <?php if (count($results) > 0): ?>
                <?php foreach ($results as $row): ?>
                    <div class="card">
                        <a href="detail.php?name=<?php echo urlencode($row['name']); ?>">
                            <img src="<?php echo htmlspecialchars($row['photo_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        </a>
                        <p><?php echo htmlspecialchars($row['company']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; font-size: 1.2rem;">該当するデータがありません。</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>


<?php require 'footer.php'; // 共通フッター ?>


</body>
</html>
