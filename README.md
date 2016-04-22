# [nodCMS](http://nodcms.com) 
NodCMS – A Free CMS powered by CodeIgniter

### Welcome to my GitHub Repository

NodCMS is a free, Multi-Language, simple and powerful CMS powered by CodeIgniter.

More information can be found at [nodcms.com](http://nodcms.com/).

Frontend Demo: [demo.nodcms.com](http://demo.nodcms.com/)

Backend Demo: [demo.nodcms.com/admin](http://demo.nodcms.com/admin)
Username: demo
Password: demo

## Download ##
You can download it directly as a ZIP file: [GitHub Download](https://github.com/khodakhah/nodcms/archive/master.zip)!

## Installation ##

NodCMS have a auto installation, but the installer is not powerful and doesn't work in all version of XAMPPs
Make sure the installer will change in the near future.

If you cannot install nodcms automatic with wizard form, please try the manual way to install your NodCMS
### Manual installation:

1. Create a new empty database on your MySQL server(Set "Collation" on utf8_general_ci)
2. Import the nodcms.sql file `nodcms/install/installer/masks/nodcms.sql` from in your database
3. Rename the file `database_manual.php` to `database.php` in `nodcms/nodcms/config/`
4. Inter your database name in line 81 of `database.php` between the last two quotation marks (`'database' => 'YOUR DATABASE NAME',`)
5. Set your host username on line 79 instead of root(`'username' => 'root',`)
6. Inter your password in line 80 between the last two quotation marks.(`'password' => 'YOUR PASSWORD',`)


After install, you can access the admin side on this URL www.your-domain.com/admin

#### Default administrator:

username: <strong>admin</strong>

password: <strong>123456</strong>

## Bugs ##
If you find an issue, let us know [here](https://github.com/khodakhah/nodcms/issues/new)!


There are various ways you can contribute:

1. Raise an [Issue](https://github.com/khodakhah/nodcms/issues) on GitHub
2. Send us a Pull Request with your bug fixes and/or new features
3. Translate nodcms into [Languages files in GitHub](https://www.github.com/khodakhah/nodcms/tree/master/frontend/language)
