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
                        <div id="alertBox-$id"></div>
                        $content
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Zamknij</button>
                        <button class="btn btn-primary" type="button" onclick="$confirmAction">$confirmText</button>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

public static function writeSelect($id = "", $labelText = "", $values = [], $selected = "", $class = "", $options = []) {
    $classAttr = $class ? $class : "";
    $attrString = "";

    foreach ($options as $key => $value) {
        if (is_bool($value) && $value) {
            $attrString .= " $key";
        } elseif (!is_bool($value)) {
            $attrString .= " $key='$value'";
        }
    }

    $html = '<div class="form-group ' . $classAttr . '">';
    $html .= "<label for='$id'>$labelText</label>";
    $html .= "<select id='$id' name='$id' class='form-control' $attrString>";
    if ($selected) {
        $html .= "<option selected disabled>$selected</option>";
    }

    foreach ($values as $optionId => $optionLabel) {
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
public static function writeDatePicker($id = "", $labelText = "", $class = "", $options = []) {
    $classAttr = $class ? $class : "";
    $attrString = "";

    foreach ($options as $key => $value) {
        // jeśli wartość jest true i to atrybut typu boolean (disabled, required itp.)
        if (is_bool($value) && $value) {
            $attrString .= " $key";
        } elseif (!is_bool($value)) {
            $attrString .= " $key='$value'";
        }
    }

    $html = '<div class="form-group ' . $classAttr . '">';
    $html .= "<label for='$id'>$labelText</label>";
    $html .= "<input type='date' id='$id' name='$id' class='form-control' $attrString>";
    $html .= "</div>";

    return $html;
}

public static function writeDataTable($id = "", $headers = [], $rows = [], $class = "table table-bordered table-striped", $headerclass = "", $options = []) {
    $classAttr = $class ? $class : "";
    $attrString = "";

    $html = "<table id='$id' class='$classAttr' width='100%' cellspacing='0' $attrString>";

    // thead
    if (!empty($headers)) {
        $html .= "<thead class='$headerclass'><tr>";
        foreach ($headers as $idx => $header) {
            $style = "";
            if (!empty($options['hiddenColumns'])) {
                if (in_array($idx, $options['hiddenColumns']) || in_array($header, $options['hiddenColumns'])) {
                    $style = " style='display:none;'";
                }
            }
            $html .= "<th$style>$header</th>";
        }
        $html .= "</tr></thead>";
    }

    // tbody
    $html .= "<tbody>";
    foreach ($rows as $row) {

        // data-id
        $dataIdAttr = "";
        if (isset($options['dataId'])) {
            $key = $options['dataId'];
            if (isset($row[$key])) {
                $dataIdAttr = " data-id='{$row[$key]}'";
            }
        }

        $html .= "<tr$dataIdAttr>";
        foreach ($row as $idx => $cell) {

            $style = "";
            $extraClass = "";

            // ukryte kolumny
            if (!empty($options['hiddenColumns'])) {
                if (in_array($idx, $options['hiddenColumns']) || in_array($headers[$idx], $options['hiddenColumns'])) {
                    $style = " style='display:none;'";
                }
            }

            // kolorowanie konkretnej celki
            if (!empty($options['colStyle']) && isset($options['colStyle'][$idx])) {
                foreach ($options['colStyle'][$idx] as $text => $className) {
                    if ($cell == $text) {
                        $extraClass = " class='$className'";
                        break;
                    }
                }
            }

            $html .= "<td$style$extraClass>$cell</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody>";

    $html .= "</table>";
    return $html;
}




}
