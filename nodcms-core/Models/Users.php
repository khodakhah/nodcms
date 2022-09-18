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

use Config\Services;

class Users extends Model
{
    public function init()
    {
        $table_name = "users";
        $primary_key = "user_id";
        $fields = array(
            'user_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'username'=>"varchar(50) DEFAULT NULL",
            'password'=>"varchar(50) DEFAULT NULL",
            'fullname'=>"varchar(255) DEFAULT NULL",
            'firstname'=>"varchar(255) DEFAULT NULL",
            'lastname'=>"varchar(255) DEFAULT NULL",
            'email'=>"varchar(50) DEFAULT NULL",
            'group_id'=>"tinyint(3) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'reset_pass_exp'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'status'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'active_register'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'active'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'active_code'=>"varchar(255) DEFAULT NULL",
            'active_code_expired'=>"int(10) DEFAULT NULL",
            'reset_password_tries'=>"int(10) unsigned DEFAULT '0'",
            'user_unique_key'=>"varchar(255) DEFAULT NULL",
            'avatar'=>"varchar(255) DEFAULT NULL",
            'mobile'=>"varchar(20) DEFAULT NULL",
            'facebook'=>"varchar(255) DEFAULT NULL",
            'google_plus'=>"varchar(255) DEFAULT NULL",
            'linkedin'=>"varchar(255) DEFAULT NULL",
            'website'=>"varchar(255) DEFAULT NULL",
            'contact_email'=>"varchar(255) DEFAULT NULL",
            'user_agent'=>"text",
            'keep_me_time'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'language_id'=>"int(11) unsigned NOT NULL DEFAULT '1'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Generate unique code for user accounts
     *
     * @return string
     */
    public function generateUniqueKey()
    {
        $unique_key = md5(time()+rand(100000, 999999));
        while ($this->getCount(array("user_unique_key"=>$unique_key))!=0) {
            $unique_key = md5(time()+rand(100000, 999999));
        }
        return $unique_key;
    }

    /**
     * Some filter on create user account
     *
     * @param array $data
     */
    public function add(array $data): int
    {
        $default_data = array(
            "user_unique_key"=>$this->generateUniqueKey(),
            "firstname"=>"",
            "lastname"=>"",
            "email"=>"",
            "username"=>"",
            "password"=>"",
            "group_id"=>20,
            "active_register"=>0,
            "active"=>1,
            "status"=>0
        );

        $data = array_merge($default_data, $data);

        if (!key_exists('fullname', $data)) {
            $data['fullname'] = "$data[firstname] $data[lastname]";
        }

        $data['password'] = md5($data['password']);

        return parent::add($data);
    }

    /**
     * Set some filter before update an user account
     *
     * @param int $id
     * @param array $data
     */
    public function edit($id, $data)
    {
        if (key_exists('password', $data)) {
            $data['password'] = md5($data['password']);
        }

        $_data = $this->getOne($id);
        if (!empty($_data)) {
            $__data = array_merge(array(
                'firstname'=>$_data['firstname'],
                'lastname'=>$_data['lastname'],
            ), $data);
            $data['fullname'] = "{$__data['firstname']} {$__data['lastname']}";
        }

        parent::edit($id, $data);
    }

    /**
     * Set a user status active
     *
     * @param int $id
     */
    public function setActive(int $id)
    {
        parent::edit($id, [
            'status'=>1,
            'active_register'=>1
        ]);
    }

    /**
     * Fetch a user with username and password
     *
     * @param $username
     * @param $password
     * @return array|null
     */
    public function loginMatch($username, $password): ?array
    {
        return $this->getOne(null, ['username'=>$username, 'password'=>md5($password)]);
    }

    /**
     * Fetch a user with secret keys
     *
     * @param string $user_unique_key
     * @param string $active_code
     * @return array|null
     */
    public function getOneWithSecretKeys(string $user_unique_key, string $active_code): ?array
    {
        return Services::model()->users()->getOne(null, ['user_unique_key'=>$user_unique_key, 'active_code'=>$active_code]);
    }

    /**
     * Select and join with groups table
     *
     * @param null $conditions
     * @param null $limit
     * @param int $page
     * @param null $sort_by
     * @return array|null
     */
    public function getAllWithGroups($conditions = null, $limit=null, $page=1, $sort_by = null): ?array
    {
        $joinWithT = Services::model()->groups()->tableName();
        $joinWithF = Services::model()->groups()->primaryKey();
        $builder = $this->getBuilder();
        $builder->join($joinWithT, "{$joinWithT}.{$joinWithF} = {$this->tableName()}.group_id");
        return $this->getAll($conditions, $limit, $page, $sort_by, $builder);
    }
}
