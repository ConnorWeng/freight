function flagRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text();
    if (text == '1') {
        $(td).text('是');
    } else {
        $(td).text('否');
    }
}

function percentRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var $td = $(td),
        text = $td.text(),
        num = parseFloat(text) * 100;
    if (!isNaN(num)) {
        $td.text(makePrecise(num, 2) + '%');
    } else {
        $td.text('0%');
    }
}

function yellowPercentRenderer(instance, td, row, col, prop, value, cellProperties) {
    percentRenderer(instance, td, row, col, prop, value, cellProperties);
    $(td).css('background', 'yellow');
}

function redPercentRenderer(instance, td, row, col, prop, value, cellProperties) {
    percentRenderer(instance, td, row, col, prop, value, cellProperties);
    $(td).css({'background': 'red', 'color': 'white'});
}

function bluePercentRenderer(instance, td, row, col, prop, value, cellProperties) {
    percentRenderer(instance, td, row, col, prop, value, cellProperties);
    $(td).css({'background': 'blue', 'color': 'white'});
}

function currencyRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text(),
        $td = $(td);
    if (text == '0') {
        $td.text('RMB');
    } else {
        $td.text('USD');
    }
}

function loanTypeRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text(),
        $td = $(td);
    if (text == '0') {
        $td.text('贷款');
    } else {
        $td.text('银承');
    }
}

function loanFlagRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text(),
        $td = $(td);
    if (text == '0') {
        $td.text('未融资');
    } else {
        $td.text('已融资');
    }
}

function orderStatusRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text(),
        $td = $(td);
    if (text == '0') {
        $td.text('未执行');
    } else {
        $td.text('已执行');
    }
}

function radioRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    $(td).html('<input type="radio" name="czxx-radio" row="' + row + '" col="' + col + '" />');
    return td;
}

function inOutDateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var rowData = instance.getDataAtRow(row);
    if (rowData['IN_AMOUNT'] == '' || rowData['IN_AMOUNT'] == null) {
        $(td).text(rowData['OUT_DATE']);
    } else {
        $(td).text(rowData['IN_DATE']);
    }
}

function inOutTypeRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var rowData = instance.getDataAtRow(row);
    if (rowData['IN_AMOUNT'] == '' || rowData['IN_AMOUNT'] == null) {
        $(td).text('出库');
    } else {
        $(td).text('入库');
    }
}

function receiveAmountRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var rowData = instance.getDataAtRow(row);
    if (rowData['RECEIVE_TYPE'] == '0') {
        $(td).text(rowData['RECEIVE_AMOUNT']);
    } else {
        $(td).text('');
    }
}

function shouldReceiveAmountRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var rowData = instance.getDataAtRow(row);
    if (rowData['RECEIVE_TYPE'] == '1') {
        $(td).text(rowData['RECEIVE_AMOUNT']);
    } else {
        $(td).text('');
    }
}

function receiveRateRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var rowData = instance.getDataAtRow(row);
    if (rowData['OUT_AMOUNT'] != '') {
        var num = (parseFloat(rowData['RECEIVE_AMOUNT']) / parseFloat(rowData['OUT_AMOUNT'])) * 100;
        if (!isNaN(num)) {
            $(td).text(makePrecise(num, 2) + '%');
        } else {
            $(td).text('0%');
        }
    }
}

function makePrecise(num, precise) {
    var text = num + '',
        dotIndex = text.indexOf('.');
    if (dotIndex != -1) {
        return text.substr(0, dotIndex + precise + 1);
    } else {
        return text;
    }
}

function validateForm($form) {
    return $form.triggerHandler('submit.validation');
}

var validatorMessage = {
    lengthBadStart : '字段长度必须在 '
};
