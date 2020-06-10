# Qiwi API Client

[![Software License](https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-squareGPL)](LICENSE.md)

API клиент для работы с API личного кабинета Qiwi - [Документация Qiwi](https://developer.qiwi.com/qiwiwallet/qiwicom_ru.html)<br><br>
<b>Версия Qiwi API:</b> Версия 1.4 от 15.05.2018<br>
<b>История измений библиотеки:</b> [history.md](HISTORY.md)


# Установка
* Скопируйте Qiwi.php из папки src/ и подключите его в вашем скрипте
```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');
```


# Пример отправка средств

```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');

$recipient = 79996661212; // Номер киви кошелька получателя
$sum = 10; // Сумма перевода
$comment = 'Тестовый перевод'; // По умолчанию null, т.е пустой
$currency = 643; // Код валюты. По умолчанию 643, т.е рубли

$response = $qiwi->sendMoneyToQiwi($recipient, $sum, $comment, $currency);

```

# Получение последних 50 записей из истории платежей за 30 дней

```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');

$rows = 50; 

$getHistory = $qiwi->getPaymentsHistory($rows);

```

# Получение данных по определенной транзакции

```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');

$getTxn = $qiwi->getTxn('11963463493');

```

# Приём платежа
```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');

$comment = $qiwi->genComment(); // Генерируем уникальный комментарий
$need_sum = 5;

$paymentLink = $qiwi->genPaymentLink(79996661212, $need_sum, $comment);
echo "Ссылка для оплаты: ".$paymentLink."<br>";

$payment = $qiwi->searchRefill($comment, $need_sum);

if (null != $payment)
{
	echo "Success! Платёж поступил<br>";
}
else
{
	echo "Платежа нет<br>";
}

```

# Методы

Метод | Описание
------------ | -------------
getAccount(Array $params) | Профиль пользователя
getPaymentsHistory(Array $params) | История платежей
getPaymentsStats(Array $params) | Статистика платежей
getBalance() | Баланс QIWI Кошелька
getCheck($txnId, Array $params) | Квитанция платежа
getTxn($txnId, Array $params) | Определенная транкзация по ID
getTax($providerId) | Комиссионные тарифы
sendMoneyToQiwi(int $recipient, float $sum, string $comment, int $currency) | Перевод на QIWI Кошелек
sendMoneyToProvider($providerId, Array $params) | Оплата услуг по ID получателя
sendMoneyToOther(Array $params) | Платеж по свободным реквизитам
checkValidAccount() | Возращает true, если нет лимита на исходящие платежи
searchRefill($comment, $sum=0) | Поиск платежа. Если не обнаружен, возращает null
genPaymentLink($number=null, $sum=null, $comment=null) | Генерация ссылки для пополнения. Указанные параметры изменить юзер не сможет
genComment($lengt=10) | Генерация уникального комментария


# To do
- [x] Добавить функцию для проверки, есть ли лимит на платежи 17.05.20
- [x] Добавить исключения
- [x] Функция для генерации ссылки оплаты 18.05.20


