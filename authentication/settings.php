<?php
require_once '../config/database.php';
require_once '../config/session.php';
require_once '../config/headers.php';
if (!isLoggedIn()) {
    header("Location: authentication/login.php");
    exit();
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if (empty($first_name) || empty($last_name) || empty($username)) {
        $error = 'Пожалуйста, заполните все обязательные поля';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $user['id']]);
        if ($stmt->fetch()) {
            $error = 'Этот никнейм уже занят';
        } else {
            if (!empty($current_password)) {
                if (!password_verify($current_password, $user['password'])) {
                    $error = 'Неверный текущий пароль';
                } elseif ($new_password !== $confirm_password) {
                    $error = 'Новые пароли не совпадают';
                } elseif (empty($new_password)) {
                    $error = 'Новый пароль не может быть пустым';
                }
            }
            if (empty($error)) {
                try {
                    if (!empty($current_password)) {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ?, password = ? WHERE id = ?");
                        $stmt->execute([$first_name, $last_name, $username, $hashed_password, $user['id']]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, username = ? WHERE id = ?");
                        $stmt->execute([$first_name, $last_name, $username, $user['id']]);
                    }
                    $_SESSION['user_id'] = $user['id'];
                    $success = 'Настройки успешно обновлены';
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $user = $stmt->fetch();
                } catch (PDOException $e) {
                    $error = 'Ошибка при обновлении данных';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки - ФитоДомик</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../security/image.php?file=icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../security/image.php?file=icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../security/image.php?file=icon/favicon-16x16.png">
    <link rel="manifest" href="../security/manifest.php?file=site.webmanifest">
    <link rel="shortcut icon" href="../security/image.php?file=icon/favicon.ico">
    <style>
        .telegram-change-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        .telegram-change-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .telegram-change-content {
            background: #1E1E1E;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            border: 1px solid #2F2F2F;
        }
        .telegram-change-content h3 {
            margin: 0 0 1.5rem 0;
            font-size: 1.5rem;
            color: #fff;
            text-align: center;
        }
        .telegram-change-steps {
            background: #2A2A2A;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .telegram-step {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            background: #1E1E1E;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid #2F2F2F;
        }
        .telegram-step:last-child {
            margin-bottom: 0;
        }
        .step-number {
            background: #4CAF50;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            font-size: 1rem;
        }
        .step-content {
            flex: 1;
        }
        .step-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #fff;
            font-size: 1.1rem;
        }
        .step-description {
            color: #B0B0B0;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .step-description code {
            background: #2A2A2A;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.9rem;
            color: #4CAF50;
            border: 1px solid #3A3A3A;
        }
        .step-description strong {
            color: #4CAF50;
            font-weight: 600;
        }
        .telegram-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .telegram-actions .auth-button {
            flex: 1;
            padding: 0.8rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .telegram-actions .auth-button:hover {
            background: #45a049;
        }
        .telegram-actions .auth-button.secondary {
            background: #2A2A2A;
            border: 1px solid #3A3A3A;
        }
        .telegram-actions .auth-button.secondary:hover {
            background: #333333;
        }
        .telegram-actions .auth-button svg {
            width: 20px;
            height: 20px;
        }
        .telegram-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .telegram-username {
            font-weight: 500;
            color: #fff;
            background: #2A2A2A;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 1rem;
            border: 1px solid #3A3A3A;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .telegram-change-modal.active {
            animation: fadeIn 0.2s ease-out;
        }
        .telegram-change-modal.active .telegram-change-content {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <button id="theme-toggle" class="theme-toggle auth-theme-toggle">
            <span class="theme-icon">🌙</span>
        </button>
        <div class="auth-form">
            <h2>Настройки профиля</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="first_name">Имя</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Фамилия</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="username">Никнейм</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Telegram</label>
                    <div class="telegram-info">
                        <span class="telegram-username">@<?php echo htmlspecialchars($user['telegram_username'] ?? ''); ?></span>
                        <button type="button" class="auth-button secondary" onclick="openTelegramChange()">Сменить Telegram</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="current_password">Текущий пароль (для изменения пароля)</label>
                    <input type="password" id="current_password" name="current_password">
                </div>
                <div class="form-group">
                    <label for="new_password">Новый пароль</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Подтверждение нового пароля</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>
                <div class="profile-actions">
                    <button type="submit" class="auth-button">Сохранить изменения</button>
                    <a href="profile.php" class="auth-button secondary return-profile">Вернуться в профиль</a>
                    <a href="../index.php" class="auth-button secondary">Вернуться на главную</a>
                </div>
            </form>
        </div>
    </div>
    <div id="telegramChangeModal" class="telegram-change-modal">
        <div class="telegram-change-content">
            <h3>Смена Telegram</h3>
            <div class="telegram-change-steps">
                <div class="telegram-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <div class="step-title">Откройте Telegram бота</div>
                        <div class="step-description">
                            Перейдите к боту <strong>@FitoDomik_bot</strong> в Telegram
                        </div>
                    </div>
                </div>
                <div class="telegram-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <div class="step-title">Отправьте команду</div>
                        <div class="step-description">
                            Отправьте команду <code>/start</code> боту
                        </div>
                    </div>
                </div>
                <div class="telegram-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <div class="step-title">Введите никнейм</div>
                        <div class="step-description">
                            Введите ваш никнейм <strong><?php echo htmlspecialchars($user['username']); ?></strong> когда бот попросит
                        </div>
                    </div>
                </div>
                <div class="telegram-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <div class="step-title">Подтверждение</div>
                        <div class="step-description">
                            После успешной верификации, ваш Telegram будет автоматически обновлен
                        </div>
                    </div>
                </div>
            </div>
            <div class="telegram-actions">
                <a href="https://t.me/FitoDomik_bot" target="_blank" class="auth-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 2L11 13"></path>
                        <path d="M22 2L15 22L11 13L2 9L22 2Z"></path>
                    </svg>
                    Открыть бота
                </a>
                <button type="button" class="auth-button secondary" onclick="closeTelegramChange()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Закрыть
                </button>
            </div>
        </div>
    </div>
    <script src="../js/theme.js"></script>
    <script>
        function openTelegramChange() {
            document.getElementById('telegramChangeModal').classList.add('active');
        }
        function closeTelegramChange() {
            document.getElementById('telegramChangeModal').classList.remove('active');
        }
        document.getElementById('telegramChangeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTelegramChange();
            }
        });
    </script>
</body>
</html> 