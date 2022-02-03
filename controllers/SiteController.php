<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\modules\svadbanaprirode\models\ElasticItems;
use common\widgets\FilterWidget;
use common\models\elastic\ItemsWidgetElastic;
use common\models\Seo;
use common\models\Filter;
use common\models\Slices;
use common\models\RoomsModule;
use common\models\RoomsUniqueId;
use backend\models\Pages;
use common\models\siteobject\SiteObjectSeo;

class SiteController extends Controller
{
    //public function getViewPath()
    //{
    //    return Yii::getAlias('@app/modules/svadbanaprirode/views/site');
    //}

    public function actionIndex()
    {
       //$rooms_mod = RoomsModule::find()->all();
       //foreach ($rooms_mod as $key => $value) {
       //    $room_uid = new RoomsUniqueId();
       //    $room_uid->id = $value->id;
       //    $room_uid->unique_id = $value->unique_id;
       //    $room_uid->save();
       //}

        $elastic_model = new ElasticItems;

        $filter_model = Filter::find()->with('items')->all();
        $slices_model = Slices::find()->all();

        $itemsWidget = new ItemsWidgetElastic;
        $apiMain = $itemsWidget->getMain($filter_model, $slices_model, 'rooms', $elastic_model);

        $seo = new Seo('index', 1, $apiMain['total']);
        $this->setSeo($seo->seo);

        $filter = FilterWidget::widget([
            'filter_active' => [],
            'filter_model' => $filter_model
        ]);

        return $this->render('index.twig', [
            'filter' => $filter,
            'widgets' => $apiMain['widgets'],
            'count' => $apiMain['total'],
            'seo' => $seo->seo,
        ]);
    }

    public function actionError()
    {
        $elastic_model = new ElasticItems;

        $filter_model = Filter::find()->with('items')->all();
        $slices_model = Slices::find()->all();

        $itemsWidget = new ItemsWidgetElastic;
        $apiMain = $itemsWidget->getMain($filter_model, $slices_model, 'rooms', $elastic_model);

        $seo = new Seo('error', 1, 0);
        $this->setSeo($seo->seo);

        $filter = FilterWidget::widget([
            'filter_active' => [],
            'filter_model' => $filter_model
        ]);

        return $this->render('error.twig', [
            'filter' => $filter,
            'widgets' => $apiMain['widgets'],
            'count' => $apiMain['total'],
            'seo' => $seo->seo,
        ]);
    }

    private function setSeo($seo){
        $this->view->title = $seo['title'];
        $this->view->params['desc'] = $seo['description'];
        $this->view->params['kw'] = $seo['keywords'];
    }
}
