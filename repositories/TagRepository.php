<?php

namespace app\repositories;

use app\models\Tag;

class TagRepository
{

    public function get($id)
    {
        return $this->getBy(['id' => $id]);
    }

    public function getBySlug($slug){
        return $this->getBy(['slug' => $slug]);
    }
    /**
     * @param Tag $post
     */
    public function save(Tag $post)
    {
        if (!$post->save())
            throw new \RuntimeException('Saving error.');
    }

    /**
     * @param Tag $post
     */
    public function remove(Tag $post)
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
        if (!$post = Tag::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Tag not found.');
        }
        return $post;
    }
}