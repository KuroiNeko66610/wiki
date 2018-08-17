<?php
/*
namespace common\models;

use Yii;


class Category extends \yii\db\ActiveRecord
{
    use \kartik\tree\models\TreeTrait;

    public static function tableName()
    {
        return '{{%category_tree}}';
    }


    public function rules()
    {
        return [
            [['root', 'lft', 'rgt', 'lvl', 'icon_type', 'active', 'selected', 'disabled', 'readonly', 'visible', 'collapsed', 'movable_u', 'movable_d', 'movable_l', 'movable_r', 'removable', 'removable_all'], 'integer'],
            [['lft', 'rgt', 'lvl', 'name'], 'required'],
            [['name'], 'string', 'max' => 60],
            [['icon'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'root' => 'Root',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'lvl' => 'Lvl',
            'name' => 'Name',
            'icon' => 'Icon',
            'icon_type' => 'Icon Type',
            'active' => 'Active',
            'selected' => 'Selected',
            'disabled' => 'Disabled',
            'readonly' => 'Readonly',
            'visible' => 'Visible',
            'collapsed' => 'Collapsed',
            'movable_u' => 'Movable U',
            'movable_d' => 'Movable D',
            'movable_l' => 'Movable L',
            'movable_r' => 'Movable R',
            'removable' => 'Removable',
            'removable_all' => 'Removable All',
        ];
    }
}*/


namespace app\models;

use kartik\tree\models\Tree;
use kartik\tree\TreeView;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


class Category extends \kartik\tree\models\Tree
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }

    public function attributeLabels()
    {
        $attribute = parent::attributeLabels();
        $attribute['id'] = 'ID';
        $attribute['name'] = 'Название категории';
        return $attribute;
    }

    /* Формирует многомерный массив дерева*/
  /*  public static function getTree($current, $items = []){
        foreach ($current->all() as $model){
            $items[] = [
                'name' => $model->name,
                'lvl' => $model->lvl,
                'items' => self::getTree($model->children(1))
            ];
        }
        return $items;
    }
*/
    /* Формируем массив хлебных крошек. Удаляем url в последнем элементне, если это массив для категорий*/
    public function getBreadcrumbsArray($for_post = 0)
    {
        $this_ = $this ;
        $cache = Yii::$app->cache; // Could be Yii::$app->cache
        return $cache->getOrSet(['breadcrumbs-for-category', 'id' => $this->id, 'view' => $for_post], function ($cache) use ($this_, $for_post) {

            $crumbNodes = $this->parents()->all();
            $crumbNodes[] = $this;
            $i = 1;
            $crumbs = [];
            foreach ($crumbNodes as $node) {
                $crumbs[$i]['label'] = $node->name;
                $crumbs[$i]['url'] =  Url::to(['/post/index', 'id' => $node->id]);
                $i++;
            }
            if(!$for_post) unset($crumbs[$i-1]['url']);

            return ($crumbs);

       }, 1000);




    }

}

