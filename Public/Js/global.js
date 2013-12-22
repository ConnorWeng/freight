function flagRenderer(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.TextCell.renderer.apply(this, arguments);
    var text = $(td).text();
    if (text == '1') {
        $(td).text('是');
    } else {
        $(td).text('否');
    }
}
