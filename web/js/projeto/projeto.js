
var Projeto = Class.extend({
    /** seletores jQuery | {fk_campo: '#fk_campo'} **/
    _s: {},
    _popUp:{},
    /** retorna um objeto jQuery via id do seletor **/
    s: function (idSeletor) {
        if (typeof (this._s[idSeletor]) == 'undefined') {
            throw 'Seletor não encontrado: ' + idSeletor;
        }
        return $(this._s[idSeletor]);
    },
   
    config: function () {
        //this._configToastr();
		this.stickyMenu();
        this._configChosen();
		$.blockUI.defaults['message'] = '<h1>Aguarde...</h1>';
    },
   alert: function (message, callback, iconType) {
        if (!iconType) {
            iconType = 'info'
        }

        swal({
            type: iconType,
            html: message,
            closeOnConfirm: true,
            allowOutsideClick: false,
            imageSize: '60x60'
        }, (callback || function () {
        }));
    },
    confirm: function (message, fnConfirm, fnCancel) {
        swal({
            title: 'Confirme',
            html: message,
            type: 'warning',
            showCancelButton: true,
            allowOutsideClick: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            confirmButtonClass: 'confirm-class',
            cancelButtonClass: 'cancel-class',
        }, function (isConfirm) {
            if (isConfirm) {
                $.isFunction(fnConfirm) && fnConfirm();
            } else {
                $.isFunction(fnCancel) && fnCancel();
            }
        });
    },
    _configToastr: function () {
       
        toastr = toastr || {};
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    },
    _configChosen: function () {
       
        $('.chosen-select').each(function (k, v) {
            var $chosen = $(v);
            $chosen.chosen({
                width: $chosen.attr('data-chosen-width') || '100%',
                allow_single_deselect: $chosen.attr('data-chosen-allow-single-deselect') || false
            });
        });
    },
    log: function (mxMsg, strID, strTipo) {

        if (_.isUndefined(console) || !_.isObject(console) || !console) {
            return false;
        }

        strTipo = strTipo || 'log';

        if (_.isUndefined(console[strTipo])) {
            throw('[projeto.log] Erro de referência: console.' + strTipo + '()');
        }

        if (strID) {
            console[strTipo](strID + ': ', mxMsg);
        } else {
            console[strTipo](mxMsg);
        }
    },
    toggleInfo: function (codInfo) {
        $('.hint-' + codInfo).toggle('fast', function () {
            var $this = $(this);
            var isOpened = $this.css('display') != 'none';

            if (isOpened) {
                $('.btn-info-' + codInfo).find('i')
                        .removeClass('fa-plus')
                        .addClass('fa-minus')
                        ;
            } else {
                $('.btn-info-' + codInfo).find('i')
                        .removeClass('fa-minus')
                        .addClass('fa-plus')
                        ;
            }
        });
    },
    /**
     * função que fixa o menu no topo quando rola o scroll
     */
    stickyMenu: function () {

            $sticky_nav         = $('div#box-cabecalho');
            // não está dentro dos forms da coleta
            if ($sticky_nav.length == 0) {
                    return;
            }

            $sticky_nav_offset  = $sticky_nav.offset();
            $sticky_nav_top     = $sticky_nav_offset.top;
            $sticky_nav_width   = $sticky_nav.width();

            var sticky_navigation = function() {
				var scroll_top = $(window).scrollTop();

				if (scroll_top >= $sticky_nav_top - 40) {
					$sticky_nav.css({
						'background-color' : '#ecf0f5', 
						'padding-top' : '2px', 
						'position'  : 'fixed', 
						'top'       : '50px', 
						'width'     : $sticky_nav_width,
						'z-index'   : 299
					});
				}
				else {
					$sticky_nav.css({
						'background-color' : '', 
						'padding-top' : '0', 
						'position'  : 'relative', 
						'top'       : '0'
					});
				}
            };

            sticky_navigation();

            $(window).scroll(function() {
				sticky_navigation();
            });
			
			$(window).resize(function() {
				$sticky_nav_width   = $('section.content-header').width();
				 $sticky_nav.css({
					'background-color' : '#ecf0f5', 
					'padding-top' : '2px', 
					'position'  : 'fixed', 
					'top'       : '50px', 
					'width'     : $sticky_nav_width,
					'z-index'   : 299
				});
            });
    },
    maskIt: function (w, e, m, r, a) {
        
        String.prototype.reverse = function () {
            return this.split('').reverse().join('');
        };


        // Cancela se o evento for Backspace
        if (!e)
            var e = window.event
        if (e.keyCode)
            code = e.keyCode;
        else if (e.which)
            code = e.which;
        // Variaveis da funcao
        var txt = (!r) ? w.value.replace(/[^\d]+/gi, '') : w.value.replace(/[^\d]+/gi, '').reverse();
        var mask = (!r) ? m : m.reverse();
        var pre = (a) ? a.pre : "";
        var pos = (a) ? a.pos : "";
        var ret = "";
        if (code == 9 || code == 8 || txt.length == mask.replace(/[^#]+/g, '').length)
            return false;
        // Loop na máscara para aplicar os caracteres
        for (var x = 0, y = 0, z = mask.length; x < z && y < txt.length; ) {
            if (mask.charAt(x) != '#') {
                ret += mask.charAt(x);
                x++;
            }
            else {
                ret += txt.charAt(y);
                y++;
                x++;
            }
        }
        // Retorno da função
        ret = (!r) ? ret : ret.reverse()
        w.value = pre + ret + pos;
    },
    showPopupContatos: function (urlPopUp) {
        if (typeof (this._popUp["ContatoPsv"]) == 'undefined' || this._popUp.ContatoPsv.closed) {

            this._popUp["ContatoPsv"] = window.open(urlPopUp, "ContatoPsv", "height=750,width=1100, scrollbars=yes,statusbar=no,resizable=no,toolbar=0");
        }
        else {

            this._popUp["ContatoPsv"].focus();
        }


    },
    closePopupContatos: function (opner) {

        if (typeof (this._popUp["ContatoPsv"]) != 'undefined' && this._popUp.ContatoPsv.closed){ 
          
            this._popUp["ContatoPsv"].parent.close();
        }
        else if (opner) {
            window.close(); 
        }
    },
});