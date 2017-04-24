<?php

use yii\helpers\Html;
use app\components\MenuLateralModuloWidget;
use yii\jui\Accordion;

$infoModulo	 = $this->context->module->info;
$this->title = $infoModulo['txt_nome'];

$this->params['breadcrumbs'][] = $this->title;
?>


<?php
echo yii\bootstrap\Collapse::widget( [
	'id'	 => 'box' ,
	'items' => [
		//DICA
		[
			'label'		 => "<i class='fa fa-info-circle'></i> Dicas de como proceder no Controle de Acesso" ,
			'content'	 =>
			['<p>
							O módulo é responsável pelo controle de acesso dos usuários. 
							Através dele é possível gerenciar todos os Módulos, os Perfis, os Menus, as Ações, as Funcionalidades
							, os Usuários (que podem ser classificados em MCidades e Prestadores, ou internos e externos). 
							Este módulo tem o objetivo de proporcionar um melhor gerenciamento dos responsáveis pelo sistema 
							sem a intervenção da equipe de Tecnologia da Informação e permitir total segurança das informações e facilitar a rastreabilidade das 
							ações realizadas.
						</p>'
			] ,
			'encode'	 => false,
			'contentOptions' => ['class' => 'in'] ,
		// open its content by default
		] ,
		
		//MODULO
		[
			'label'		 => "<h5>
<strong style='color: #DD4B39'>Módulos:</strong> Esta é a principal funcionalidade deste módulo, pois é por meio dela que as informações de perfis, menus, 
					ações, funcionalidades e usuários são vinculados.</h5>" ,
			'content'	 =>
			['<b><i>1 - Consultar:</i></b> Apresenta os módulos cadastrados com a opção de consulta. Para consultar 
								informe valor para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, 
								exibir, alterar, excluir, cadastrar perfil, cadastrar menu e cadastrar funcionalidade.' ,
				'<b><i>2 - Incluir e Alterar:</i></b> Informe valores para os campos obrigatórios e uma imagem que represente o módulo. 
								Esta imagem será salva e apresentada posteriormente na página inicial e no menu lateral direito para 
								possível seleção.' ,
				'<b><i>3 - Exibir:</i></b> Apresenta todas as informações cadastradas do módulo selecionado, data e usuário de inclusão, 
								data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.
		' ,
				'<b><i>4 - Excluir:</i></b> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma 
								confirmação. O usuário tem a opção de confirmar ou cancelar.'
			] ,
			'encode'	 => false,

		// open its content by default
		] ,
		//PERFIS
		[
			'label'			 => '<h5>
<strong style="color: #DD4B39">Perfis:</strong>
Esta funcionalidade está disponível pela funcionalidade de módulos, clicando no botão de cadastrar perfil na listagem dos módulos. Desta forma todos os perfis apresentados serão do módulo selecionado.</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta os perfis cadastrados com a opção de consulta. Para consultar informe valor 
								para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, 
								excluir, vincular usuários e vincular menus.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios.
						' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas do perfil selecionado, data e usuário de inclusão, 
								data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.
	' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
								de exclusão. O usuário tem a opção de confirmar ou cancelar.' ,
				'<b><i>5 - Vincular Usuários:</b></i> Por meio de um componente bastante intuitivo, o sistema disponibiliza do lado 
								esquerdo os usuários cadastrados no sistema. Para vincular os usuários ao perfil selecionado, faça uma 
								pesquisa e em seguida selecione os usuários que deseja vincular. Clique nas setas para vinculação. 
								Em seguida os usuários vinculados estarão disponíveis na listagem do lado direito. O usuário tem a 
								opção de vincular todos ou somente os selecionados, assim como desvincular todos ou somente os 
								selecionados.' ,
				'<b><i>6 - Vincular Menus:</b></i> Por meio de um componente bastante intuitivo, o sistema disponibiliza do lado 
								esquerdo os menus cadastrados para o módulo selecionado. Para vincular os menus ao perfil selecionado, 
								faça uma pesquisa e em seguida selecione os menus que deseja vincular. Clique nas setas para vinculação. 
								Em seguida os menus vinculados estarão disponíveis na listagem do lado direito. O usuário tem a opção de 
								vincular todos ou somente os selecionados, assim como desvincular todos ou somente os selecionados.'
			]
			,
			'encode'		 => false ,
			
			//'options'		 => [] ,
		] ,
		//MENUS
		[
			'label'			 => '<h5><strong style="color: #DD4B39">Menus:</strong>			
					Esta funcionalidade está disponível pela funcionalidade de módulos, clicando no botão de cadastrar menu. Desta forma todos os 
					menus apresentados são do módulo selecionado.</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta os menus cadastrados com a opção de consulta. Para consultar informe valor 
								para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, 
								excluir.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios. Nesta funcionalidade é possível cadastrar 
								um menu pai e seus filhos (Ex: Relatórios – menu pai, Menus por Módulo – menu filho) ou somente o menu 
								pai (Ex: Ações). Pensando em otimizar as funcionalidades deste módulo, o sistema permite a vinculação 
								do menu aos perfis previamente cadastrados.' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas do menu selecionado, data e usuário de inclusão, 
								data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
								de exclusão. O usuário tem a opção de confirmar ou cancelar.'
			] ,
			'contentOptions' => [] ,
			'encode'		 => false ,
			'options'		 => [] ,
			//'footer'		 => '' // the footer label in list-group
		] ,
		//ACAO
		[
			'label'			 => '<h5><strong style="color: #DD4B39">Ações:</strong>			
					Esta funcionalidade permite gerenciar as possíveis ações das funcionalidades do sistema. Chamamos aqui de ações: 
					consultar, incluir, alterar, excluir, vincular, etc. Esta funcionalidade está disponível no menu lateral 
					esquerdo independente dos módulos, pois uma ação poderá ser utilizada em todas as funcionalidades do sistema.</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta as ações cadastrados com a opção de consulta. Para consultar informe valor para 
								cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, excluir.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios.' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas da ação selecionada, data e usuário de inclusão, 
								data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
								de exclusão. O usuário tem a opção de confirmar ou cancelar.'
			] ,
			'contentOptions' => [] ,
			'encode'		 => false ,
			'options'		 => [] ,
			//'footer'		 => '' // the footer label in list-group
		] ,
		//FUNCIONALIDADE
		[
			'label'			 => '<h5><strong style="color: #DD4B39">Funcionalidades:</strong>			
					Esta funcionalidade está disponível pela funcionalidade de módulos, clicando no botão de cadastrar funcionalidade. Desta forma 
					todos as funcionalidades apresentados são do módulo selecionado.</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta as funcionalidades cadastradas com a opção de consulta. Para consultar informe 
								valor para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, 
								excluir.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios. Para estas ações é necessário 
								informar o menu no qual a funcionalidade estará disponível, quais ações tal funcionalidade irá ter e 
								os perfis que poderão acessá-la.' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas da funcionalidade selecionada, data e usuário de 
								inclusão, data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página 
								de consulta.' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
								de exclusão. O usuário tem a opção de confirmar ou cancelar.'
			] ,
			'contentOptions' => [] ,
			'encode'		 => false ,
			'options'		 => [] ,
			//'footer'		 => '' // the footer label in list-group
		] ,
		//Usuários (MCidades)
		[
			'label'			 => '<h5><strong style="color: #DD4B39">Usuários (MCidades):</strong>			
					Esta funcionalidade permite gerenciar os usuários do MCidades e suas restrições. Aqui são apresentados os usuários 
					administrativos do ministério ou usuários internos. Esta funcionalidade está disponível no menu lateral esquerdo independente dos 
					módulos, pois um usuário poderá ser vinculado a diversos módulos, com perfis e funcionalidades específicas.	</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta os usuários cadastrados com a opção de consulta. Para consultar informe valor 
								para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, 
								excluir e restringir acesso.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios. Ao confirmar a inclusão de um novo 
								o usuário, este recebe por e-mail os dados para acesso ao sistema, como: usuário, senha e link de acesso.' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas do usuário selecionado, data e usuário de inclusão, 
								data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
								de exclusão. O usuário tem a opção de confirmar ou cancelar.' ,
				'<b><i>5 - Restringir Acesso:</b></i> A restrição ocorre sempre para um usuário que está vinculado a um perfil, que por sua vez pertence a um módulo. 
								Contudo pode ocorrer de um determinado usuário não ter acesso a uma ação específica dentro de uma 
								funcionalidade. Assim, basta selecionar o módulo/perfil e informar as ações que devem ser restritas para 
								o usuário selecionado.'
			] ,
			'contentOptions' => [] ,
			'encode'		 => false ,
			'options'		 => [] ,
			//'footer'		 => '' // the footer label in list-group
		] ,
		//Usuários (Prestadores)
		[
			'label'			 => '<h5><strong style="color: #DD4B39">Usuários (Prestadores):</strong>
					Esta funcionalidade permite gerenciar os usuários que são prestadores ou usuários externos. 
					Esta funcionalidade está disponível no menu lateral esquerdo independente dos módulos, pois um usuário poderá 
					ser vinculado a diversos módulos, com perfis e funcionalidades específicas. Contudo, esta funcionalidade poderá 
					ser acessada somente pelos usuários que possuem o perfil de <i>"Administrador do Prestador"</i>.</h5>' ,
			'content'		 => [
				'<b><i>1 - Consultar:</b></i> Apresenta os usuários cadastrados com a opção de consulta. Para consultar informe valor 
								para cada coluna apresentada. Nesta página estão disponíveis as opções de incluir, exibir, alterar, 
				excluir.' ,
				'<b><i>2 - Incluir e Alterar:</b></i> Informe valores para os campos obrigatórios. Ao confirmar a inclusão de um novo 
				o usuário, este recebe por e-mail os dados para acesso ao sistema, como: usuário, senha e link de acesso. 
				O usuário sempre será vinculado a um prestador, este sendo um campo obrigatório. É necessário informar 
				para cada módulo, o perfil e as funcionalidade que o usuário terá acesso.' ,
				'<b><i>3 - Exibir:</b></i> Apresenta todas as informações cadastradas do usuário selecionado, data e usuário de inclusão, 
				data de alteração. Possibilita a alteração e exclusão do registro, além de voltar para página de consulta.' ,
				'<b><i>4 - Excluir:</b></i> Clique no botão de exclusão. Por questão de segurança, o sistema solicita uma confirmação 
				de exclusão. O usuário tem a opção de confirmar ou cancelar.'
			] ,
			'contentOptions' => [] ,
			'options'		 => [] ,
			'encode'		 => false ,
			//'footer'		 => '' // the footer label in list-group
		] ,
	]
] );
?>

