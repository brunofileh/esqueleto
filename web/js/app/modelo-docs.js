$(function () {
	
	$('#tabmodelodocs-tipo_modelo_documento_fk').change(function() {
		$('#tabmodelodocs-cabecalho_fk').prop('disabled', projeto.util.attrVal($(this).find('option:selected')) == 'tipo-modelo-documento-email');
		$('#tabmodelodocs-rodape_fk').prop('disabled', projeto.util.attrVal($(this).find('option:selected')) == 'tipo-modelo-documento-email');
	})
	.change();
	
});