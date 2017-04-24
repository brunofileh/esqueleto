/**
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
}));