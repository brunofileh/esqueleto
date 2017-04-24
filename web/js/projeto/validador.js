
(function (projeto, $, undefined) {

	// validadores
	projeto.validador = new (Class.extend({
		_regex: {
			integer: {
				expressao: /^[\s]*(([\d]{1,3}){1}(((\.[\d]{3})*)|(\d*))?)?$/,
				erro: 'Informe um número inteiro não negativo. Ex.: 15, 1.200, 1500'
			}
		},
		_fn: {
			min: function (intValor, intMin) {
				if (!_.isNumber(intValor)) {
					throw('[projeto.validador] Função min requer número, passado: ' + typeof(intValor));
				}
				return intValor >= intMin;
			}
		},
		validar: function (mxValor, mxTipoValidador) {
			var c, result = [];

			if (!_(mxTipoValidador).isArray()) {
				mxTipoValidador = [mxTipoValidador];
			}

			_(mxTipoValidador).each(function(strTipoValidar) {
				c = this._regex[strTipoValidar]
				if (_(c).isUndefined()) {
					throw('[projeto.validador] Referência "' + strTipoValidar + '" não encontrada.');
				}
				if(!c.expressao.test(mxValor)) {
					result.push({strTipoValidar:projeto.validador.regex[strTipoValidar].erro});
				}
			});

			return result;
		}
	}));

})(projeto, jQuery, 'undefined');