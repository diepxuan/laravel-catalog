<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-14 16:36:30
 */

namespace Diepxuan\Catalog\Commands;

use Illuminate\Console\Command;

class CatalogSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:catalog-sync {mode=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Intergration sync';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $mode = $this->argument('mode', 'all');

        switch ($mode) {
            case 'pro':
                $this->call(Products::class);

                break;

            case 'cat':
                $this->call(Categories::class);

                break;

            default:
                $this->call(Categories::class);
                $this->call(Products::class);

                break;
        }
    }
}
