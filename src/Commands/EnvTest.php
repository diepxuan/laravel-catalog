<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-04-13 16:00:32
 */

namespace Diepxuan\Catalog\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
    protected $signature = 'app:sync:env';

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
        $this->checkSQLConnection($DB_CONNECTION);

        $SIMBA_CONNECTION = config('simba.connection');
        $SQLSRV_URL       = config("database.connections.{$SIMBA_CONNECTION}.url");
        $SQLSRV_HOST      = config("database.connections.{$SIMBA_CONNECTION}.host");
        $SQLSRV_PORT      = config("database.connections.{$SIMBA_CONNECTION}.port");
        $SQLSRV_DATABASE  = config("database.connections.{$SIMBA_CONNECTION}.database");
        $SQLSRV_USERNAME  = config("database.connections.{$SIMBA_CONNECTION}.username");
        $SQLSRV_PASSWORD  = config("database.connections.{$SIMBA_CONNECTION}.password");
        $this->output->writeln('[i] --------------------------------');
        $this->output->writeln("[i] SIMBA_CONNECTION    {$SIMBA_CONNECTION}");
        $this->output->writeln("[i] SQLSRV_URL          {$SQLSRV_URL}");
        $this->output->writeln("[i] SQLSRV_HOST         {$SQLSRV_HOST}");
        $this->output->writeln("[i] SQLSRV_PORT         {$SQLSRV_PORT}");
        $this->output->writeln("[i] SQLSRV_DATABASE     {$SQLSRV_DATABASE}");
        $this->output->writeln("[i] SQLSRV_USERNAME     {$SQLSRV_USERNAME}");
        $this->output->writeln("[i] SQLSRV_PASSWORD     {$SQLSRV_PASSWORD}");
        $this->checkSQLConnection($SIMBA_CONNECTION);

        $MAGENTO_BASE_URL            = config('magento.base_url');
        $MAGENTO_CONSUMER_KEY        = maskKey(config('magento.consumer_key'));
        $MAGENTO_CONSUMER_SECRET     = maskKey(config('magento.consumer_secret'));
        $MAGENTO_ACCESS_TOKEN        = maskKey(config('magento.token'));
        $MAGENTO_ACCESS_TOKEN_SECRET = maskKey(config('magento.token_secret'));
        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] MAGENTO_BASE_URL            {$MAGENTO_BASE_URL}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_KEY        {$MAGENTO_CONSUMER_KEY}");
        $this->output->writeln("[i] MAGENTO_CONSUMER_SECRET     {$MAGENTO_CONSUMER_SECRET}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN        {$MAGENTO_ACCESS_TOKEN}");
        $this->output->writeln("[i] MAGENTO_ACCESS_TOKEN_SECRET {$MAGENTO_ACCESS_TOKEN_SECRET}");
        $this->checkMagentoConnection();
        $this->output->writeln('[i] -------------------------------------------');
        $this->output->writeln("[i] {$this->currentTime()} Testing is finished!!!");
    }

    public function currentTime()
    {
        return Carbon::now()->toDateTimeString();
    }

    private function checkMagentoConnection(): void
    {
        try {
            $response = Http::withToken(config('magento.token'))
                ->get(config('magento.base_url') . '/rest/V1/store/storeViews')
            ;

            if ($response->successful()) {
                $this->output->writeln('[✓] Magento API OAuth OK!');
            } else {
                $this->output->writeln('[x] Magento API FAILED: HTTP ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->output->writeln('[x] Magento API ERROR: ' . $e->getMessage());
        }
    }

    private function checkSQLConnection($CONNECTION): void
    {
        $CONNECTION_NAME = Str::upper("{$CONNECTION}");

        try {
            \DB::connection($CONNECTION)->getPdo();
            $this->output->writeln("[✓] {$CONNECTION_NAME} connection OK!");
        } catch (\Exception $e) {
            $this->output->writeln("[x] {$CONNECTION_NAME} connection FAILED: " . $e->getMessage());
        }
    }
}
