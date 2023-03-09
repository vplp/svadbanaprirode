<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\helpers\Html;
use common\components\GorkoLeadApi;
use frontend\modules\svadbanaprirode\models\ElasticItems;

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

    public function actionRoom() {
        $elastic_model = new ElasticItems;
        $item = $elastic_model::find()
            ->query(['bool' => ['must' => ['match'=>['unique_id' => $_POST['room_id']]]]])
            ->limit(1)
            ->search();

        $item = $item['hits']['hits'][0];

//        echo '<pre>';
//        print_r($item);

        $to   = $_POST['book_email'];
        $from = Yii::$app->params['senderEmail'];
        $sub = 'Информация о месте для свадьбы - '.$item['restaurant_name'];

        $msg  = $this->renderPartial('//emails/roominfo.twig', array(
//            'url' => Yii::$app->params['subdomen_alias'] ? 'https://'.Yii::$app->params['subdomen_alias'].'.svadbanaprirode_dev.com/catalog/'.$_POST['room_id'].'/'  : 'http://svadbanaprirode_dev.com/catalog/'.$_POST['room_id'].'/',
            'url' => 'http://svadbanaprirode.com/catalog/'.$item['unique_id'].'/',
            'item' => $item,
//            'link' => Yii::$app->params['subdomen_alias'] ? 'https://'.Yii::$app->params['subdomen_alias'].'.svadbanaprirode_dev.com' : 'http://svadbanaprirode_dev.com'
        ));

        if (isset($_POST['book_email'])) {
            $message  = Yii::$app->mailer->compose()
                ->setFrom($from)
                ->setTo($to)
                ->setSubject($sub)
                ->setCharset('utf-8')
                ->setHtmlBody($msg.'.');

            if (count($_FILES) > 0) {
                foreach ($_FILES['files']['tmp_name'] as $k => $v) {
                    $message->attach($v, ['fileName' => $_FILES['files']['name'][$k]]);
                }
            }

            $message->send();
        }

        $payload = [];
        $payload['details'] = '';

        if(isset($_POST['type']))
            $payload['details'] .= ' Клиент сделал заявку на конкретный зал - '.$_POST['url'];
        if(isset($_POST['url']))
            $payload['details'] .= 'Заявка отправлена с '.$_POST['url'];
        if (isset($_POST['room_id']))
            $payload['room_id'] = $_POST['room_id'];
        if (isset($_POST['book_email']))
            $payload['email'] = $_POST['book_email'];

        $resp = GorkoLeadApi::send_lead('v.gorko.ru', 'svadbanaprirode', $payload);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $resp;
    }
}
