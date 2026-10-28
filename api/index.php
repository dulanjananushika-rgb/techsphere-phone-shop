<?php

// Prepare writable directories in /tmp for Vercel serverless environment
$storagePath = '/tmp/storage';
$subdirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/cache',
    $storagePath . '/logs',
];

foreach ($subdirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy pre-seeded SQLite database to writable /tmp
$targetDb = '/tmp/database.sqlite';
if (!file_exists($targetDb)) {
    $seedDb = __DIR__ . '/../database/seed_database.sqlite';
    if (file_exists($seedDb)) {
        copy($seedDb, $targetDb);
    } else {
        touch($targetDb);
    }
}

// Ensure proper storage path in Laravel
require __DIR__ . '/../public/index.php';
