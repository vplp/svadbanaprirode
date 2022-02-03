<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
//use frontend\modules\svadbanaprirode\assets\AppAsset;

frontend\modules\svadbanaprirode\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?php //<meta name="robots" content="noindex, nofollow" />?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/img/svadbanaprirode.ico">
    <title><?php echo $this->title ?></title>
    <?php $this->head() ?>
    <?php if (isset($this->params['desc']) and !empty($this->params['desc'])) echo "<meta name='description' content='".$this->params['desc']."'>";?>
    <?php if (isset($this->params['canonical']) and !empty($this->params['canonical'])) echo "<link rel='canonical' href='".$this->params['canonical']."'>";?>
    <?php if (isset($this->params['kw']) and !empty($this->params['kw'])) echo "<meta name='keywords' content='".$this->params['kw']."'>";?>
    <?= Html::csrfMetaTags() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="main_wrap">
        
        <header>
            <div class="header_wrap">
                <a href="/" class="header_logo">
                    <div class="header_logo_img"></div>
                </a>
                <div class="header_menu">
                    <a class="header_menu_item <?if(!empty($this->params['menu']) and $this->params['menu'] == 'v-podmoskovie')echo '_active';?>" href="/catalog/v-podmoskovie/">За городом</a>
                    <a class="header_menu_item <?if(!empty($this->params['menu']) and $this->params['menu'] == 'v-moskve')echo '_active';?>" href="/catalog/v-moskve/">В Москве</a>
                    <a class="header_menu_item <?if(!empty($this->params['menu']) and $this->params['menu'] == 'v-sharte')echo '_active';?>" href="/catalog/v-sharte/">В шатре</a>
                    <a class="header_menu_item <?if(!empty($this->params['menu']) and $this->params['menu'] == 'y-vody')echo '_active';?>" href="/catalog/y-vody/">У воды</a>
                    <a class="header_menu_item <?if(!empty($this->params['menu']) and $this->params['menu'] == 'na-verande')echo '_active';?>" href="/catalog/na-verande/">На веранде</a>

                    <div class="header_menu_item_mobile header_phone_button" data-open-popup-form>
                        <p class="_link">Подберите мне зал для свадьбы</p>
                    </div>
                </div>
                <div class="header_phone">
                    <a href="tel:+79252382671"><p>7-925-238-26-71</p></a>
                    <div class="header_phone_button" data-open-popup-form>
                        <p class="_link">Подберите мне зал для свадьбы</p>
                    </div>
                </div>
                <div class="header_burger">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </header>

        <div class="content_wrap">
            <?= $content ?>
        </div>

        <footer>
            <div class="footer_wrap">
                <div class="footer_row">
                    <div class="footer_block _left">
                        <a href="/" class="footer_logo">
                            <div class="footer_logo_img"></div>
                        </a>
                        <div class="footer_info">
                            <p class="footer_copy">© <?php echo date("Y");?> Свадьба на природе</p>
                            <a href="/privacy/" target="_blank" class="footer_pc _link">Политика конфиденциальности</a>
                        </div>                        
                    </div>
                    <div class="footer_block _right">
                        <div class="footer_phone">
                            <a href="tel:+79252382671"><p>Тел.: 7-925-238-26-71</p></a>
                        </div>
                        <div class="footer_phone_button" data-open-popup-form>
                            <p class="_link">Подберите мне зал для свадьбы</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <div class="popup_wrap">

        <div class="popup_layout" data-close-popup></div>

        <div class="popup_form">
            <?=$this->render('//components/generic/form.twig')?>
        </div>

    </div>

<?php $this->endBody() ?>
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700&display=swap&subset=cyrillic" rel="stylesheet">
<!-- Yandex.Metrika counter -->
<noscript><div><img src="https://mc.yandex.ru/watch/64598434" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<!-- Global site tag (gtag.js) - Google Analytics -->
</body>
</html>
<?php $this->endPage() ?>
