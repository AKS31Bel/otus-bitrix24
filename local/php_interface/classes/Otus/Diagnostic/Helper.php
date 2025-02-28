<?php

namespace Otus\Diagnostic;

class Helper
{

    public static function writeToLog($data, $title = ''): bool
    {
        $file = $_SERVER["DOCUMENT_ROOT"] .'/logs/otus_my.log';
        $log = "\n------------------------\n";
        $log .= date("Y.m.d G:i:s")."\n";
        $log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
        $log .= print_r($data, 1);
        $log .= "\n------------------------\n";
        file_put_contents($file, $log, FILE_APPEND);
        return true;
    }
}