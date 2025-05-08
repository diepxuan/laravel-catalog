<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-05-04 18:02:14
 */

namespace Diepxuan\Catalog\Models;

use Diepxuan\Catalog\Observers\ProductObserver;
use Diepxuan\Simba\Models\Product as SProduct;

#[ObservedBy([ProductObserver::class])]
class Product extends SProduct {}
