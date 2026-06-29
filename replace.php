<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('app/Views'));
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $orig = $content;
        
        // Text changes
        $content = str_replace('Cotización', 'Presupuesto', $content);
        $content = str_replace('cotización', 'presupuesto', $content);
        $content = str_replace('Cotizaciones', 'Presupuestos', $content);
        $content = str_replace('cotizaciones', 'presupuestos', $content);
        
        // Fix some variable names that might have been accidentally changed if we did case-insensitive,
        // but since we used exact case, $cotizacion became $presupuesto (which we don't want to break if models still use it, 
        // wait, we replaced 'cotización' with accent, so variables like $cotizacion are safe!
        // But what about 'cotizaciones'? variable $cotizaciones became $presupuestos. That WOULD break!
        // Let's only replace the words without '$' prefix.
        
        $content = preg_replace('/(?<!\$)\bCotización\b/', 'Presupuesto', $orig);
        $content = preg_replace('/(?<!\$)\bcotización\b/', 'presupuesto', $content);
        $content = preg_replace('/(?<!\$)\bCotizaciones\b/', 'Presupuestos', $content);
        // "cotizaciones" without accent is used in URLs and variables. 
        // Let's be careful. We mainly want to change visible text.
        // I will change it manually using replace_file_content for the most important ones to be perfectly safe.
    }
}
