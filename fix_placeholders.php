<?php
$dir = __DIR__ . '/packages/Alamia/Admin/src/Resources/lang';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $original = $content;
        
        // Replacement 1: The powered-by description pattern
        $pattern = "/'description'\s*=>\s*['\"]Powered\s*by\s*:AlamiaConnect,\s*an\s*open-source\s*project\s*by\s*:webkul\.?['\"]/i";
        $replacement = "'description' => 'Powered by :alamia_connect, a project by :alamiasoft.'";
        $content = preg_replace($pattern, $replacement, $content);
        
        // General placeholder replacements just in case
        $content = str_replace(':AlamiaConnect', ':alamia_connect', $content);
        $content = str_replace(':webkul', ':alamiasoft', $content);
        
        if ($content !== $original) {
            file_put_contents($file->getRealPath(), $content);
            echo "Updated: " . $file->getRealPath() . PHP_EOL;
        }
    }
}
echo "Done." . PHP_EOL;
