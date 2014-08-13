<?php

require_once 'Gleb.php';

/*
Отправляем пользователю логин/пароль при любой регистрации (и настроящей, и скриптом, и при заказе)
Для того, чтобы отправлялось это уведомление нужно:
1. деактивировать шаблон по событию "USER_INFO"
2. создать почтовое событие SKYIN_NEW_USER
3. шаблон для него примерно следующего характера:
	Здравствуйте!

	Для Вас был создан аккаунт:

	Логин: #LOGIN#
	Пароль: #PASSWORD#
*/
AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserSimpleRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler(&$arFields)
{
    if (intval($arFields["ID"])>0)
    {
        $toSend = Array();
        $toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["EMAIL"] = $arFields["EMAIL"];
        $toSend["USER_ID"] = $arFields["ID"];
        $toSend["USER_IP"] = $arFields["USER_IP"];
        $toSend["USER_HOST"] = $arFields["USER_HOST"];
        $toSend["LOGIN"] = $arFields["LOGIN"];
        $toSend["NAME"] = trim($arFields["NAME"]);
        $toSend["LAST_NAME"] = trim ($arFields["LAST_NAME"]);
        CEvent::SendImmediate("SKYIN_NEW_USER", SITE_ID, $toSend);
    }
    return $arFields;
}