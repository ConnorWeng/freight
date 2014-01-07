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

function radioRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    $(td).html('<input type="radio" name="czxx-radio" row="' + row + '" col="' + col + '" />');
    return td;
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
