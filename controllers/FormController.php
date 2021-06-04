<?php
namespace app\modules\svadbanaprirode\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\helpers\Html;
use frontend\modules\svadbanaprirode\models\ElasticItems;
use app\modules\svadbanaprirode\models\ItemSpecials;

class FormController extends Controller
{
    //public function getViewPath()
    //{
    //    return Yii::getAlias('@app/modules/svadbanaprirode/views/site');
    //}

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionSend()
    {
        $messageApi = $this->sendApi($_POST);

        $log = file_get_contents('/var/www/pmnetwork/pmnetwork/log/manual.log');
        $log .= json_encode($messageApi);
        file_put_contents('/var/www/pmnetwork/pmnetwork/log/manual.log', $log);

        return json_encode($messageApi);

        //$to   = ['martynov@liderpoiska.ru', 'sharapova@liderpoiska.ru', 'sites@plusmedia.ru'];
        $to   = ['martynov@liderpoiska.ru', 'sharapova@liderpoiska.ru'];

        if($_POST['type'] == 'main'){
            $subj = "Заявка на выбор зала.";
        }
        else{
            $subj = "Заявка на бронирование зала.";
        }
        
        $msg  = "";

        $post_string_array = [
            'name'  => 'Имя',
            'phone' => 'Телефон',
            'date'  => 'Дата',
            'count' => 'Количество гостей',
            'url'   => 'Страница отправки' 
        ];

        $post_checkbox_array = [
            'water'  => 'у воды',
            'tent' => 'с шатром',
            'country'  => 'за городом',
            'incity' => 'в черте города',
        ];

        foreach ($post_string_array as $key => $value) {
            if(isset($_POST[$key]) && $_POST[$key] != ''){
                $msg .= $value.': '.$_POST[$key].'<br/>';
            }
        }   
        
        if($_POST['type'] == 'main'){
            $checkbox_msg = '';
            foreach ($post_checkbox_array as $key => $value) {
                if(isset($_POST[$key]) && $_POST[$key] != ''){
                    $checkbox_msg .= $value.', ';
                }
            }
            if($checkbox_msg != '')
                $msg .= 'Зал должен быть: <br/>'.$checkbox_msg;
        }
        

        $message = $this->sendMail($to,$subj,$msg);
        if ($message) {
            $responseMsg = empty($responseMsg) ? 'Успешно отправлено!' : $responseMsg;
            $resp = [
                'error' => 0,
                'msg' => $responseMsg,
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
            ];              
        } else {
            $resp = ['error'=>1, 'msg'=>'Ошибка'];//.serialize($_POST)
        }       
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $resp;
    }

    public function actionRoom()
    {
        $elastic_model = new ElasticItems;
        $item = $elastic_model::get($_POST['room_id']);
        $special_obj = new ItemSpecials($item->restaurant_special);
        $item->restaurant_special = $special_obj->special_arr;

        $to   = [$_POST['book_email']];
        $subj = "Информация о зале для свадьбы.";
        $msg  = $this->renderPartial('//emails/roominfo.twig', array(
            'url' => 'https://svadbanaprirode.com/catalog/'.$_POST['room_id'].'/',
            'item' => $item,
        ));



        $message = $this->sendMail($to,$subj,$msg);
        if ($message) {
            $responseMsg = empty($responseMsg) ? 'Успешно отправлено!' : $responseMsg;
            $resp = [
                'error' => 0,
                'msg' => $responseMsg,
            ];              
        } else {
            $resp = ['error'=>1, 'msg'=>'Ошибка'];//.serialize($_POST)
        }       
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $resp;
    }

    public function sendMail($to,$subj,$msg) {
        $message = Yii::$app->mailer->compose()
            ->setFrom(['post@smilerooms.ru' => 'Свадьба на природе.'])
            ->setTo($to)
            ->setSubject($subj)
            ->setCharset('utf-8')
            //->setTextBody('Plain text content')
            ->setHtmlBody($msg.'.');
        if (count($_FILES) > 0) {
            foreach ($_FILES['files']['tmp_name'] as $k => $v) {
                $message->attach($v, ['fileName' => $_FILES['files']['name'][$k]]);
            }
        }
        return $message->send();
    }

    public function sendApi($post) {
        $post_data = json_decode(json_encode($_POST), true);

        //$log = file_get_contents('/var/www/pmnetwork/pmnetwork/log/manual.log');
        //$log .= json_encode($post_data);
        //file_put_contents('/var/www/pmnetwork/pmnetwork/log/manual.log', $log);

        $payload = [];

        $payload['city_id'] = 4400;
        $payload['event_type'] = "Wedding";

        $details = '';

        foreach ($post_data as $key => $value) {
            switch ($key) {
                case 'date':
                    if($value)    
                        $payload['date'] = $newDate = date("Y.m.d", strtotime($value));
                    break;
                case 'name':
                    $payload['name'] = $value;
                    break;
                case 'phone':
                    $payload['phone'] = $value;
                    break;
                case 'count':
                    if($value)
                        $payload['guests'] = $value;
                    break;
                case 'venue_id':
                    $payload['venue_id'] = $value;
                    break;
                case 'room_name':
                    $details = 'Выбранный зал: '.$value;
                    break;
                case 'water':
                    $details .= ' У воды ';
                    break;
                case 'tent':
                    $details .= ' С шатром ';
                    break;
                case 'country':
                    $details .= ' За городом ';
                    break;
                case 'incity':
                    $details .= ' В черте города ';
                    break;

                default:
                    break;
            }
        }

        if($details != '')
            $payload['details'] = $details;

        //return json_encode([
        //    //'response' => $response,
        //    //'info' => $info,
        //    'payload' => $payload,
        //]);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://v.gorko.ru/api/svadbanaprirode/inquiry/put');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);



        return json_encode([
            'response' => $response,
            'info' => $info,
            'payload' => $payload,
        ]);
    }
}
