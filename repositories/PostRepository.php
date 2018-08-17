<?php

namespace app\repositories;

use app\models\Post;

class PostRepository
{

    public function findByUserId($id)
    {
        return Post::find()->andWhere(['or', ['user_id' => $id]])->one();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function get($id, $status = [Post::STATUS_APPROVED])
    {
        return $this->getBy(['id' => $id, 'status' => $status]);
    }

    /**
     * @param Post $post
     */
    public function save(Post $post)
    {
        if (!$post->save())
            throw new \RuntimeException('Saving error.');
    }

    /**
     * @param Post $post
     */
    public function remove(Post $post)
    {
        if (!$post->delete())
            throw new \RuntimeException('Removing error.');
    }


    /**
     * @param array $condition
     * @return array|null|\yii\db\ActiveRecord
     */
    private function getBy(array $condition)
    {
        $post = Post::find()->andWhere($condition)->limit(1)->one();
        /*
        if (!$post = Post::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Post not found.');
        }
        */
      //$post->form_tags = ;
        return $post;
    }
}