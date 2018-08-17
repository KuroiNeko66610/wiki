<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%post_files}}".
 *
 * @property int $id
 * @property int $post_id
 * @property string $filename
 *
 * @property Post $post
 */
class PostFiles extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => '\yiidreamteam\upload\FileUploadBehavior',
                'attribute' => 'filename',
                'filePath' => '@webroot/uploads/post/[[id_path]]/[[filename]].[[extension]]',
                'fileUrl' => '/uploads/post/[[id_path]]/[[filename]].[[extension]]',
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_files}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'integer'],
            [['filename'], 'file'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'filename' => 'Filename',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
