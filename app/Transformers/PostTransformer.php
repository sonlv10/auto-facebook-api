<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Post;
use Carbon\Carbon;

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
            preg_match("/[(]*\d{3}[)]*\s*[.\-\s]*\d{3}[.\-\s]*\d{4}/", $comment['message'], $phone_matches);
            preg_match("/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i", $comment['message'], $email_matches);

            $result[] = [
                'fb_uid'         => $comment['from']['id'],
                'name'         => $comment['from']['name'],
                'comment_id'         => $comment['id'],
                'message'         => $comment['message'],
                'phone'         => !empty($phone_matches) ? $phone_matches[0] : null,
                'email'         => !empty($email_matches) ? $email_matches[0] : null,
                'created_time' => Carbon::parse($comment['created_time'])->format('d-m-Y H:i:s'),
            ];
        }
        return $result;
    }
}
