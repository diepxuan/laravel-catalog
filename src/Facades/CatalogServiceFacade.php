<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-24 16:07:07
 */

namespace Diepxuan\Catalog\Facades;

use Illuminate\Support\Facades\Facade;

class CatalogServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'catalog-service';
    }
}
