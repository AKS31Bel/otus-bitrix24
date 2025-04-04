<?php

namespace UserTypes;

use CJSCore;

class IPopupWrite
{
    public static function GetUserTypeDescription()
    {
        return [
            'PROPERTY_TYPE'         => 'S', // тип поля
            'USER_TYPE'             => 'iblock_popup_write', // код типа пользовательского свойства
            'DESCRIPTION'           => 'Запись процедуры', // название типа пользовательского свойства
            'GetPropertyFieldHtml'  => [self::class, 'GetPropertyFieldHtml'], // метод отображения свойства
            'GetPublicEditHTML'     => [self::class, 'GetPropertyFieldHtml'], // метод отображения значения в форме редактирования
            'GetPublicViewHTML'     => [self::class, 'GetPublicViewHTML'], // метод отображения значения
        ];
    }

    private static function GetInfoProcedure($arValue)
    {
        if (!$arValue['VALUE']){
            $element_id = $arValue['ELEMENT_ID'];
            $idblock_id = $arValue['IBLOCK_ID'];
            $arFilter = ['IBLOCK_ID' => $idblock_id, 'ID' => $element_id];
            $doctor = \Models\Lists\DoctorsPropertyValuesTable::query()
                ->setSelect([
                    'ID'=>'IBLOCK_ELEMENT_ID',
                    'NAME' => 'ELEMENT.NAME',
                    'FNAME',
                    'LNAME',
                    'SNAME',
                    'PROCEDUR_MULTI' => 'PROCEDUR_MULTI',
                ])
                ->where('ID', $element_id)
                ->fetch();

            $procedures_all = \Models\Lists\ProcsPropertyValuesTable::getList([
                'select'=>['ID'=>'IBLOCK_ELEMENT_ID', 'NAME'=>'ELEMENT.NAME'],
                'filter'=>['ID'=> $doctor['PROCEDUR_MULTI']]
            ])->fetchAll();
            CJSCore::Init(['popup']);
            ob_start(); ?>

<script>
    function setDoctorId(proc_id, doc_id){
        console.log("START", proc_id, doc_id);
        BX.PopupWindowManager.create("popup-message", null, {
            content:
                '<div class="ui-ctl ui-ctl-textbox">' +
                    '<div class="ui-ctl ui-ctl-textbox">' +
                        '<input type="text" class="ui-ctl-element" placeholder="Имя пациента" id="fio'+doc_id+'">' +
                    '</div>' +
                    '<div class="ui-ctl ui-ctl-textbox" style="margin: 15px 0px;"> ' +
                        '<div class="ui-ctl-after ui-ctl-icon-calendar"></div>' +
                        '<input type="text" value="01.04.2024 15:15:00" class="ui-ctl-element" id="date'+doc_id+'" type="date" onclick="BX.calendar({node: this, field: this, bTime: true});">' +
                    '</div>' +
                    '<input type="hidden" value="'+doc_id+'" name="doctor_id">' +
                    '<input type="hidden" value="'+proc_id+'" name="proc_id">' +
                '</div>',
            width: 400, // ширина окна
            height: 290, // высота окна
            zIndex: 100, // z-index
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },
            titleBar: 'Бронирование процедуры',
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: false, // закрытие при клике вне окна
            draggable: true, // можно двигать или нет
            resizable: true, // можно ресайзить
            min_height: 400, // минимальная высота окна
            min_width: 290, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок
            overlay: {
                // объект со стилями фона
                backgroundColor: 'black',
                opacity: 500
            },
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Записать', // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                        click: function() {
                            console.log("save-btn", {'doctor_id': doc_id, 'proc_id': proc_id, 'fio': document.getElementById("fio"+doc_id).value, 'date': document.getElementById("date"+doc_id).value});
                            BX.ajax({
                                url: '/app/IPopupWrite/save.php', // файл на который идет запрос
                                method: 'POST', // метод запроса GET/POST
                                // параметры передаваемый запросом
                                data: {
                                    doctor_id: doc_id, proc_id: proc_id, fio: document.getElementById("fio"+doc_id).value, date: document.getElementById("date"+doc_id).value
                                },
                                // ответ сервера лежит в data
                                onsuccess: function(data) {
                                    let result = JSON.parse(data);
                                    if (result.error == true){
                                        alert(result.message);
                                    }else{
                                        window.location.href = result.message;
                                    }
                                }
                            })
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: 'Закрыть',
                    id: 'copy-btn',
                    className: 'ui-btn ui-btn-primary',
                    events: {
                        click: function() {
                            this.popupWindow.close();
                        }
                    }
                })
            ],
            events: {
                onPopupShow: function() {
                    // Событие при показе окна
                },
                onPopupClose: function() {
                    // Событие при закрытии окна
                }
            }
        }).show();
    }

</script>

            <?php
            $txt = ob_get_contents();
            foreach ($procedures_all as $proc){
                $txt .= '<a onclick="setDoctorId('.$proc['ID'].', '.$doctor['ID'].')" href="javascript:void(0);">'.$proc['NAME'] . '</a><br>';
            }
            $arValue['VALUE'] = $txt;
        }

        return $arValue['VALUE'];

    }

    public static function GetPublicViewHTML($arProperty, $arValue, $strHTMLControlName)
    {
        $strResult = self::GetInfoProcedure($arValue);
        return $strResult;
    }

    public static function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
    {
        //dump($arProperty, $arValue, $strHTMLControlName);
        $value = self::GetInfoProcedure($arValue);
        $strResult = 'Это техническое поле, берет данные от "Процедуры"';
        return $strResult;
    }
}

