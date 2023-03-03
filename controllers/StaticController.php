<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Pages;
use common\models\Seo;

class StaticController extends Controller
{

	public function actionPrivacy()
	{
		$page = Pages::find()
			->where([
				'type' => 'privacy',
			])
			->one();

		$seo = new Seo('privacy', 1);
        $this->setSeo($seo->seo);

		return $this->render('privacy.twig', [
			'page' => $page,
		]);
	}

	public function actionRobots()
	{
        header('Content-type: text/plain');
		return "
User-agent: *
Disallow: *district_code=*
Disallow: *gostey=*
Disallow: *area_type=*
Disallow: *y-vody=*
Disallow: *chek=*
Disallow: *na-kryshe=*
Disallow: *?keyword=*
Disallow: *?yhid=*
Disallow: *?from=*
Disallow: /samara/
Disallow: /kazan/
Disallow: /spb/
Disallow: /chelyabinsk/
Disallow: /ekaterinburg/
Disallow: /krasnodar/
Disallow: /ufa/
Disallow: /rostov/
Disallow: /nn/
Sitemap:  https://svadbanaprirode.com/sitemap/		
		";
		exit;
	}

	private function setSeo($seo){
        $this->view->title = $seo['title'];
        $this->view->params['desc'] = $seo['description'];
        $this->view->params['kw'] = $seo['keywords'];
    }
}