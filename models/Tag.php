<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }

    /**
     * @param $name
     * @param $slug
     * @return static
     */
    public static function create($name, $slug)
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    /**
     * @param $name
     * @param $slug
     */
    public function edit($name, $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }
}
