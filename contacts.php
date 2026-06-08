<?php
session_start();
require_once 'config/database.php';
require_once 'includes/email_notifications.php'; // Подключаем email уведомления

// SEO мета-данные
$page_title = "Контакты - Театр танца Столица | Адреса филиалов в Москве";
$page_description = "Контакты театра танца Столица в Москве. Адреса и телефоны филиалов: м. Ломоносовский проспект, м. Университет. Запись на занятия танцами.";
$page_keywords = "контакты театр танца столица, адрес, телефон, ломоносовский проспект, университет, запись на танцы";

// Дополнительные CSS файлы
$additional_css = ['/assets/css/contacts.css'];

// Дополнительные JS файлы
$additional_js = ['/assets/js/contacts.js'];

// Обработка отправки заявки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_application'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $class_type = $_POST['class_type'];
    $branch = $_POST['branch'];
    $age = trim($_POST['age']);
    $message = trim($_POST['message']);
    $privacy_consent = isset($_POST['privacy_consent']) ? 1 : 0;
    
    // Проверка согласия на обработку данных
    if (!$privacy_consent) {
        $error_message = "Необходимо дать согласие на обработку персональных данных.";
    } elseif (!empty($name) && !empty($phone)) {
        try {
            // Сохраняем заявку в БД
            $stmt = $pdo->prepare("INSERT INTO applications (name, phone, email, class_type, branch, age, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$name, $phone, $email, $class_type, $branch, $age, $message])) {
                // Подготавливаем данные для email уведомления
                $applicationData = [
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'class_type' => $class_type,
                    'branch' => $branch,
                    'age' => $age,
                    'message' => $message
                ];
                
                // Отправляем email уведомление
                $emailSent = sendApplicationEmail($applicationData);
                
                // Логируем результат отправки email
                if ($emailSent) {
                    error_log("Email notification sent successfully for: " . $name);
                } else {
                    error_log("Failed to send email notification for: " . $name);
                }
                
                $success_message = "Ваша заявка отправлена! Мы свяжемся с вами в ближайшее время.";
                
                // Очищаем поля формы после успешной отправки
                $_POST = [];
                
            } else {
                $error_message = "Произошла ошибка при отправке заявки. Попробуйте еще раз.";
            }
            
        } catch (Exception $e) {
            error_log("Application submission error: " . $e->getMessage());
            $error_message = "Произошла ошибка при отправке заявки. Попробуйте еще раз.";
        }
    } else {
        $error_message = "Пожалуйста, заполните обязательные поля: имя и телефон.";
    }
}

// Включаем заголовок
require_once 'includes/header.php';
?>

<style>
.contacts-hero {
    background: linear-gradient(135deg, var(--blue-bright) 0%, var(--pink-medium) 100%);
}

.contacts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    margin-bottom: 3rem;
}

.contact-info {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.contact-info h3 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    color: var(--blue-dark);
    text-transform: uppercase;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(179, 229, 252, 0.1);
    border-radius: 15px;
    border-left: 4px solid var(--blue-bright);
}

.contact-icon {
    font-size: 1.5rem;
    color: var(--blue-bright);
    margin-top: 0.2rem;
}

.contact-details h4 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.contact-details p {
    color: #666;
    line-height: 1.6;
}

.contact-details a {
    color: var(--blue-bright);
    text-decoration: none;
    font-weight: 500;
}

.contact-details a:hover {
    text-decoration: underline;
}

.application-form {
    background: white;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.application-form h3 {
    font-size: 1.8rem;
    margin-bottom: 2rem;
    color: var(--blue-dark);
    text-transform: uppercase;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--blue-bright);
}

.form-group textarea {
    resize: vertical;
    height: 120px;
}

/* Стили для чекбокса согласия */
.privacy-consent {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin: 1.5rem 0;
    padding: 1rem;
    background: rgba(179, 229, 252, 0.1);
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.privacy-consent input[type="checkbox"] {
    width: auto;
    margin: 0;
    transform: scale(1.2);
}

.privacy-consent label {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.4;
    cursor: pointer;
}

.privacy-consent a {
    color: var(--blue-bright);
    text-decoration: underline;
}

.submit-btn {
    width: 100%;
    padding: 1.2rem;
    background: var(--blue-bright);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background: var(--blue-dark);
    transform: translateY(-2px);
}

.submit-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.branches-section {
    background: rgba(179, 229, 252, 0.1);
    padding: 4rem 0;
    margin-top: 4rem;
}

.branches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.branch-card {
    background: white;
    padding: 2.5rem;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.branch-card h4 {
    font-size: 1.4rem;
    color: var(--blue-dark);
    margin-bottom: 1rem;
}

.branch-address {
    color: #666;
    margin-bottom: 1.5rem;
}

.branch-metro {
    display: inline-block;
    background: var(--pink-light);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.map-section {
    margin-top: 4rem;
}

.yandex-map {
    width: 100%;
    height: 400px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.success-message {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.error-message {
    background: linear-gradient(135deg, #f8d7da, #f1aeb5);
    color: #721c24;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .contacts-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .contact-info, .application-form {
        padding: 2rem;
    }
    
    .branches-grid {
        grid-template-columns: 1fr;
    }
    
    .yandex-map {
        height: 300px;
    }
}
.branch-card a {
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.branch-card a:hover {
    transform: translateY(-2px);
}

.branch-card a .branch-metro {
    transition: all 0.3s ease;
    cursor: pointer;
}

.branch-card a:hover .branch-metro {
    background: var(--blue-bright);
    box-shadow: 0 5px 15px rgba(179, 229, 252, 0.4);
    transform: scale(1.05);
}

/* Стили для активного состояния */
.branch-card a:active .branch-metro {
    transform: scale(0.98);
}
</style>

<!-- Page Hero -->
<section class="page-hero contacts-hero">
    <div class="container">
        <div class="page-hero-content">
            <h1 class="page-title">Контакты</h1>
            <p class="page-subtitle">Свяжитесь с нами для записи на занятия</p>
        </div>
    </div>
</section>

<!-- Breadcrumbs -->
<section class="breadcrumbs">
    <div class="container">
        <ul class="breadcrumbs-list">
            <li><a href="/">Главная</a></li>
            <li>Контакты</li>
        </ul>
    </div>
</section>

<!-- Contact Section -->
<section class="content-section">
    <div class="container">
        <div class="contacts-grid">
            <!-- Contact Information -->
            <div class="contact-info">
                <h3>Свяжитесь с нами</h3>
                
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-details">
                        <h4>Телефоны для записи на Белорусской </h4>
                        <p>
						
							<a href="tel:+79154134347">+7 (915) 413-43-47</a><br>
							<a href="tel:+79999303660">+7 (999) 930-36-60</a>
                        </p>
						<h4>Телефон для записи на Мичуринском</h4>
                        <p>
						
							<a href="tel:+79154134347">+7 (915) 413-43-47</a><br>
							<a href="tel:+79646430593">+7 (964) 643-05-93</a>
                        </p>
						
                    </div>
                </div>


                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-details">
                        <h4>Наши филиалы</h4>
                        <p>
                            м. Ломоносовский проспект<br>
                            м. Белорусская<br>
                           
                        </p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">🕒</div>
                    <div class="contact-details">
                        <h4>Режим работы</h4>
                        <p>
                            Ежедневно: 10:00 - 22:00<br>
                            Администратор: 11:00 - 20:00
                        </p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">💬</div>
                    <div class="contact-details">
                        <h4>Мессенджеры</h4>
                        <p>
                            <a href="https://wa.me/79154134347" target="_blank">WhatsApp</a><br>
                            <a href="https://t.me/theatrestolitsa" target="_blank">Telegram</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="application-form">
                <h3>Записаться на занятие</h3>
                
                <?php if (isset($success_message)): ?>
                    <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
                
                <form method="POST" id="applicationForm">
                    <div class="form-group">
                        <label for="name">Имя *</label>
                        <input type="text" id="name" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон *</label>
                        <input type="tel" id="phone" name="phone" required value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="class_type">Направление</label>
                        <select id="class_type" name="class_type">
                            <option value="">Выберите направление</option>
                            <option value="classical" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'classical') ? 'selected' : '' ?>>Классический танец</option>
                            <option value="folk" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'folk') ? 'selected' : '' ?>>Народный танец</option>
                            <option value="jazz-modern" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'jazz-modern') ? 'selected' : '' ?>>Джаз-модерн</option>
                            <option value="baby-ballet" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'baby-ballet') ? 'selected' : '' ?>>Танцевальная практика</option>
                            <option value="gymnastics" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'gymnastics') ? 'selected' : '' ?>>Партерная гимнастика</option>
                            <option value="acrobatics" <?= (isset($_POST['class_type']) && $_POST['class_type'] === 'acrobatics') ? 'selected' : '' ?>>Акробатика</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="branch">Филиал</label>
                        <select id="branch" name="branch">
                            <option value="">Выберите филиал</option>
                            <option value="lomonosovsky" <?= (isset($_POST['branch']) && $_POST['branch'] === 'lomonosovsky') ? 'selected' : '' ?>>м. Ломоносовский проспект</option>
                            <option value="belorusskaya" <?= (isset($_POST['branch']) && $_POST['branch'] === 'belorusskaya') ? 'selected' : '' ?>>м. Белорусская</option>
                           
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Возраст ученика</label>
                        <input type="text" id="age" name="age" placeholder="Например: 7 лет" value="<?= isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Дополнительная информация</label>
                        <textarea id="message" name="message" placeholder="Опишите ваши пожелания или вопросы"><?= isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '' ?></textarea>
                    </div>

                    <!-- Согласие на обработку персональных данных -->
                    <div class="privacy-consent">
                        <input type="checkbox" id="privacy_consent" name="privacy_consent" value="1" <?= (isset($_POST['privacy_consent']) && $_POST['privacy_consent']) ? 'checked' : '' ?>>
                        <label for="privacy_consent">
                            Я даю согласие на <a href="/privacy.php" target="_blank">обработку персональных данных</a> 
                            и соглашаюсь с <a href="/terms.php" target="_blank">условиями использования</a> сайта.
                        </label>
                    </div>
                    
                    <button type="submit" name="submit_application" class="submit-btn" id="submitBtn">
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Branches Section -->
<section class="branches-section">
    <div class="container">
        <h2 class="section-title">Наши филиалы</h2>
        <div class="branches-grid">
            <div class="branch-card">
                <h4>Филиал на Ломоносовском</h4>
                <p class="branch-address">Мичуринский проспект, 3</p>
                <a href="https://yandex.ru/maps/-/CLaNB0Km" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    <span class="branch-metro">Открыть карту</span>
                </a>
            </div>
            
            <div class="branch-card">
                <h4>Филиал на Белорусской</h4>
				                <p class="branch-address">ул. 3-Ямского поля, 22, с3</p>
                <a href="https://yandex.ru/maps/-/CDwbiGIg" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    <span class="branch-metro">Открыть карту</span>
                </a>
               
            </div>
            

        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section" id="map">
    <div class="container">
        <h2 class="section-title">Как нас найти</h2>
        <div class="yandex-map">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A6804b80bf0bdede6c865874b800360d70b00d54ae7198de94dbe76e15ed58ad6&amp;source=constructor" 
                    width="100%" 
                    height="400" 
                    frameborder="0">
            </iframe>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="content-section">
    <div class="container">
        <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 3rem; color: var(--blue-dark);">
            Часто задаваемые вопросы
        </h2>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <details style="margin-bottom: 1.5rem; background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <summary style="font-weight: 600; color: var(--blue-dark); cursor: pointer; font-size: 1.1rem;">
                    С какого возраста можно заниматься танцами?
                </summary>
                <p style="margin-top: 1rem; color: #666; line-height: 1.6;">
                    Мы принимаем детей с 3-х лет в группы детского балета. Для взрослых ограничений по возрасту нет. 
                    У нас занимаются люди от 16 до 70 лет!
                </p>
            </details>

            <details style="margin-bottom: 1.5rem; background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <summary style="font-weight: 600; color: var(--blue-dark); cursor: pointer; font-size: 1.1rem;">
                    Что нужно для первого занятия?
                </summary>
                <p style="margin-top: 1rem; color: #666; line-height: 1.6;">
                    Для первого пробного занятия достаточно удобной спортивной одежды и носочков. 
                    После записи в группу мы предоставим список необходимой танцевальной формы.
                </p>
            </details>

            <details style="margin-bottom: 1.5rem; background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <summary style="font-weight: 600; color: var(--blue-dark); cursor: pointer; font-size: 1.1rem;">
                    Как часто проходят занятия?
                </summary>
                <p style="margin-top: 1rem; color: #666; line-height: 1.6;">
                    Групповые занятия проходят 2 раза в неделю по 55 минут. Индивидуальные занятия 
                    можно проводить с любой периодичностью по договоренности с педагогом.
                </p>
            </details>

            <details style="margin-bottom: 1.5rem; background: white; padding: 1.5rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <summary style="font-weight: 600; color: var(--blue-dark); cursor: pointer; font-size: 1.1rem;">
                    Есть ли возможность участвовать в концертах?
                </summary>
                <p style="margin-top: 1rem; color: #666; line-height: 1.6;">
                    Да, все ученики принимают участие в отчетных концертах, а наиболее способные - 
                    в профессиональных спектаклях на больших сценах Москвы. Мы регулярно организуем выступления.
                </p>
            </details>
        </div>
    </div>
</section>

<script>
// Маска для телефона
document.getElementById('phone').addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D+/g, "");
    let formattedInputValue = value.substring(0, 11);
    let formattedValue = '';
    
    if (formattedInputValue.length >= 1) {
        formattedValue += '+7 (' + formattedInputValue.substring(1, 4);
        if (formattedInputValue.length >= 4) {
            formattedValue += ') ' + formattedInputValue.substring(4, 7);
            if (formattedInputValue.length >= 7) {
                formattedValue += '-' + formattedInputValue.substring(7, 9);
                if (formattedInputValue.length >= 9) {
                    formattedValue += '-' + formattedInputValue.substring(9, 11);
                }
            }
        }
    }
    
    e.target.value = formattedValue;
});

// Валидация формы с проверкой согласия
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    const privacyConsent = document.getElementById('privacy_consent');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!privacyConsent.checked) {
        e.preventDefault();
        alert('Необходимо дать согласие на обработку персональных данных для отправки заявки.');
        privacyConsent.focus();
        return false;
    }
});

// Динамическое изменение состояния кнопки
document.addEventListener('DOMContentLoaded', function() {
    const privacyConsent = document.getElementById('privacy_consent');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateSubmitButton() {
        if (privacyConsent.checked) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
        }
    }
    
    // Изначально кнопка отключена
    updateSubmitButton();
    
    privacyConsent.addEventListener('change', updateSubmitButton);
});
</script>

<?php
// Включаем подвал
require_once 'includes/footer.php';
?>