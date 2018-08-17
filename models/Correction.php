<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%correction}}".
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $title
 * @property string $text
 * @property string $reject_reason
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Post $post
 * @property User $user
 */
class Correction extends \yii\db\ActiveRecord
{
    //const STATUS_DRAFT = 10;
    const STATUS_PENDING = 20;
    const STATUS_APPROVED = 30;
    const STATUS_REJECTED = 40;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%correction}}';
    }


    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    private function getBy(array $condition)
    {
        $model = self::find()->andWhere($condition)->limit(1)->one();
        return $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text', 'title'], 'required'],
            [['title','text', 'reject_reason'], 'string'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Статья',
            'user_id' => 'Автор',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'reject_reason' => 'Причина отклонения',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }

    public static function create($post_id, $user_id, $title, $text)
    {
        $correction = new static();
        $correction->user_id = $user_id;
        $correction->post_id = $post_id;
        $correction->title = $title;
        $correction->text = $text;
        $correction->status = self::STATUS_PENDING;
        $correction->created_at = time();
        $correction->updated_at = $correction->created_at;

        return $correction;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
