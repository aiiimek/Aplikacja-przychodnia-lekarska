<?php
require_once __DIR__ . '/../SaySoft/dbconn.php';

class Dashboard {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = db_connect();
    }

    public function writeContent() {


        $html = '
        <a href="#" data-toggle="modal" data-target="#setVisitModal"
        class="d-none d-sm-inline-block btn btn-xl btn-success shadow-sm display-flex justify-content-center"><i
        class="fas fa-plus fa-sm text-white-50"></i> UMÓW WIZYTĘ</a>';

        $html .= $this->setVisitModal();
        
        return $html;
    }

    private function setVisitModal(){
        

        $form = "";
        $specialisationSelect = SaySoft::writeSelect("selectSpecialisation", "Wybierz specjalizację", $this->getSpecs(), " ","mt-2", ['onchange'=>'Dashboard.filterDoctors()']);
        $doctorSelect = SaySoft::writeSelect("selectDoctor", "Wybierz lekarza", ["1"=>"twoja stara", "2"=>"twoj stary"], " ","mt-2 mb-2");
        $notesArea = SaySoft::writeTextArea("notes","Dodatkowe uwagi","Np. objawy, preferencje godziny","mt-2 mb-2",3);
        $datePicker = SaySoft::writeDatePicker("visitDate", "Wybierz datę wizyty", "mt-2 mb-2", ["min"=>date("Y-m-d"), "max"=>date("Y-m-d", strtotime("+1 year")), "disabled"=>false]);


        $form =  $specialisationSelect . $doctorSelect . $notesArea . $datePicker;

        return SaySoft::modalComponent(
            "setVisitModal",// id - to musi być w buttonie który wywołje tego modaala
            "Umów wizytę",// tytuł modala
            "Umów!",  // tekst przycisku potwierdzenia
            "#",// link przycisku (albo akcja JS)
            $form// tutaj wchodzi HTML selecta
        );
    }

    private function getSpecs(){
        $stmt = $this->pdo->query("SELECT id, name FROM tbspecialisation ORDER BY name");
        $specs = $stmt->fetchAll();

        $options = array_column($specs, 'name', 'id');

        return $options;
    }
    
}
