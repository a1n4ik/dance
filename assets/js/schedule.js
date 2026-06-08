// ========== assets/js/schedule.js - Исправленное расписание ========== 

// Глобальные переменные
let scheduleData = {};
let selectedMetro = 'lomonosovsky';
let isLoading = false;

// Маппинг ключей филиалов для совместимости
const branchMapping = {
    'lomonosovsky': 'lomonosovsky',
    'belorusskaya': 'belorusskaya'
};

// Фолбэк данные на случай если API недоступен
const fallbackScheduleData = {
    'lomonosovsky': {
        'general': [
            { day: 'Понедельник', time: '14:45', name: 'Танцевальные занятия 5-7 лет (90 мин)', teacher: 'По записи (все направления)' },
            { day: 'Понедельник', time: '15:45', name: 'Танцевальные занятия 7-9 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Понедельник', time: '17:15', name: 'Танцевальные занятия 9-13 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Среда', time: '14:45', name: 'Танцевальные занятия 5-7 лет (90 мин)', teacher: 'По записи (все направления)' },
            { day: 'Среда', time: '15:45', name: 'Танцевальные занятия 7-9 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Среда', time: '17:15', name: 'Танцевальные занятия 9-14 лет (120 мин)', teacher: 'По записи (все направления)' }
        ]
    },
    'belorusskaya': {
        'general': [
            { day: 'Вторник', time: '16:00', name: 'Танцевальные занятия 7-9 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Вторник', time: '18:00', name: 'Танцевальные занятия 9-14 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Четверг', time: '16:00', name: 'Танцевальные занятия 7-9 лет (120 мин)', teacher: 'По записи (все направления)' },
            { day: 'Четверг', time: '18:00', name: 'Танцевальные занятия 9-14 лет (120 мин)', teacher: 'По записи (все направления)' }
        ]
    }
};

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule module loading...');
    
    // Загружаем расписание из БД
    loadScheduleFromDB().then(() => {
        initializeSchedule();
    }).catch(error => {
        console.error('Failed to load schedule from DB, using fallback data:', error);
        scheduleData = fallbackScheduleData;
        initializeSchedule();
    });
});

// Загрузка расписания с сервера
async function loadScheduleFromDB() {
    console.log('Loading schedule from database...');
    isLoading = true;
    
    try {
        const response = await fetch('/api/schedule.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success && result.data) {
            scheduleData = result.data;
            console.log('Schedule loaded successfully:', scheduleData);
            
            // Показываем уведомление о количестве загруженных занятий
            if (result.total_classes > 0) {
                console.log(`Loaded ${result.total_classes} classes from database`);
            }
        } else {
            throw new Error(result.message || 'Failed to load schedule data');
        }
        
    } catch (error) {
        console.error('Error loading schedule:', error);
        throw error;
    } finally {
        isLoading = false;
    }
}

// Инициализация интерфейса расписания
function initializeSchedule() {
    console.log('Initializing schedule interface...');
    
    // Добавляем обработчики событий для кнопок филиалов
    const metroButtons = document.querySelectorAll('[data-metro]');
    metroButtons.forEach(button => {
        button.addEventListener('click', function() {
            const metro = this.getAttribute('data-metro');
            if (metro && branchMapping[metro]) {
                selectMetro(metro);
            }
        });
    });
    
    // Устанавливаем начальные значения
    if (scheduleData && Object.keys(scheduleData).length > 0) {
        // Выбираем первый доступный филиал
        const availableBranches = Object.keys(scheduleData);
        if (availableBranches.length > 0) {
            selectedMetro = availableBranches[0];
        }
    }
    
    // Устанавливаем активную кнопку и показываем расписание
    updateButtonStates();
    showSchedule();
    
    console.log('Schedule interface initialized');
}

// Выбор филиала
function selectMetro(metro) {
    console.log('Selected metro:', metro);
    selectedMetro = metro;
    
    // Обновляем активные кнопки филиалов
    updateButtonStates();
    
    // Показываем расписание
    showSchedule();
}

// Обновление состояния кнопок
function updateButtonStates() {
    // Обновляем кнопки филиалов
    const metroButtons = document.querySelectorAll('[data-metro]');
    metroButtons.forEach(button => {
        const metro = button.getAttribute('data-metro');
        if (metro === selectedMetro || branchMapping[metro] === selectedMetro) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// Отображение расписания
function showSchedule() {
    console.log('Showing schedule for:', selectedMetro);
    
    const wrapper = document.getElementById('scheduleTableWrapper');
    const table = document.getElementById('scheduleTable');
    
    if (!wrapper || !table) {
        console.error('Schedule table elements not found');
        return;
    }
    
    // Показываем индикатор загрузки
    if (isLoading) {
        table.innerHTML = `
            <thead>
                <tr><th style="text-align: center; padding: 2rem;">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <div style="width: 20px; height: 20px; border: 2px solid #ddd; border-top: 2px solid #007bff; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                        Загрузка расписания...
                    </div>
                </th></tr>
            </thead>
        `;
        wrapper.classList.add('show');
        return;
    }
    
    // Получаем данные расписания
    const metroData = scheduleData[selectedMetro];
    if (!metroData) {
        table.innerHTML = `
            <thead>
                <tr><th style="text-align: center; padding: 3rem; font-size: 1.2rem;">
                    Расписание для выбранного филиала временно недоступно.<br>
                    <span style="font-size: 1rem; opacity: 0.7; font-weight: normal;">
                        Свяжитесь с нами для уточнения времени занятий: +7-999-930-36-60
                    </span>
                    <br><br>
                    <button onclick="loadScheduleFromDB().then(() => showSchedule())" 
                            style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                        Обновить расписание
                    </button>
                </th></tr>
            </thead>
        `;
        wrapper.classList.add('show');
        return;
    }
    
    // Собираем все занятия из всех типов для филиала
    let allClasses = [];
    Object.keys(metroData).forEach(classType => {
        if (metroData[classType] && Array.isArray(metroData[classType])) {
            allClasses = allClasses.concat(metroData[classType]);
        }
    });
    
    if (allClasses.length === 0) {
        table.innerHTML = `
            <thead>
                <tr><th style="text-align: center; padding: 3rem; font-size: 1.2rem;">
                    Расписание для выбранного филиала временно недоступно.<br>
                    <span style="font-size: 1rem; opacity: 0.7; font-weight: normal;">
                        Свяжитесь с нами для уточнения времени занятий: +7-999-930-36-60
                    </span>
                </th></tr>
            </thead>
        `;
        wrapper.classList.add('show');
        return;
    }
    
    // Группируем по дням
    const byDay = {
        'Понедельник': [],
        'Вторник': [],
        'Среда': [],
        'Четверг': [],
        'Пятница': [],
        'Суббота': [],
        'Воскресенье': []
    };
    
    allClasses.forEach(item => {
        if (byDay[item.day]) {
            byDay[item.day].push(item);
        }
    });
    
    // Создаем HTML таблицы
    let html = `
        <thead>
            <tr>
                <th>Время</th>
                <th>Понедельник</th>
                <th>Вторник</th>
                <th>Среда</th>
                <th>Четверг</th>
                <th>Пятница</th>
                <th>Суббота</th>
            </tr>
        </thead>
        <tbody>
    `;
    
    // Получаем все уникальные времена и сортируем
    const allTimes = [...new Set(allClasses.map(item => item.time))].sort();
    
    if (allTimes.length === 0) {
        html += `
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; opacity: 0.7;">
                    Занятий на этой неделе нет
                </td>
            </tr>
        `;
    } else {
        allTimes.forEach(time => {
            html += '<tr>';
            html += `<td class="time-slot">${time}</td>`;
            
            ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'].forEach(day => {
                const dayClasses = byDay[day].filter(item => item.time === time);
                html += '<td>';
                dayClasses.forEach(cls => {
                    html += `
                        <div class="class-info">
                            <span class="class-name">${cls.name}</span>
                            <span class="teacher-name">${cls.teacher}</span>
                        </div>
                    `;
                });
                html += '</td>';
            });
            
            html += '</tr>';
        });
    }
    
    html += '</tbody>';
    
    table.innerHTML = html;
    wrapper.classList.add('show');
    
    console.log('Schedule displayed successfully');
}

// Функция для обновления расписания (может быть вызвана из админ-панели)
async function refreshSchedule() {
    console.log('Refreshing schedule data...');
    try {
        await loadScheduleFromDB();
        showSchedule();
        console.log('Schedule refreshed successfully');
    } catch (error) {
        console.error('Failed to refresh schedule:', error);
        // Показываем fallback данные
        scheduleData = fallbackScheduleData;
        showSchedule();
    }
}

// Экспорт функций для использования в других скриптах
window.scheduleModule = {
    loadScheduleFromDB,
    refreshSchedule,
    selectMetro,
    showSchedule
};

// CSS анимация для индикатора загрузки
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .schedule-table-wrapper {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }
    
    .schedule-table-wrapper.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .schedule-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        text-align: center;
        font-weight: 600;
    }
    
    .schedule-table td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }
    
    .schedule-table .class-info {
        margin-bottom: 0.5rem;
        padding: 0.8rem;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 5px;
        border-left: 3px solid #667eea;
        text-align: left;
    }
    
    .schedule-table .class-name {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
    }
    
    .schedule-table .teacher-name {
        display: block;
        font-size: 0.85rem;
        color: #666;
        font-style: italic;
    }
    
    .schedule-table .time-slot {
        font-weight: 600;
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
        font-size: 1.1rem;
    }
    
    .schedule-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.02);
    }
    
    .selector-btn {
        padding: 0.8rem 1.5rem;
        border: 2px solid #667eea;
        background: transparent;
        color: #667eea;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        margin: 0 0.5rem 1rem 0;
    }
    
    .selector-btn:hover {
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }
    
    .selector-btn.active {
        background: #667eea;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .selector-group {
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .selector-label {
        display: block;
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .schedule-table {
            font-size: 0.9rem;
        }
        
        .schedule-table th,
        .schedule-table td {
            padding: 0.5rem;
        }
        
        .schedule-table .class-info {
            padding: 0.5rem;
            margin-bottom: 0.3rem;
        }
        
        .schedule-table .class-name {
            font-size: 0.85rem;
        }
        
        .schedule-table .teacher-name {
            font-size: 0.8rem;
        }
        
        .selector-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    }
`;
document.head.appendChild(style);

console.log('Schedule module loaded');