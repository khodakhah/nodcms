<?php

namespace NodCMS\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Models;
use Config\Services;
use NodCMS\Core\Validation\Validation;

class SettingsAdmin extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'NodCMS';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'settings:admin';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Create or update an admin user with the given parameters.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'settings:admin [firstname] [lastname] [email] [password]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'firstname' => 'First name.',
        'lastname' => 'Last name.',
        'email' => 'Valid email address.',
        'password' => 'Password.',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        if (count($params) < 4) {
            CLI::error('Params missing!');
            CLI::write('Please make sure to use the command correctly.');
            $this->showHelp();
            return;
        }

        [$firstName, $lastName, $email, $password] = $params;

        if (!preg_match('/^.+\@.+\..+$/', $email)) {
            CLI::error('Invalid email address: ' . $email);
            CLI::write('Please make sure to enter a valid.');
            CLI::newLine();
            return;
        }

        if (strlen(trim($password)) < 6) {
            CLI::error('Password was to short.');
            CLI::write('Please enter 6 or more character as password.');
            CLI::newLine();
            return;
        }

        $data = [
            'firstname' => trim($firstName),
            'lastname' => trim($lastName),
            'email' => trim($email),
            'password' => trim($password),
        ];

        $userAdmin = Models::users()->getOne(1);
        if (!empty($userAdmin)) {
            Models::users()->edit(1, $data);

            return;
        }

        $data['group_id'] = 1;
        $data['username'] = "admin";

        Models::users()->add($data);
    }
}
