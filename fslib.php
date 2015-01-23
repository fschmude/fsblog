<?
/**
 * verschiedene Funktionen
 */

/**
 * print out variable content
 */
function fdebug($file, $line, $var, $title='', $bReplBlanks = false ) {
    $txt = '';
    $txt .= Date('Y-m-d H:i:s').' '.$title."\n";
    ob_start();
    if ($bReplBlanks) {
        $var = (string)$var;
        $var = str_replace("\n", 'n', $var );
        $var = str_replace(" ", '.', $var );
    }
    var_dump($var);
    $txt .= ob_get_contents();
    ob_end_clean();
    $txt .= "\n";
    
    $handle = fopen(LOG_FILE, "a");
    fwrite( $handle, $txt );
    fclose( $handle );
}

// debug a numeric array of rows as CSV
function f_debug_csv( $title, $a ) {
    $txt = '';
    $txt .= Date('Y-m-d H:i:s').' '.$title."\n";
    if (!count($a)) {
        $txt .= 'no entries.'."\n";
        
    } else {
        foreach ($a[0] as $key => $val) {
            $txt .=  $key.';';
        }
        $txt .= "\n";
        foreach ($a as $i => $row) {
            foreach ($row as $key => $val) {
                $txt .=  $val.';';
            }
            $txt .=  "\n";
        }
    }
    $handle = fopen(LOG_FILE, "a");
    fwrite($handle, $txt );
    fclose($handle );
}
