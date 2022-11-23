## Пример использования библиотеки PHPMailer ##
1. Склонируйте репозиторий в корень сайта
2. Создайте в корне сайта файл .env
3. Добавьте в файл .env следующие переменные с установленными вашими данными
```
SMTP_HOST=имя или IP адрес почтового сервера
SMTP_LOGIN=ваш логин от почты
SMTP_PASSWORD=ваш пароль от почты
ENCRYPTION_STARTTLS=тип защищенного соединения (tls, ssl) или удалите эту переменную, если используется незащищенное соединение
SMTP_PORT=порт почтового сервера для подключения
MAIL_FROM=почтовый адрес "От кого". (Желательно, чтобы сопадал с вашим почтовым ящиком логин от которого используется для отправки почты
MAIL_FROM_NAME=Имя "От кого"
MAIL_RECIPIENT=получатель почты. На этот адрес будет отправлятся почта из формы.
MAIL_SUBJECT=Тема письма
```
4. Пример файла .env
```
SMTP_HOST=smtp.yandex.ru
SMTP_LOGIN=mymail@ya.ru
SMTP_PASSWORD=MySuperSecretWord
ENCRYPTION_STARTTLS=ssl
SMTP_PORT=465
MAIL_FROM=mymail@ya.ru
MAIL_FROM_NAME='Форма обратной сязи с моего сайта'
MAIL_RECIPIENT=myofficemail@mail.ru
MAIL_SUBJECT='Сообщение с сайта'
```
5. Для отправки данных зайдите по адресу http://ваш_сайт/contactform.html
