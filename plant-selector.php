<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'security/security_bootstrap.php';
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Помощь в посадке - ФитоДомик</title>
    <meta name="description" content="Выберите растение для выращивания в ФитоДомике. Подробная информация о популярных домашних растениях и их требованиях к уходу.">
    <meta name="keywords" content="выбор растений, домашние растения, выращивание, фитодомик, базилик, мята, петрушка, розмарин">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://fitodomik.online/plant-selector.php">
    <meta property="og:title" content="Помощь в посадке - ФитоДомик">
    <meta property="og:description" content="Выберите растение для выращивания в ФитоДомике. Подробная информация о популярных домашних растениях.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://fitodomik.online/plant-selector.php">
    <meta property="og:image" content="https://fitodomik.online/security/image.php?file=icon/apple-touch-icon.png">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="ФитоДомик">
    <link rel="stylesheet" href="security/css.php?file=styles.css">
    <link rel="stylesheet" href="css/plant-selector.css">
    <script src="security/js.php?file=theme.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="security/image.php?file=icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="security/image.php?file=icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="security/image.php?file=icon/favicon-16x16.png">
    <link rel="manifest" href="security/manifest.php?file=site.webmanifest">
    <link rel="shortcut icon" href="security/image.php?file=icon/favicon.ico">
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            document.querySelector('.theme-icon').textContent = newTheme === 'dark' ? '🌙' : '☀️';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.querySelector('.theme-icon').textContent = savedTheme === 'dark' ? '🌙' : '☀️';
        });
    </script>
</head>
<body itemscope itemtype="https://schema.org/WebPage">
    <header class="main-header">
        <div class="header-content">
            <div class="header-left">
                <div class="user-info" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <div class="user-avatar">
                        <?php if ($user && !empty($user['avatar'])): ?>
                            <img src="security/image.php?file=avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="Фото профиля" loading="lazy" width="40" height="40" itemprop="image">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <span itemprop="name"><?php echo $user ? strtoupper(substr($user['first_name'], 0, 1)) . strtoupper(substr($user['last_name'], 0, 1)) : 'Г'; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-dropdown">
                        <button class="profile-button">Профиль</button>
                        <div class="dropdown-content">
                            <?php if (!$user): ?>
                                <a href="authentication/login.php">Войти</a>
                                <a href="authentication/register.php">Регистрация</a>
                            <?php else: ?>
                                <a href="authentication/profile.php">Настройки</a>
                                <a href="authentication/logout.php">Выйти</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="site-title" itemprop="headline">ФитоДомик</h1>
            <div class="header-right">
                <button onclick="toggleTheme()" class="theme-toggle">
                    <span class="theme-icon">☀️</span>
                </button>
            </div>
        </div>
    </header>
    <main class="plant-selector-container" itemprop="mainContentOfPage">
        <section class="hero-section">
            <h1 class="hero-title">Помощь в посадке 🌱</h1>
            <p class="hero-subtitle">Выберите растение, чтобы узнать, как за ним ухаживать и какие параметры задать в системе.</p>
            <p class="hero-description">Здесь собраны самые популярные домашние растения и грибы — от базилика до суккулентов и вешенок.<br>
            Узнайте оптимальные условия роста и выберите, что подходит вашему фитодомику.</p>
        </section>
        <section class="search-filters-section">
            <div class="search-container">
                <input type="text" id="plantSearch" placeholder="Найти растение..." class="search-input">
                <div class="search-icon">🔍</div>
            </div>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">Все растения</button>
                <button class="filter-btn" data-filter="herbs">Травы</button>
                <button class="filter-btn" data-filter="vegetables">Овощи</button>
                <button class="filter-btn" data-filter="flowers">Цветы</button>
                <button class="filter-btn" data-filter="succulents">Суккуленты</button>
                <button class="filter-btn" data-filter="mushrooms">Грибы</button>
            </div>
        </section>
        <section class="plants-gallery" id="plantsGallery">
        </section>
        <div class="back-to-system-container">
            <a href="index.php" class="back-to-system-button">
                ← Вернуться в систему управления
            </a>
        </div>
        <div id="plantModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modalContent">
                </div>
            </div>
        </div>
    </main>
    <script src="js/plant-selector.js"></script>
</body>
</html>