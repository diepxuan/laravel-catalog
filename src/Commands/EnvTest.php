<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-15 20:59:38
 */

namespace Diepxuan\Catalog\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * @internal
 *
 * @coversNothing
 */
final class EnvTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:env-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test environment';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->output->writeln("[i] {$this->currentTime()} Testing is starting...");

        $DB_CONNECTION = config('database.default');
        $DB_HOST       = config("database.connections.{$DB_CONNECTION}.host");
        $DB_PORT       = config("database.connections.{$DB_CONNECTION}.port");
        $DB_DATABASE   = config("database.connections.{$DB_CONNECTION}.database");
        $DB_USERNAME   = config("database.connections.{$DB_CONNECTION}.username");
        $DB_PASSWORD   = config("database.connections.{$DB_CONNECTION}.password");
        $this->output->writeln('[i] --------------------------------');
        $this->output->writeln("[i] DB_CONNECTION   {$DB_CONNECTION}");
        $this->output->writeln("[i] DB_HOST         {$DB_HOST}");
        $this->output->writeln("[i] DB_PORT         {$DB_PORT}");
        $this->output->writeln("[i] DB_DATABASE     {$DB_DATABASE}");
        $this->output->writeln("[i] DB_USERNAME     {$DB_USERNAME}");
        $this->output->writeln("[i] DB_PASSWORD     {$DB_PASSWORD}");

        $this->output->writeln('[i] --------------------------------');
        $this->output->writeln("[i] SQLSRV_URL      {$DB_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_HOST     {$DB_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_PORT     {$DB_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_DATABASE {$DB_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_USERNAME {$DB_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_PASSWORD {$DB_CONNECTION}");

        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] MAGENTO_AUTH_METHOD         {$DB_CONNECTION}");
        $this->output->writeln("[i] MAGENTO_BASE_URL            {$DB_CONNECTION}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_KEY        {$DB_CONNECTION}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_SECRET     {$DB_CONNECTION}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN        {$DB_CONNECTION}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN_SECRET {$DB_CONNECTION}");
        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] {$this->currentTime()} Testing is finished!!!");
    }

    public function currentTime()
    {
        return Carbon::now()->toDateTimeString();
    }
}
