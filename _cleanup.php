<?php

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

if (php_sapi_name() !== 'cli') {
    exit;
}

$files = [
    '.github',
    '.cliffignore',
    '.editorconfig',
    '.gitignore',
    '_release.php',
    'CHANGELOG.md',
    'cliff.toml',
    'README.md'
];

foreach ($files as $item) {
    $path = ROOT . $item;

    if (is_file($path)) {
        removeFile($path);
    } elseif (is_dir($path)) {
        removeDir($path);
    }
}

/**
 * Remove target file
 *
 * @param string $file Path to file
 */
function removeFile(string $file): void
{
    if (unlink($file)) {
        echo "- File removed: $file" . PHP_EOL;
    } else {
        echo "- File cannot be removed: $file" . PHP_EOL;
        exit;
    }
}

/**
 * Remove folder (recursively)
 *
 * @param string $dir Path to folder
 */
function removeDir(string $dir): void
{
    $it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $file) {
        if ($file->isDir()) {
            removeDir($file->getPathname());
        } else {
            removeFile($file->getPathname());
        }
    }

    if (rmdir($dir)) {
        echo "- Folder removed: $dir" . PHP_EOL;
    } else {
        echo "- Folder cannot be removed: $dir" . PHP_EOL;
        exit;
    }
}
