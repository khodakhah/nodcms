<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Core\Models;


use CodeIgniter\Database\ConnectionInterface;
use Config\Database;

abstract class CoreModel
{
    /**
     * Database Connection
     *
     * @var ConnectionInterface
     */
    protected $db;

    /**
     * The Database connection group that
     * should be instantiated.
     *
     * @var string
     */
    protected $DBGroup;


    /**
     * Model constructor.
     * @param ConnectionInterface|null $db
     */
    public function __construct(ConnectionInterface $db = null)
    {
        if($db != null)
            $this->db = $db;
        else
            $this->db = Database::connect($this->DBGroup);
    }

}
