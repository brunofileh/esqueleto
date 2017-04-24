$(function () {
	
	function slug(cmpFrom, cmpTo) {
		
		var from = 'ãáàäéèëíïìóôõúùü';
			to   = 'aaaaeeeiiiooouuu';
			v = $.trim($(cmpFrom).val()), 
			n = ''
		;
		
		if (v.length > 0 && $(cmpTo).val().length == 0) {
			n = v.toLowerCase()
				.replace(/\s/g, '-')
				.replace(/[^a-z0-9-]+/g,'')
			;
			
			for (var i=0, l=from.length ; i<l ; i++) {
				n = n.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
			}
			
			$(cmpTo).val(n);
		}
	}

	$('#tabatributos-dsc_descricao').blur(function() {
		slug($(this), '#tabatributos-sgl_chave');
	});
});