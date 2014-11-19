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
    public static function SendSMS($message, $phone, $from = 'RESERVE24'){
        $tm = self::GetTimestamp();
        $sign = array(
            'timestamp' => $tm,
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
            'timestamp' => $tm
        );
        return self::Query('send', $params);
    }


    /**
     * @return string Возвращает текущий баланс пользователя
     */
    public static function GetBalance(){
        $tm = self::GetTimestamp();
        $sign = array(
            'timestamp' => $tm,
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => $tm
        );
        return self::Query('balance', $params);
    }


    /**
     * @return string Возвращает список баз в формате json
     */
    public static function GetDataList(){
        $tm = self::GetTimestamp();
        $sign = array(
            'timestamp' => $tm,
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => $tm
        );
        return self::Query('base', $params);
    }


    /**
     * @return string Возвращает список одобренных отправителей в формате json
     */
    public static function GetSenderList(){
        $tm = self::GetTimestamp();
        $sign = array(
            'timestamp' => $tm,
            'login'     => self::$login,
        );
        $params = Array(
            'login'     => self::$login,
            'signature' => self::GetSignature($sign),
            'timestamp' => $tm
        );
        return self::Query('senders', $params);
    }


    /**
     * @param $number string Номер телефона
     * @return string Возвращает название оператора в формате json
     */
    public static function GetOperator($number){
        $tm = self::GetTimestamp();
        $sign = array(
            'timestamp' => $tm,
            'login'     => self::$login,
            'phone'     => $number
        );
        $params = Array(
            'login'     => self::$login,
            'phone'     => $number,
            'signature' => self::GetSignature($sign),
            'timestamp' => $tm
        );
        return self::Query('operator', $params);
    }

    /**
     * @param string $name Название базы данных
     * @return mixed|string Результат в формате xml
     */
    public static function CreateBase($name = 'Main'){
        $src = '<?xml version="1.0" encoding="utf-8" ?>
                    <request>
                        <security>
                            <token value="' . self::$apikey . '" />
                        </security>
                        <bases>
                            <base name_base="' . mb_convert_encoding($name, 'Windows-1251', 'UTF-8') . '">' . mb_convert_encoding($name, 'Windows-1251', 'UTF-8') . '</base>
                        </bases>
                    </request>';
        $src = iconv("windows-1251", "utf-8", $src);
        $res = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CRLF, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $src);
        curl_setopt($ch, CURLOPT_URL, 'https://xml.sms16.ru/xml/bases.php');
        $result = curl_exec($ch);
        $res = $result;
        curl_close($ch);
        return $res;
    }


    /**
     * @param $base string Номер базы
     * @param $users mixed Ключ массива - номер телефона, значения - поля
     */
    public static function AddNumbers($base, $users){
        $src = '<?xml version="1.0" encoding="utf-8" ?>';
        $src .= '<request>';
        $src .= '<security>';
        $src .= '<token value="' . self::$apikey . '" />';
        $src .= '</security>';
        $src .= '<base id_base="'.$base.'">';
        foreach($users as $phone=>$user) {
            $src .= '<phone phone="' . $phone . '" name="' . mb_convert_encoding($user['name'], 'Windows-1251', 'UTF-8') . '" surname="' . mb_convert_encoding($user['lastname'], 'Windows-1251', 'UTF-8') . '" patronymic="' . mb_convert_encoding($user['secondname'], 'Windows-1251', 'UTF-8') . '" date_birth="' . $user['birth'] . '" male="' . $user['gender'] . '" />';
        }
        $src .= '</base>';
        $src .= '</request>';
        $src = iconv("windows-1251", "utf-8", $src);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CRLF, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $src);
        curl_setopt($ch, CURLOPT_URL, 'https://xml.sms16.ru/xml/phones.php');
        curl_exec($ch);
        curl_close($ch);
    }
}