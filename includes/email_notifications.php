<?php
// ========== includes/email_notifications.php - Email уведомления ==========

/**
 * Отправка email уведомления о новой заявке
 * @param array $applicationData - данные заявки
 * @return bool - успех отправки
 */
function sendApplicationEmail($applicationData) {
    // Email для отправки уведомлений
    $to = 'Stolitsa-dance@yandex.ru';
    $from = 'noreply@stolitsa-dance.ru';
    $subject = '🎭 Новая заявка на сайте Театр танца "Столица"';
    
    // Формируем HTML письмо
    $message = buildEmailMessage($applicationData);
    
    // Заголовки
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Отправляем письмо
    try {
        $sent = mail($to, $subject, $message, implode("\r\n", $headers));
        
        // Логируем результат
        if ($sent) {
            error_log("✅ Email notification sent successfully for application: " . $applicationData['name'] . " (" . $applicationData['phone'] . ")");
        } else {
            error_log("❌ Failed to send email notification for application: " . $applicationData['name'] . " (" . $applicationData['phone'] . ")");
        }
        
        return $sent;
        
    } catch (Exception $e) {
        error_log("Email notification error: " . $e->getMessage());
        return false;
    }
}

/**
 * Формирование HTML содержимого письма
 * @param array $data - данные заявки
 * @return string - HTML письма
 */
function buildEmailMessage($data) {
    // Переводим коды направлений в читаемые названия
    $classTypes = [
        'classical' => 'Классический танец',
        'folk' => 'Народный танец',
        'jazz-modern' => 'Джаз-модерн',
        'baby-ballet' => 'Детский балет',
        'gymnastics' => 'Партерная гимнастика',
        'acrobatics' => 'Акробатика'
    ];
    
    // Переводим коды филиалов в названия
    $branches = [
        'lomonosovsky' => 'м. Ломоносовский проспект',
        'belorusskaya' => 'м. Белорусская',
        '1905' => 'м. 1905 года'
    ];
    
    $classType = isset($classTypes[$data['class_type']]) ? $classTypes[$data['class_type']] : ($data['class_type'] ?: 'Не указано');
    $branch = isset($branches[$data['branch']]) ? $branches[$data['branch']] : ($data['branch'] ?: 'Не указан');
    
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Новая заявка - Театр танца Столица</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
            .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 30px; text-align: center; }
            .header h1 { margin: 0; font-size: 32px; font-weight: 700; }
            .header p { margin: 15px 0 0 0; opacity: 0.9; font-size: 18px; }
            .content { padding: 40px 30px; }
            .field { margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 12px; border-left: 5px solid #667eea; }
            .field-label { font-weight: 700; color: #333; margin-bottom: 8px; display: block; font-size: 16px; }
            .field-value { color: #555; font-size: 16px; line-height: 1.5; }
            .important { background: linear-gradient(135deg, #fff3cd, #ffeaa7); border-left-color: #f39c12; }
            .contact-info { background: linear-gradient(135deg, #e7f3ff, #cce7ff); border-left-color: #0066cc; }
            .footer { background: #f8f9fa; padding: 30px; text-align: center; color: #666; }
            .footer h3 { color: #333; margin-bottom: 20px; font-size: 20px; }
            .footer p { margin-bottom: 15px; line-height: 1.6; }
            .footer a { color: #667eea; text-decoration: none; font-weight: 600; }
            .footer a:hover { text-decoration: underline; }
            .date-time { background: linear-gradient(135deg, #e7f3ff, #cce7ff); border-left-color: #0066cc; }
            .divider { height: 2px; background: linear-gradient(90deg, #667eea, #764ba2); margin: 30px 0; border-radius: 1px; }
            .highlight { background: linear-gradient(135deg, #d4edda, #c3e6cb); border-left-color: #28a745; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🎭 Новая заявка!</h1>
                <p>Театр танца "Столица"</p>
            </div>
            
            <div class="content">
                <div class="field important">
                    <span class="field-label">👤 Имя клиента:</span>
                    <div class="field-value"><strong>' . htmlspecialchars($data['name']) . '</strong></div>
                </div>
                
                <div class="field important">
                    <span class="field-label">📞 Телефон для связи:</span>
                    <div class="field-value"><strong><a href="tel:' . htmlspecialchars($data['phone']) . '" style="color: #e74c3c; text-decoration: none;">' . htmlspecialchars($data['phone']) . '</a></strong></div>
                </div>';
                
    if (!empty($data['email'])) {
        $html .= '
                <div class="field contact-info">
                    <span class="field-label">📧 Email:</span>
                    <div class="field-value"><a href="mailto:' . htmlspecialchars($data['email']) . '" style="color: #667eea;">' . htmlspecialchars($data['email']) . '</a></div>
                </div>';
    }
    
    $html .= '<div class="divider"></div>';
    
    if (!empty($data['class_type'])) {
        $html .= '
                <div class="field highlight">
                    <span class="field-label">💃 Направление танца:</span>
                    <div class="field-value"><strong>' . htmlspecialchars($classType) . '</strong></div>
                </div>';
    }
    
    if (!empty($data['branch'])) {
        $html .= '
                <div class="field">
                    <span class="field-label">📍 Предпочитаемый филиал:</span>
                    <div class="field-value">' . htmlspecialchars($branch) . '</div>
                </div>';
    }
    
    if (!empty($data['age'])) {
        $html .= '
                <div class="field">
                    <span class="field-label">🎂 Возраст ученика:</span>
                    <div class="field-value">' . htmlspecialchars($data['age']) . '</div>
                </div>';
    }
    
    if (!empty($data['message'])) {
        $html .= '
                <div class="field">
                    <span class="field-label">💬 Дополнительная информация:</span>
                    <div class="field-value">' . nl2br(htmlspecialchars($data['message'])) . '</div>
                </div>';
    }
    
    $html .= '
                <div class="field date-time">
                    <span class="field-label">🕒 Дата и время подачи заявки:</span>
                    <div class="field-value"><strong>' . date('d.m.Y в H:i') . '</strong></div>
                </div>
            </div>
            
            <div class="footer">
                <h3>📋 Что делать дальше:</h3>
                <p><strong>1.</strong> Свяжитесь с клиентом по указанному телефону</p>
                <p><strong>2.</strong> Обсудите удобное время для пробного занятия</p>
                <p><strong>3.</strong> Расскажите о выбранном направлении и расписании</p>
                <p><strong>4.</strong> Отметьте заявку как обработанную в <a href="https://stolitsa-dance.ru/admin">админ-панели</a></p>
                
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #e9ecef;">
                    <p style="font-size: 14px; color: #888;">
                        Это автоматическое уведомление с сайта<br>
                        <a href="https://stolitsa-dance.ru">stolitsa-dance.ru</a>
                    </p>
                    <p style="font-size: 14px; color: #888;">
                        <strong>Телефоны театра:</strong><br>
                        <a href="tel:+79999303660">+7 (999) 930-36-60</a> • 
                        <a href="tel:+79854117649">+7 (985) 411-76-49</a> • 
                        <a href="tel:+79163942321">+7 (916) 394-23-21</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    return $html;
}

/**
 * Простая функция для быстрого использования
 * @param string $name - имя клиента
 * @param string $phone - телефон
 * @param string $email - email (необязательно)
 * @param string $classType - направление танца
 * @param string $branch - филиал
 * @param string $age - возраст
 * @param string $message - дополнительное сообщение
 * @return bool - успех отправки
 */
function quickSendApplicationEmail($name, $phone, $email = '', $classType = '', $branch = '', $age = '', $message = '') {
    $data = [
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'class_type' => $classType,
        'branch' => $branch,
        'age' => $age,
        'message' => $message
    ];
    
    return sendApplicationEmail($data);
}

/**
 * Тестовая функция для проверки отправки
 * @return bool
 */
function testEmailNotification() {
    $testData = [
        'name' => 'Тестовая заявка',
        'phone' => '+7 (999) 123-45-67',
        'email' => 'test@example.com',
        'class_type' => 'classical',
        'branch' => 'lomonosovsky',
        'age' => '25 лет',
        'message' => 'Тестовое сообщение для проверки работы email уведомлений. Если вы получили это письмо, значит система работает корректно!'
    ];
    
    return sendApplicationEmail($testData);
}
?>