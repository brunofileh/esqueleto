<?php 

namespace projeto\swiftmailer;

use Yii;
use projeto\pdf\ModeloDoc;
use yii\helpers\Url;
use yii\helpers\FileHelper;

class Mailer extends \yii\swiftmailer\Mailer
{
	public $messageClass = 'projeto\swiftmailer\Message';
	public $remetente = '';
	
	public function init()
	{
		parent::init();
		/**
		 * @todo pegar o remetente dos parâmetros
		 */
		$this->remetente = 'snis.drenagem@cidades.gov.br';
	}
	
	public function emailFinalizarPreenchimento(array $to, array $cc=[])
	{
		$params = \Yii::$app->params;
		
		return $this->compose()
			->setFrom($this->remetente)
			->setTo($to)
			->setCC($cc)
			->setSubject("[SNIS] Finalização da Coleta de Dados {$params['ano-ref']}")
			->setHtmlBody(ModeloDoc::gerarEmail('email-finalizacao-coleta', [
				'ano-ref' => $params['ano-ref'],
				'data-extenso' => \projeto\Util::humanDate(),
			]))
			->send()
		;
	}
	
	public function registroNovoUsuario($model, $txtSenha)
	{
		return $this->compose()
			->setFrom($this->remetente)
			->setTo($model->txt_email)
			->setSubject('[SNIS] Registro de novo usuário')
			->setHtmlBody(ModeloDoc::gerarEmail('confirma-registro-novo-usuario', [
				'nome-usuario' => $model->txt_nome,
				'login-usuario' => $model->txt_login,
				'senha-usuario' => $txtSenha,
				'url-sistema' => Url::home(true),
			]))
			->send()
		;
	}
	
	public function alteracaoSituacaoContato($model)
	{
		return $this->compose()
			->setFrom($this->remetente)
			->setTo($model->txt_email)
			->setSubject('[SNIS] Alteração na situação do contato '.$model->cod_contato)
			->setHtmlBody(ModeloDoc::gerarEmail('email-alteracao-situacao-conta', [
				'nome-usuario' => $model->txt_nome,
				'cod_contato' => $model->cod_contato,
				'situacao' => \app\models\TabAtributosValoresSearch::findOneAsArray(["cod_atributos_valores" => ($model->cod_situacao_fk) ])['dsc_descricao'],
			]))
			->send()
		;
	}
	
	public function boasVindasNovoUsuario($model, $txtSenha)
	{
		return $this->compose()
			->setFrom($this->remetente)
			->setTo($model->txt_email)
			->setSubject('[SNIS] Registro de novo usuário - Águas Pluviais')
			->setHtmlBody(ModeloDoc::gerarEmail('boas-vindas-novo-usuario', [
				'nome-usuario' => $model->txt_nome,
				'nome-administrador' => \Yii::$app->user->identity->txt_nome,
				'login-usuario' => $model->txt_login,
				'senha-usuario' => $txtSenha,
				'url-sistema' => Url::home(true),
			]))
			->send()
		;
	}
	
	public function redefinirSenha($model)
	{
		return $this->compose()
			->setFrom($this->remetente)
			->setTo([$model->txt_email => $model->txt_nome])
			->setSubject('[SNIS] Solicitação de recuperação de senha')
			->setHtmlBody(ModeloDoc::gerarEmail('e-mail-recuperacao-senha-usr', [
				'nome-usuario' => $model->txt_nome,
				'link' => Url::to(['/recuperar-senha', 'token' => $model->cod_usuario], true),
			]))
			->send()
		;
	}
    
	public function reenviarSenha($model, $txtSenha)
	{
		return $this->compose()
			->setFrom($this->remetente)
			->setTo($model->txt_email)
			->setSubject('[SNIS] Reenvio de senha para usuário')
			->setHtmlBody(ModeloDoc::gerarEmail('reenvio-senha-usuario', [
				'nome-usuario' => $model->txt_nome,
				'login-usuario' => $model->txt_login,
				'senha-usuario' => $txtSenha,
				'url-sistema' => Url::home(true),
			]))
			->send()
		;
	}
    
    public function reenvioInicioColeta($contatos, $link)
	{
        $default = \Yii::$app->user->identity->txt_email; // e-mail usuario
        
        $to = $cc = [];
        if (YII_ENV == 'prod') {
            if ($contatos['txt_mandatario_email'])
                $to[] = $contatos['txt_mandatario_email']; // e-mail 1 prefeito
            if ($contatos['txt_email'])
                $to[] = $contatos['txt_email']; // e-mail 1 prefeitura
            if ($contatos['txt_mandatario_email2'])
                $cc[] = $contatos['txt_mandatario_email2']; // e-mail 2 prefeito
            if ($contatos['txt_email2'])
                $cc[] = $contatos['txt_email2']; // e-mail 2 prefeitura
            
            if (!$to)
                return false;
        } else {
            $to[] = $default;
        }
        
		$message = $this->compose()->setFrom($this->remetente);
        
        $message->setTo($to);
        $message->setCC($cc);
        
        $message->setSubject('[SNIS] Reenvio: Início da Coleta de Dados SNIS - Águas Pluviais');
        
        $message->setHtmlBody(ModeloDoc::gerarEmail('email-reenvio-inicio-coleta', [
            'txt_mandatario_nome' => $contatos['txt_mandatario_nome'],
            'link-inicio-coleta' => $link
        ]));
        
        $filename = Yii::getAlias("@projeto/oficios/Oficio-" . \Yii::$app->params['ano-ref'] . ".pdf");
        
        if (file_exists($filename)) {
            $message->attach($filename, ['fileName' => 'Oficio-' . \Yii::$app->params['ano-ref'] . '.pdf']);
        }
        
        return $message->send();
	}
}