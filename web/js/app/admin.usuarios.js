Projeto.prototype.usuarios = new (Projeto.extend({
    init: function () {
        this.getPerfil();
        this.adicionaPerfil();
        this.verificaCpf();

    },
    getPerfil: function () {
        $('#tabusuariossearch-cod_modulo').change(function ( ) {
            if ($('#tabusuariossearch-cod_modulo').val()) {
                var urlInclusao = $('base').attr('href') + 'admin/perfis/busca-perfis-modulo';

                var selecao = {dados: $('#tabusuariossearch-cod_modulo').val()};


                projeto.ajax.post(urlInclusao, selecao, function (response) {
                    $('#tabusuariossearch-cod_perfil').html(response);
                });
            } else {
                $('#tabusuariossearch-cod_perfil').val(null);
            }

        });
    },
    verificaCpf: function () {
        $('#tabusuariossearch-num_cpf').blur(function ( ) {

            if ($('#tabusuariossearch-num_cpf').val() && ($('#tabusuariossearch-num_cpf').val().replace('_', '').length == 14)) {
                var urlInclusao = $('base').attr('href') + 'admin/usuarios/verifica-cpf';

                var selecao = {dados: $('#tabusuariossearch-num_cpf').val()};

                projeto.ajax.post(urlInclusao, selecao, function (response) {

                    setTimeout(function () {
                    
                        if (response != 0) {

                            if (!$('.field-tabusuariossearch-num_cpf').hasClass("has-error")) {
                                $('.field-tabusuariossearch-num_cpf').removeClass("has-success");
                                $('.field-tabusuariossearch-num_cpf').addClass("has-error");
                                $('.field-tabusuariossearch-num_cpf .help-block').html('CPF "'+$('#tabusuariossearch-num_cpf').val()+'" já foi utilizado.');
                            } else {

                                $('.field-tabusuariossearch-num_cpf .help-block').html('CPF "'+$('#tabusuariossearch-num_cpf').val()+'" já foi utilizado.');
                            }
                        }
                    }, 600);
                });
            }

        });
    },
    adicionaPerfil: function () {
        $('#adicionarPerfil').click(function ( ) {

            var urlInclusao = $('base').attr('href') + 'admin/perfis/adiciona-perfis-modulo';

            var selecao = {cod_modulo: $('#tabusuariossearch-cod_modulo').val(), cod_perfil: $('#tabusuariossearch-cod_perfil').val()};

            projeto.ajax.post(urlInclusao, selecao, function (response) {
                $('#tabusuariossearch-cod_modulo').val(null);
                $('#tabusuariossearch-cod_perfil').html('<option value=""> -- selecione -- </option>');
                $('#tabusuariossearch-cod_perfil').val(null);
                var dados = $.parseJSON(response);
                $('#grid-perfil-modulos').html(dados.form);
            });

        });
    },
}));
