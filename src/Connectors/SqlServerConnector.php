<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-06-14 15:26:13
 */

namespace Diepxuan\Catalog\Connectors;

use Illuminate\Database\Connectors\SqlServerConnector as BaseSqlServerConnector;

class SqlServerConnector extends BaseSqlServerConnector
{
    /**
     * Establish a database connection.
     *
     * @return \PDO
     */
    public function connect(array $config)
    {
        $options = $this->getOptions($config);

        \Debugbar::info($this->getDsn($config), $config, $options);

        $connection = $this->createConnection($this->getDsn($config), $config, $options);

        $this->configureIsolationLevel($connection, $config);

        return $connection;
    }

    public function getOptions(array $config)
    {
        // \Debugbar::info($config);
        // Lấy các options mặc định từ class cha
        $options = parent::getOptions($config);

        // Ghi đè hoặc đảm bảo giá trị chúng ta muốn là giá trị cuối cùng
        $options[\PDO::ATTR_EMULATE_PREPARES] = true;
        \Debugbar::info($config);

        return $options;
    }
}
