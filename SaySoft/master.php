<?php

// serce systemu

require_once __DIR__ . '/dbconn.php';

$modelDir = __DIR__ . '/../model';
if (is_dir($modelDir)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modelDir));
    foreach ($it as $file) {
        /** @var SplFileInfo $file */
        if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
            require_once $file->getPathname();
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SaySoft {
    private $haslo = 'woltyżerka69';
    //klasa jest statyczna - nie trzeba towrzyć obiektu, żeby jej użyć; publicznba - można używać wszędzie
    public static function SetSess($keyArray, $val, &$sess = NULL){
        if ($sess === NULL){
            $sess = &$_SESSION;
        }

        if(count($keyArray)>1){
            if(!isset($sess[$keyArray[0]]))
                $sess[$keyArray[0]] = array();

            self::SetSess(array_slice($keyArray, 1), $val, $sess[$keyArray[0]]);
        }else {
            $sess[$keyArray[0]] = $val;
        }
    }

    public static function GetSess($keyArray, $nullvalue = "", $sess = NULL)
    {
        if ($sess === NULL) {
            $sess = $_SESSION;
        }

        if (count($keyArray) > 1) {
            return self::GetSess(array_slice($keyArray, 1), $nullvalue, $sess[$keyArray[0]]);
        } else
            return isset($sess[$keyArray[0]]) ? $sess[$keyArray[0]] : $nullvalue;
    }


    public static function IsSetSess($keyArray, $sess = NULL)
    {
        if ($sess === NULL) {
            $sess = $_SESSION;
        }

        if (count($keyArray) > 1) {
            if (!isset($sess[$keyArray[0]]))
                return FALSE;
            return self::IsSetSess(array_slice($keyArray, 1), $sess[$keyArray[0]]);
        } else
            return isset($sess[$keyArray[0]]);
    }

    public static function UnsetSess($keyArray, &$sess = NULL)
    {
        if ($sess === NULL) {
            $sess = &$_SESSION;
        }

        if (count($keyArray) > 1) {
            if (!isset($sess[$keyArray[0]]))
                return;
            self::UnsetSess(array_slice($keyArray, 1), $sess[$keyArray[0]]);
        } else {
            unset($sess[$keyArray[0]]);
        }
    }

    public static function sendMailSimulation($email, $subject, $body){
        return "Mail do $email wysłany! Temat: $subject. Treść: $body";
    }

    public static function InitSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function requireLogin() {
        if (empty($_SESSION['userId'])) {
            header('Location: /Aplikacja-przychodnia-lekarska/sites/login/');
            exit;
        }
    }

}
