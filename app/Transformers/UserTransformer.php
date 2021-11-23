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
                'view_feed' => [
                    'enable' => !empty($model->params['view_feed']['enable']) ?  1 : 0,
                    'time' => !empty($model->params['view_feed']['time']) ?  $model->params['view_feed']['time'] : 30,
                    'like' => !empty($model->params['view_feed']['like']) ?  1 : 0,
                    'like_number' => !empty($model->params['view_feed']['like_number']) ?  $model->params['view_feed']['like_number'] : 1,
                    'comment' => !empty($model->params['view_feed']['comment']) ?  1 : 0,
                    'comment_number' => !empty($model->params['view_feed']['comment_number']) ?  $model->params['view_feed']['comment_number'] : 1,
                    'comment_content' => !empty($model->params['view_feed']['comment_content']) ?  $model->params['view_feed']['comment_content'] : '',
                ],
                'view_watch' => [
                    'enable' => !empty($model->params['view_watch']['enable']) ?  1 : 0,
                    'time' => !empty($model->params['view_watch']['time']) ?  $model->params['view_watch']['time'] : 30,
                    'number' => !empty($model->params['view_watch']['number']) ?  $model->params['view_watch']['number'] : 1,
                    'like' => !empty($model->params['view_watch']['like']) ?  1 : 0,
                    'like_number' => !empty($model->params['view_watch']['like_number']) ?  $model->params['view_watch']['like_number'] : 1,
                    'comment' => !empty($model->params['view_watch']['comment']) ?  1 : 0,
                    'comment_number' => !empty($model->params['view_watch']['comment_number']) ?  $model->params['view_watch']['comment_number'] : 1,
                    'comment_content' => !empty($model->params['view_watch']['comment_content']) ?  $model->params['view_watch']['comment_content'] : '',
                ],
                'action_friend' => [
                    'enable' => !empty($model->params['action_friend']['enable']) ?  1 : 0,
                    'time' => !empty($model->params['action_friend']['time']) ?  $model->params['action_friend']['time'] : 30,
                    'number' => !empty($model->params['action_friend']['number']) ?  $model->params['action_friend']['number'] : 1,
                    'like' => !empty($model->params['action_friend']['like']) ?  1 : 0,
                    'like_number' => !empty($model->params['action_friend']['like_number']) ?  $model->params['action_friend']['like_number'] : 1,
                    'pokes' => !empty($model->params['action_friend']['pokes']) ?  1 : 0,
                    'pokes_number' => !empty($model->params['action_friend']['pokes_number']) ?  $model->params['action_friend']['pokes_number'] : 1,
                    'comment' => !empty($model->params['action_friend']['comment']) ?  1 : 0,
                    'comment_number' => !empty($model->params['action_friend']['comment_number']) ?  $model->params['action_friend']['comment_number'] : 1,
                    'comment_content' => !empty($model->params['action_friend']['comment_content']) ?  $model->params['action_friend']['comment_content'] : '',
                ],
                'action_group' => [
                    'enable' => !empty($model->params['action_group']['enable']) ?  1 : 0,
                    'time' => !empty($model->params['action_group']['time']) ?  $model->params['action_group']['time'] : 30,
                    'number' => !empty($model->params['action_group']['number']) ?  $model->params['action_group']['number'] : 1,
                    'like' => !empty($model->params['action_group']['like']) ?  1 : 0,
                    'like_number' => !empty($model->params['action_group']['like_number']) ?  $model->params['action_group']['like_number'] : 1,
                    'comment' => !empty($model->params['action_group']['comment']) ?  1 : 0,
                    'comment_number' => !empty($model->params['action_group']['comment_number']) ?  $model->params['action_group']['comment_number'] : 1,
                    'comment_content' => !empty($model->params['action_group']['comment_content']) ?  $model->params['action_group']['comment_content'] : '',
                ],
                'add_friend' => [
                    'enable' => !empty($model->params['add_friend']['enable']) ?  1 : 0,
                    'by_suggest' => !empty($model->params['add_friend']['by_suggest']) ?  1 : 0,
                    'suggest_number' => !empty($model->params['add_friend']['suggest_number']) ?  $model->params['add_friend']['suggest_number'] : 1,
                    'by_uid' => !empty($model->params['add_friend']['by_uid']) ?  1 : 0,
                    'uids' => !empty($model->params['add_friend']['uids']) ?  $model->params['add_friend']['uids'] : '',
                ],
                'add_group' => [
                    'enable' => !empty($model->params['add_friend']['enable']) ?  1 : 0,
                    'by_suggest' => !empty($model->params['add_friend']['by_suggest']) ?  1 : 0,
                    'suggest_number' => !empty($model->params['add_friend']['suggest_number']) ?  $model->params['add_friend']['suggest_number'] : 1,
                    'by_gid' => !empty($model->params['add_friend']['by_gid']) ?  1 : 0,
                    'gids' => !empty($model->params['add_friend']['gids']) ?  $model->params['add_friend']['gids'] : '',
                ],
                'threads' => !empty($model->params['threads']) ?  $model->params['threads'] : 1,
                'google_seo' => [
                    'url' => !empty($model->params['google_seo']['url']) ?  $model->params['google_seo']['url'] : '',
                    'key_word' => !empty($model->params['google_seo']['key_word']) ?  $model->params['google_seo']['key_word'] : '',
                    'url1' => !empty($model->params['google_seo']['url1']) ?  $model->params['google_seo']['url1'] : '',
                    'url2' => !empty($model->params['google_seo']['url2']) ?  $model->params['google_seo']['url2'] : '',
                    'url3' => !empty($model->params['google_seo']['url3']) ?  $model->params['google_seo']['url3'] : '',
                    'threads' => !empty($model->params['google_seo']['threads']) ?  $model->params['google_seo']['threads'] : 2,
                    'number' => !empty($model->params['google_seo']['number']) ?  $model->params['google_seo']['number'] : 100,
                ],
            ],
        ];
        return $result;
    }
}
