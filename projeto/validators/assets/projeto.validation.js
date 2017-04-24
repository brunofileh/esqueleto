
projeto = (typeof projeto == "undefined" || !projeto) ? {} : projeto;

projeto.validation = (function($) {
	var pub = {
		isEmpty: function(value) {
			return value === null || value === undefined || value == [] || value === '';
		},
		addMessage: function(messages, message, value) {
			messages.push(message.replace(/\{value\}/g, value));
		},
		telefone: function(value, messages, options) {
			if (options.skipOnEmpty && pub.isEmpty(value)) {
				return;
			}
			
			var va 		= value.replace(/[^0-9]/g, ''),
				v 		= va.substring(2),
				n 		= null,
				valid 	= true
			;
			
			/* verifica (xx) 1111-1111 | (xx) 2222-2222, etc. */
			for (var i=1; i<=9; i++) {
				n = i.toString().repeat(8);
				
				if (v == n || v == (n+i).toString()) {
					valid = false;
					break;
				}
			}

			/* calcula o tamanho */
			if (valid) {
				valid = !(va.length < 10 || va.length > 11);
			}

			if (!valid) {
				pub.addMessage(messages, options.message, value);
			}
		},
		cep: function(value, messages, options) {
			if (options.skipOnEmpty && pub.isEmpty(value)) {
				return;
			}
			
			var va 	= value.replace(/[^0-9]/g, '');
			
			/* calcula o tamanho */
			if ((va.length != 8)) {
				pub.addMessage(messages, options.message, value);
			}
		},
                cnpj: function(value, messages, options) {
			if (options.skipOnEmpty && pub.isEmpty(value)) {
				return;
			}
			var b = [6,5,4,3,2,9,8,7,6,5,4,3,2], c = value;
                        
                        if ((c = c.replace(/[^\d]/g, "").split("")).length != 14)
                            pub.addMessage(messages, options.message, value);;
                        for (var i = 0, n = 0; i < 12; n += c[i] * b[++i])
                            ;
                        if (c[12] != (((n %= 11) < 2) ? 0 : 11 - n))
                            pub.addMessage(messages, options.message, value);;
                        for (var i = 0, n = 0; i <= 12; n += c[i] * b[i++])
                            ;
                        if (c[13] != (((n %= 11) < 2) ? 0 : 11 - n))
                            pub.addMessage(messages, options.message, value);;
                    }
                
	};
	
	return pub;

})(jQuery);
