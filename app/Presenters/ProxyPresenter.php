<?php

namespace App\Presenters;

use App\Transformers\ProxyTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ProxyPresenter.
 *
 * @package namespace App\Presenters;
 */
class ProxyPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ProxyTransformer();
    }
}
