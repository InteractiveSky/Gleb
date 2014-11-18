<?php

/**
 * Class CSms
 * Класс для работы с СМС сервисом sms16.ru
 */

class CSms {
    private static $login;
    private static $apikey;

    /**
     * @param $login string логин на сайте sms16.ru
     * @param $apikey string API ключ
     */
    function __construct($login, $apikey){
        self::$login = $login;
        self::$apikey = $apikey;
    }

    /**
     * @param $func string Функция API
     * @param $params array Параметры запроса
     * @return string Выводит результат в формате json
     */
    public static function Query($func, $params){
        return file_get_contents('https://new.sms16.ru/get/' . $func . '.php?' . http_build_query($params, '', '&'));
    }

    /**
     * @return string Возвращает текущий timestamp
     */
    public static function GetTimestamp(){
        return file_get_contents('https://new.sms16.ru/get/timestamp.php');
    }

    /**
     * @param $params array В массив передаются все параметры запроса
     * @return string Возвращает подпись
     */
    public static function GetSignature($params){
        ksort($params);
        reset($params);
        return md5(implode($params) . self::$apikey);
    }


    /**
     * @param $message string Сообщение для отправки
     * @param $phone string Телефон на который отправляем
     * @param string $from От кого (подпись должна быть подтверждена на сайте)
     * @return string Возвращает результат в формате json
     */
    public static function SendSMS($message, $phone, $from = 'mytestsms'){
        $sign = array(
            'timestamp' => self::GetTimestamp(),
            'login'     => self::$login,
            'phone'     => $phone,
            'text'      => $message,
            'sender'    => $from
        );
        $params = Array(
            "login"     => self::$login,
            'signature' => self::GetSignature($sign),
            'phone'     => $phone,
            'text'      => $message,
            'sender'    => $from,
            'timestamp' => self::GetTimestamp()
        );
        return self::Query('send', $params);
    }


    /**
     * @return string Возвращает текущий баланс пользователя
     */
    public static function GetBalance(){
        $sign = array(
            'timestamp' => self::GetTimestamp(),
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => self::GetTimestamp()
        );
        return self::Query('balance', $params);
    }


    /**
     * @return string Возвращает список баз в формате json
     */
    public static function GetDataList(){
        $sign = array(
            'timestamp' => self::GetTimestamp(),
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => self::GetTimestamp()
        );
        return self::Query('base', $params);
    }


    /**
     * @return string Возвращает список одобренных отправителей в формате json
     */
    public static function GetSenderList(){
        $sign = array(
            'timestamp' => self::GetTimestamp(),
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => self::GetTimestamp()
        );
        return self::Query('senders', $params);
    }


    /**
     * @param $number string Номер телефона
     * @return string Возвращает название оператора в формате json
     */
    public static function GetOperator($number){
        $sign = array(
            'timestamp' => self::GetTimestamp(),
            'login'     => self::$login,
            'phone'     => $number
        );
        $params = Array(
            'login'     => self::$login,
            'phone'     => $number,
            'signature' => self::GetSignature($sign),
            'timestamp' => self::GetTimestamp()
        );
        return self::Query('operator', $params);
    }
}