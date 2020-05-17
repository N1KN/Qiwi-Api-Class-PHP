# Qiwi API Client

[![Software License](https://img.shields.io/badge/license-GNU-brightgreen.svg?style=flat-square)](LICENSE.md)

API клиент для работы с API личного кабинета Qiwi - [Документация Qiwi](https://developer.qiwi.com/qiwiwallet/qiwicom_ru.html)<br><br>
<b>Версия Qiwi API:</b> Версия 1.4 от 15.05.2018<br>


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

$getHistory = $qiwi->getPaymentsHistory([
	'startDate' => '2018-03-01T00:00:00+03:00',
	'endDate' => '2018-03-01T00:00:00+03:00',
	'rows' => '50'
]);

```

# Получение данных по определенной транзакции

```php
require_once(__DIR__.'/Qiwi.php');
$qiwi = new Qiwi(79996661212, 'a9760264ca3e817264ee2340aa877');

$getTxn = $qiwi->getTxn('11963463493');

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



