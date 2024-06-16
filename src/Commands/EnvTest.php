<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-16 08:41:37
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

        $SIMBA_CONNECTION = config('simba.connection');
        $SQLSRV_URL       = config('database.connections.sqlsrv.url');
        $SQLSRV_HOST      = config('database.connections.sqlsrv.host');
        $SQLSRV_PORT      = config('database.connections.sqlsrv.port');
        $SQLSRV_DATABASE  = config('database.connections.sqlsrv.database');
        $SQLSRV_USERNAME  = config('database.connections.sqlsrv.username');
        $SQLSRV_PASSWORD  = config('database.connections.sqlsrv.password');
        $this->output->writeln('[i] --------------------------------');
        $this->output->writeln("[i] SIMBA_CONNECTION    {$SIMBA_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_URL          {$SQLSRV_URL}");
        $this->output->writeln("[i] SQLSRV_HOST         {$SQLSRV_HOST}");
        $this->output->writeln("[i] SQLSRV_PORT         {$SQLSRV_PORT}");
        $this->output->writeln("[i] SQLSRV_DATABASE     {$SQLSRV_DATABASE}");
        $this->output->writeln("[i] SQLSRV_USERNAME     {$SQLSRV_USERNAME}");
        $this->output->writeln("[i] SQLSRV_PASSWORD     {$SQLSRV_PASSWORD}");

        $MAGENTO_BASE_URL            = config('magento.base_url');
        $MAGENTO_CONSUMER_KEY        = config('magento.base_url');
        $MAGENTO_CONSUMER_SECRET     = config('magento.base_url');
        $MAGENTO_ACCESS_TOKEN        = config('magento.base_url');
        $MAGENTO_ACCESS_TOKEN_SECRET = config('magento.base_url');
        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] MAGENTO_BASE_URL            {$MAGENTO_BASE_URL}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_KEY        {$MAGENTO_CONSUMER_KEY}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_SECRET     {$MAGENTO_CONSUMER_SECRET}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN        {$MAGENTO_ACCESS_TOKEN}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN_SECRET {$MAGENTO_ACCESS_TOKEN_SECRET}");
        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] {$this->currentTime()} Testing is finished!!!");
    }

    public function currentTime()
    {
        return Carbon::now()->toDateTimeString();
    }
}
