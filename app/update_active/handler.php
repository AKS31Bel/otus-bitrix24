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


if(isset($_POST['event']) && isset($_POST['data'])) {
    $event = $_POST['event'];
    $data = $_POST['data'];

    switch($event) {
        case 'ONCRMTIMELINECOMMENTADD':
            writeToLog($data, 'ONCRMTIMELINECOMMENTADD');
            $id = $data['FIELDS']['ID'];
            $result = CRest::call(
                'crm.timeline.comment.get',
                [
                    'id' => $id
                ]
            );
            writeToLog($result, 'crm.timeline.comment.get');
            if($result['result']['ENTITY_TYPE'] == 'contact') {
                update_date_UF($result['result']['ENTITY_ID']);
            }
            break;

        case 'ONCRMCONTACTUPDATE':
            writeToLog($data, 'ONCRMCONTACTUPDATE');
            $id = $data['FIELDS']['ID'];
            update_date_UF($id);
            break;
    }
}

function update_date_UF($id) {

    $result = CRest::call('crm.contact.get',['ID' => $id]);
    writeToLog($result, "crm.contact.get <$id>");
    $dateString = $result['result']['UF_CONNECT_DATE']; // 2025-06-01T03:00:00+00:00
    $date = DateTime::createFromFormat('Y-m-d\TH:i:sP', $dateString);
    $now = new DateTime();
    $diff = $date->diff($now);
    writeToLog(['date' => $date, 'now' => $now], "Diff: ".$diff->format('%i'));
    if ($diff->format('%i') > 5) {
        // Разница больше 5 минут
        $result = CRest::call(
            'crm.contact.update',
            [
                'ID' => $id,
                'FIELDS' => [
                    'UF_CONNECT_DATE' => date('d.m.Y H:i:s'),
                ],
                'PARAMS' => [
                    'REGISTER_SONET_EVENT' => 'N',
                    'REGISTER_HISTORY_EVENT' => 'N',
                ]
            ]
        );
        writeToLog($result, "crm.contact.update <$id>");
    } else {
        writeToLog([], 'Разница меньше или равна 5 минут');
    }

    return $result;
}