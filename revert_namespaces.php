<?php
$dir = __DIR__ . '/packages/Alamia';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$namespaces = [
    'Activity', 'Admin', 'Attribute', 'Contact', 'Core', 'DataGrid', 'DataTransfer', 
    'Email', 'EmailTemplate', 'Marketing', 'Lead', 'Product', 'Quote', 'Tag', 
    'User', 'Warehouse', 'WebForm', 'Automation'
];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        $original = $content;
        
        foreach ($namespaces as $ns) {
            // Revert Alamia\NS to Webkul\NS if it's NOT one of the Alamia packages themselves
            // Alamia packages are: Admin, Installer, rest-api (RestApi)
            if (in_array($ns, ['Admin', 'Installer', 'RestApi'])) continue;
            
            $content = str_replace("Alamia\\$ns", "Webkul\\$ns", $content);
        }
        
        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Reverted namespaces in: $path" . PHP_EOL;
        }
    }
}
echo "Done." . PHP_EOL;
