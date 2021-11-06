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

class Nodcms_admin_sign_model extends CoreModel
{
    function check_login($username,$pass)
    {
        $this->db->where('username', $username);
        $this->db->where('password', $pass);
        $query = $this->db->get('users');
        return $query;
    }
}
