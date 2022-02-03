<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\helpers\Html;
use common\components\GorkoLeadApi;

class FormController extends Controller
{
    public function beforeAction($action){
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSend()
    {
        $payload = [];

        if(isset($_POST['name']))
            $payload['name'] = $_POST['name'];
        if(isset($_POST['phone']))
            $payload['phone'] = $_POST['phone'];
        if(isset($_POST['count']))
            $payload['guests'] = intval($_POST['count']);
        if(isset($_POST['date']))
            $payload['date'] = $_POST['date'];
        if(isset($_POST['venue_id']))
            $payload['venue_id'] = $_POST['venue_id'];
        $payload['details'] = '';
        if(isset($_POST['water']) || isset($_POST['tent']) || isset($_POST['country']) || isset($_POST['incity'])){
            $payload['details'] .= 'Клиент просит подобрать зал по условиям: ';
            if(isset($_POST['water']))
                $payload['details'] .= ' у воды; ';
            if(isset($_POST['tent']))
                $payload['details'] .= ' с шатром; ';
            if(isset($_POST['country']))
                $payload['details'] .= ' за городом; ';
            if(isset($_POST['incity']))
                $payload['details'] .= ' в черте города; ';
        }
        if(isset($_POST['type']) && $_POST['type'] == 'item')
            $payload['details'] .= ' Клиент сделал заявку на конкретный зал - '.$_POST['url'];
        if(isset($_POST['url']))
            $payload['details'] .= 'Заявка отправлена с '.$_POST['url'];

        $payload['event_type'] = "Wedding";
        $payload['city_id'] = 4400;

        $resp = GorkoLeadApi::send_lead('v.gorko.ru', 'svadbanaprirode', $payload);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $resp;
    }
}
