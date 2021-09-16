<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\FacebookUser;

/**
 * Class FacebookUserTransformer.
 *
 * @package namespace App\Transformers;
 */
class FacebookUserTransformer extends TransformerAbstract
{
    /**
     * Transform the FacebookUser entity.
     *
     * @param \App\Entities\FacebookUser $model
     *
     * @return array
     */
    public function transform(FacebookUser $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
