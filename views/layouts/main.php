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
    <?php if(Yii::$app->params['noindex_global'] === true){
        echo '<meta name="robots" content="noindex" />';
    } ?>
    <?php if (isset($this->params['desc']) and !empty($this->params['desc'])) echo "<meta name='description' content='".$this->params['desc']."'>";?>
    <?php if (isset($this->params['canonical']) and !empty($this->params['canonical'])) echo "<link rel='canonical' href='".$this->params['canonical']."'>";?>
    <?php if (isset($this->params['kw']) and !empty($this->params['kw'])) echo "<meta name='keywords' content='".$this->params['kw']."'>";?>
    <script src="https://www.googleoptimize.com/optimize.js?id=OPT-W4RJZ95"></script>
    <?= Html::csrfMetaTags() ?>
</head>
<body>
<script type="text/javascript">
    var fired = false;
    const REAL_USER_EVENT_TRIGGERS = [
        'click',
        'scroll',
        'keypress',
        'wheel',
        'mousemove',
        'touchmove',
        'touchstart',
    ];
    REAL_USER_EVENT_TRIGGERS.forEach(event => {
        window.addEventListener(event, () => {
            if (fired === false) {
                fired = true;
                load_other();
            }
        });
    }, {passive: true});

    function load_other() {
        setTimeout(() => {
            (function (m, e, t, r, i, k, a) {
                m[i] = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(67719148, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true
            });

            var googletagmanager_js = document.createElement('script');
            googletagmanager_js.src = 'https://www.googletagmanager.com/gtag/js?id=UA-179040293-1';
            document.body.appendChild(googletagmanager_js);

        }, 500);
    }

    setTimeout(() => {
        fired = true;
        load_other();
        gtag('event', 'read', {'event_category': '15 seconds'});
    }, 15000);
</script>
<?php $this->beginBody() ?>

    <div class="main_wrap">
        
        <header>
            <div class="header_wrap">
                <div class="header_wrap_back hidden"></div>
                <div class="header_logo">
                    <a href="/<?= Yii::$app->params['subdomen'] ?>">
                        <div class="header_logo_img"></div>
                        <span class="header_logo_subtitle">Банкетная служба <?= Yii::$app->params['subdomen_rod'] ?></span>
                    </a>
                    <div class="city_choose">
                        <div class="city_choose_select">
                            <img src="/img/city_choose.svg" alt="Выберите город">
                            <span class="header_menu_item"
                                  data-city-block
                                  data-alias="<?= Yii::$app->params['subdomen'] ?>"
                                  data-id="<?= Yii::$app->params['subdomen_id'] ?>"
                                  data-baseid="<?= Yii::$app->params['subdomen_baseid'] ?>"
                            ><?= Yii::$app->params['subdomen_name'] ?></span>
                        </div>
                        <div class="all_cities hidden">
                            <div class="all_cities_polygon"></div>
                            <div class="all_cities_wrap">
                                <ul>
                                    <?php foreach (Yii::$app->params['subdomen_list'] as $subdomen): ?>
                                        <li>
                                            <?php if($subdomen['alias'] != 'msk'): ?>
                                                <a href="/<? echo $subdomen['alias'] ?>/" class="<?= $subdomen['name'] == Yii::$app->params['subdomen_name'] ? 'active_city' : '' ?>"><? echo $subdomen['name'] ?></a>
                                            <?php else: ?>
                                                <a href="/" class="<?= $subdomen['name'] == Yii::$app->params['subdomen_name'] ? 'active_city' : '' ?>"><? echo $subdomen['name'] ?></a>
                                            <?php endif;?>
                                        </li>
                                    <?php endforeach;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="header_menu">
                    <div class="header_menu_mobile_under hidden">
                        <div class="under_cities_wrap">
                            <div class="under_cities_back">
                                <img src="/img/widget_link_arrow.svg" alt="Назад">
                                <span>Назад</span>
                            </div>
                            <p>Выберите свой город:</p>
                            <ul>
                                <?php foreach (Yii::$app->params['subdomen_list'] as $subdomen): ?>
                                    <li>
                                        <?php if($subdomen['alias'] != 'msk'): ?>
                                            <a href="/<? echo $subdomen['alias'] ?>/" class="<?= $subdomen['name'] == Yii::$app->params['subdomen_name'] ? 'active_city_mobile' : '' ?>"><? echo $subdomen['name'] ?></a>
                                        <?php else: ?>
                                            <a href="/" class="<?= $subdomen['name'] == Yii::$app->params['subdomen_name'] ? 'active_city' : '' ?>"><? echo $subdomen['name'] ?></a>
                                        <?php endif;?>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>

                    <div class="header_menu_top">
                        <div class="city_choose">
                            <div class="city_choose_select">
                                <img src="/img/city_choose.svg" alt="Выберите город">
                                <span class="header_menu_item"
                                      data-city-block
                                      data-alias="<?= Yii::$app->params['subdomen'] ?>"
                                      data-id="<?= Yii::$app->params['subdomen_id'] ?>"
                                      data-baseid="<?= Yii::$app->params['subdomen_baseid'] ?>"
                                ><?= Yii::$app->params['subdomen_name'] ?></span>
                            </div>
                        </div>
                    </div>

                    <?php foreach (Yii::$app->params['menu'] as $item): ?>
                        <div class="header_menu_item_wrap">
                            <a class="header_menu_item <?= (!empty($this->params['menu']) and $this->params['menu'] == $item['link']) ? '_active' : '' ?>" href="/<?= Yii::$app->params['subdomen'] ?><?= $item['link'] ?>/"><?= $item['title'] ?></a>
                            <?php if(!empty($item['submenu'])): ?>
                                 <img src="/img/arrow_dropdown.svg" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="header_menu_item_mobile header_phone_button" data-open-popup-form>
                        <p class="_link">Подберите мне зал для свадьбы</p>
                    </div>
                </div>
                <div class="header_phone">
                    <a href="tel:<?=Yii::$app->params['subdomen_phone']?>"><p><?=Yii::$app->params['subdomen_phone_pretty']?></p></a>
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
                        <a href="/<?= Yii::$app->params['subdomen'] ?>" class="footer_logo">
                            <div class="footer_logo_img"></div>
                        </a>
                        <div class="footer_info">
                            <p class="footer_copy">© <?php echo date("Y");?> Свадьба на природе</p>
                            <a href="/privacy/" target="_blank" class="footer_pc _link">Политика конфиденциальности</a>
                        </div>                        
                    </div>
                    <div class="footer_block _right">
                        <div class="footer_phone">
                            <a href="tel:<?=Yii::$app->params['subdomen_phone']?>"><p><?=Yii::$app->params['subdomen_phone_pretty']?></p></a>
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
