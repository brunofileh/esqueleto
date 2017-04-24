
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
    init: function () {
        this.log('[projeto.init] Inicializando aplicação');
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
        this.log('[projeto.config] Configurando o toastr js');
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
        this.log('[projeto.config] Configurando o Chosen combobox');
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
});/**
 * Funções utilitárias de uso geral
 */
Projeto.prototype.util = new (Projeto.extend({
	jr: function (seletor) {
		return $(seletor).val();
	},
	jw: function (seletor, value) {
		$(seletor).val(value);
	},
	attrId: function (mxObj) {
		return Number(this._extractAttrVlr(mxObj)[0]);
	},
	attrVal: function (mxObj) {
		return String(this._extractAttrVlr(mxObj)[1]);
	},
	/**
	 * Extrai o par "id@sigla" dos campos que implementam a tabela tab_atributos_valores
	 * Aceita:
	 *			1. seletor jQuery. 	ex: #id | .classe
	 *			2. objeto jQuery. 	ex: $('#id')
	 *			3. objeto html. 	ex: <select>..</select>
	 *			4. string. 			ex: '22@DN'
	 */
	_extractAttrVlr: function (mxObj) {
		var r, m, s = '@';

		if (!mxObj) return '';

		if (_.isString(mxObj)) {
			// string 
			if (mxObj.indexOf(s) > 0) {
				r = mxObj.split(s);
			}
			// seletor jQuery
			else if ((s = $(mxObj)).length > 0) {
				r = s.val().split(s);
			}
			else {
				throw new Error('[projeto.util._extractAttrVlr] tipo do objeto "mxObj" não reconhecido como string válida.');
			}
		}
		// objeto jquery $(..)
		else if (mxObj instanceof jQuery) {
			r = mxObj.val().split(s);
		}
		// objeto javascript <select>..</select>
		else if (typeof mxObj == 'object') {
			r = $(mxObj).val().split(s);
		}
		else {
			throw new Error('[projeto.util._extractAttrVlr] tipo do objeto "mxObj" não reconhecido como objeto válido.');
		}

		return r;		
	},
    retiraFormatoMoeda: function (valor) {
        valor = (valor) ? valor.replace(/\./g, "").replace(",", ".") : 0;
        return valor;
	},
	colocaFormatoMoeda: function (n, c, d, t) {
        c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");		
	},
	scrollTo: function (el, top) {
		top = top || 60;
		var _el = typeof(el) == 'jQuery' ? el : $("#"+ el);
		if (_el.length > 0) {
			$("html, body").animate({scrollTop: _el.offset().top - top}, 2000);
		}
	},
	scrollTop: function () {
		$("html, body").animate({scrollTop: 0}, 2000);
	}
}));/**
 * Tratamento de conexões via ajax
 */
Projeto.prototype.ajax = new (Projeto.extend({
	
	intContadorReq: 0, // contador de requisições ajax

	init: function () {
		this.log('[projeto.ajax] ajax pronto para uso');
	},
	
	defaultBlockUI: function(){
		$.blockUI();
	},
	defaultUnblockUI: function(){
		$.unblockUI();
	},
	blockUI: null,
	unblockUI: null,
	
	fnAjaxFail: function (jqXhr, textStatus, errorThrown) {
		console && console.log('[projeto.ajax.fail] Falha na requisição [#'+ jqXhr.status +']: ' + errorThrown, null, 'error');
		var msg = 'Erro interno';
		if (typeof(jqXhr.responseJSON) !== 'undefined' && jqXhr.responseJSON.msg) {
			msg = jqXhr.responseJSON.msg;
		}
		if (typeof(projeto.ajax.unblockUI) === 'function') {
			projeto.ajax.unblockUI();
			projeto.ajax.unblockUI = null;
		}
		else {
			projeto.ajax.defaultUnblockUI();
		}
		sweetAlert('Houve um problema na requisição', msg, 'error');
	},

	fnAjaxAlways: function (mxDataOrJqXhr, textStatus, jqXhrOrErrorThrown) {
		var resposta = {
			mxDataOrJqXhr: mxDataOrJqXhr,
			textStatus: textStatus,
			jqXhrOrErrorThrown: jqXhrOrErrorThrown,
		};
		
		if (typeof(projeto.ajax.unblockUI) === 'function') {
			projeto.ajax.unblockUI();
			projeto.ajax.unblockUI = null;
		}
		else {
			projeto.ajax.defaultUnblockUI();
		}
		
		console && console.log(resposta, '[projeto.ajax.always] Resposta da requisição ['+ 
			mxDataOrJqXhr.projetoAjaxIntContadorReq +']');
	},

	fnAjaxDone: function (data, textStatus, jqXhr) {
		if (typeof(projeto.ajax.unblockUI) === 'function') {
			projeto.ajax.unblockUI();
			projeto.ajax.unblockUI = null;
		}
		else {
			projeto.ajax.defaultUnblockUI();
		}
	},

	/**
	 * Requisição genérica
	 */
	request: function (strUrl, objDados, fnCallback, strTipoRequisicao) {
		var me = this;
		// function(data) {}
		fnCallback = fnCallback || function() {};
		// par {chave: valor, ...}
		objDados = objDados || {};
		// POST | GET | JSON
		strTipoRequisicao = strTipoRequisicao || 'POST'; 

		/**
		 * Yii 2.0 CSRF validation for AJAX request
		 */
		 if ($.inArray(strTipoRequisicao.toUpperCase(), ['GET', 'POST', 'OPTIONS'])) {
			var csrfParam = $('meta[name="csrf-param"]').attr("content");
			var csrfToken = $('meta[name="csrf-token"]').attr("content");
			objDados[csrfParam] = csrfToken;
		 }

		 objDados.projetoAjaxIntContadorReq = ++this.intContadorReq;

		var jqXhr = $.ajax({
			type: strTipoRequisicao,
			url: strUrl,
			data: objDados,
			success: function(data, textStatus, jqXHR){
				if (typeof(projeto.ajax.unblockUI) === 'function') {
					projeto.ajax.unblockUI();
					projeto.ajax.unblockUI = null;
				}
				else {
					projeto.ajax.defaultUnblockUI();
				}
				
				if (typeof(jqXHR.responseJSON) !== 'undefined' && jqXHR.responseJSON.status == 'undefined') {
					$.error('Erro ao receber a resposta da requisição. "status" precisa estar presente na resposta.');
				}
				else if (typeof(jqXHR.responseJSON) !== 'undefined' && jqXHR.responseJSON.status == 'erro') {
					sweetAlert('Houve um problema na requisição', (jqXHR.responseJSON.msg || ''), 'error');
				}
				else {
					fnCallback(data, textStatus, jqXHR);
				}
			},
			cache: false,
			async: true,
			beforeSend: function(jqXhr) {
				if (typeof(projeto.ajax.blockUI) === 'function') {
					projeto.ajax.blockUI();
					projeto.ajax.blockUI = null;
				}
				else {
					projeto.ajax.defaultBlockUI();
				}
				
				// log das requisições
				var ac = this.intContadorReq,
					msg = '[projeto.ajax.request] ['+ ac +'] Requisição Ajax Efetuada: ' + strUrl
				;
				me.log(objDados || {}, msg);
			}
		})
		.then(function(dataJSON, textStatus, xhr) {
			if (strTipoRequisicao == 'POST' || _.isObject(dataJSON)) {
				if (dataJSON.status == 'erro') {
					return $.Deferred()
						.reject(xhr, dataJSON, dataJSON.msg)
						.promise();
				} 
			}
			return dataJSON;
		})
		.always(this.fnAjaxAlways)
		.fail(this.fnAjaxFail)
		.done(this.fnAjaxDone);

		return jqXhr;
	},
	post: function (strUrl, objDados, fnCallback) {
		
		if (_.isFunction(objDados)) {
			fnCallback = objDados;
			objDados = {};
		}
		else if (_.isUndefined(objDados)) {
			objDados = {};
		}
		
		return this.request(strUrl, objDados, fnCallback, 'POST');
	},
	get: function (strUrl, fnCallback) {
		return this.request(strUrl, {}, fnCallback, 'GET');
	}
}));
projeto = new Projeto();projeto.config();