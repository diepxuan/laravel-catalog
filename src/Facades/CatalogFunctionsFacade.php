<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-03 22:30:07
 */

namespace Diepxuan\Catalog\Facades;

use Illuminate\Support\Facades\Facade;

class CatalogFunctionsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'catalog-functions';
    }
}
