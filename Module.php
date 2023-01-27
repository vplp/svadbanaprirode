<?php

namespace app\modules\svadbanaprirode;


use Yii;
use common\models\Subdomen;
/**
 * svadbanaprirode module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\svadbanaprirode\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {

        $noindex_global = false;
        foreach ($_GET as $key => $value) {
            if ($key != 'page' && $key != 'q') {
                $noindex_global = true;
            }
        }
        Yii::$app->params['noindex_global'] = $noindex_global;

        $subdomen_model = Subdomen::find()
            ->where(['alias' => ''])
            ->one();

        Yii::$app->params['subdomen_phone'] = $subdomen_model->phone;
        $subdomen_phone_pretty = null;
        if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{2})(\d{2})$/', $subdomen_model->phone,  $matches ) )
        {
            $subdomen_phone_pretty = '7-'.$matches[1].'-'.$matches[2].'-'. $matches[3].'-'. $matches[4];
        }
        Yii::$app->params['subdomen_phone_pretty'] = $subdomen_phone_pretty;
        //Yii::$app->setLayoutPath('@app/modules/svadbanaprirode/layouts');
        //Yii::$app->layout = 'svadbanaprirode';
        //$this->viewPath = '@app/modules/svadbanaprirode/views/';
        parent::init();
        //$this->viewPath = '@app/modules/svadbanaprirode/views/';


        // custom initialization code goes here
    }
}
