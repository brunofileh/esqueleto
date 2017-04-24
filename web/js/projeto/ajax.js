/**
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
