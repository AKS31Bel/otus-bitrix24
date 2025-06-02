BX.addCustomEvent('onTimeManWindowOpen', function () {
    let btn = document.querySelector('#timeman-container');
    let popup = BX.PopupWindowManager.create("timeman-notify", btn, {
            content: "Хотите начать рабочий день?",
            autoHide: true,
            closeIcon: {
                right: "20px", top: "10px"
            },
            closeByEsc: true,
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок
            overlay: {
                // объект со стилями фона
                backgroundColor: 'black',
                opacity: 500
            },
            darkMode: false,
            events: {
                onPopupShow: function() {},
                onPopupClose: function() {btn.click();},
            },
            buttons: [
                new BX.PopupWindowButton({
                    text: "Начать рабочий день",
                    className: "popup-window-button-accept",
                    events: {
                        click: function(){
                            document.querySelector("td.tm-popup-timeman-layout-button > div > button").click();
                            this.popupWindow.close();
                        }
                    },
                }),
                new BX.PopupWindowButton({
                    text: "Отмена",
                    className: "webform-button-link-cancel",
                    events: {
                        click: function(){
                            this.popupWindow.close();
                        }
                    }
                }),
            ]
        }
    );
    popup.show();

})


BX.addCustomEvent('Crm.EntityProgress.onSaveBefore', function (value) {
    console.log("EntityProgress", value);
    return false
});