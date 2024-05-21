<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-05-21 11:54:12
 */

namespace Diepxuan\Catalog\Commands;

use Carbon\Carbon;
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
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        $mode = $this->argument('mode', 'all');
        $this->output->writeln("[i] {$this->currentTime()} Intergration is starting...");

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
        $this->output->writeln("[i] {$this->currentTime()} Intergration is finished!!!");
    }

    public function currentTime()
    {
        return Carbon::now()->toDateTimeString();
    }
}
