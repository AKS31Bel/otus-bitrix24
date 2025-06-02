BX.addCustomEvent('CrmCreateDealFromCompany', function () {
    console.log("START >> CrmCreateDealFromCompany");
    setTimeout(function() {
        console.log("START >> ", document.querySelector("#popup-window-titlebar-current > span"));
    if (!document.querySelector("#popup-window-titlebar-current > span")) return;
    document.querySelector("#popup-window-titlebar-current > span").innerHTML = 'Выберите тип';
    document.querySelector("#popup-window-content-current").innerHTML = `<table class="bx-crm-deal-category-selector-dialog" cellspacing="2" style="display: block;"><tbody><tr><td><label>Тип:</label></td><td><select><option value="0">Общая</option><option value="1">Заказчики111</option></select></td></tr></tbody></table>`;
    }, 500);
})


BX.addCustomEvent('BX.Main.Filter:blur', function(filterInstance) {
    console.log('Filter hidden: ', filterInstance);
});


// BX.addCustomEvent('onAjaxSuccess', BX.delegate(function(filterInstance) {
//     console.log('onAjaxSuccess: ', filterInstance, 'type', typeof filterInstance);
//     if(typeof filterInstance === 'object' && 'data' in filterInstance && 'categories' in filterInstance.data && typeof filterInstance.data.categories === 'object') {
//         console.log('categories ДО: ', filterInstance.data.categories);
//         filterInstance.data.categories = filterInstance.data.categories.filter(function( obj ) {
//             return obj.id !== 1;
//         });
//         console.log('categories ПОСЛЕ: ', filterInstance.data.categories);
//     }
//
// }));

BX.addCustomEvent('onAjaxSuccess', function(x1, x2, x3, x4) {
    console.log('onAjaxSuccess: ', x1, x2, x3, x4);


});