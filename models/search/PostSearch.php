<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;
use yii\helpers\VarDumper;

/**
 * Post represents the model behind the search form of `common\models\Post`.
 */
class PostSearch extends Post
{
    public $text;
    public $form_tags;
    public $cats;

    public $category_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', ], 'integer'],
            [['text','category_name', 'title', 'description'], 'trim',],
            [['text','category_name'],'string'],
            [['title', 'user_id', 'form_tags','description', 'status','category_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text' => 'Текст',
            'category_id' => 'Категория',
            'category_name' => 'Категория2',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'description' => 'Опиание',
            'form_tags' => '',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Post::find()
            ->joinWith('category cat')
            ->joinWith('user u')
            ->joinWith('tags t');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['updated_at' => SORT_DESC]
            ]
        ]);

        $dataProvider->sort->attributes['category_name'] = [
            'asc' => ['cat.name' => SORT_ASC],
            'desc' => ['cat.name' => SORT_DESC],
            'label' => "Категория"
        ];

        $this->load($params);

        if(!empty($this->category_id))
            $this->cats = explode(',', $this->category_id);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(intval($this->user_id))
            $query->andFilterWhere([
                'user_id' => $this->user_id,
            ]);
        else
            $query->andFilterWhere(['like', 'u.username', $this->user_id]);

        $query->andFilterWhere([
            'id' => $this->id,
            't.id' => $this->form_tags,
            'category_id' => $this->cats,
            '{{%post}}.status' => $this->status,
        ]);

        $query->andFilterWhere([
            'and',
            ['like', 'cat.name', $this->category_name],
            ['like','description', $this->description],
            ['like','title', $this->title]
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'title', $this->text],
            ['like', 'description', $this->text],
        ]);


        return $dataProvider;
    }

    function statusList(){
            return [Post::STATUS_DRAFT => 'Черновик', Post::STATUS_PENDING => 'На модерации', Post::STATUS_APPROVED => 'Одобрена', Post::STATUS_REJECTED => 'Отклонена'];
    }
}
