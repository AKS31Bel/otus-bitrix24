<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @var CMain $APPLICATION
 */
$APPLICATION->SetTitle('Врачи');

use Bitrix\Main\Page\Asset;
use Models\Lists\DoctorsPropertyValuesTable as DoctorsTable;
use Models\Lists\ProcsPropertyValuesTable as ProcsTable;

Asset::getInstance()->addJs('/doctors/script.js');
Asset::getInstance()->addCss('/doctors/style.css');

$path = trim($_GET['path'], '/');
$action = $doctor_name = $fio = $spec_names = '';
$doctors = $doctor = $procedures_names = $procedures = $procedures_all = [];

if (!empty($path)) {
    $path_parts = explode('/', $path);
    if(sizeof($path_parts) < 3) {
        if(sizeof($path_parts) == 2 && $path_parts[0] == 'edit') {
            $action = 'edit';
            $doctor_name = $path_parts[1];
        } elseif(sizeof($path_parts) == 1 && in_array($path_parts[0], ['new', 'new-procedure'])) {
            $action = $path_parts[0];
        } else{
            $doctor_name = $path_parts[0];
        }
    }
}

//dump($path, $action, $doctor_name);

if(!empty($doctor_name)) {
    $doctor = DoctorsTable::query()
        ->setSelect([
            'ID'=>'IBLOCK_ELEMENT_ID',
            'NAME' => 'ELEMENT.NAME',
            'FNAME',
            'LNAME',
            'SNAME',
            'PROCEDUR_MULTI' => 'PROCEDUR_MULTI',
        ])
        ->where('NAME', $doctor_name)
        ->fetch();

    if(is_array($doctor)) {
        // Объединяем значения полей в одно поле "ФИО"
        $fio = $doctor['LNAME'] . ' ' . $doctor['FNAME'] . ' ' . $doctor['SNAME'];
        $APPLICATION->SetTitle('Врач: ' . $fio);

        // Процедуры
        if ($doctor['PROCEDUR_MULTI']) {

            $procedures = ProcsTable::query()
                ->setSelect([
                    'ID' => 'IBLOCK_ELEMENT_ID',
                    'NAME' => 'ELEMENT.NAME',
                ])
                ->where('ID', 'in', $doctor['PROCEDUR_MULTI'])
                ->fetchAll();

            $procedures_names = implode(', ', array_map(function ($val) {
                return mb_strtolower($val['NAME']);
            }, $procedures));
            //dump("Специализации врача: ".$fio." >> ".$procedures_names);
        }
    }

    //dump("Сведения о враче: ".$fio, $doctor);
}

if(empty($doctor_name) && empty($action)) {
    $doctors = DoctorsTable::getList([
        'select'=>[
            'ID'=>'IBLOCK_ELEMENT_ID',
            'NAME'=>'ELEMENT.NAME',
            'FNAME'=>'FNAME',
            'LNAME'=>'LNAME',
            'SNAME'=>'SNAME',
        ]
    ])->fetchAll();

    //dump("Список врачей", $doctors);
}

if($action == 'new-procedure') {
    if(isset($_POST['proc-submit'])){
        unset($_POST['proc-submit']);
        if(ProcsTable::add($_POST)) {
            header("Location: /doctors/");
            exit();
        } else{
            dump('Ошибка добавления процедуры');
        }
    }
}

if(in_array($action, ['new', 'edit'])) {
    if(isset($_POST['doctor-submit'])){
        unset($_POST['doctor-submit']);
        if($action == 'edit' && !empty($_POST['ID'])) {
            $ID = $_POST['ID'];
            unset($_POST['ID']);
            $_POST['IBLOCK_ELEMENT_ID'] = $ID;
            $procedures = $_POST['PROCEDUR_MULTI'];
            unset($_POST['PROCEDUR_MULTI']);
            //dump('ЗАПИСЬ изменений для врача: '.$fio, $_POST, $procedures);
            CIBlockElement::SetPropertyValues($ID, DoctorsTable::IBLOCK_ID, $procedures, 'PROCEDUR_MULTI');
            if(DoctorsTable::update($ID, $_POST)) {
                header("Location: /doctors/");
                exit();
            } else{
                dump('Ошибка записи данных о враче');
            }
        }

        if($action == 'new') {
            if(DoctorsTable::add($_POST)) {
                header("Location: /doctors/");
                exit();
            } else {
                dump('Ошибка добавления врача');
            }
        }
    }

    $procedures_all = ProcsTable::getList([
        'select'=>[
            'ID'=>'IBLOCK_ELEMENT_ID',
            'NAME'=>'ELEMENT.NAME',
        ]
    ])->fetchAll();
    //dump('Все процедуры', $procedures_all);
}

?>

<section class="doctors">
    <h1><a href="/doctors/">Врачи</a></h1>

    <?php if(empty($action)): ?>
    <div class="add-buttons">
        <?php if(empty($doctor_name)): ?>
        <a href="/doctors/new" class="ui-btn ui-btn-success">Добавить врача</a>
        <a href="/doctors/new-procedure" class="ui-btn ui-btn-success">Добавить процедуру</a>
        <?php else: ?>
            <a href="/doctors/edit/<?=$doctor_name?>" class="ui-btn ui-btn-success">Редактировать врача</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="cards-list">
        <?php foreach ($doctors as $doc): ?>
        <a class="card" href="/doctors/<?=$doc['NAME']?>">
            <div class="fio"><?=$doc['LNAME']?> <?=$doc['FNAME']?> <?=$doc['SNAME']?></div>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if(in_array($action, ['new', 'edit'])): ?>
    <form method="post">
        <h2 style="text-align: center">Данные врача</h2>
        <div class="doctor-add-form">
            <input type="text" name="NAME" placeholder="Имя латиницей" id="name" value="<?=$doctor['NAME']?>">
            <input type="text" name="FNAME" placeholder="Имя" id="fname" value="<?=$doctor['FNAME']?>">
            <input type="text" name="LNAME" placeholder="Фамилия" id="lname" value="<?=$doctor['LNAME']?>">
            <input type="text" name="SNAME" placeholder="Отчество" id="sname" value="<?=$doctor['SNAME']?>">
            <select name="PROCEDUR_MULTI[]" multiple>
                <?php foreach ($procedures_all as $proc): ?>
                    <?php if(!empty($doctor['PROCEDUR_MULTI'])): ?>
                        <option value="<?=$proc['ID']?>" <?=in_array($proc['ID'], $doctor['PROCEDUR_MULTI']) ? 'selected' : ''?>><?=$proc['NAME']?></option>
                    <?php else: ?>
                        <option value="<?=$proc['ID']?>"><?=$proc['NAME']?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="ID" value="<?=$doctor['ID']?>">
            <input type="submit" name="doctor-submit" value="Сохранить">
            <script>
                function set_name(){
                    let name = document.getElementById('lname').value + ' ' + document.getElementById('fname').value + ' ' + document.getElementById('sname').value;
                    document.getElementById('name').value = translit(name);
                }
                document.getElementById('fname').addEventListener('change',function(event) {
                    set_name();
                });
                document.getElementById('lname').addEventListener('change',function(event) {
                    set_name();
                });
                document.getElementById('sname').addEventListener('change',function(event) {
                    set_name();
                });
            </script>
        </div>
    </form>
    <?php endif; ?>

    <?php if(in_array($action, ['new-procedure'])): ?>
        <form method="post">
            <h2 style="text-align: center">Добавление процедуры</h2>
            <div class="doctor-add-form">
                <input type="text" name="NAME" placeholder="Название процедуры" >
                <input type="submit" name="proc-submit" value="Сохранить">
            </div>
        </form>
    <?php endif; ?>

    <?php if(empty($action) && !empty($fio)): ?>
    <h2 style="text-align: center"><?=$fio?></h2>
    <h3>Процедуры врача:</h3>
    <ul>
        <?php foreach ($procedures as $proc): ?>
            <li><?=$proc['NAME']?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</section>
