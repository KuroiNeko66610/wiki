<?php
use kartik\widgets\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">--></span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>


    <nav class="navbar navbar-static-top" role="navigation">
           <div class="" style="float: left; position:relative; min-width: 75%; margin-top: 8px">
            <?php
            $template =
               '<div style="display: flex; justify-content: space-between">'.

                        '<div> <span style="">{{{title}}}</span>'.
                        '<span style="margin-left:20px; font-size: smaller; color: #777">{{{breadcrumbs}}}</span></div>'.
                        '<div><span>{{{tags}}}</span></div>'.
                '</div>';
          /*  $template =
                '<div style="float: left"><p class="repo-language">{{type}}</p>' .
                '<p class="repo-name">{{title}}</p>' .
                '</div>'.
                '<div><p>{{breadcrumbs}}</p>'.
                '<p>{{tags}}</p></div>'
            ;*/
            // Usage with ActiveForm and model (with search term highlighting)
            echo Typeahead::widget([
                'name' => 'search',
                'options' => ['placeholder' => 'Поиск ...', 'class' => 'header-search'],
                'pluginOptions' => ['highlight'=>true],
                'pluginEvents' => [
                    'typeahead:selected' => 'function(obj, datum, name) {loadQuickSearchResult(datum.controller,datum.id)}',
                ],
                'dataset' => [
                    [
                        'display' => 'Наименование',
                        'remote' => [
                            'url' => Url::to(['/search/quick']) . '&q=%QUERY',
                            'wildcard' => '%QUERY',
                            'rateLimitWait' => 1000,
                           'cache' => 'sessionStorage',
                            'ttl' => 3600000
                        ],
                        'limit' => 999,
                        'templates' => [
                            'notFound' => '<div class="text-danger" style="padding:0 8px">Ничего не найдено.</div>',
                            'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                        ]
                    ]
                ],
            ]);

            ?>
           </div>
        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu"><?= Html::a('Профиль', Url::to(['user/profile'])) ?></li>
                <li class="dropdown user user-menu">
                    <?= Html::a(Yii::$app->user->identity->username. ' (выход)', Url::to(['auth/logout']), ['data-method' => 'POST']) ?>
                </li>

            </ul>
        </div>
    </nav>
</header>

