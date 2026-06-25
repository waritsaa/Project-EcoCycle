<?php
$conn = mysqli_connect("localhost", "root", "", "ecocycle");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function asset_url($pathFromRoot) {
    if (empty($pathFromRoot)) {
        return '';
    }
    // Jangan tambah prefix kalau sudah berupa URL absolut
    if (preg_match('#^(https?://|/)#', $pathFromRoot)) {
        return $pathFromRoot;
    }

    // Root project = 1 folder di atas config/ (tempat file ini berada).
    $rootDir = realpath(__DIR__ . '/..');
    $scriptDir = isset($_SERVER['SCRIPT_FILENAME'])
        ? realpath(dirname($_SERVER['SCRIPT_FILENAME']))
        : $rootDir;

    if (!$rootDir || !$scriptDir || stripos($scriptDir, $rootDir) !== 0) {
        return $pathFromRoot;
    }

    $relative = trim(substr($scriptDir, strlen($rootDir)), '/\\');

    if ($relative === '') {
        $depth = 0;
    } else {
        // Normalize separator: Windows pakai \, Linux pakai /
        $relative = str_replace('\\', '/', $relative);
        $depth = count(explode('/', $relative));
    }

    return str_repeat('../', $depth) . $pathFromRoot;
}
?>
