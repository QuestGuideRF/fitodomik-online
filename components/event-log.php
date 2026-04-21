<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isGuest = !isset($_SESSION['user_id']);
$user_id = $isGuest ? 1 : $_SESSION['user_id'];
$guestEvents = [];
if ($isGuest) {
    $guestEvents = [
        [
            'id' => 1001,
            'user_id' => 1,
            'event_type' => 'temperature',
            'event_description' => 'Тревога: Повышение температуры выше допустимого предела (30.5°C)',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours'))
        ],
        [
            'id' => 1002,
            'user_id' => 1,
            'event_type' => 'humidity',
            'event_description' => 'Тревога: Снижение влажности ниже допустимого предела (35%)',
            'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
        ],
        [
            'id' => 1003,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Тревога: Отключение системы полива',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 1004,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Система запущена',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ],
        [
            'id' => 1005,
            'user_id' => 1,
            'event_type' => 'lighting',
            'event_description' => 'Установлен режим освещения: 16 часов в сутки',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
        ],
        [
            'id' => 1006,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Перезагрузка контроллера',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 1007,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Информационное событие: Установлена влажность 55%',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))
        ],
        [
            'id' => 1008,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Информационное событие: Включен экономичный режим',
            'created_at' => date('Y-m-d H:i:s', strtotime('-8 hours'))
        ],
        [
            'id' => 1009,
            'user_id' => 1,
            'event_type' => 'device',
            'event_description' => 'Информационное событие: Завершен цикл полива',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days 3 hours'))
        ]
    ];
}
if ($isGuest) {
    $events = $guestEvents;
} else {
    $stmt = $pdo->prepare("SELECT * FROM event_log WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$filteredEvents = array_filter($events, function($event) {
    $type = $_GET['type'] ?? 'all';
    $period = $_GET['period'] ?? 'all';
    if ($type !== 'all' && $event['event_type'] !== $type) {
        return false;
    }
    if ($period !== 'all') {
        $eventDate = new DateTime($event['created_at']);
        $now = new DateTime();
        $diff = $now->diff($eventDate);
        if ($period === 'day' && $diff->days > 1) {
            return false;
        } elseif ($period === 'week' && $diff->days > 7) {
            return false;
        } elseif ($period === 'month' && $diff->days > 30) {
            return false;
        }
    }
    return true;
});
?>
<div class="accordion">
    <div class="accordion-header" onclick="this.nextElementSibling.classList.toggle('active'); this.querySelector('.accordion-icon').classList.toggle('active');" style="background-color: var(--primary-color); color: white;">
        <span>Журнал событий 📜</span>
        <span class="accordion-icon">▼</span>
    </div>
    <div class="accordion-content" itemscope itemtype="https://schema.org/EventSeries">
        <meta itemprop="name" content="Журнал событий умной фермы">
        <meta itemprop="description" content="История событий и тревог умной фермы ФитоДомик">
        <div class="event-log-container">
            <?php if ($isGuest): ?>
            <div class="guest-notice">
                <p>Вы просматриваете данные в режиме гостя. Для доступа к полному журналу событий необходимо <a href="authentication/login.php">авторизоваться</a>.</p>
            </div>
            <?php endif; ?>
            <div class="event-filters">
                <div class="event-type-label">Тип события:</div>
                <button class="filter-button" onclick="setFilter('all')" aria-label="Показать все типы событий" title="Показать все типы событий">Все</button>
                <button class="filter-button" onclick="setFilter('alarm')" aria-label="Показать только тревоги" title="Показать только события типа 'Тревога'">Тревоги</button>
                <button class="filter-button" onclick="setFilter('system')" aria-label="Показать только системные события" title="Показать только системные события">Система</button>
                <button class="filter-button" onclick="setFilter('info')" aria-label="Показать только информационные события" title="Показать только информационные события">Информация</button>
            </div>
            <div class="event-period">
                <div class="period-label">Период:</div>
                <button class="period-button" onclick="setPeriod('day')" aria-label="Показать события за день" title="Показать события за последние 24 часа">День</button>
                <button class="period-button" onclick="setPeriod('week')" aria-label="Показать события за неделю" title="Показать события за последние 7 дней">Неделя</button>
                <button class="period-button" onclick="setPeriod('month')" aria-label="Показать события за месяц" title="Показать события за последние 30 дней">Месяц</button>
            </div>
            <div class="event-list" itemprop="events" itemscope itemtype="https://schema.org/ItemList">
                <meta itemprop="numberOfItems" content="<?php echo count($filteredEvents); ?>">
                <?php if (empty($filteredEvents)): ?>
                    <div class="event-item" style="text-align: center; padding: 20px; background-color: var(--background-color);">
                        Нет событий для отображения.
                    </div>
                <?php else: ?>
                    <?php foreach ($filteredEvents as $index => $event): ?>
                        <div class="event-item <?php echo $event['event_type']; ?>"
                             style="background-color: <?php
                            switch($event['event_type']) {
                                case 'temperature':
                                case 'humidity':
                                    echo 'rgba(220, 53, 69, 0.85)';
                                    break;
                                case 'device':
                                case 'lighting':
                                    echo 'rgba(13, 110, 253, 0.85)';
                                    break;
                                default:
                                    echo 'rgba(25, 135, 84, 0.85)';
                            }
                            ?>; color: white; margin-bottom: 10px; padding: 10px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                             itemprop="itemListElement" itemscope itemtype="https://schema.org/Event">
                            <meta itemprop="position" content="<?php echo $index + 1; ?>">
                            <meta itemprop="url" content="https://fitodomik.online/index.php#event<?php echo $event['id']; ?>">
                            <div class="event-time" itemprop="startDate" content="<?php echo date('c', strtotime($event['created_at'])); ?>">
                                <?php echo date('d.m.Y H:i:s', strtotime($event['created_at'])); ?>
                            </div>
                            <div class="event-message" itemprop="name"><?php echo $event['event_description']; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="event-actions" style="margin-top: 20px; text-align: right;">
                <button class="clear-button" onclick="clearEventLog()" <?php echo $isGuest ? 'disabled title="Недоступно в гостевом режиме"' : ''; ?>
                        style="background-color: rgba(220, 53, 69, 0.85); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; <?php echo $isGuest ? 'opacity: 0.5; cursor: not-allowed;' : ''; ?>"
                        aria-label="Очистить журнал событий"
                        title="Удалить все записи из журнала событий">Очистить журнал</button>
            </div>
            <meta itemprop="dateModified" content="<?php echo date('c'); ?>">
        </div>
    </div>
</div>
<div id="shareModal" class="modal" role="dialog" aria-labelledby="shareModalTitle" aria-modal="true">
    <div class="modal-content">
        <span class="close" aria-label="Закрыть окно" title="Закрыть">&times;</span>
        <h2 id="shareModalTitle">Поделиться доступом</h2>
        <div class="share-options">
            <div class="share-option">
                <h3>Код доступа</h3>
                <div class="share-input-container">
                    <label for="eventLogShareCode">Код доступа для предоставления гостевого просмотра:</label>
                    <input type="text" id="eventLogShareCode" class="form-control share-input" readonly
                           aria-label="Код доступа для гостя" title="Код доступа для предоставления гостевого просмотра">
                    <button class="copy-btn" onclick="copyText('eventLogShareCode')" aria-label="Копировать код доступа" title="Копировать код в буфер обмена">Копировать</button>
                </div>
            </div>
            <div class="share-option">
                <h3>Ссылка для гостя</h3>
                <div class="share-input-container">
                    <label for="eventLogShareLink">Ссылка для гостевого просмотра:</label>
                    <input type="text" id="eventLogShareLink" class="form-control share-input" readonly
                           aria-label="Ссылка для гостевого просмотра" title="Ссылка для гостевого просмотра вашей фермы">
                    <button class="copy-btn" onclick="copyText('eventLogShareLink')" aria-label="Копировать ссылку" title="Копировать ссылку в буфер обмена">Копировать</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .filter-button, .period-button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: inline-block;
    }
    .filter-button.active, .period-button.active {
        background-color: var(--secondary-color);
    }
    .guest-notice {
        background-color: rgba(255, 193, 7, 0.2);
        border-left: 4px solid
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .guest-notice p {
        margin: 0;
        color: var(--text-color,
    }
    .guest-notice a {
        color:
        text-decoration: none;
        font-weight: bold;
    }
    .guest-notice a:hover {
        text-decoration: underline;
    }
    .copy-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color:
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 1100;
        animation: fadeInOut 2s ease-in-out;
    }
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(-20px); }
        20% { opacity: 1; transform: translateY(0); }
        80% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-20px); }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.filter-button[onclick="setFilter(\'all\')"]').classList.add('active');
        document.querySelector('.period-button[onclick="setPeriod(\'day\')"]').classList.add('active');
    });
    function setFilter(type) {
        document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.filter-button[onclick="setFilter('${type}')"]`).classList.add('active');
        filterEvents(type, document.querySelector('.period-button.active').getAttribute('onclick').match(/setPeriod\('(.+?)'\)/)[1]);
    }
    function setPeriod(period) {
        document.querySelectorAll('.period-button').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.period-button[onclick="setPeriod('${period}')"]`).classList.add('active');
        filterEvents(document.querySelector('.filter-button.active').getAttribute('onclick').match(/setFilter\('(.+?)'\)/)[1], period);
    }
    function filterEvents(type, period) {
        const eventList = document.querySelector('.event-list');
        const events = <?php echo json_encode($events); ?>;
        const filteredEvents = events.filter(event => {
            if (type !== 'all') {
                switch(type) {
                    case 'alarm':
                        return ['temperature', 'humidity', 'device'].includes(event.event_type) &&
                               event.event_description.toLowerCase().includes('тревога');
                    case 'system':
                        return ['device', 'lighting'].includes(event.event_type) &&
                               !event.event_description.toLowerCase().includes('тревога');
                    case 'info':
                        return event.event_type === 'device' &&
                               (event.event_description.toLowerCase().includes('событие') ||
                                event.event_description.toLowerCase().includes('режим') ||
                                event.event_description.toLowerCase().includes('информац'));
                    default:
                        return false;
                }
            }
            return true;
        }).filter(event => {
            const eventDate = new Date(event.created_at);
            const now = new Date();
            const diff = Math.floor((now - eventDate) / (1000 * 60 * 60 * 24));
            switch(period) {
                case 'day':
                    return diff <= 1;
                case 'week':
                    return diff <= 7;
                case 'month':
                    return diff <= 30;
                default:
                    return true;
            }
        });
        if (filteredEvents.length === 0) {
            eventList.innerHTML = '<div class="event-item" style="text-align: center; padding: 20px; background-color: var(--background-color);">Нет событий для отображения.</div>';
        } else {
            eventList.innerHTML = filteredEvents.map(event => {
                let backgroundColor;
                if (event.event_description.toLowerCase().includes('тревога')) {
                    backgroundColor = 'rgba(220, 53, 69, 0.75)';
                } else if (['device', 'lighting'].includes(event.event_type) &&
                          !event.event_description.toLowerCase().includes('событие') &&
                          !event.event_description.toLowerCase().includes('режим') &&
                          !event.event_description.toLowerCase().includes('информац')) {
                    backgroundColor = 'rgba(13, 110, 253, 0.75)';
                } else {
                    backgroundColor = 'rgba(25, 135, 84, 0.75)';
                }
                return `
                    <div class="event-item ${event.event_type}" style="background-color: ${backgroundColor}; color: white; margin-bottom: 10px; padding: 10px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="event-time">${new Date(event.created_at).toLocaleString('ru-RU')}</div>
                        <div class="event-message">${event.event_description}</div>
                    </div>
                `;
            }).join('');
        }
    }
    function clearEventLog() {
        const isGuest = <?php echo $isGuest ? 'true' : 'false'; ?>;
        if (isGuest) {
            alert('Очистка журнала недоступна в гостевом режиме');
            return;
        }
        if (confirm('Вы уверены, что хотите очистить журнал событий? Это действие нельзя отменить.')) {
            fetch('/api/clear-event-log.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.event-list').innerHTML = '<div class="event-item" style="text-align: center; padding: 20px; background-color: var(--background-color);">Нет событий для отображения.</div>';
                    alert('Журнал событий успешно очищен');
                } else {
                    alert('Ошибка при очистке журнала: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при очистке журнала');
            });
        }
    }
    function copyText(elementId) {
        const copyInput = document.getElementById(elementId);
        copyInput.select();
        document.execCommand('copy');
        const notification = document.createElement('div');
        notification.className = 'copy-notification';
        notification.textContent = 'Скопировано в буфер обмена';
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.remove();
        }, 2000);
    }
</script>