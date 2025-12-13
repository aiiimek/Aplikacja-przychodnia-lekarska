# 🏥 Lekario - System Zarządzania Przychodnią Lekarską

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![SASS](https://img.shields.io/badge/SASS-CC6699?style=for-the-badge&logo=sass&logoColor=white)
![FontAwesome](https://img.shields.io/badge/Font_Awesome-528DD7?style=for-the-badge&logo=font-awesome&logoColor=white)
![DataTables](https://img.shields.io/badge/DataTables-1F4788?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAowAAAKMB8MeazgAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAEXSURBVCiRY2AYBaNgFAwCwIjN8P///zMwMDAwMjIyMvz//x+mCKYBpgGmAaYBpgGm4f///zANMA0wDTANME1gk2EaYBpgGmAaYJpgGmAaYBpgGmCaYBpgGmAaYBpgmmAaYBpgGmAaYJpgGmAaYBpgGmCaYBpgGmAaYBpgmmAaYBpgGv7//w/TANMA0wDTBNMA0wDTANMA0wTTANMA0wDTANME0wDTANMA0wDTBNMA0wDTANMA0wTTANMA0wDTANME0wDTANMA0wDTBNMA0wDTANMA0wTTANMA0wDTANME0wDTANMA0wDTBNMA0wDTANMA0wTTANMA0wDTANME0wDTANMA0wDTBNMA0wDTANMA0wTTANMwCgYBAACuLQ4PfRjLhAAAAABJRU5ErkJggg==&logoColor=white)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg?style=for-the-badge)](https://github.com/yourusername/Aplikacja-przychodnia-lekarska/graphs/commit-activity)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=for-the-badge)](http://makeapullrequest.com)

### 🚀 Kompleksowy system do zarządzania przychodnią lekarską z zaawansowanym systemem szyfrowania danych medycznych

[Funkcjonalności](#-funkcjonalności) • [Instalacja](#-instalacja) • [Technologie](#-stack-technologiczny) • [Architektura](#-architektura) • [Bezpieczeństwo](#-bezpieczeństwo) • [Dokumentacja](#-dokumentacja)

---

</div>

## 📋 O Projekcie

**Lekario** to nowoczesna aplikacja webowa stworzona do kompleksowego zarządzania przychodnią lekarską. System umożliwia zarządzanie wizytami, pacjentami, lekarzami oraz całą dokumentacją medyczną z naciskiem na bezpieczeństwo danych osobowych zgodnie z wymogami RODO.

### 🎯 Kluczowe Cechy

- ✅ **Wielopoziomowa autoryzacja** - System ról (Pacjent, Lekarz, Administrator)
- 🔐 **Zaawansowane szyfrowanie** - AES-256 dla danych wrażliwych + SHA-256 dla haseł
- 📅 **Inteligentny system wizyt** - Rezerwacja, anulowanie i zarządzanie terminarzem
- 📊 **Dashboard z analityką** - Wykresy i statystyki w czasie rzeczywistym
- 💊 **Zarządzanie receptami** - Elektroniczny system recept
- 📱 **Responsywny design** - Pełna kompatybilność z urządzeniami mobilnymi
- 🗄️ **Procedury składowane** - Optymalizacja wydajności bazy danych
- 🔍 **Zaawansowane wyszukiwanie** - DataTables z filtrowaniem i sortowaniem

## ✨ Funkcjonalności

### 👤 Panel Pacjenta
- 📝 Rejestracja i logowanie z weryfikacją email
- 📅 Przeglądanie dostępnych terminów wizyt
- 🏥 Rezerwacja wizyt u wybranych lekarzy
- 📋 Historia wizyt i dokumentacja medyczna
- 💊 Przeglądanie recept elektronicznych
- ⚙️ Zarządzanie profilem i ustawieniami

### 👨‍⚕️ Panel Lekarza
- 📊 Dashboard z dziennymi statystykami
- 👥 Lista pacjentów i ich historie chorobowe
- 📅 Zarządzanie kalendarzem wizyt
- 📝 Wystawianie recept elektronicznych
- 📈 Raporty i analityka
- 🔔 Powiadomienia o nowych wizytach

### 🛡️ Panel Administratora
- 👨‍💼 Zarządzanie użytkownikami (pacjenci, lekarze, personel)
- 🏥 Konfiguracja systemu przychodni
- 📊 Globalne statystyki i raporty
- 🗄️ Zarządzanie bazą danych
- 🔐 Kontrola dostępu i uprawnień
- 📝 Logi systemowe i audyt

## 🛠️ Stack Technologiczny

### Backend
| Technologia | Wersja | Zastosowanie |
|------------|--------|--------------|
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) | 7.4+ | Logika serwera, API endpoints |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) | 8.0+ | Baza danych, procedury składowane |

### Frontend
| Technologia | Wersja | Zastosowanie |
|------------|--------|--------------|
| ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white) | 5 | Struktura stron |
| ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white) | 3 | Stylizacja |
| ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black) | ES6+ | Interakcje, AJAX |
| ![SASS](https://img.shields.io/badge/SASS-CC6699?style=flat-square&logo=sass&logoColor=white) | - | Preprocessor CSS |

### Biblioteki & Frameworki
| Biblioteka | Zastosowanie |
|------------|--------------|
| ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat-square&logo=bootstrap&logoColor=white) **Bootstrap 4.6** | Responsywny layout, komponenty UI |
| ![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=flat-square&logo=jquery&logoColor=white) **jQuery 3.6** | Manipulacja DOM, AJAX requests |
| ![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=flat-square&logo=chartdotjs&logoColor=white) **Chart.js 2.9** | Wykresy i wizualizacje danych |
| ![DataTables](https://img.shields.io/badge/DataTables-1F4788?style=flat-square) **DataTables 1.10** | Zaawansowane tabele z filtrowaniem |
| ![FontAwesome](https://img.shields.io/badge/Font_Awesome-528DD7?style=flat-square&logo=font-awesome&logoColor=white) **FontAwesome 5** | Ikony i symbole |
| **jQuery Easing** | Animacje smooth scrolling |

### Dodatkowe Narzędzia
- **SB Admin 2** - Szablon panelu administracyjnego
- **Gulp.js** - Automatyzacja zadań (build, minifikacja)
- **Git** - Kontrola wersji

## 📁 Architektura Projektu

```
Aplikacja-przychodnia-lekarska/
│
├── 📂 assets/                      # Zasoby statyczne
│   ├── css/                        # Stylizacja
│   │   ├── custom.css              # Własne style
│   │   └── sb-admin-2.min.css      # Style szablonu
│   ├── js/                         # JavaScript
│   │   ├── base/                   # Główne skrypty
│   │   │   ├── dashboard.js        # Logika dashboardu
│   │   │   └── login.js            # Logika logowania
│   │   └── demo/                   # Wykresy i tabele
│   ├── scss/                       # SASS source files
│   └── vendor/                     # Biblioteki zewnętrzne
│       ├── bootstrap/
│       ├── chart.js/
│       ├── datatables/
│       ├── fontawesome-free/
│       └── jquery/
│
├── 📂 dbEssentials/                # Skrypty bazodanowe
│   ├── BDsql.sql                   # Schemat bazy danych
│   └── lekariodb.xml               # Eksport struktury
│
├── 📂 includes/                    # Komponenty PHP
│   ├── header.php                  # Nagłówek strony
│   ├── footer.php                  # Stopka
│   ├── sidenav.php                 # Menu boczne
│   └── logoutModal.php             # Modal wylogowania
│
├── 📂 model/                       # Warstwa modelu (MVC)
│   ├── Dashboard.php               # Model dashboardu
│   ├── Login.php                   # Model autoryzacji
│   └── Register.php                # Model rejestracji
│
├── 📂 SaySoft/                     # Core system
│   ├── dbconn.php                  # Połączenie z bazą danych
│   ├── master.php                  # Master config
│   └── pattern.js                  # Wzorce walidacji
│
├── 📂 sites/                       # Moduły aplikacji
│   ├── dashboard/                  # Panel główny
│   │   ├── index.php
│   │   ├── setVisit.php            # Umawianie wizyt
│   │   ├── cancelVisit.php         # Anulowanie wizyt
│   │   └── getDoctors.php          # Pobieranie listy lekarzy
│   ├── login/                      # Moduł logowania
│   ├── register/                   # Moduł rejestracji
│   └── patient/                    # Panel pacjenta
│
├── 📄 DB.sql                       # Główny skrypt SQL
├── 📄 ADMINdash.php                # Dashboard admina
├── 📄 DOCTOR.php                   # Panel lekarza
├── 📄 RECEPTY.php                  # Moduł recept
└── 📄 README.md                    # Ten plik

```

## 🔐 Bezpieczeństwo

### Mechanizmy Ochrony Danych

#### 🔒 Szyfrowanie
- **AES-256** - Szyfrowanie danych osobowych (email, nazwisko)
- **SHA-256** - Hashowanie haseł przed zapisem do bazy
- **Klucz szyfrujący** - Przechowywany bezpiecznie poza kodem źródłowym

#### 🛡️ Zabezpieczenia Aplikacji
- ✅ **Prepared Statements** - Ochrona przed SQL Injection
- ✅ **CSRF Protection** - Tokeny w formularzach
- ✅ **XSS Protection** - Sanityzacja input/output
- ✅ **Session Management** - Bezpieczne zarządzanie sesjami
- ✅ **Password Policy** - Wymuszanie silnych haseł
- ✅ **Role-Based Access Control (RBAC)** - Kontrola dostępu oparta na rolach

#### 📋 Zgodność z RODO
- 📝 Zgoda na przetwarzanie danych osobowych
- 🔐 Szyfrowanie danych wrażliwych
- 📊 Możliwość eksportu danych użytkownika
- 🗑️ Prawo do usunięcia danych (soft delete)
- 📝 Logi dostępu do danych medycznych

### Struktura Bazy Danych

#### Tabela: `tbusers`
```sql
CREATE TABLE tbusers (
  id CHAR(36) PRIMARY KEY,              -- UUID v4
  login CHAR(64) UNIQUE NOT NULL,       -- Login użytkownika
  email VARBINARY(255) NOT NULL,        -- Email (zaszyfrowany AES-256)
  password VARCHAR(255) NOT NULL,       -- Hasło (SHA-256)
  name VARCHAR(100),                    -- Imię
  surname VARBINARY(255),               -- Nazwisko (zaszyfrowane)
  role VARCHAR(50) DEFAULT 'patient',   -- Rola: patient/doctor/admin
  createdt TIMESTAMP DEFAULT NOW(),     -- Data utworzenia
  agreement TINYINT(1),                 -- Zgoda RODO
  confirmdt TIMESTAMP NULL,             -- Data potwierdzenia email
  status VARCHAR(50)                    -- Status: NEW/ACTIVE/BLOCKED
);
```

#### Procedury Składowane
- **`registerUser`** - Bezpieczna rejestracja z szyfrowaniem
- **`loginUser`** - Autoryzacja użytkownika
- **`getAvailableDoctors`** - Lista dostępnych lekarzy
- **`createVisit`** - Tworzenie nowej wizyty
- **`cancelVisit`** - Anulowanie wizyty

## 🚀 Instalacja

### Wymagania Systemowe

```bash
PHP >= 7.4
MySQL >= 8.0 lub MariaDB >= 10.5
Apache/Nginx
Composer (opcjonalnie)
Node.js >= 14 (dla development)
```

### Krok po kroku

#### 1️⃣ Sklonuj repozytorium
```bash
git clone https://github.com/yourusername/Aplikacja-przychodnia-lekarska.git
cd Aplikacja-przychodnia-lekarska
```

#### 2️⃣ Konfiguracja bazy danych
```bash
# Utwórz bazę danych
mysql -u root -p

CREATE DATABASE lekario_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lekario_db;

# Zaimportuj schemat
SOURCE DB.sql;
# lub
mysql -u root -p lekario_db < DB.sql
```

#### 3️⃣ Konfiguracja połączenia
Edytuj plik `SaySoft/dbconn.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'lekario_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');

// Klucz szyfrowania (ZMIEŃ NA SWÓJ!)
define('ENCRYPTION_KEY', 'your-secret-encryption-key-min-32-chars');
?>
```

#### 4️⃣ Uprawnienia katalogów
```bash
chmod -R 755 assets/
chmod -R 644 assets/css/*.css
chmod -R 644 assets/js/*.js
```

#### 5️⃣ Konfiguracja wirtualnego hosta (opcjonalnie)

**Apache - `/etc/apache2/sites-available/lekario.conf`:**
```apache
<VirtualHost *:80>
    ServerName lekario.local
    DocumentRoot /var/www/html/Aplikacja-przychodnia-lekarska
    
    <Directory /var/www/html/Aplikacja-przychodnia-lekarska>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/lekario_error.log
    CustomLog ${APACHE_LOG_DIR}/lekario_access.log combined
</VirtualHost>
```

**Nginx - `/etc/nginx/sites-available/lekario`:**
```nginx
server {
    listen 80;
    server_name lekario.local;
    root /var/www/html/Aplikacja-przychodnia-lekarska;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### 6️⃣ Uruchom aplikację
```bash
# Jeśli używasz wbudowanego serwera PHP (development)
php -S localhost:8000

# Lub otwórz w przeglądarce
http://localhost/Aplikacja-przychodnia-lekarska
# lub
http://lekario.local
```

### 🎨 Development Mode

```bash
# Zainstaluj dependencies dla SASS compilation
cd assets/vendor/sbadmin
npm install

# Watch SCSS changes
npm run watch

# Build production CSS
npm run build
```

## 📖 Dokumentacja

### Moduły Systemu

#### 🔑 Logowanie (`sites/login/`)
- Autoryzacja użytkownika
- Walidacja danych po stronie serwera i klienta
- Regeneracja sesji po logowaniu
- Przekierowanie na podstawie roli użytkownika

#### 📝 Rejestracja (`sites/register/`)
- Formularz rejestracji z walidacją
- Sprawdzanie unikalności loginu/emaila
- Szyfrowanie danych wrażliwych
- Email weryfikacyjny (opcjonalnie)

#### 📊 Dashboard (`sites/dashboard/`)
- Statystyki wizyt (Chart.js)
- Nadchodzące wizyty
- Szybkie akcje
- Powiadomienia

#### 👥 Zarządzanie Wizytami
- **setVisit.php** - Rezerwacja wizyty
- **cancelVisit.php** - Anulowanie wizyty  
- **getDoctors.php** - API endpoint dla listy lekarzy

### API Endpoints

#### Wizyty
```javascript
// Pobierz listę lekarzy
GET /sites/dashboard/getDoctors.php

// Umów wizytę
POST /sites/dashboard/setVisit.php
Body: { doctorId, date, time, reason }

// Anuluj wizytę
POST /sites/dashboard/cancelVisit.php
Body: { visitId }
```

#### Autoryzacja
```javascript
// Logowanie
POST /sites/login/login.php
Body: { username, password }

// Rejestracja
POST /sites/register/save.php
Body: { username, email, password, firstName, lastName, agreement }

// Wylogowanie
GET /sites/login/logout.php
```

## 🎨 Customizacja

### Zmiana kolorów motywu
Edytuj `assets/scss/_variables.scss`:
```scss
// Primary color
$primary: #4e73df;

// Success, info, warning, danger
$success: #1cc88a;
$info: #36b9cc;
$warning: #f6c23e;
$danger: #e74a3b;
```

### Dodanie nowego modułu

1. Utwórz katalog w `sites/nazwa_modulu/`
2. Dodaj plik `index.php`
3. Dodaj link w `includes/sidenav.php`
4. Dodaj kontroler w `model/NazwaModulu.php`

## 🐛 Rozwiązywanie Problemów

### Problem: Nie można połączyć się z bazą danych
```bash
# Sprawdź status MySQL
sudo systemctl status mysql

# Sprawdź dane dostępowe w dbconn.php
# Zweryfikuj czy użytkownik ma uprawnienia
GRANT ALL PRIVILEGES ON lekario_db.* TO 'user'@'localhost';
FLUSH PRIVILEGES;
```

### Problem: Błąd 500 - Internal Server Error
```bash
# Sprawdź logi Apache
tail -f /var/log/apache2/error.log

# Sprawdź logi PHP
tail -f /var/log/php/error.log

# Włącz wyświetlanie błędów (tylko development!)
# W php.ini:
display_errors = On
error_reporting = E_ALL
```

### Problem: Sesja nie działa
```bash
# Sprawdź uprawnienia katalogu sesji
ls -la /var/lib/php/sessions

# Upewnij się, że session_start() jest pierwszą linią
# Sprawdź czy nie ma output przed session_start()
```

## 🤝 Contributing

Zachęcamy do współpracy! Jeśli chcesz dodać nową funkcjonalność lub naprawić błąd:

1. 🍴 Fork projektu
2. 🌿 Utwórz branch (`git checkout -b feature/AmazingFeature`)
3. 💾 Commit zmian (`git commit -m 'Add some AmazingFeature'`)
4. 📤 Push do brancha (`git push origin feature/AmazingFeature`)
5. 🎉 Otwórz Pull Request

### Zasady Contribution
- ✅ Kod zgodny z PSR-12 (PHP)
- ✅ Komentarze w języku polskim lub angielskim
- ✅ Testy jednostkowe dla nowych funkcji
- ✅ Dokumentacja dla API endpoints

## 📜 Licencja

Projekt jest dostępny na licencji **MIT License** - szczegóły w pliku [LICENSE](LICENSE).

```
MIT License

Copyright (c) 2025 Lekario Team

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

## 👨‍💻 Autorzy & Współtwórcy

<div align="center">

**Zespół Lekario**

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/yourusername)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://linkedin.com/in/yourprofile)

</div>

---

### 🤝 Kontrybutorzy

Dziękujemy wszystkim, którzy przyczynili się do rozwoju projektu! Każdy wkład jest dla nas ważny.

<!-- ALL-CONTRIBUTORS-LIST:START - Nie modyfikuj tego komentarza ręcznie -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->

<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%">
        <a href="https://github.com/yourusername">
          <img src="https://github.com/yourusername.png?s=100" width="100px;" alt="Twój Profil"/>
          <br />
          <sub><b>Twoje Imię</b></sub>
        </a>
        <br />
        <a href="https://github.com/yourusername/Aplikacja-przychodnia-lekarska/commits?author=yourusername" title="Code">💻</a>
        <a href="#design-yourusername" title="Design">🎨</a>
        <a href="#ideas-yourusername" title="Ideas">🤔</a>
      </td>
      <!-- Dodaj tutaj kolejnych kontrybutorów - skopiuj powyższy blok <td>...</td> -->
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

#### Jak dodać kontrybutora:
Skopiuj poniższy template i wklej w tabeli powyżej:

```html
<td align="center" valign="top" width="14.28%">
  <a href="https://github.com/USERNAME">
    <img src="https://github.com/USERNAME.png?s=100" width="100px;" alt="NAME"/>
    <br />
    <sub><b>NAME</b></sub>
  </a>
  <br />
  <a href="https://github.com/yourusername/Aplikacja-przychodnia-lekarska/commits?author=USERNAME" title="Code">💻</a>
  <a href="#design-USERNAME" title="Design">🎨</a>
</td>
```

**Legenda emoji:**
- 💻 Code - Wkład w kod
- 🎨 Design - Design i UI/UX
- 📖 Documentation - Dokumentacja
- 🤔 Ideas - Pomysły i koncepcje
- 🐛 Bug reports - Zgłaszanie bugów
- 🔧 Maintenance - Utrzymanie projektu
- 🔌 Plugin/Utilities - Narzędzia i wtyczki
- 📆 Project Management - Zarządzanie projektem
- 💬 Answering Questions - Odpowiadanie na pytania
- ⚠️ Tests - Testy
- 🌍 Translation - Tłumaczenia

**Automatyczne wyświetlanie kontrybutorów:**

[![Contributors](https://contrib.rocks/image?repo=yourusername/Aplikacja-przychodnia-lekarska)](https://github.com/yourusername/Aplikacja-przychodnia-lekarska/graphs/contributors)

*Kliknij w avatar, aby przejść do profilu kontrybutora na GitHubie*

---

### 🌟 Podziękowania

- [SB Admin 2](https://startbootstrap.com/theme/sb-admin-2) - Bootstrap admin template
- [FontAwesome](https://fontawesome.com) - Ikony
- [Chart.js](https://www.chartjs.org) - Biblioteka wykresów
- [DataTables](https://datatables.net) - Plugin jQuery do tabel

## 📞 Kontakt & Wsparcie

- 📧 Email: support@lekario.pl
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/Aplikacja-przychodnia-lekarska/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/yourusername/Aplikacja-przychodnia-lekarska/discussions)

## 📈 Roadmapa

### Wersja 2.0 (Planowane)
- [ ] 🔔 System powiadomień push
- [ ] 📱 Aplikacja mobilna (React Native)
- [ ] 🌐 Wsparcie dla wielu języków (i18n)
- [ ] 📄 Generowanie PDF z historią wizyt
- [ ] 💳 Integracja z płatnościami online
- [ ] 📊 Zaawansowana analityka i raporty
- [ ] 🤖 Chatbot do obsługi pacjentów
- [ ] 🔗 API RESTful dla integracji zewnętrznych
- [ ] 📹 Telemedycyna - wizyty online
- [ ] 🗓️ Synchronizacja z Google Calendar

### Wersja 1.5 (W trakcie)
- [x] ✅ System logowania i rejestracji
- [x] ✅ Dashboard z wykresami
- [x] ✅ Zarządzanie wizytami
- [ ] 🔄 System powiadomień email
- [ ] 📝 Elektroniczne recepty
- [ ] 🏥 Moduł dla lekarzy

---

<div align="center">

### ⭐ Jeśli projekt Ci się podoba, zostaw gwiazdkę na GitHub! ⭐

**Zbudowane z ❤️ przez zespół Lekario**

![Visitors](https://api.visitorbadge.io/api/visitors?path=https%3A%2F%2Fgithub.com%2Fyourusername%2FAplikacja-przychodnia-lekarska&label=Visitors&countColor=%23263759&style=flat-square)
![GitHub last commit](https://img.shields.io/github/last-commit/yourusername/Aplikacja-przychodnia-lekarska?style=flat-square)
![GitHub repo size](https://img.shields.io/github/repo-size/yourusername/Aplikacja-przychodnia-lekarska?style=flat-square)

</div>
