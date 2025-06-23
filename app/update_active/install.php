<?php
require_once (__DIR__.'/crest.php');

function writeToLog($data, $title = ''): bool
{
    $file = $_SERVER["DOCUMENT_ROOT"] .'/app/update_active/info.log';
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s")."\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents($file, $log, FILE_APPEND);
    return true;
}

$result = CRest::installApp();
if($result['install'] == true){
    $result = CRest::call(
        'event.bind',
        [
            'event' => 'ONCRMCONTACTUPDATE',
            'handler' => 'https://otus.seo31.site/app/update_active/handler.php',
        ]
    );

    writeToLog($result, 'install event.bind ONCRMCONTACTUPDATE');

    $result = CRest::call(
        'event.bind',
        [
            'event' => 'ONCRMTIMELINECOMMENTADD',
            'handler' => 'https://otus.seo31.site/app/update_active/handler.php',
        ]
    );
    writeToLog($result, 'install event.bind ONCRMTIMELINECOMMENTADD');


}
