<?php

namespace app\services;

use app\models\Category;
use app\models\Post;
use app\repositories\NotFoundException;
use app\repositories\PostRepository;
use app\repositories\TagRepository;
use Yii;
use yii\web\ForbiddenHttpException;

class PostService
{
    private $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function get($id, $status = [Post::STATUS_APPROVED]){

        $post = $this->posts->get($id, $status);

        if(empty($post)) {
            $post = $this->posts->get($id, [Post::STATUS_DRAFT, Post::STATUS_APPROVED, Post::STATUS_PENDING, Post::STATUS_REJECTED]);

            if(!empty($post) and ! Yii::$app->user->can('UpdateModel', ['model' => $post]))
                throw new ForbiddenHttpException('У вас недостаточно прав для выполнения указанного действия');
        }

        if (empty($post))
            throw new NotFoundException('Post not found.');


        return $post;
    }

    public function create(Post $form)
    {
        $post = Post::create(
            $category_id = $form->category_id,
            $user_id = \Yii::$app->user->id,
            $title = $form->title,
            $description = $form->description
        );
        $this->posts->save($post);
        return $post;
    }

    /**
     * @param $id
     * @param Post $form
     */
    public function edit($id, Post $form)
    {
        $post = $this->posts->get($id, [Post::STATUS_DRAFT, Post::STATUS_APPROVED, Post::STATUS_PENDING, Post::STATUS_REJECTED]);
        $post->edit(
            $category_id = $form->category_id,
            $title = $form->title,
            $description = $form->description
        );

        $post->tags = $form->form_tags;
        $this->posts->save($post);
    }

    public function pending($id){
        $post = $this->posts->get($id, [Post::STATUS_DRAFT, Post::STATUS_REJECTED]);
        $post->setStatus(Post::STATUS_PENDING);
        $post->save();
    }

    public function approve($id){
        $post = $this->posts->get($id, Post::STATUS_PENDING);
        $post->setStatus(Post::STATUS_APPROVED);
        $post->save();
    }

    public function reject($id){
        $post = $this->posts->get($id, [Post::STATUS_PENDING, Post::STATUS_APPROVED]);
        $post->setStatus(Post::STATUS_REJECTED);
        $post->save();
    }

    /**
     * @param $id
     */
    public function remove($post)
    {
        $this->posts->remove($post);
    }

    public function updateDescription($id, $description){
        $post = $this->posts->get($id);
        $post->description = $description;
        $post->save();
    }
    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function getLastPosts(){
        $query = Post::find()->where(['status' => Post::STATUS_APPROVED])->orderBy(['updated_at' => SORT_DESC])->with(['user','tags','category']);

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'totalCount' => 10,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);


        return $data_provider;
    }

    public function getPending(){
        $query = Post::find()->where(['status' => Post::STATUS_PENDING])->orderBy(['updated_at' => SORT_DESC])->with(['user','tags','category']);

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $data_provider;
    }

    /**
     * Список черновиков
     * @return \yii\data\ActiveDataProvider
     */
    public function getDrafts($user_id){
        $where = ['user_id' =>$user_id,'status' => Post::STATUS_DRAFT] ;
        $query = $this->getAllBy($where);

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $data_provider;
    }

    public function getFavorites($favorites){
        $favorites = explode(',',$favorites);
        $where = ['id' => $favorites, 'status' => [Post::STATUS_APPROVED]] ;
        $query = $this->getAllBy($where);

        $data_provider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $data_provider;

    }

    /**
     * Поиск по условию
     * @param $condition
     * @return $this
     */
    private function getAllBy($condition){
        return Post::find()->where($condition)->orderBy(['updated_at' => SORT_DESC])->with(['user','tags','category']);
    }
}