<?php

namespace app\services;

use app\models\Category;
use app\models\Post;
use app\repositories\NotFoundException;
use app\repositories\PostRepository;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class SearchService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    public function search($q = '')
    {

        $cache = Yii::$app->cache; // Could be Yii::$app->cache
        return $cache->getOrSet(['quick_search', 'q' => $q,], function ($cache) use ($q) {
            $result = [];
            $i = 0;
            $posts = Post::find()->where(['like', 'title', $q])->orWhere(['like', 'description', $q])->andWhere(['status' => Post::STATUS_APPROVED])->with(['tags', 'category'])->all();

            foreach ($posts as $post) {

                $result[$i]['tags'] = '';
                foreach ($post->tags as $tag)
                    $result[$i]['tags'] .= '<span class="label label-primary">' . $tag->name . '</span>';

                $result[$i]['breadcrumbs'] = '';
                foreach ($post->category->getBreadcrumbsArray() as $cat)
                    $result[$i]['breadcrumbs'] .= $cat['label'] . '/';
                $result[$i]['breadcrumbs'] = substr($result[$i]['breadcrumbs'], 0, strlen($result[$i]['breadcrumbs']) - 1);

                $result[$i]['id'] = $post->id;
                $result[$i]['controller'] = 'post';
                $result[$i]['type'] = 'Статья';
                $result[$i]['title'] = '<span style="color:#3c8dbc">' . $post->title . '</span>';

                $i++;
            }


            $categories = Category::find()->where(['like', 'name', $q])->all();

            foreach ($categories as $category) {
                $result[$i]['breadcrumbs'] = '';
                foreach ($category->getBreadcrumbsArray() as $cat)
                    $result[$i]['breadcrumbs'] .= $cat['label'] . '/';
                $result[$i]['breadcrumbs'] = substr($result[$i]['breadcrumbs'], 0, strlen($result[$i]['breadcrumbs']) - 1);
                $result[$i]['id'] = $category->id;
                $result[$i]['controller'] = 'category';
                $result[$i]['type'] = 'Категория';
                $result[$i]['title'] = '<span style="color:#00a65a">' . $category->name . '</span>';
                $i++;

            }

            return $result;

        }, 1000);


    }

    public function searchByCategory($category_id = null){

        $category = Category::findOne($category_id);

        if (empty($category))
            throw new NotFoundException('Категория не найдена');

        /// $category = isset($category['id'])?$category['id']:null;

        $sub_category = ArrayHelper::map($category->children()->asArray()->all(), 'id', 'id');
        $sub_category[] = $category_id;
        $query = Post::find()->where(['category_id' => $sub_category, 'status' => Post::STATUS_APPROVED])->with('user');

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                   'pageSize' => 10,
               ],
        ]);

        return $data_provider;
    }


    public function searchByTag($slug){
        $query = Post::find()->joinWith('tags as t')->where(['t.slug' => $slug, 'status' => Post::STATUS_APPROVED]);

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $data_provider;
    }

    public function getCategory($category_id){
        return  $category = Category::find()->where(['id' => $category_id])->asArray()->one();
    }

    public function getByUserId($user_id){
            $condition = ['user_id' => $user_id, 'status' => Post::STATUS_APPROVED];

        $query = Post::find()->where($condition);

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
    }
}