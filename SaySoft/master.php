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

    public static function modalComponent($id, $title, $confirmText, $confirmAction, $content) {
        echo <<<HTML
        <div class="modal fade" id="$id" tabindex="-1" role="dialog" aria-labelledby="modalLabel-$id" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel-$id">$title</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        $content
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Zamknij</button>
                        <a class="btn btn-primary" href="$confirmAction">$confirmText</a>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

public static function writeSelect($id = "", $labelText = "", $values = [],  $selected = "", $class = "", $disabled = false) {
    $disableAttr = $disabled ? "disabled" : "";
    $classAttr = $class ? $class : "";

    $html = '<div class="form-group $classAttr">';
    $html .= "<label for='$id'>$labelText</label>";
    $html .= "<select id='$id' name='$id' class='form-control' $disableAttr>";
    $html .= "<option selected disabled>$selected</option>";

    foreach($values as $optionId => $optionLabel) {
        $html .= "<option value='$optionId'>$optionLabel</option>";
    }

    $html .= "</select></div>";

    return $html;
}

// Textarea
public static function writeTextArea($id = "", $labelText = "", $placeholder = "", $class = "", $rows = 3, $disabled = false) {
    $disableAttr = $disabled ? "disabled" : "";
    $classAttr = $class ? $class : "";

    $html = '<div class="form-group ' . $classAttr . '">';
    $html .= "<label for='$id'>$labelText</label>";
    $html .= "<textarea id='$id' name='$id' class='form-control' rows='$rows' placeholder='$placeholder' $disableAttr></textarea>";
    $html .= "</div>";

    return $html;
}

// Date Picker
public static function writeDatePicker($id = "", $labelText = "", $class = "", $disabled = false, $min = "", $max = "") {
    $disableAttr = $disabled ? "disabled" : "";
    $classAttr = $class ? $class : "";

    $html = '<div class="form-group ' . $classAttr . '">';
    $html .= "<label for='$id'>$labelText</label>";
    $html .= "<input type='date' id='$id' name='$id' class='form-control' $disableAttr";

    if ($min) $html .= " min='$min'";
    if ($max) $html .= " max='$max'";

    $html .= ">";
    $html .= "</div>";

    return $html;
}




}
