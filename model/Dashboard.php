<?php
require_once __DIR__ . '/../SaySoft/dbconn.php';

class Dashboard
{

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = db_connect();
    }

    public function writeContent()
    {


        $html = '
        <a href="#" data-toggle="modal" data-target="#setVisitModal"
        class="d-none d-sm-inline-block btn btn-xl btn-success shadow-sm display-flex justify-content-center"><i
        class="fas fa-plus fa-sm text-white-50"></i> UMÓW WIZYTĘ</a>';

        $html .= $this->setVisitModal();

        return $html;
    }

    private function setVisitModal()
    {


        $form = "";
        $specialisationSelect = SaySoft::writeSelect("selectSpecialisation", "Wybierz specjalizację", $this->getSpecs(), " ", "mt-2", ['onchange' => 'Dashboard.filterDoctors()']);
        $doctorSelect = SaySoft::writeSelect("selectDoctor", "Wybierz lekarza", ["1" => "twoja stara", "2" => "twoj stary"], " ", "mt-2 mb-2");
        $notesArea = SaySoft::writeTextArea("notes", "Dodatkowe uwagi", "Np. objawy, preferencje godziny", "mt-2 mb-2", 3);
        $datePicker = SaySoft::writeDatePicker("visitDate", "Wybierz datę wizyty", "mt-2 mb-2", ["min" => date("Y-m-d"), "max" => date("Y-m-d", strtotime("+1 year")), "disabled" => false]);

        $userId = $_SESSION['userId'];

        $form =  $specialisationSelect . $doctorSelect . $datePicker . $notesArea ;

        return SaySoft::modalComponent(
            "setVisitModal", // id - to musi być w buttonie który wywołje tego modaala
            "Umów wizytę", // tytuł modala
            "Umów!",  // tekst przycisku potwierdzenia
            "Dashboard.setVisit()", // link przycisku (albo akcja JS)
            $form // tutaj wchodzi HTML selecta
        );
    }

    private function getSpecs()
    {
        $stmt = $this->pdo->query("SELECT id, name FROM tbspecialisation ORDER BY name");
        $specs = $stmt->fetchAll();

        $options = array_column($specs, 'name', 'id');

        return $options;
    }

    public function getDoctors()
    {
        $stmt = $this->pdo->query("SELECT * FROM vwDoctorsBySpec");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $doctors = [];
        foreach ($rows as $row) {
            $doctors[$row['specid']][] = [
                'id' => $row['doctor_id'],
                'name' => $row['doctor_name']
            ];
        }

        return $doctors;
    }

    public function setVisit($spec, $docId, $visitdate, $visitDesc, $userId)
    {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($spec) || empty($docId)) {
            echo json_encode(["code" => 0, "txt" => "Wybierz specjalizację i lekarza!"]);
            return;
        }

        if (empty($visitdate)) {
            echo json_encode(["code" => 0, "txt" => "Wybierz preferowaną datę wizyty!"]);
            return;
        }

        $today = new DateTime('today');
        $timestamp = strtotime($visitdate);
        $visitDateTime = $timestamp ? (new DateTime())->setTimestamp($timestamp) : false;



        if (!$visitDateTime || $visitDateTime < $today) {
            echo json_encode(["code" => 0, "txt" => "Nieprawidłowa data. Data wizyty nie może być z przeszłości ani w niepoprawnym formacie."]);
            return;
        }

        $formattedVisitDate = $visitDateTime->format('Y-m-d H:i:s');

        if (empty($visitDesc) || strlen($visitDesc) < 10) {
            echo json_encode(["code" => 0, "txt" => "Uzupełnij dokładnie opis! Opis musi mieć co najmniej 10 znaków. Jeżeli wybrany lekarz stwierdzi, że specjalizacja jest niezgodna z opisem, skieruje Cię do innego lekarza."]);
            return;
        }

        if (empty($userId)) {
            echo json_encode(["code" => 0, "txt" => "Błąd sesji. Użytkownik niezalogowany."]);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT setVisit(:spec, :docId, :vDate, :vDesc, :userId) AS status");
            $stmt->bindParam(':spec', $spec, PDO::PARAM_INT);
            $stmt->bindParam(':docId', $docId, PDO::PARAM_INT);
            $stmt->bindParam(':vDate', $formattedVisitDate, PDO::PARAM_STR);
            $stmt->bindParam(':vDesc', $visitDesc, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

            $stmt->execute();
            $status = $stmt->fetchColumn();

            $message = '';
            $code = 0;

            switch ($status) {
                case 'SUCCESS':
                    $message = "Wizyta zarejestrowana.";
                    $code = 1;
                    break;
                case 'WAITING':
                    $message = "Masz już oczekującą wizytę u tego lekarza.";
                    break;
                case 'OVER5':
                    $message = "Przekroczono limit wizyt dziennie (max 5).";
                    break;
                case 'BUSY':
                    $message = "Lekarz jest zajęty w tym dniu (limit 7h przekroczony).";
                    break;
                case 'NO_PATIENT':
                    $message = "Błąd: nie znaleziono Twojego konta pacjenta.";
                    break;
                default:
                    $message = "Wystąpił nieznany błąd podczas rezerwacji.";
            }

            echo json_encode(["code" => $code, "txt" => $message]);
        } catch (PDOException $e) {
            echo json_encode(["code" => 0, "txt" => "Błąd bazy danych (PDO): " . $e->getMessage()]);
        }
    }

public function getUserVisits($userId) {
    // to powinna być funkcja w bazie ale wspaniały mysql nie obsługuje jsonów, więc - jebać <3
    $stmt = $this->pdo->prepare("
        SELECT 
            v.id,
            DATE_FORMAT(v.visitDate, '%Y-%m-%d %H:%i:%s') AS visitDate,
            CONCAT('dr ', d.name, ' ', d.surname) AS doctor,
            s.name AS spec,
            IFNULL(v.visitDesc, '') AS visitDesc,
            IFNULL(v.status, '') AS status
        FROM tbvisits v
        JOIN tbdoctors d ON v.tbDoctors_id = d.id
        JOIN tbspecialisation s ON v.specid = s.id
        JOIN tbpatients p ON v.tbPatients_id = p.id
        WHERE p.tbusers_id = :uid
        ORDER BY v.visitDate DESC
    ");
    $stmt->execute(['uid' => $userId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //otrzymujemy tablice asocjacyjnąz indeksami 'opisowymi' w stylu 'id' => 1, więc musimy konwertować na indeksy liczbowe
    //bo datatable.js korzysta z liczbowych
    $formattedRows = [];

    foreach ($rows as $row) {
        // oczekwiane statusy to WAITING, APPROVED, DONE, CANCELLED
        if($row['status'] == 'WAITING') $row['status'] = 'oczekujący na akceptację lekarza';
        if($row['status'] == 'APPROVED') $row['status'] = 'zaakceptowana';
        if($row['status'] == 'DONE') $row['status'] = 'ukończona';
        if($row['status'] == 'CANCELLED') $row['status'] = 'odwołana';

        $formattedRows[] = [
            $row['id'], // ID ukryte
            $row['visitDate'], // Data
            $row['doctor'], // Lekarz
            $row['spec'], // Specjalizacja
            $row['visitDesc'], // Opis
            $row['status'], // Status
            '<button 
                class="btn btn-danger btn-circle" 
                data-toggle="modal" 
                data-target="#cancelVisitModal_' . $row['id'] . '"
                onclick="Dashboard.prepareCancel(' . $row['id'] . ')">
                <i class="fas fa-trash"></i>
            </button>'
        ];

        SaySoft::modalComponent(
            "cancelVisitModal_" . $row['id'],
            "Czy odwołać wizytę?",
            "Odwołaj wizytę",
            "Dashboard.confirmCancel()",
            "Cofnięcie tej akcji będzie niemożliwe."
        );

    }

    return $formattedRows;
}

public function cancelVisit($visitId) {
    $sql = "UPDATE tbvisits SET status = 'CANCELLED' WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(['id' => $visitId]);
}



}
