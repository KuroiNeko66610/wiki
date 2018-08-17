<aside class="main-sidebar">

    <section class="sidebar">

            <?= dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                    'items' => [
                        [
                            'label' => 'Личный раздел',
                            'icon' => 'home',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Избранное', 'icon' => 'none', 'url' => ['personal/favorites'],],
                                ['label' => 'Свои статьи', 'icon' => 'none', 'url' => ['personal/index'],],
                                ['label' => 'Черновики', 'icon' => 'none', 'url' => ['personal/drafts'],],
                            ],
                        ],
                        [
                            'label' => 'Модерация',
                            'icon' => 'bell-o',
                            'items' => [
                                ['label' => 'Статьи', 'url' => ['moderator/index'], 'icon' => 'none','visible' => Yii::$app->user->can('moderator')],
                                ['label' => 'Дополнения', 'url' => ['correction/index'], 'icon' => 'none', 'visible' => Yii::$app->user->can('moderator')],
                            ],
                        ],

                        ['label' => 'Пользователи', 'url' => ['user/index'], 'icon' => 'user', 'visible' => Yii::$app->user->can('admin')],
                        ['label' => 'Категории', 'url' => ['category/index'], 'icon' => 'folder-open-o', 'visible' => Yii::$app->user->can('moderator')],
                        ['label' => 'Теги', 'url' => ['tag/index'], 'icon' => 'tag', 'visible' => Yii::$app->user->can('moderator')],
                        ['label' => 'Статьи', 'url' => ['post/index'], 'icon' => 'file','visible' => Yii::$app->user->can('author')],
                        ['label' => 'Расширенный поиск', 'url' => ['search/full'], 'icon' => 'search','visible' => Yii::$app->user->can('author')],
                    ],
                ]
            ) ?>

    </section>

</aside>
