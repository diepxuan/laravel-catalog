<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-04-21 10:18:47
 */

namespace Diepxuan\Catalog\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class Scavenger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scavenger:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Integration scavenger from Everon';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->productIntergration();
    }

    public function productIntergration(): void
    {
        $this->output->writeln('[i] Starting scavenger products');
    }

    public static function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->everyMinute();
    }
}
