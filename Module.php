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
        Yii::$app->params['subdomen_list'] = Subdomen::find()
            ->where(['active' => 1])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        //ПОЛУЧАЕМ ALIAS ГОРОДА
        $currentSubdomenAlias = '';
        $urlArray = explode('/', $_SERVER['REQUEST_URI']);
        $currentSubdomenAlias = (string)$urlArray[1];

        $subdomen_model = Subdomen::find()->where(['alias' => $currentSubdomenAlias])->one();

        //ПО УМОЛЧАНИЮ - МОСКВА
        if (empty($subdomen_model)) {
            $currentSubdomenAlias = 'msk';
            $subdomen_model = Subdomen::find()->where(['alias' => $currentSubdomenAlias])->one();
        }

        //ЗАПОЛНЯЕМ PARAMS
        Yii::$app->params['subdomen'] = ($currentSubdomenAlias == 'msk') ? '' : $currentSubdomenAlias . '/';
        Yii::$app->params['subdomen_id'] = $subdomen_model->city_id;
        Yii::$app->params['subdomen_alias'] = $subdomen_model->alias;
        Yii::$app->params['subdomen_baseid'] = $subdomen_model->id;
        Yii::$app->params['subdomen_name'] = $subdomen_model->name;
        Yii::$app->params['subdomen_dec'] = $subdomen_model->name_dec;
        Yii::$app->params['subdomen_rod'] = $subdomen_model->name_rod;
        Yii::$app->params['subdomen_phone'] = $subdomen_model->phone;

        Yii::$app->params['menu'] = [
            ['title' => 'За городом', 'link' => 'catalog/za-gorodom', 'submenu' => []],
            ['title' => 'В городе', 'link' => 'catalog/v-gorode', 'submenu' => []],
            ['title' => 'В шатре', 'link' => 'catalog/v-sharte', 'submenu' => []],
            ['title' => 'У воды', 'link' => 'catalog/y-vody', 'submenu' => []],
            ['title' => 'На веранде', 'link' => 'catalog/na-verande', 'submenu' => []],
        ];

        if (Yii::$app->params['subdomen_name'] == 'Москва') {
            Yii::$app->params['menu'][0]['link'] = 'catalog/v-podmoskovie';
            Yii::$app->params['menu'][1]['link'] = 'catalog/v-moskve';
        }

        $subdomen_phone_pretty = null;
        if (preg_match('/^\+\d(\d{3})(\d{3})(\d{2})(\d{2})$/', $subdomen_model->phone, $matches)) {
            $subdomen_phone_pretty = '7-' . $matches[1] . '-' . $matches[2] . '-' . $matches[3] . '-' . $matches[4];
        }
        Yii::$app->params['subdomen_phone_pretty'] = $subdomen_phone_pretty;
        //Yii::$app->setLayoutPath('@app/modules/svadbanaprirode/layouts');
        //Yii::$app->layout = 'svadbanaprirode';
        //$this->viewPath = '@app/modules/svadbanaprirode/views/';
        parent::init();
        //$this->viewPath = '@app/modules/svadbanaprirode/views/';

//        echo '<pre>';
//        print_r(Yii::$app->params);
//        die();

        // custom initialization code goes here
    }
}




