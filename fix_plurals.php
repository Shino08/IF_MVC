<?php
function processDir($dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        if (pathinfo($file, PATHINFO_EXTENSION) !== 'php' && pathinfo($file, PATHINFO_EXTENSION) !== 'sql') continue;
        
        $content = file_get_contents($file);
        $original = $content;
        
        $content = str_replace('carritoes', 'carritos', $content);
        $content = str_replace('Carritoes', 'Carritos', $content);
        
        if ($content !== $original) {
            file_put_contents($file, $content);
        }
    }
}
processDir('app');
processDir('public');
echo "Done Fixing Plurals";
