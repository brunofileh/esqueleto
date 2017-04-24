<?php
	echo "<label class='control-label' for='tabusuariosprestadoressearch-cod_perfil_fk'>Perfil</label>";
	echo "<select id='tabusuariosprestadoressearch-cod_perfil_fk' class='form-control' name='TabUsuariosPrestadoresSearch[cod_perfil_fk][$cod_modulo]'>";
		echo "<option value=''>{$this->app->params['txt-prompt-select']}</option>";
		foreach ($arrPerfis as $key => $value) {
			echo "<option value='$key' $value[selected]>$value[value]</option>";
        }
	echo "</select>";
	echo "<div class='help-block'></div>";

	echo "<label class='control-label' for='tabusuariosprestadoressearch-cod_funcionalidade_fk'>Funcionalidades</label>";
	echo "<table class='table'>";
		$x=1;
		echo "<tr>";
		foreach ($arrFuncionalidades as $key => $value) {
			if ($x==3) {
				$x=1;
				echo "</tr>";
				echo "<tr>";
			}			
			echo "<td><input type='checkbox' name='TabUsuariosPrestadoresSearch[cod_funcionalidade_fk][$cod_modulo][]' value='$key' $value[checked]>&nbsp;&nbsp; $value[value]</td>";
			$x++;
		}		
		echo "</tr>";			
	echo "</table>";
	echo "<div class='help-block'></div>";
?>
