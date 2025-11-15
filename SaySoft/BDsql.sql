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
