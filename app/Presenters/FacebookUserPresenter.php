<?php

namespace App\Presenters;

use App\Transformers\FacebookUserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class FacebookUserPresenter.
 *
 * @package namespace App\Presenters;
 */
class FacebookUserPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FacebookUserTransformer();
    }
}
