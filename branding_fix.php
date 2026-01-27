<?php
$dir = __DIR__ . '/packages/Alamia';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $ext = $file->getExtension();
        if (!in_array($ext, ['php', 'blade.php'])) continue;
        
        $path = $file->getRealPath();
        if (strpos($path, 'node_modules') !== false || strpos($path, 'vendor') !== false) continue;

        $content = file_get_contents($path);
        $original = $content;
        
        // 1. Replace placeholders strictly
        $content = str_replace(':webkul', ':alamiasoft', $content);
        $content = str_replace(':AlamiaConnect', ':alamia_connect', $content);
        $content = str_replace(':silvercrm', ':alamia_connect', $content);
        
        // 2. Replace "Webkul" ONLY IF it's in a translation file or it's a string in a view
        // For PHP files (translations), it's usually 'key' => 'Webkul' or similar
        // For Blade files, it's plain text or @lang
        if ($ext === 'php' && strpos($path, 'lang') !== false) {
            // Replace Webkul in values, not keys or code
            $content = preg_replace("/=>\s*['\"]([^'\"]*)Webkul([^'\"]*)['\"]/", "=> '$1Alamia$2'", $content);
            $content = str_replace('webkul.com', 'alamiasoft.com', $content);
        } elseif ($ext === 'php' && strpos($path, 'Config') !== false) {
             $content = str_replace('webkul.com', 'alamiasoft.com', $content);
             $content = str_replace('Webkul', 'Alamia', $content);
        }
        
        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Updated: $path" . PHP_EOL;
        }
    }
}
echo "Done." . PHP_EOL;
