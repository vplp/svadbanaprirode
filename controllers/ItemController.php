<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Rooms;
use common\models\Seo;
use app\modules\svadbanaprirode\models\ItemSpecials;
use frontend\modules\svadbanaprirode\models\ElasticItems;
use common\components\Breadcrumbs;
use common\models\elastic\ItemsWidgetElastic;

class ItemController extends Controller
{

	public function actionIndex($id)
	{
		$elastic_model = new ElasticItems;
		$item = $elastic_model::get($id);

		$seo = new Seo('item', 1, 0, $item);
		$seo = $seo->seo;
        $this->setSeo($seo);

        $seo['h1'] = $item->name;
		$seo['breadcrumbs'] = Breadcrumbs::get_breadcrumbs(2);
		$seo['address'] = $item->restaurant_address;

		$special_obj = new ItemSpecials($item->restaurant_special);
		$item->restaurant_special = $special_obj->special_arr;


		$itemsWidget = new ItemsWidgetElastic;
		$other_rooms = $itemsWidget->getOther($item->restaurant_id, $id, $elastic_model);

		return $this->render('index.twig', array(
			'item' => $item,
			'queue_id' => $id,
			'seo' => $seo,
			'other_rooms' => $other_rooms
		));
	}

	private function setSeo($seo){
        $this->view->title = $seo['title'];
        $this->view->params['desc'] = $seo['description'];
        $this->view->params['kw'] = $seo['keywords'];
    }

}