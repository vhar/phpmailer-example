<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Используем библиотеку https://github.com/vlucas/phpdotenv для работы с переменными окружения
 */
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$success  = false;
$message  = [];

/**
 * Сохраняем данные формы в переменную $formData
 */
$formData = $_POST['formdata'] ?? [];

/**
 * Создаем экземпляр класса
 */
$mail = new PHPMailer(true);

/**
 * Определяем для экземпляра работу с SMTP
 */
$mail->isSMTP();

/**
 * Устанавливаем необходимые данне для подклбючения по SMTP
 * 
 * Host - адрес SMTP сервера
 * SMTPAuth - использовать авторизацию для отправки почты
 * Username - логин пользователя на сервере
 * Password - пароль пользователя
 * SMTPSecure - тип защищенного соединения (ssl, tls). Если не используется, то ''
 * Port - порт сервера для подключения по SMTP. Как правило 25, 465 или 587
 * CharSet - кодировка письма
 */
$mail->Host       = $_ENV['SMTP_HOST'];
$mail->SMTPAuth   = true;
$mail->Username   = $_ENV['SMTP_LOGIN'];
$mail->Password   = $_ENV['SMTP_PASSWORD'];
$mail->SMTPSecure = $_ENV['ENCRYPTION_STARTTLS'] ?? '';
$mail->Port       = $_ENV['SMTP_PORT'] ?? 25;
$mail->CharSet    = "UTF-8";

/**
 * Почтовый адрес получателя письма. На этот адрес будет отправлено содержимое формы
 */
$mail->addAddress($_ENV['MAIL_RECIPIENT']);
/**
 * Адрес для ответа на письмо. Можно указать адрес отправителя формы. Вторым параметром передается имя получателя ответа.
 */
$mail->addReplyTo($formData['email'], htmlentities($formData['fullname']) ?? '');

/**
 * От имени кого отправляется письмо
 * From - email адрес. Лучше, если он будет совпадать с Username или, как минимум, из того же домена.
 * FromName - Имя отправителя.
 */
$mail->From     = $_ENV['MAIL_FROM'];
$mail->FromName = $_ENV['MAIL_FROM_NAME'];

/**
 * Собираем в содержимое письма значение из всех полей формы, разделяя их тегом <br>
 */
$mailBody = nl2br(implode("\n", $formData));

/**
 * Определяем, что сообщение будет в HTML формате
 */
$mail->isHTML(true);
/**
 * Устанавливаем тему письма
 */
$mail->Subject  = $_ENV['MAIL_SUBJECT'] ?? 'PHPMailer Example';
/**
 * Устанавливаем содержимое письма
 * Body - в HTML формате
 * AltBody - содержимое в текстовом фомате. Просто вырезаем теги из HTML
 */
$mail->Body     = '<html><head></head><body>' . addslashes($mailBody). '</body></head>';
$mail->AltBody  = strip_tags($mailBody);

/**
 * Отправляем письмо
 * В случае возникновения исключения, выводим сообщение об ошибке и информацию о самой ошибке.
 */
try {
    $mail->send();
    $message [] = 'Ваше сообщение успешно отправлено';
    $success = true;
} catch (Exception $e) {
    $message[] = 'При отправке сообщения возникли сложности';
    $message[] = $mail->ErrorInfo;
}

/**
 * Возвращаем JSON ответ в JS
 */
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['success' => $success, 'message' => $message]);
exit;
