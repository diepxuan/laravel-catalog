<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-14 10:31:05
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
    protected $signature = 'app:catalog-sync';

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
        $this->call('app:csf:install');
    }
}
