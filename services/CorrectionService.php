<?php

namespace app\services;

use app\models\Correction;
use app\models\Post;
use app\repositories\NotFoundException;
use app\repositories\PostRepository;
use Yii;
use yii\web\ForbiddenHttpException;

class CorrectionService
{
    private $corrections;

    public function __construct(Correction $correction)
    {
        $this->corrections = $correction;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function get($id){

        $correction = $this->corrections->get($id);

      /*  if(empty($post) and Yii::$app->user->can('moderator', ['model' => $correction])) {
            $correction = $this->corrections->get($id, [Correction::STATUS_APPROVED, Correction::STATUS_PENDING, Correction::STATUS_REJECTED]);
        }
        */

        if (empty($correction))
            throw new NotFoundException('Correction not found.');

        return $correction;
    }

    public function create($post_id, Correction $form)
    {
        $correction = Correction::create(
            $post_id,
            $user_id = \Yii::$app->user->id,
            $text = $form->title,
            $text = $form->text
        );
        $correction->save($correction);
        return $correction;
    }


    public function approve($id){
        $post = $this->corrections->get($id);
        $post->status = Correction::STATUS_APPROVED;
        $post->save();
    }

    public function reject($id){
        $post = $this->corrections->get($id);
        $post->status = Correction::STATUS_REJECTED;
        $post->save();
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        $post = $this->corrections->get($id);
        $this->posts->remove($post);
    }


}