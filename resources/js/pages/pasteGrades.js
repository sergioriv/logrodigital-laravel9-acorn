/**
 *
 * PasteGrades
 *
 * Pages.Logro.Teacher.Subjects.Show page content scripts. Initialized from scripts.js file.
 *
 *
 */

class PasteGrades {
    constructor() {
        this.data = "";
        this.period = "";

        this._bindPaste();
        this._clickPaste();
    }

    _bindPaste() {
        const _this = this;
        $(".qualify-period").bind("paste", function (e) {

            _this.period = $(this).attr('id');

            document.getElementById(_this.period).reset();

            _this.data = e.originalEvent.clipboardData
                .getData("text")
                .replaceAll(",", ".")
                .replaceAll("\r", "");


            _this._pasteValues();
        });
    }

    _clickPaste() {
        const _this = this;

        const btnPaste = document.getElementById("clickPaste");
        btnPaste.addEventListener("click", async () => {

            _this.period = btnPaste.getAttribute('data-period-id');

            /* Se solicitarán permision de Portapapeles al usuario */
            navigator.clipboard
                .readText()
                .then((value) => _this._initPasteValues(value));
        });
    }

    _initPasteValues(values) {
        const _this = this;

        document.getElementById(_this.period).reset();

        _this.data = values.replaceAll(",", ".").replaceAll("\r", "");

        _this._pasteValues();
    }

    _pasteValues() {
        const _this = this;

        var rows = _this.data.split("\n");

        let i = 1;
        for (var y in rows) {
            var cells = rows[y].split("\t");

            for (var x in cells) {
                if (!isNaN(cells[x])) {
                    $("#" + _this.period + "-grade-" + i).val(cells[x]);
                }

                i++;
            }
        }
    }
}
