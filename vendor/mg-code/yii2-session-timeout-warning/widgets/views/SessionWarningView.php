<?php
use yii\helpers\Url;

/** @var \yii\web\View $this */
/** @var string|integer $userId */
/** @var array|string $extendUrl */
/** @var integer $warnBefore */
/** @var array|string $logoutUrl */
?>

    <div id="session-warning-modal" data-backdrop="false" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" data-warn-before="<?= $warnBefore; ?>" data-user-id="<?= $userId; ?>" data-extend-url="<?= Url::to($extendUrl); ?>">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"><i class="fa fa-hourglass-half"> </i> Alerta de inatividade</h3>
                </div>
                <div class="modal-body">
                    <h4 class="text-center"><span class="message"></span></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success continue"><i class="fa fa-refresh"> </i> Revalidar sessão</button>
                </div>
            </div>
        </div>
    </div>

<?php
\mgcode\sessionWarning\assets\SessionWarningAsset::register($this)
    ->initPlugin($this, [
        'logoutUrl' => $logoutUrl,
    ]);