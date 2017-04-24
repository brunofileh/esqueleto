Projeto.prototype.iniciar_coleta = new (Projeto.extend({
    init: function () {

        this.mudaBotaoImportacao();
        this.validaAjaxImportacao();

    },
    mudaBotaoImportacao: function () {
        $("#tabmunicipiospopulacoes-file").change(function () {
            $("#importacao").hide();
            $("form .btn.btn-success").show();
            $("form .btn.btn-primary").hide();
        });
    },
    validaAjaxImportacao: function () {
        $("form .btn.btn-success, form .btn.btn-primary").click(function () {
            setTimeout(function () {
                var form = $("form");
                if (!form.find(".has-error").length) {
                    projeto.ajax.defaultBlockUI();
                }
            }, 300);
        });
    },
}));
