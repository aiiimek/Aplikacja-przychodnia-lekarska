-- ########################################################
-- Opcjonalnie: baza danych (zmień nazwę, jeśli chcesz)
-- ########################################################
CREATE DATABASE IF NOT EXISTS lekario
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE lekario;

-- ########################################################
-- Tabela użytkowników
-- ########################################################

DROP TABLE IF EXISTS tbusers;

CREATE TABLE IF NOT EXISTS tbusers (
  id CHAR(36) NOT NULL,                 -- UUID v4
  login CHAR(64) NOT NULL UNIQUE,
  email VARBINARY(255) NOT NULL,        -- mail szyfrowany (dane poufne)
  password VARCHAR(255) NOT NULL,
  name VARCHAR(100),
  surname VARBINARY(255),
  role VARCHAR(50) NOT NULL DEFAULT 'patient', -- patient, doctor, admin
  createdt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  agreement TINYINT(1),
  confirmfdt TIMESTAMP NULL,
  status VARCHAR(50),                   -- NEW, ACTIVE, REMOVED, BLOCKED
  PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- ########################################################
-- Procedura: registerUser
-- ########################################################

DROP PROCEDURE IF EXISTS registerUser;
DELIMITER $$

CREATE PROCEDURE registerUser(
    IN _key   VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN _data  JSON,
    OUT _status VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
)
BEGIN
    DECLARE v_id       CHAR(36)    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    DECLARE v_login    CHAR(64)    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    DECLARE v_email    VARBINARY(255);
    DECLARE v_password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    DECLARE v_name     VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    DECLARE v_surname  VARBINARY(255);
    DECLARE v_agreement TINYINT(1);
    DECLARE v_count    INT;

    -- pobieranie danych z JSON
    SET v_login    = JSON_UNQUOTE(JSON_EXTRACT(_data, '$.username'));
    SET v_email    = AES_ENCRYPT(JSON_UNQUOTE(JSON_EXTRACT(_data, '$.email')), _key);
    SET v_password = SHA2(JSON_UNQUOTE(JSON_EXTRACT(_data, '$.password')), 256);
    SET v_name     = JSON_UNQUOTE(JSON_EXTRACT(_data, '$.firstName'));
    SET v_surname  = AES_ENCRYPT(JSON_UNQUOTE(JSON_EXTRACT(_data, '$.lastName')), _key);
    SET v_agreement = CAST(JSON_UNQUOTE(JSON_EXTRACT(_data, '$.agr')) AS UNSIGNED);

    -- sprawdzenie czy istnieje login lub email
    SELECT COUNT(*) INTO v_count
    FROM tbusers
    WHERE login = v_login
       OR email = v_email;

    IF v_count > 0 THEN
        SET _status = 'EXISTS';
    ELSE
        SET v_id = UUID();

        INSERT INTO tbusers (
            id, login, email, password, name, surname, agreement, role, status
        ) VALUES (
            v_id, v_login, v_email, v_password, v_name, v_surname, v_agreement, 'patient', 'NEW'
        );

        SET _status = 'OK';
    END IF;
END$$

DELIMITER ;

-- ########################################################
-- Procedura: loginUser
-- ########################################################

DROP PROCEDURE IF EXISTS loginUser;
DELIMITER $$

CREATE PROCEDURE loginUser(
    IN _username VARCHAR(64)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    IN _password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    OUT _status  VARCHAR(20)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    OUT _userId  CHAR(36)     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    OUT _role    VARCHAR(50)  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
)
BEGIN
    DECLARE v_password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

    -- pobranie hasła z bazy
    SELECT password, id, role
      INTO v_password, _userId, _role
    FROM tbusers
    WHERE login = _username
      AND status = 'ACTIVE'
    LIMIT 1;

    IF v_password IS NULL THEN
        SET _status = 'WRONG';
        SET _userId = NULL;
        SET _role = NULL;
    ELSEIF v_password = SHA2(_password, 256) THEN
        SET _status = 'OK';
    ELSE
        SET _status = 'WRONG';
        SET _userId = NULL;
        SET _role = NULL;
    END IF;
END$$

DELIMITER ;

-- ########################################################
-- Procedura: getUsers
-- ########################################################

DROP PROCEDURE IF EXISTS getUsers;
DELIMITER $$

CREATE PROCEDURE getUsers(
    IN _key VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
)
BEGIN
    SELECT
        id,
        login,
        CAST(AES_DECRYPT(email, _key) AS CHAR(255)
             CHARACTER SET utf8mb4) AS email,
        name,
        CAST(AES_DECRYPT(surname, _key) AS CHAR(255)
             CHARACTER SET utf8mb4) AS surname,
        role,
        createdt,
        agreement,
        confirmfdt,
        status
    FROM tbusers;
END$$

DELIMITER ;




-- #################################################################################################### nowość od 02.12.2025

CREATE TABLE IF NOT EXISTS tbroles (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. Modyfikacja tbusers
ALTER TABLE tbusers
ADD FOREIGN KEY (role) REFERENCES tbroles(id);


-- 3. Tabela tbadmin 
CREATE TABLE IF NOT EXISTS tbadmin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tbusers_id CHAR(36) NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (tbusers_id) REFERENCES tbusers(id)
) ENGINE=InnoDB;


-- 4. Tabela tbpatients 
CREATE TABLE IF NOT EXISTS tbpatients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tbusers_id CHAR(36) NOT NULL,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    pesel VARCHAR(11) UNIQUE,
    addrStreet VARCHAR(150),
    addrCity VARCHAR(100),
    addrPostCode VARCHAR(10),
    addrFlat VARCHAR(10),
    FOREIGN KEY (tbusers_id) REFERENCES tbusers(id)
) ENGINE=InnoDB;


-- 5. Tabela tbspecialisation
CREATE TABLE IF NOT EXISTS tbspecialisation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;


-- 6. Tabela tbdoctors 
CREATE TABLE IF NOT EXISTS tbdoctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tbusers_id CHAR(36) NOT NULL,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    FOREIGN KEY (tbusers_id) REFERENCES tbusers(id)
) ENGINE=InnoDB;


-- 7. Tabela tbdoctors_has_tbspecialisation
CREATE TABLE IF NOT EXISTS tbdoctors_has_tbspecialisation (
    tbDoctors_id INT NOT NULL,
    tbspecialisation_id INT NOT NULL,

    PRIMARY KEY (tbDoctors_id, tbspecialisation_id),

    FOREIGN KEY (tbDoctors_id)
        REFERENCES tbdoctors(id),

    FOREIGN KEY (tbspecialisation_id)
        REFERENCES tbspecialisation(id)
) ENGINE=InnoDB;


-- 8. Tabela tbvisits
CREATE TABLE IF NOT EXISTS tbvisits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tbDoctors_id INT NOT NULL,
    tbPatients_id INT NOT NULL,

    visitDate DATETIME NOT NULL,
    visitDesc TEXT,

    FOREIGN KEY (tbDoctors_id)
        REFERENCES tbdoctors(id),

    FOREIGN KEY (tbPatients_id)
        REFERENCES tbpatients(id)
) ENGINE=InnoDB;


-- 9. Tabela tbrefs
CREATE TABLE IF NOT EXISTS tbrefs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date DATE NOT NULL,
    description TEXT
) ENGINE=InnoDB;



-- ############################################################# WSTAWIANIE TESTOWYCH DANYCH
SET @UUID_DR_KOWALSKI = 'd1d1d1d1-e2e2-f3f3-g4g4-h5h5h5h5h5h5';
SET @UUID_DR_NOWAK = 'e1e1e1e1-f2f2-g3g3-h4h4-i5i5i5i5i5i5i5';
SET @UUID_PACJENT_JAN = 'p1p1p1p1-a2a2-c3c3-e4e4-n5n5n5n5n5n5';
SET @UUID_PACJENT_ANNA = 'p2p2p2p2-b3b3-d4d4-f5f5-o6o6o6o6o6o6';


INSERT INTO tbusers (id, login, email, password, role, agreement, status) VALUES
(@UUID_DR_KOWALSKI, 'dr.kowalski', 'dr.kowalski@klinika.pl', 'haslo_lekarza1', 'doctor', 1, 'ACTIVE'),
(@UUID_DR_NOWAK, 'dr.nowak', 'dr.nowak@klinika.pl', 'haslo_lekarza2', 'doctor', 1, 'ACTIVE'),
(@UUID_PACJENT_JAN, 'jan.pacjent', 'jan.pacjent@mail.com', 'haslo_pacjenta1', 'patient', 1, 'ACTIVE'),
(@UUID_PACJENT_ANNA, 'anna.pacjent', 'anna.pacjent@mail.com', 'haslo_pacjenta2', 'patient', 1, 'ACTIVE');


INSERT INTO tbspecialisation (id, name) VALUES
(1, 'Kardiolog'),
(2, 'Pediatra'),
(3, 'Neurolog'),
(4, 'Laryngolog');


INSERT INTO tbdoctors (id, tbusers_id, name, surname, phone) VALUES
(1, @UUID_DR_KOWALSKI, 'Adam', 'Kowalski', '600100200'),
(2, @UUID_DR_NOWAK, 'Ewa', 'Nowak', '600300400');


INSERT INTO tbpatients (id, tbusers_id, name, surname, phone, pesel, addrCity) VALUES
(1, @UUID_PACJENT_JAN, 'Jan', 'Pacjent', '555111222', '90010112345', 'Warszawa'),
(2, @UUID_PACJENT_ANNA, 'Anna', 'Kowalska', '555333444', '95050554321', 'Kraków');


INSERT INTO tbdoctors_has_tbspecialisation (tbDoctors_id, tbspecialisation_id) VALUES
(1, 1),
(1, 3),
(2, 2);




-- ######################## nowe 03.12.2025
-- to do wprowadzenia systemu że lekarz musi potwierdzić wizytę, aby się odbyła.

ALTER TABLE tbvisits
ADD COLUMN status VARCHAR(50);
-- oczekwiane statusy to WAITING, APPROVED, DONE, CANCELLED

-- a tego zapomniałam albo nie chciałam pamiętać
ALTER TABLE tbvisits
ADD COLUMN specid INT(11),
ADD FOREIGN KEY (specid) REFERENCES tbspecialisation(id);

-- dobra to też się przyda; użytkownik nie może zaspamować lekarzowi skrzynki - zrobimy na podstawie daty: max 1 do 1 lekarza i max 5 na dzień?
ALTER TABLE tbvisits
ADD COLUMN credt TIMESTAMP
DEFAULT NOW();

-- a to do sprawdzania czy lekarz nie jest już zajęty i ewentualnie do wpisania wolnego/urlopu xd
ALTER TABLE tbvisits
ADD COLUMN visitDuration TIME
DEFAULT '00:15:00';

ALTER TABLE tbdoctors
ADD COLUMN defaultVisitDuration TIME
DEFAULT '00:15:00';


-- ############### procedura na dodanie wizyty
DELIMITER //

CREATE FUNCTION setVisit (
    _spec INT,
    _doctorid INT, 
    _visitDate TIMESTAMP,
    _visitDesc VARCHAR(268),
    _userId CHAR(36)
)
RETURNS VARCHAR(20)
DETERMINISTIC -- Wskazuje, że dla tych samych danych, funkcja zwróci ten sam wynik
BEGIN
    DECLARE current_doc_visits INT DEFAULT 0;
    DECLARE patient_visits_today INT DEFAULT 0;
    DECLARE patientID_val INT; 
    DECLARE defaultVisitTime_val TIME;
    DECLARE docsShift_val TIME;
    
    -- szukam id pacjenta
    SELECT id INTO patientID_val
    FROM tbpatients
    WHERE tbusers_id = _userId;
    
    -- a ziomek w ogóle z nami jest?
    IF patientID_val IS NULL THEN
        RETURN 'NO_PATIENT';
    END IF;
    
    -- patrze czy typ nie spamuje lekarzowi (max 1 waiting)
    SELECT COUNT(*)
    INTO current_doc_visits
    FROM tbvisits
    WHERE tbpatients_id = patientID_val 
      AND tbdoctors_id = _doctorid
      AND status = 'WAITING';
    
    -- czy pacjent nie spamuje ogółem (5 dziennie max)
    SELECT COUNT(*)
    INTO patient_visits_today
    FROM tbvisits
    WHERE tbpatients_id = patientID_val
      AND DATE(visitDate) = DATE(_visitDate); -- sprawdzamy na konkretny dzień

    -- nie przemęczamy lekarzy
    SELECT defaultVisitDuration INTO defaultVisitTime_val
    FROM tbdoctors
    WHERE id = _doctorid;
    
    -- Łączny czas wizyt w tym dniu
    -- Użyto COALESCE, aby zamienić NULL (brak wizyt) na '00:00:00'
    SELECT COALESCE(SUM(visitDuration), '00:00:00')
    INTO docsShift_val
    FROM tbvisits
    WHERE tbdoctors_id = _doctorid
      AND DATE(visitDate) = DATE(_visitDate);
    
    
    -- lece z ifozą
    IF current_doc_visits > 0 THEN
        RETURN 'WAITING'; -- Pacjent już ma oczekującą wizytę u tego lekarza
    
    ELSEIF patient_visits_today >= 5 THEN
        RETURN 'OVER5'; -- Przekroczony limit 5 wizyt dziennie
    
    -- porównanie w sekundach: Lekarz ma już zarezerwowane 7 lub więcej godzin
    ELSEIF TIME_TO_SEC(docsShift_val) >= TIME_TO_SEC('07:00:00') THEN
        RETURN 'BUSY'; 
    
    ELSE
        -- Wszystkie warunki wstępne OK, wstawiamy wizytę
        INSERT INTO tbvisits (
            tbDoctors_id, 
            tbpatients_id, 
            visitDate, 
            visitDesc, 
            status, 
            specid, 
            visitDuration
        )
        VALUES (
            _doctorid, 
            patientID_val, 
            _visitDate, 
            _visitDesc, 
            'WAITING', 
            _spec, 
            defaultVisitTime_val
        );
        
        RETURN 'SUCCESS'; -- Pomyślne wstawienie
        
    END IF; -- Koniec if

END //

DELIMITER ;


-- #################### NOWE trigger dla tbpatients - dodajemy rekord wraz z rejestracją

DELIMITER $$

CREATE TRIGGER after_user_insert
AFTER INSERT ON tbusers
FOR EACH ROW
BEGIN
    IF NEW.role = 'patient' THEN
        INSERT INTO tbpatients (tbusers_id, name, surname)
        VALUES (NEW.id, NEW.name, NEW.surname);
    END IF;
END$$

DELIMITER ;


-- to do szukania lekarzuf

CREATE OR REPLACE VIEW vwDoctorsBySpec AS
SELECT 
    s.id AS specid,
    d.id AS doctor_id,
    CONCAT('dr ', d.name, ' ', d.surname) AS doctor_name
FROM tbdoctors_has_tbspecialisation ds
JOIN tbdoctors d ON ds.tbdoctors_id = d.id
JOIN tbspecialisation s ON ds.tbspecialisation_id = s.id
ORDER BY s.id, d.name;


-- to do pokazuwania wizytuf
DROP FUNCTION IF EXISTS getVisits;
DELIMITER $$

CREATE FUNCTION getVisits(_userId CHAR(36))
RETURNS JSON
DETERMINISTIC
BEGIN
    RETURN (
        SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'id', v.id,
            'visitDate', DATE_FORMAT(v.visitDate, '%Y-%m-%d %H:%i:%s'),
            'doctor', CONCAT(d.name, ' ', d.surname),
            'spec', s.name,
            'visitDesc', v.visitDesc,
            'status', v.status
        ))
        FROM tbvisits v
        JOIN tbpatients p ON v.tbPatients_id = p.id
        JOIN tbdoctors d ON v.tbDoctors_id = d.id
        JOIN tbspecialisation s ON v.specid = s.id
        WHERE p.tbusers_id = _userId
        ORDER BY v.visitDate DESC
    );
END$$

DELIMITER ;


-- inspo https://agnieszkanicpon.igabinet.pl/b/?i=YTo5OntzOjE6ImwiO3M6MzoicG9sIjtzOjI6ImxsIjtpOjA7czoxOiJwIjtOO3M6MjoicGwiO2k6MDtzOjE6ImciO047czoyOiJnbCI7aTowO3M6MToicyI7czowOiIiO3M6Mjoic2wiO2k6MDtzOjE6ImEiO3M6MToiciI7fQ%3D%3D_8ebc349bb7f9ec4eae06c375f45246bb89ccec2528d5f163cc96ff2743734ff0&referer=https%3A%2F%2Fagnieszkanicpon.pl%2F