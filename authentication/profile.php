<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../config/headers.php';
if (!isLoggedIn()) {
    echo '<script>window.location.href = "login.php";</script>';
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$success_message = '';
$error_message = '';
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/avatars';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024;
    if (!in_array($file['type'], $allowed_types)) {
        $error_message = 'Разрешены только изображения в форматах JPEG, PNG и GIF';
    } elseif ($file['size'] > $max_size) {
        $error_message = 'Размер файла не должен превышать 5MB';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error_message = 'Ошибка при загрузке файла';
    } else {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $user['id'] . '_' . time() . '.' . $extension;
        $upload_path = $upload_dir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            if ($stmt->execute([$filename, $user['id']])) {
                $success_message = 'Фотография профиля успешно обновлена';
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$user['id']]);
                $user = $stmt->fetch();
            } else {
                $error_message = 'Ошибка при обновлении фотографии профиля';
            }
        } else {
            $error_message = 'Ошибка при сохранении файла';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль - ФитоДомик</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../security/image.php?file=icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../security/image.php?file=icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../security/image.php?file=icon/favicon-16x16.png">
    <link rel="manifest" href="../security/manifest.php?file=site.webmanifest">
    <link rel="shortcut icon" href="../security/image.php?file=icon/favicon.ico">
</head>
<body>
    <div class="auth-container">
        <button id="theme-toggle" class="theme-toggle auth-theme-toggle">
            <span class="theme-icon">🌙</span>
        </button>
        <div class="auth-form">
            <h2>Профиль пользователя</h2>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <div class="profile-content">
                <div class="profile-info">
                    <div class="profile-field">
                        <label>Имя:</label>
                        <span><?php echo htmlspecialchars($user['first_name']); ?></span>
                    </div>
                    <div class="profile-field">
                        <label>Фамилия:</label>
                        <span><?php echo htmlspecialchars($user['last_name']); ?></span>
                    </div>
                    <div class="profile-field">
                        <label>Никнейм:</label>
                        <span><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="profile-field">
                        <label>Telegram:</label>
                        <span>@<?php echo htmlspecialchars($user['telegram_username'] ?? 'Не указан'); ?></span>
                    </div>
                </div>
                <div class="profile-avatar">
                    <div class="user-avatar">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="https://fitodomik.online/uploads/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Фото профиля" loading="lazy" width="100" height="100">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <span><?php echo strtoupper(substr($user['first_name'], 0, 1)) . strtoupper(substr($user['last_name'], 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="avatar-form">
                        <label for="avatar" class="avatar-upload-label">
                            <span class="upload-icon">📷</span>
                            Изменить фото
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="avatar-input">
                    </form>
                </div>
            </div>
            <div class="profile-actions">
                <a href="settings.php" class="auth-button">Настройки</a>
                <a href="logout.php" class="auth-button logout">Выйти</a>
                <a href="../index.php" class="auth-button secondary">Вернуться на главную</a>
            </div>
        </div>
    </div>
    <script>
        document.querySelector('.avatar-input').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                this.closest('form').submit();
            }
        });
    </script>
    <script src="js/theme.js"></script>
</body>
</html>