<?php
// session_start();
require 'db.php';
require 'header.php'; // ログインユーザー名の表示を含む共通ヘッダー

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // 管理者認証情報（固定）
    $adminUsername = 'admin'; // 管理者のユーザー名
    $adminPasswordHash = password_hash('admin123', PASSWORD_DEFAULT); // 管理者のパスワード（ハッシュ化済み）

    // 認証処理
    if ($username === $adminUsername && password_verify($password, $adminPasswordHash)) {
        // 認証成功
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php'); // 管理者専用ページにリダイレクト
        exit;
    } else {
        $error = "ユーザー名またはパスワードが間違っています。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
</head>
<body>
    <h1>管理者ログイン</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" action="admin_login.php">
        <label for="username">ユーザー名：</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">パスワード：</label>
        <input type="password" name="password" id="password" required><br>
        <button type="submit">ログイン</button>
    </form>
</body>
</html>
