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
    var text = $(td).text(),
        num = parseFloat(text) * 100;
    $(td).text(makePrecise(num, 2) + '%');
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
