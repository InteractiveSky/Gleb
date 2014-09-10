<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $DB;

$from = date("Y-m-d", strtotime("-3 days"));

$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_basket")->Fetch();
print "<p>Всего временных корзин: <strong>".$q['c']."</strong></p>";

$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_basket WHERE DATE_INSERT=DATE_UPDATE AND DATE_UPDATE<'$from' AND ORDER_ID IS NULL")->Fetch();
print "<p>Из них удалим (они старше $from, и не стали заказом): <strong>".$q['c']."</strong></p>";
if ($DB->Query("DELETE FROM b_sale_basket WHERE DATE_INSERT=DATE_UPDATE AND DATE_UPDATE<'$from' AND ORDER_ID IS NULL")) {
    print "<p style='color: green;'>Удалено</p>";
} else {
    print "<p style='color: red;'>Какая-то ошибка. Возможно из так много, что надо запустить еще раз</p>";
}


$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_basket_props")->Fetch();
print "<p>Всего свойств временных корзин: <strong>".$q['c']."</strong></p>";

$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_basket_props WHERE BASKET_ID NOT IN (SELECT ID FROM  b_sale_basket)")->Fetch();
print "<p>Из них принадлежало удаленным корзинам: <strong>".$q['c']."</strong></p>";
if ($DB->Query("DELETE FROM b_sale_basket_props WHERE BASKET_ID NOT IN (SELECT ID FROM  b_sale_basket)")) {
    print "<p style='color: green;'>Удалено</p>";
} else {
    print "<p style='color: red;'>Какая-то ошибка. Возможно из так много, что надо запустить еще раз</p>";
}

$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_fuser")->Fetch();
print "<p>Всего временных юзеров: <strong>".$q['c']."</strong></p>";

$q = $DB->Query("SELECT COUNT(*) as c FROM b_sale_fuser WHERE DATE_INSERT=DATE_UPDATE AND DATE_UPDATE<'$from' AND USER_ID IS NULL")->Fetch();
print "<p>Из них удалим (они старше $from, и не стали настоящим юзером): <strong>".$q['c']."</strong></p>";
if ($DB->Query("DELETE FROM b_sale_fuser WHERE DATE_INSERT=DATE_UPDATE AND DATE_UPDATE<'$from' AND USER_ID IS NULL")) {
    print "<p style='color: green;'>Удалено</p>";
} else {
    print "<p style='color: red;'>Какая-то ошибка. Возможно из так много, что надо запустить еще раз</p>";
}




