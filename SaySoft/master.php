<?php
// SaySoft/master.php — include DB connector and all PHP files from the model/ directory
// This is the canonical master loader for SaySoft components.

// Ensure DB connection helper is available
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
