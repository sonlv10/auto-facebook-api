<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Proxy;

/**
 * Class ProxyTransformer.
 *
 * @package namespace App\Transformers;
 */
class ProxyTransformer extends TransformerAbstract
{
    /**
     * Transform the Proxy entity.
     *
     * @param \App\Entities\Proxy $model
     *
     * @return array
     */
    public function transform(Proxy $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
