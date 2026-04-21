<?php
require_once 'config/database.php';
$isGuest = !isset($_SESSION['user_id']);
$user_id = $isGuest ? 1 : $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM farm_status WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$status = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$status) {
    $stmt = $pdo->query("SELECT temperature, humidity, light_level FROM farm_status ORDER BY created_at DESC LIMIT 1");
    $lastStatus = $stmt->fetch(PDO::FETCH_ASSOC);
    $status = [
        'temperature' => $lastStatus ? $lastStatus['temperature'] : '25.0',
        'humidity' => $lastStatus ? $lastStatus['humidity'] : '60',
        'light_level' => $lastStatus ? $lastStatus['light_level'] : '16',
        'photo' => '',
        'photo_analysis' => '',
        'comment' => 'Все показатели в норме. Температура поддерживается в оптимальном диапазоне. Влажность почвы достаточная для роста растений. Уровень CO₂ в допустимых пределах. Система освещения работает по расписанию. Следующий полив запланирован через 2 часа.'
    ];
}
$lastUpdate = strtotime($status['created_at'] ?? 'now');
$updateTime = date('H:i', $lastUpdate);
?>
<div class="accordion">
    <div class="accordion-header" onclick="toggleFarmStatus()">
        <h2>Состояние фермы</h2>
        <div class="header-right-content">
            <span class="last-update">Последнее обновление: <span id="lastUpdate"><?php echo $updateTime; ?></span></span>
            <i class="fas fa-chevron-down accordion-icon" id="farmStatusIcon"></i>
        </div>
    </div>
    <div class="accordion-content active" id="farmStatusContent" itemscope itemtype="https://schema.org/Product">
        <meta itemprop="name" content="Умная ферма ФитоДомик">
        <meta itemprop="description" content="Система умного мониторинга и управления растениями с климат-контролем">
        <meta itemprop="productID" content="farm-<?php echo $user_id; ?>">
        <meta itemprop="dateModified" content="<?php echo date('c', $lastUpdate); ?>">
        <?php if ($isGuest): ?>
            <div class="guest-message">
                <p>Для просмотра фотографий и данных вашей фермы, пожалуйста, авторизуйтесь.</p>
            </div>
        <?php else: ?>
            <div class="farm-content-grid">
                <div class="farm-photos-column">
                    <div class="farm-photo-container" itemprop="image">
                        <h3>Оригинальное фото</h3>
                        <?php if (!empty($status['photo'])): ?>
                            <img src="security/image.php?file=farm_photos/<?php echo htmlspecialchars($status['photo']); ?>" alt="Фото фермы" class="farm-photo" loading="lazy" width="320" height="240">
                        <?php else: ?>
                            <div class="farm-photo-placeholder">
                                <span>Нет фото</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="farm-photo-container">
                        <h3>Анализ фото</h3>
                        <?php if (!empty($status['photo_analysis'])): ?>
                            <img src="security/image.php?file=farm_photos/<?php echo htmlspecialchars($status['photo_analysis']); ?>" alt="Анализ фото" class="farm-photo" loading="lazy" width="320" height="240">
                        <?php else: ?>
                            <div class="farm-photo-placeholder">
                                <span>Нет анализа</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="farm-info-column">
                    <div class="farm-status-text" itemprop="description">
                        <h3>Общее состояние системы</h3>
                        <p><?php
                            if ($isGuest) {
                                echo 'Для просмотра состояния вашей фермы, пожалуйста, авторизуйтесь.';
                            } else {
                                echo nl2br(htmlspecialchars($status['comment'] ?? 'Информация о состоянии системы отсутствует.'));
                            }
                        ?></p>
                    </div>
                    <div class="farm-sensors-grid" itemscope itemtype="https://schema.org/ItemList">
                        <meta itemprop="numberOfItems" content="7">
                        <meta itemprop="name" content="Параметры микроклимата умной фермы">
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="1">
                            <div class="sensor-icon">🌡️</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Температура</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '-- °C';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT temperature FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $temp = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo number_format((float)$temp, 1, '.', '') . '°C';
                                    }
                                ?></p>
                                <meta itemprop="unitCode" content="CEL">
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="2">
                            <div class="sensor-icon">💧</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Влажность</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '--%';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT humidity FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $hum = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo number_format((float)$hum, 1, '.', '') . '%';
                                    }
                                ?></p>
                                <meta itemprop="unitCode" content="P1">
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="3">
                            <div class="sensor-icon">🌱</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Влажность почвы</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '--%';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT soil_moisture FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $soil = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo number_format((float)$soil, 1, '.', '') . '%';
                                    }
                                ?></p>
                                <meta itemprop="unitCode" content="P1">
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="4">
                            <div class="sensor-icon">🌍</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">CO₂</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '-- ppm';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT co2 FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $co2 = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo (int)$co2 . ' ppm';
                                    }
                                ?></p>
                                <meta itemprop="unitCode" content="PPM">
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="5">
                            <div class="sensor-icon">🌬️</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Давление</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '-- мм.рт.ст';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT pressure FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $press = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo number_format((float)$press, 1, '.', '') . ' мм.рт.ст';
                                    }
                                ?></p>
                                <meta itemprop="unitCode" content="MMHG">
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="6">
                            <div class="sensor-icon">🚪</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Шторы</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '--';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT curtains_state FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $curtains = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo $curtains ? 'Открыты' : 'Закрыты';
                                    }
                                ?></p>
                            </div>
                        </div>
                        <div class="sensor-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/PropertyValue">
                            <meta itemprop="position" content="7">
                            <div class="sensor-icon">💡</div>
                            <div class="sensor-info">
                                <h4 itemprop="name">Освещение</h4>
                                <p itemprop="value"><?php
                                    if ($isGuest) {
                                        echo '--';
                                    } else {
                                        $stmt = $pdo->prepare("SELECT lamp_state FROM sensor_data WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                                        $stmt->execute([$user_id]);
                                        $lamp = $stmt->fetch(PDO::FETCH_COLUMN);
                                        echo $lamp ? 'Включено' : 'Выключено';
                                    }
                                ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
.accordion{margin-bottom:20px;border:1px solid var(--border-color);border-radius:8px;overflow:hidden;background-color:var(--card-bg)}
.accordion-header{background-color:var(--primary-color);padding:15px 20px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;color:white;border-radius:8px;-webkit-user-select:none;user-select:none}
.accordion-header:hover{background-color:var(--primary-color)}
.accordion-header h2{margin:0;font-size:18px;color:white}
.header-right-content{display:flex;align-items:center;gap:15px}
.last-update{color:rgba(255,255,255,0.8);font-size:14px}
.accordion-icon{transition:transform 0.3s ease}
.accordion-icon.rotate{transform:rotate(180deg)}
.accordion-content{display:none;padding:20px;background-color:var(--card-bg);border-radius:0 0 8px 8px}
.accordion-content.active{display:block}
.farm-content-grid{display:grid;grid-template-columns:350px 1fr;gap:20px}
.farm-photos-column{display:flex;flex-direction:column;gap:20px}
.farm-photo-container{background-color:var(--background);padding:10px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
.farm-photo-container h3{margin-top:0;margin-bottom:10px;color:var(--text-color);text-align:center;font-size:14px}
.farm-photo{width:100%;max-height:250px;object-fit:contain;border-radius:4px;display:block}
.farm-photo-placeholder{height:150px;display:flex;align-items:center;justify-content:center;background-color:var(--background);border:2px dashed var(--border-color);border-radius:4px;color:var(--text-color);font-size:14px}
.farm-info-column{display:flex;flex-direction:column;gap:20px}
.farm-status-text{background-color:var(--background);padding:15px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
.farm-status-text h3{margin-top:0;margin-bottom:10px;color:var(--text-color);font-size:16px}
.farm-status-text p{margin:0;color:var(--text-color);line-height:1.25;font-size:15px}
.farm-sensors-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;padding:15px;background-color:var(--background);border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
.sensor-item{display:flex;align-items:center;gap:10px;padding:12px;background-color:var(--card-bg);border-radius:6px;border:1px solid var(--border-color)}
.sensor-icon{width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:20px;background-color:var(--background);border-radius:50%}
.sensor-info{flex:1}
.sensor-info h4{margin:0 0 4px 0;font-size:13px;color:var(--text-color);opacity:0.8}
.sensor-info p{margin:0;font-size:16px;font-weight:500;color:var(--text-color)}
.guest-message{text-align:center;padding:40px;background-color:var(--background);border-radius:8px;color:var(--text-color)}
@media (max-width:768px){
    .farm-content-grid{grid-template-columns:1fr}
    .sensor-item{padding:10px}
    .sensor-icon{width:28px;height:28px;font-size:18px}
    .farm-status-content.active{padding:15px}
    .farm-photo,.farm-photo-placeholder{max-height:200px}
    .sensor-info h4 {
        text-align: center;
    }
    .sensor-info p {
        text-align: center;
    }
    .temperature-block .settings-row,
    .humidity-block .settings-row {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .value-input,
    .tolerance-input {
        width: 100%;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .save-settings {
        width: 100%;
        margin-top: 10px;
        text-align: center;
    }
    .farm-sensors-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width:480px){
    .farm-sensors-grid {
        grid-template-columns: 1fr;
    }
    .sensor-item {
        display: flex;
        flex-direction: column;
        text-align: center;
        padding: 15px 10px;
    }
    .sensor-icon {
        margin: 0 auto 8px auto;
        width: 36px;
        height: 36px;
    }
    .value-input label,
    .tolerance-input label {
        margin-bottom: 5px;
    }
}
</style>
<script>
let farmUpdateTimer;
function getFarmStatus() {
    <?php if ($isGuest): ?>
        return;
    <?php endif; ?>
    fetch('api/get-farm-status.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const originalContainer = document.querySelector('.farm-photo-container:first-child');
                if (data.photo && originalContainer) {
                    const img = originalContainer.querySelector('img');
                    if (img) {
                        img.src = `security/image.php?file=farm_photos/${data.photo}`;
                    } else {
                        const placeholder = originalContainer.querySelector('.farm-photo-placeholder');
                        if (placeholder) {
                            const newImg = document.createElement('img');
                            newImg.src = `security/image.php?file=farm_photos/${data.photo}`;
                            newImg.alt = "Фото фермы";
                            newImg.className = "farm-photo";
                            newImg.loading = "lazy";
                            newImg.width = 320;
                            newImg.height = 240;
                            placeholder.innerHTML = '';
                            placeholder.appendChild(newImg);
                        }
                    }
                }
                const analysisContainer = document.querySelector('.farm-photo-container:nth-child(2)');
                if (data.photo_analysis && analysisContainer) {
                    const img = analysisContainer.querySelector('img');
                    if (img) {
                        img.src = `security/image.php?file=farm_photos/${data.photo_analysis}`;
                    } else {
                        const placeholder = analysisContainer.querySelector('.farm-photo-placeholder');
                        if (placeholder) {
                            const newImg = document.createElement('img');
                            newImg.src = `security/image.php?file=farm_photos/${data.photo_analysis}`;
                            newImg.alt = "Анализ фото";
                            newImg.className = "farm-photo";
                            newImg.loading = "lazy";
                            newImg.width = 320;
                            newImg.height = 240;
                            placeholder.innerHTML = '';
                            placeholder.appendChild(newImg);
                        }
                    }
                }
                const commentElement = document.querySelector('.farm-status-text p');
                if (commentElement && data.comment) {
                    commentElement.innerHTML = data.comment;
                }
                const lastUpdateElement = document.getElementById('lastUpdate');
                if (lastUpdateElement) {
                    const date = new Date();
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    lastUpdateElement.textContent = `${hours}:${minutes}`;
                }
            }
        })
        .catch(error => console.error('Ошибка получения статуса фермы:', error));
    clearTimeout(farmUpdateTimer);
    farmUpdateTimer = setTimeout(getFarmStatus, 30000);
}
document.addEventListener('DOMContentLoaded', function() {
    getFarmStatus();
    const textElements = document.querySelectorAll('.farm-status-text p');
    textElements.forEach(el => {
        el.style.whiteSpace = 'normal';
        el.style.overflow = 'hidden';
    });
});
function toggleFarmStatus() {
    const content = document.getElementById('farmStatusContent');
    const icon = document.getElementById('farmStatusIcon');
    content.classList.toggle('active');
    icon.classList.toggle('rotate');
}
</script>