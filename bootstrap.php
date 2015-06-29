<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

class SkyinWizard
{
    private static $url = "https://github.com/InteractiveSky/Gleb/archive/wizard.zip";

    public static function DoLoad()
    {
        $arFile = CFile::MakeFileArray(self::$url);
        $fileID = CFile::SaveFile($arFile, "/skyin/wizard/", false, false);
        return $fileID;
    }

    public static function DoUnzip($file_id)
    {
        $path = $_SERVER["DOCUMENT_ROOT"] . CFile::GetPath($file_id);
        $dest_path = $_SERVER["DOCUMENT_ROOT"];

        $zip = new ZipArchive;
        if ($zip->open($path)) {
            if ($zip->extractTo($dest_path)) {
                print "Архив распакован";
                CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/Gleb-wizard/", $_SERVER["DOCUMENT_ROOT"], true, true, true);
                self::DoUninstall($file_id);
            } else {
                self::DoUninstall($file_id);
                print "Ошибка разархивирования";
            }
            $zip->close();
        } else {
            print "Не удалось считать zip файл";
        }
        self::DoUninstall($file_id);
    }

    public static function DoUninstall($file_id)
    {
        CFile::Delete($file_id);
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"] . "/upload/skyin/");
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"] . "/Gleb-wizard/");
    }
}

$file_id = SkyinWizard::DoLoad();
if ($file_id > 0) {
    SkyinWizard::DoUnzip($file_id);
} else {
    die("Не удается скачать файл");
}