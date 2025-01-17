<?php
// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ユーザー情報取得
$userName = $_SESSION['user_name'] ?? null;
?>
<header class="navbar">
    <a href="index.php" class="logo">コミュニケーション場</a>
    <div class="nav-right">
        <?php if ($userName): ?>
            <a href="group_list.php" class="nav-link">グループ一覧</a>
            <a href="requests.php" class="nav-link">申請一覧</a>
            <a href="detail.php?name=<?php echo urlencode($userName); ?>" class="username-link">
                ようこそ、<?php echo htmlspecialchars($userName); ?> さん
            </a>
            <a href="logout.php" class="logout-link">ログアウト</a>
        <?php else: ?>
            <a href="login.php" class="login-link">ログイン</a>
            <a href="touroku.php" class="register-link" style="margin-left: 10px;">新規登録</a>
        <?php endif; ?>
    </div>
</header>

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
    color: white;
}

.username-link {
    margin-left: 10px;
    font-weight: normal;
    color: white;
}
</style>
