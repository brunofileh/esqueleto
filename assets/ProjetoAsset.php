<?php

namespace app\assets;

/**
 * Conjunto mínimo requerido de assets da aplicação
 */
class ProjetoAsset extends Base
{
	public $css = [
		/** EXTENSÕES PRIMEIRO **/
		'css/adminlte/_all-skins_projeto.css' ,
		'js/3rd/chosen/chosen.min.css',
		'js/3rd/swal/sweetalert.css',
		
		/** DEPOIS **/
		'css/projeto.css',
	];
	public $js = [
		/** EXTENSÕES PRIMEIRO **/
		'js/3rd/class.js',
		'js/3rd/underscore.min.js',
		'js/3rd/jquery.blockui.js',
		'js/3rd/chosen/chosen.jquery.min.js',
		'js/3rd/slimScroll/jquery.slimscroll.min.js',
		'js/3rd/swal/sweetalert.min.js',
		'js/3rd/js.cookie.js',
		'js/projeto/yii.js',
		
		/** DEPOIS **/
		'js/js.php', // @todo compilar e minificar os scripts via ferramenta de build
	];

	public $depends = [
		'yii\web\YiiAsset',
		'dmstr\web\AdminLteAsset',
	];
}
