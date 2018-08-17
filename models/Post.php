<?php

namespace app\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use Yii;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 * @property User $user
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 10;
    const STATUS_PENDING = 20;
    const STATUS_APPROVED = 30;
    const STATUS_REJECTED = 40;

    public $form_tags;

    use SaveRelationsTrait;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class'     => SaveRelationsBehavior::className(),
               'relations' => ['tags','user','category'],
            ],
            'class' => \nemmo\attachments\behaviors\FileBehavior::className()
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['category_id','description', 'title'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['form_tags'], 'each', 'rule' => ['integer']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'description' => 'Опиание',
            'form_tags' => 'Теги',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }

    public static function create($category_id, $user_id, $title, $description)
    {
        $post = new static();
        $post->user_id = $user_id;
        $post->category_id = $category_id;
        $post->title = $title;
        $post->description = $description;
        $post->status = self::STATUS_DRAFT;
        $post->created_at = time();
        $post->updated_at = $post->created_at;

        return $post;
    }

    /**
     * @param $category_id
     * @param $title
     * @param $description
     */
    public function edit($category_id, $title, $description)
    {
        $this->category_id = $category_id;
        $this->title = $title;
        $this->description = $description;
        $this->updated_at = time();
    }

    /**
     * @param $status
     */
    public function setStatus($status){
        $this->status = $status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

  /*  public function getFiles()
    {
        return $this->hasMany(PostFiles::className(), ['post_id' => 'id']);
    }
    */

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%post_tag}}', ['post_id' => 'id']);
    }
}
