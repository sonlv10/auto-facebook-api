<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Post;

/**
 * Class PostTransformer.
 *
 * @package namespace App\Transformers;
 */
class PostTransformer extends TransformerAbstract
{
    /**
     * Transform the Post entity.
     *
     * @param \App\Entities\Post $model
     *
     * @return array
     */
    public function transform($model)
    {
        $result = array();
        foreach ($model as $comment) {
            $result[] = [
                'fb_uid'         => $comment['from']['id'],
                'name'         => $comment['from']['name'],
                'comment_id'         => $comment['id'],
                'message'         => $comment['message'],
                'created_time' => $comment['created_time'],
            ];
        }
        return $result;
    }
}
