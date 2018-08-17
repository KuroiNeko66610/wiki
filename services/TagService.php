<?php

namespace app\services;

use app\models\Tag;
use app\repositories\TagRepository;
use yii\helpers\ArrayHelper;

class TagService
{
    private $tag;

    public function __construct(TagRepository $tags)
    {
        $this->tag = $tags;
    }

    public function get($id){
        return $this->tag->get($id);
    }

    public function getBySlug($slug){
        return $this->tag->getBySlug($slug);
    }

    public function create(Tag $form)
    {
        $tag = Tag::create(
            $form->name,
            $form->slug
        );
        $this->tag->save($tag);
        return $tag;
    }

    public function edit($id, Tag $form)
    {
        $tag = $this->tag->get($id);
        $tag->edit(
            $form->name,
            $form->slug
        );
        $this->tag->save($tag);
    }

    public function remove($id)
    {
        $tag = $this->tag->get($id);
        $this->tag->remove($tag);
    }

    public function getArray(){
        return ArrayHelper::map(Tag::find()->asArray()->all(),'id','name');
    }
}