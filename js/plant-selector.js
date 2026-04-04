const plantsData = [
    {
        id: 1,
        name: "Базилик",
        latin: "Ocimum basilicum",
        category: "herbs",
        image: "🌿",
        facts: [
            "Быстро растёт и даёт много листьев",
            "Незаменим в кухне (песто, соусы)",
            "Отпугивает мелких насекомых"
        ],
        temperature: "18–26 °C",
        humidity: "40–70%",
        light: "6–8 ч/сутки",
        careTips: "Поливайте регулярно, но не переувлажняйте почву. Собирайте листья регулярно для стимуляции роста. Предпочитает хорошо дренированную почву.",
        detailedParams: {
            temperature: "18–26 °C",
            humidity: "40–70% (умеренно влажная)",
            light: "6–8 часов в сутки"
        }
    },
    {
        id: 2,
        name: "Мята",
        latin: "Mentha spp.",
        category: "herbs",
        image: "🌱",
        facts: [
            "Очень ароматна (эфирные масла)",
            "Легко размножается (агрессивный корневой рост)",
            "Отлично для чая и ароматерапии"
        ],
        temperature: "15–24 °C",
        humidity: "50–80%",
        light: "4–6 ч/сутки",
        careTips: "Мята любит влажную почву и полутень. Растёт очень быстро, поэтому может потребоваться ограничение роста. Отлично подходит для выращивания в горшках.",
        detailedParams: {
            temperature: "15–24 °C",
            humidity: "50–80% (предпочитает влажную почву)",
            light: "4–6 часов в сутки (полутень)"
        }
    },
    {
        id: 3,
        name: "Петрушка",
        latin: "Petroselinum crispum",
        category: "herbs",
        image: "🌿",
        facts: [
            "Богата витамином C и железом",
            "Двулетнее растение (второй год даёт цветы/семена)",
            "Хороша как сосед-растение в горшке с овощами"
        ],
        temperature: "10–21 °C",
        humidity: "40–60%",
        light: "4–6 ч/сутки",
        careTips: "Петрушка предпочитает прохладную температуру и умеренную влажность. Собирайте внешние листья, оставляя центр для дальнейшего роста.",
        detailedParams: {
            temperature: "10–21 °C",
            humidity: "40–60%",
            light: "4–6 часов в сутки"
        }
    },
    {
        id: 4,
        name: "Розмарин",
        latin: "Rosmarinus officinalis",
        category: "herbs",
        image: "🌿",
        facts: [
            "Древесное ароматное растение",
            "Засухоустойчив",
            "Используется в кулинарии и косметике"
        ],
        temperature: "12–24 °C",
        humidity: "20–40%",
        light: "6–8 ч/сутки",
        careTips: "Розмарин не любит переувлажнения. Поливайте только когда почва полностью высохнет. Предпочитает хорошо дренированную почву и много света.",
        detailedParams: {
            temperature: "12–24 °C",
            humidity: "20–40% (предпочитает суховато)",
            light: "6–8 часов в сутки"
        }
    },
    {
        id: 5,
        name: "Тимьян",
        latin: "Thymus vulgaris",
        category: "herbs",
        image: "🌿",
        facts: [
            "Низкорослый кустик",
            "Антисептические свойства эфирных масел",
            "Хорош для балконов и каменных горшков"
        ],
        temperature: "10–24 °C",
        humidity: "20–40%",
        light: "6–8 ч/сутки",
        careTips: "Тимьян очень неприхотлив и засухоустойчив. Поливайте редко, но обильно. Отлично подходит для начинающих садоводов.",
        detailedParams: {
            temperature: "10–24 °C",
            humidity: "20–40%",
            light: "6–8 часов в сутки"
        }
    },
    {
        id: 6,
        name: "Шнитт-лук",
        latin: "Allium schoenoprasum",
        category: "herbs",
        image: "🌿",
        facts: [
            "Даёт съедобные перья и цветы",
            "Неприхотлив",
            "Отпугивает некоторых вредителей"
        ],
        temperature: "10–24 °C",
        humidity: "40–60%",
        light: "4–6 ч/сутки",
        careTips: "Лук-резанец очень прост в уходе. Срезайте перья регулярно для стимуляции роста. Цветы также съедобны и красивы.",
        detailedParams: {
            temperature: "10–24 °C",
            humidity: "40–60%",
            light: "4–6 часов в сутки"
        }
    },
    {
        id: 7,
        name: "Листовой салат",
        latin: "Lactuca sativa",
        category: "vegetables",
        image: "🥬",
        facts: [
            "Быстрый цикл роста (урожай за 4–6 недель для листьев)",
            "Любит прохладу",
            "Множество сортов"
        ],
        temperature: "10–18 °C",
        humidity: "60–80%",
        light: "10–12 ч/сутки",
        careTips: "Салат предпочитает прохладную температуру и влажную почву. Собирайте внешние листья, чтобы растение продолжало расти.",
        detailedParams: {
            temperature: "10–18 °C",
            humidity: "60–80% (влажная)",
            light: "10–12 часов в сутки"
        }
    },
    {
        id: 8,
        name: "Шпинат",
        latin: "Spinacia oleracea",
        category: "vegetables",
        image: "🥬",
        facts: [
            "Богат железом и витаминами",
            "Склонен к стрелкованию в сильную жару",
            "Быстро растёт в прохладе"
        ],
        temperature: "5–20 °C",
        humidity: "60–80%",
        light: "8–10 ч/сутки",
        careTips: "Шпинат лучше всего растёт в прохладных условиях. Избегайте высоких температур, чтобы предотвратить стрелкование.",
        detailedParams: {
            temperature: "5–20 °C",
            humidity: "60–80%",
            light: "8–10 часов в сутки"
        }
    },
    {
        id: 9,
        name: "Микрозелень",
        latin: "Разные культуры",
        category: "vegetables",
        image: "🌱",
        facts: [
            "Сбор через 7–14 дней",
            "Высокая концентрация нутриентов в маленьких ростках",
            "Требует плотной посадки"
        ],
        temperature: "18–24 °C",
        humidity: "60–80%",
        light: "10–14 ч/сутки",
        careTips: "Микрозелень требует постоянной влажности и хорошего освещения. Собирайте когда ростки достигнут 5-7 см в высоту.",
        detailedParams: {
            temperature: "18–24 °C",
            humidity: "60–80%",
            light: "10–14 часов в сутки"
        }
    },
    {
        id: 10,
        name: "Черри-томат",
        latin: "Solanum lycopersicum",
        category: "vegetables",
        image: "🍅",
        facts: [
            "Плодоносящее растение",
            "Нуждается в опылении (внутри — руками/ветром)",
            "Даёт много маленьких плодов"
        ],
        temperature: "18–26 °C",
        humidity: "60–70%",
        light: "12–16 ч/сутки",
        careTips: "Томаты требуют много света и тепла. Регулярно поливайте и подкармливайте. Для опыления слегка встряхивайте растения.",
        detailedParams: {
            temperature: "18–26 °C",
            humidity: "60–70%",
            light: "12–16 часов в сутки"
        }
    },
    {
        id: 11,
        name: "Острый перец",
        latin: "Capsicum annuum",
        category: "vegetables",
        image: "🌶️",
        facts: [
            "Любит тепло",
            "Плоды богаты капсаицином",
            "Декоративен и съедобен"
        ],
        temperature: "20–28 °C",
        humidity: "50–70%",
        light: "12–16 ч/сутки",
        careTips: "Перец любит тепло и много света. Поливайте регулярно, но не переувлажняйте. Подкармливайте во время цветения и плодоношения.",
        detailedParams: {
            temperature: "20–28 °C",
            humidity: "50–70%",
            light: "12–16 часов в сутки"
        }
    },
    {
        id: 12,
        name: "Клубника",
        latin: "Fragaria × ananassa",
        category: "vegetables",
        image: "🍓",
        facts: [
            "Может плодоносить в горшках",
            "Бывают ремонтантные сорта для повторных урожаев",
            "Требует опыления"
        ],
        temperature: "15–24 °C",
        humidity: "60–80%",
        light: "8–10 ч/сутки",
        careTips: "Клубника предпочитает прохладную температуру и влажную почву. Обеспечьте хороший дренаж и регулярное опыление.",
        detailedParams: {
            temperature: "15–24 °C",
            humidity: "60–80%",
            light: "8–10 часов в сутки"
        }
    },
    {
        id: 13,
        name: "Алоэ вера",
        latin: "Aloe vera",
        category: "succulents",
        image: "🌵",
        facts: [
            "Суккулент с лечебным гелем",
            "Очень неприхотлив",
            "Светолюбив"
        ],
        temperature: "18–30 °C",
        humidity: "10–30%",
        light: "6–8 ч/сутки",
        careTips: "Алоэ вера очень неприхотливо. Поливайте редко, только когда почва полностью высохнет. Предпочитает яркий свет и хороший дренаж.",
        detailedParams: {
            temperature: "18–30 °C",
            humidity: "10–30% (сухой режим)",
            light: "6–8 часов в сутки (яркий свет)"
        }
    },
    {
        id: 14,
        name: "Хлорофитум",
        latin: "Chlorophytum comosum",
        category: "flowers",
        image: "🌿",
        facts: [
            "Отличное очищающее воздух растение",
            "Быстро даёт «деток»",
            "Легко в разведении"
        ],
        temperature: "15–25 °C",
        humidity: "40–60%",
        light: "4–6 ч/сутки",
        careTips: "Хлорофитум очень неприхотлив и отлично очищает воздух. Поливайте умеренно, избегайте прямых солнечных лучей.",
        detailedParams: {
            temperature: "15–25 °C",
            humidity: "40–60%",
            light: "4–6 часов в сутки (не любит прямого палящего солнца)"
        }
    },
    {
        id: 15,
        name: "Сциндапсус",
        latin: "Epipremnum aureum",
        category: "flowers",
        image: "🌿",
        facts: [
            "Один из самых неприхотливых комнатных растений",
            "Очищает воздух",
            "Растёт в подвесных кашпо"
        ],
        temperature: "15–30 °C",
        humidity: "30–60%",
        light: "4–6 ч/сутки",
        careTips: "Сциндапсус очень неприхотлив и может расти даже при слабом освещении. Поливайте умеренно, давая почве просохнуть между поливами.",
        detailedParams: {
            temperature: "15–30 °C",
            humidity: "30–60%",
            light: "4–6 часов в сутки (терпит низкий свет)"
        }
    },
    {
        id: 16,
        name: "Сансевиерия",
        latin: "Sansevieria",
        category: "succulents",
        image: "🌿",
        facts: [
            "Почти неубиваемая",
            "Выживает при редком поливе",
            "Ночью выделяет кислород"
        ],
        temperature: "15–30 °C",
        humidity: "5–30%",
        light: "2–6 ч/сутки",
        careTips: "Сансевиерия — одно из самых неприхотливых растений. Поливайте очень редко, растение лучше переносит засуху, чем переувлажнение.",
        detailedParams: {
            temperature: "15–30 °C",
            humidity: "5–30% (очень сухой цикл)",
            light: "2–6 часов в сутки (низкое требование)"
        }
    },
    {
        id: 17,
        name: "Спатифиллум",
        latin: "Spathiphyllum",
        category: "flowers",
        image: "🌸",
        facts: [
            "Декоративное цветущее растение",
            "Сигнализирует о поливе — поникает",
            "Эффективен в очистке воздуха"
        ],
        temperature: "18–26 °C",
        humidity: "60–80%",
        light: "4–6 ч/сутки",
        careTips: "Спатифиллум любит влажность и рассеянный свет. Поливайте когда листья начинают поникать. Отлично очищает воздух.",
        detailedParams: {
            temperature: "18–26 °C",
            humidity: "60–80%",
            light: "4–6 часов в сутки (яркий рассеянный)"
        }
    },
    {
        id: 18,
        name: "Сенполия",
        latin: "Saintpaulia",
        category: "flowers",
        image: "🌸",
        facts: [
            "Компактные цветы круглый год при хорошем уходе",
            "Чувствительна к холодной воде",
            "Любит равномерную влажность"
        ],
        temperature: "20–24 °C",
        humidity: "40–60%",
        light: "6–8 ч/сутки",
        careTips: "Фиалки требуют аккуратного ухода. Поливайте тёплой водой, избегайте попадания воды на листья. Предпочитают рассеянный свет.",
        detailedParams: {
            temperature: "20–24 °C",
            humidity: "40–60%",
            light: "6–8 часов в сутки (рассеянный свет)"
        }
    },
    {
        id: 19,
        name: "Суккуленты",
        latin: "Echeveria, Sedum и др.",
        category: "succulents",
        image: "🌵",
        facts: [
            "Запасают воду в листьях",
            "Требуют хорошего дренажа",
            "Декоративны и разнообразны по форме"
        ],
        temperature: "15–28 °C",
        humidity: "5–20%",
        light: "6–10 ч/сутки",
        careTips: "Суккуленты очень неприхотливы. Поливайте редко, только когда почва полностью высохнет. Обеспечьте хороший дренаж и яркий свет.",
        detailedParams: {
            temperature: "15–28 °C",
            humidity: "5–20% (сухой режим)",
            light: "6–10 часов в сутки (яркий свет)"
        }
    },
     {
         id: 20,
         name: "Лаванда",
         latin: "Lavandula angustifolia",
         category: "flowers",
         image: "🌸",
         facts: [
             "Ароматная, используется в сушке и эфирных маслах",
             "Требует хорошего дренажа",
             "Привлекает опылителей на балконе"
         ],
         temperature: "15–24 °C",
         humidity: "20–40%",
         light: "8–10 ч/сутки",
         careTips: "Лаванда любит сухую почву и много света. Поливайте редко, но обильно. Обеспечьте хороший дренаж и проветривание.",
         detailedParams: {
             temperature: "15–24 °C",
             humidity: "20–40% (не любит переувлажнения)",
             light: "8–10 часов в сутки"
         }
     },
     {
         id: 21,
         name: "Вешенки",
         latin: "Pleurotus ostreatus",
         category: "mushrooms",
         image: "🍄",
         facts: [
             "Быстро растут и дают обильный урожай",
             "Неприхотливы в выращивании",
             "Богаты белком и витаминами"
         ],
         temperature: "18–24 °C",
         humidity: "80–90%",
         light: "0–2 ч/сутки",
         careTips: "Вешенки предпочитают высокую влажность и прохладную температуру. Не требуют света для роста. Собирайте когда шляпки достигнут 5-8 см.",
         detailedParams: {
             temperature: "18–24 °C",
             humidity: "80–90% (очень высокая влажность)",
             light: "0–2 часа в сутки (не требуют света)"
         }
     },
     {
         id: 22,
         name: "Шиитаке",
         latin: "Lentinula edodes",
         category: "mushrooms",
         image: "🍄",
         facts: [
             "Ценный съедобный гриб с лечебными свойствами",
             "Требует специального субстрата",
             "Долгий цикл выращивания"
         ],
         temperature: "20–26 °C",
         humidity: "70–85%",
         light: "0–2 ч/сутки",
         careTips: "Шиитаке требует качественного субстрата и стабильных условий. Поливайте субстрат, но не переувлажняйте. Собирайте когда шляпки полностью раскроются.",
         detailedParams: {
             temperature: "20–26 °C",
             humidity: "70–85% (высокая влажность)",
             light: "0–2 часа в сутки (не требуют света)"
         }
     },
     {
         id: 23,
         name: "Рейши",
         latin: "Ganoderma lucidum",
         category: "mushrooms",
         image: "🍄",
         facts: [
             "Лечебный гриб с иммуностимулирующими свойствами",
             "Растёт медленно",
             "Используется в медицине и чаях"
         ],
         temperature: "22–28 °C",
         humidity: "75–85%",
         light: "0–2 ч/сутки",
         careTips: "Рейши требует терпения - растёт медленно. Поддерживайте высокую влажность и стабильную температуру. Собирайте когда гриб достигнет зрелости.",
         detailedParams: {
             temperature: "22–28 °C",
             humidity: "75–85% (высокая влажность)",
             light: "0–2 часа в сутки (не требуют света)"
         }
     },
     {
         id: 24,
         name: "Львиная грива",
         latin: "Hericium erinaceus",
         category: "mushrooms",
         image: "🍄",
         facts: [
             "Необычный внешний вид с длинными шипами",
             "Улучшает когнитивные функции",
             "Деликатесный вкус"
         ],
         temperature: "18–25 °C",
         humidity: "80–90%",
         light: "0–2 ч/сутки",
         careTips: "Львиная грива требует очень высокой влажности. Собирайте когда шипы достигнут 2-3 см в длину. Не допускайте пересыхания субстрата.",
         detailedParams: {
             temperature: "18–25 °C",
             humidity: "80–90% (очень высокая влажность)",
             light: "0–2 часа в сутки (не требуют света)"
         }
     }
];
let currentFilter = 'all';
let searchQuery = '';
document.addEventListener('DOMContentLoaded', function() {
    initializePlantSelector();
    setupEventListeners();
});
function initializePlantSelector() {
    renderPlants(plantsData);
}
function setupEventListeners() {
    const searchInput = document.getElementById('plantSearch');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', handleFilter);
    });
    const modal = document.getElementById('plantModal');
    const closeBtn = document.querySelector('.close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
}
function handleSearch(event) {
    searchQuery = event.target.value.toLowerCase();
    filterAndRenderPlants();
}
function handleFilter(event) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    currentFilter = event.target.dataset.filter;
    filterAndRenderPlants();
}
function filterAndRenderPlants() {
    let filteredPlants = plantsData;
    if (currentFilter !== 'all') {
        filteredPlants = filteredPlants.filter(plant => plant.category === currentFilter);
    }
    if (searchQuery) {
        filteredPlants = filteredPlants.filter(plant => 
            plant.name.toLowerCase().includes(searchQuery) ||
            plant.latin.toLowerCase().includes(searchQuery) ||
            plant.facts.some(fact => fact.toLowerCase().includes(searchQuery))
        );
    }
    renderPlants(filteredPlants);
}
function renderPlants(plants) {
    const gallery = document.getElementById('plantsGallery');
    if (!gallery) return;
    if (plants.length === 0) {
        gallery.innerHTML = '<div class="no-results">Растения не найдены. Попробуйте изменить поисковый запрос или фильтр.</div>';
        return;
    }
    gallery.innerHTML = plants.map(plant => createPlantCard(plant)).join('');
    gallery.querySelectorAll('.plant-card').forEach(card => {
        card.addEventListener('click', function() {
            const plantId = parseInt(this.dataset.plantId);
            openPlantModal(plantId);
        });
    });
}
function createPlantCard(plant) {
    return `
        <div class="plant-card" data-plant-id="${plant.id}">
            <div class="plant-image-container">
                ${plant.image ? `<div class="plant-image-placeholder">${plant.image}</div>` : '<div class="plant-image-placeholder">🌱</div>'}
            </div>
            <div class="plant-info">
                <h3 class="plant-name">${plant.name}</h3>
                <p class="plant-latin">${plant.latin}</p>
                <ul class="plant-facts">
                    ${plant.facts.map(fact => `<li>${fact}</li>`).join('')}
                </ul>
                <div class="plant-parameters">
                    <div class="parameter">
                        <span class="parameter-icon">🌡️</span>
                        <span>${plant.temperature}</span>
                    </div>
                    <div class="parameter">
                        <span class="parameter-icon">💧</span>
                        <span>${plant.humidity}</span>
                    </div>
                    <div class="parameter">
                        <span class="parameter-icon">☀️</span>
                        <span>${plant.light}</span>
                    </div>
                </div>
                <button class="plant-button">Подробнее</button>
            </div>
        </div>
    `;
}
function openPlantModal(plantId) {
    const plant = plantsData.find(p => p.id === plantId);
    if (!plant) return;
    const modal = document.getElementById('plantModal');
    const modalContent = document.getElementById('modalContent');
    if (!modal || !modalContent) return;
    modalContent.innerHTML = createModalContent(plant);
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function createModalContent(plant) {
    return `
        <div class="modal-plant-image-container">
            ${plant.image ? `<div class="modal-plant-image-placeholder">${plant.image}</div>` : '<div class="modal-plant-image-placeholder">🌱</div>'}
        </div>
        <div class="modal-plant-info">
            <h2 class="modal-plant-name">${plant.name}</h2>
            <p class="modal-plant-latin">${plant.latin}</p>
            <ul class="modal-plant-facts">
                ${plant.facts.map(fact => `<li>${fact}</li>`).join('')}
            </ul>
            <div class="parameters-section">
                <h3 class="parameters-title">Параметры для ФитоДомика</h3>
                <div class="parameters-grid">
                    <div class="parameter-item">
                        <div class="parameter-item-icon">🌡️</div>
                        <h4 class="parameter-item-title">Температура</h4>
                        <p class="parameter-item-value">${plant.detailedParams.temperature}</p>
                    </div>
                    <div class="parameter-item">
                        <div class="parameter-item-icon">💧</div>
                        <h4 class="parameter-item-title">Влажность почвы</h4>
                        <p class="parameter-item-value">${plant.detailedParams.humidity}</p>
                    </div>
                    <div class="parameter-item">
                        <div class="parameter-item-icon">☀️</div>
                        <h4 class="parameter-item-title">Освещение</h4>
                        <p class="parameter-item-value">${plant.detailedParams.light}</p>
                    </div>
                </div>
            </div>
            <div class="care-tips">
                <h3 class="care-tips-title">Советы по уходу</h3>
                <p class="care-tips-text">${plant.careTips}</p>
            </div>
            <div class="system-tip">
                <p class="system-tip-text">💡 Эти параметры можно использовать при создании режима в системе ФитоДомик.</p>
            </div>
        </div>
    `;
}
function closeModal() {
    const modal = document.getElementById('plantModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
function openFeedbackForm() {
    alert('Форма обратной связи будет добавлена в следующих версиях. Спасибо за интерес!');
}
window.PlantSelector = {
    openPlantModal,
    closeModal,
    openFeedbackForm
};
