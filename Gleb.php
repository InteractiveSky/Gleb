<?

class Gleb
{


    private $yandex_cleanweb_api_key;

    /**
     * Создает массив из XML объекта
     * @param mixed $xmlObject XML объект - результат функции simplexml_load_string(), например
     * @return array
     */
    public static function XmlToArray($xmlObject)
    {
        $out = array();
        foreach ((array)$xmlObject as $index => $node) {
            if (empty($node) and (is_object($node) || is_array($node))) {
                $node = '';
            }
            if (is_object($node) || is_array($node)) {
                $out[$index] = SELF::xmlToArray($node);
            } else {
                $out[$index] = $node;
            }
        }
        return $out;
    }

    /**
     * Выводит что-л. на экран или возвращает строку с этим чем-л.
     * @param mixed $object Массив, строка или что там еще вы хотите показать себе на экране во время разработки
     * @param bool $for_all Показываем только админам по умолчанию. можем показывать всем подраяд, в том числе навторизованым пользовтелям
     * @param bool $return Возвращем результат или выводим его на экран
     * @return string
     */
    public static function Message($object, $for_all = false, $return = true)
    {
        $text = '<pre>' . print_r($object, true) . '</pre>';
        if ($for_all) {
            if ($return == false)
                return $text;
            echo $text;
        } else {
            global $USER;
            if ($USER->IsAdmin()) {
                if ($return == false)
                    return $text;
                echo $text;
            }
        }
    }


    /**
     * Возвращает тип файла (строковый код, например xls, pdf, doc). Бывает необходимо когда надо задать класс иконки, например .icon--pdf
     * @param $file_array array Принимает массив - результат битриксовой функции CFile::GetFileArray($file_id)
     * @return string Тип файла
     */
    public static function GetFileType($file_array)
    {
        $type = false;
        switch ($file_array['CONTENT_TYPE']) {
            case 'application/pdf':
                $type = 'pdf';
                break;
            case 'application/x-zip-compressed':
            case 'application/zip':
                $type = 'zip';
                break;
            case 'image/jpeg':
                $type = 'jpeg';
                break;
            case 'application/octet-stream':
                if (preg_match('/\.(doc|docx)$/', $file_array['FILE_NAME'])) {
                    $type = 'doc';

                }
                if (preg_match('/\.(xls|xlsx)$/', $file_array['FILE_NAME'])) {
                    $type = 'xls';

                }
                break;
            default:
                $type = "doc";
        }
        return $type;
    }

    /**
     * Склонение
     * @static
     * @param $n int Количество
     * @param $forms array Формы (1, 2, 5)
     *
     * @return string Нужная форма слова
     */
    public static function Sklon($n, $forms)
    {
        return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
    }

    /**
     * Письмо с правильными заголовками через mail()
     * @static
     * @param $to string Email получателя
     * @param $subject string Тема письма
     * @param $text string тело письма
     * @param $from mixed Email отправителя
     *
     * @return boolean Результат отправки письма
     */
    public static function Pismo($to, $subject, $text, $from)
    {
        $headers = "MIME-Version: 1.0" . "\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\n";
        $headers .= 'From: ' . $from . "\n";
        $headers .= 'Reply-To: ' . $from . "\n";
        $headers .= 'Return-Path: ' . $from . "\n";
        return mail($to, $subject, $text, $headers);
    }

    public function SetCleanwebAPIKey($key)
    {
        $this->yandex_cleanweb_api_key = $key;
    }

    /**
     * Проверка формы на спам
     * @static
     * @param $value mixed Массив или строка для проверки на спам
     *
     * @return boolean Спам (true) или нет (false)
     */
    public function IsSpam($value)
    {
        if ($this->yandex_cleanweb_api_key) {
            $url_api = 'http://cleanweb-api.yandex.ru/1.0/';

            if (is_array($value)) {
                $value = implode(", ", $value);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL, $url_api . 'check-spam');
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'key=' . urlencode($this->yandex_cleanweb_api_key) . '&body-plain=' . urlencode($value));
            $response = new SimpleXMLElement(curl_exec($ch));
            curl_close($ch);
            return ($response->text['spam-flag'] == 'yes');
        } else {
            print "Не задан API ключ. Используйте SetCleanwebAPIKey. Получить можно тут – <a href='http://api.yandex.ru/key/form.xml?service=cw'>http://api.yandex.ru/key/form.xml?service=cw</a>";
            return false;
        }
    }

    /**
     * Проверка битрикс-формы на спам
     * @static
     * @param $values array Массив со значениями результата формы
     *
     * @return boolean Спам (true) или нет (false)
     */
    public function IsBitrixFormValuesSpam($values)
    {
        $fields_to_check = Array();
        foreach ($values as $id => $value) {
            if ((strpos($id, "form_") === 0) && $value) {
                $fields_to_check[] = $value;
            }
        }
        $string_to_check = implode(", ", $fields_to_check);

        return $this->IsSpam($string_to_check);
    }

    /**
     * Проверка, вызвана ли страница AJAX'ом
     * @static
     *
     * @return boolean true если ajax
     */
    public static function IsAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    /**
     * Получение информации о регионе по IP
     * @static
     * @param $ip string IP адрес (обычно $_SERVER['REMOTE_ADDR'])
     *
     * @return array Массив с информацией о регионе
     */
    public static function GetIPInfo($ip)
    {
        $xml = simplexml_load_string(file_get_contents("http://ipgeobase.ru:7020/geo?ip=" . $ip));
        $result = (array)$xml->ip;
        return $result;
    }

    /**
     * Переадресация с кодом 301
     * @static
     * @param $url string Адрес для переадресации
     */
    public static function Redirect301($url)
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location:' . $url);
    }

    /**
     * Форматирование числа в красивый вид
     * @static
     * @param $number string Число для преобразования
     * @param $decimals int (optional) Число для преобразования
     *
     * @return string Форматированная строка
     */
    public static function NumFormat($number, $decimals = 0)
    {
        $thousands_sep = '&nbsp;';
        if (phpversion() < '5.4') {
            $thousands_sep = ' ';
        }
        return number_format($number, $decimals, '.', $thousands_sep);
    }

}