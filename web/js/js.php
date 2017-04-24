<?php

$filename = './projeto.js';

//if (!file_exists($filename)) {
	ob_start();
	
	include './projeto/projeto.js';
	include './projeto/util.js';
	// include './projeto/funcao.js';
	include './projeto/ajax.js';
	
	
	// include './projeto/validador.js';

	echo 'projeto = new Projeto();';
	echo 'projeto.config();';
	
	$contents = ob_get_clean();
	file_put_contents($filename, $contents);
//}

readfile($filename);