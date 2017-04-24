<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

use Yii;
use projeto\web\Controller;

class InicioController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
