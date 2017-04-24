
(function (projeto, $, undefined) {
	/**
	 * Objeto global do sistema
	 */
	projeto = window.projeto || {};
	/**
	 * Declaração de funções
	 */
	 projeto.fn = {};
	 projeto.fn._funcoes = {};
	 projeto.fn._modulos = ['sis', 'gestao', 'drenagem'];

	 projeto.fn._extractFuncNamespace = function (strFuncNome) {
	 	var arrNome = strFuncNome.split('.');
	 	if (arrNome.lenght == 1) {
	 		throw('Referência "namespace" não encontrada no nome da função: ' + arrNome);
	 	}
	 	return {
	 		modulo: arrNome[0],
	 		funcao: arrNome[1]
	 	};
	 };
	 
	 projeto.fn.registrar = function (strFuncNome, fnFuncao) {
	 	var arrNome = projeto.fn._extractFuncNamespace(strFuncNome),
	 		strModulo = arrNome[0],
	 		strNome = arrNome[1]
	 	;
	 	
	 	if (typeof(projeto.fn._funcoes[strModulo]) == undefined) {
	 		projeto.fn._funcoes[strModulo] = {};
	 	}

	 	projeto.fn._funcoes[strModulo][strNome] = fnFuncao;
	 	return projeto.fn;
	 };

	 projeto.fn.x = function (strFuncNome) {
	 	var arrNome = projeto.fn._extractFuncNamespace(strFuncNome),
	 		strModulo = arrNome[0],
	 		strNome = arrNome[1],
	 		arrArguments = $.map(arguments, function (v) {
	 			return v;
	 		})
	 	;
		arrArguments.shift();
	 	return projeto.fn._funcoes[strModulo][strNome].apply(projeto, arrArguments);
	 }
	 
	 projeto.fn.get = function (strFuncName) {
	 	var arrNome = projeto.fn._extractFuncNamespace(strFuncNome);
	 };

	 projeto.fn.registrar('sis.hello', function(world) {
	 	this.log('hello ' + world);
	 });//.x('sis.hello', 'world');

	 //projeto.fn.x('sis.hello', 'yada');

})(projeto, jQuery);