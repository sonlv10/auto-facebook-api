<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;
use Carbon\Carbon;

/**
 * Class PostTransformer.
 *
 * @package namespace App\Transformers;
 */
class UserTransformer extends TransformerAbstract
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
        $result = [
            'id' => $model->id,
            'email' => $model->email,
            'fbUserInfo' => $model->fbUserInfo,
            'name' => $model->name,
            'fb_access_token' => $model->fb_access_token,
            'params' => [
                'view_watch_enable' => !empty($model->params['view_watch_enable']) ?  1 : 0,
                'view_watch_time' => !empty($model->params['view_watch_time']) ? intval($model->params['view_watch_time']) : 0,
                'view_feed_enable' => !empty($model->params['view_feed_enable']) ?  1 : 0,
                'view_feed_time' => !empty($model->params['view_feed_time']) ? intval($model->params['view_feed_time']) : 0,
                'view_like_enable' => !empty($model->params['view_like_enable']) ?  1 : 0,
                'view_like_number' => !empty($model->params['view_like_number']) ? intval($model->params['view_like_number']) : 0,
                'view_comment_enable' => !empty($model->params['view_comment_enable']) ?  1 : 0,
                'view_comment_number' => !empty($model->params['view_comment_number']) ? intval($model->params['view_comment_number']) : 0,
                'schedule_enable' => !empty($model->params['schedule_enable']) ?  1 : 0,
                'schedule_time' => !empty($model->params['schedule_time']) ? ($model->params['schedule_time']) : 0,
            ],
        ];
        return $result;
    }
}
