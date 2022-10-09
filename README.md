# [NodCMS](http://nodcms.com) 
NodCMS is a <strong>free</strong>, Multilingual, simple and powerful CMS based on [CodeIgniter4]((https://codeigniter.com/)).

More information can be found at [nodcms.com](http://nodcms.com/).

This repository holds a source, composer dependencies, and latest released versions of the NodCMS.

## Demo
[demo.nodcms.com](http://demo.nodcms.com/)

Username: `demo`, Password: `demo`

## NodCMS v3.x
> IMPORTANT: **CodeIgniter4 Application Structure**!
> 
> Since NodCMS v3.0 (based on Codeigniter4) your domain should point to the public folder.
> 
> `public/` is your website root directory. [Learn More](https://codeigniter.com/user_guide/concepts/structure.html#public)

The following points are the most important changes on NodCMS since v3.x
1. Codeigniter 4
2. New NodCMS core & structure
3. Better modular structure
4. Some quick debugs

## Download ##
You can download the latest released version as a ZIP file from [nodcms-bundle](https://github.com/khodakhah/nodcms-bundle). 

[Download ZIP File](https://github.com/khodakhah/nodcms-bundle/archive/master.zip)

## Composer Installation
### Create a NodCMS Project

This installation technique would suit a developer who wishes to start a new NodCMS based project.

```
composer create-project khodakhah/nodcms
```
#### Upgrading
```
composer update
```

### Local installation
1. Run ```git clone https://github.com/khodakhah/nodcms.git```
2. Open the project folder  e.g. on Linux and Mac```cd nodcms```
3. Run ```composer install```
4. Run ```composer env-development```
5. Run ```composer start```
6. Open http://localhost:8000 in the browser.

### Server requirement

Please check this link https://codeigniter.com/user_guide/intro/requirements.html

### Adding NodCMS to an Existing Project
```
composer require khodakhah/nodcms
```
Copy the `public`, `writable`, and all folders with the prefix `nodcms-` from `vendor/khodakhah/nodcms` to your project root

## Database
NodCMS database structure will be automatically generated form Models.

To build database there is two options.

For both way you need to create your table(an empty table) manually.

### 1. User Interface
NodCMS database structure can be created automatically from models throw a wizard CMS installation.

You need only open the project with a browser and follow the installation steps.
[Learn more](https://nodcms.com/user-guide/)

## Docker
Please install docker compose or install docker desktop which will install compose plugin.

[Install docker compose](https://docs.docker.com/compose/install/)

Open the project folder and run ``docker-compose up`` or ``docker-compose up -d``

Then open the http://localhost:8000 in the browser.

Database parameters for docker:
- HOST: nodcmsdb
- PASSWORD: nodcms
- DATABASE: nodcms
- USER: nodcms
- PASSWORD: nodcms

To stop the docker please run ``docker-composer stop`` if you run the ``docker-compose -d`` in the previous step.

**NOTE**

In order to install dependencies without PHP environment checking, please run `composer install --ignore-platform-reqs` 

---

### 2. Command Interface
To setup database and create tables via CLI, you need to run the following commands:
```shell
# 1. Save database connection parameters in .env file
php spark database:setup localhost root db-password table-name

# 2. Create database tables
php spark database:build

# 3. Create/Update an admin user with the given parameters
php spark settings:admin [firstname] [lastname] [email] [password]
```
#### Important
If you already have some tables in your given database, the command `database:build` will not overwrite the existed tables.

To overwrite exists tables you need to add `-overwrite` option on command.
```shell
# Build database overwrite exists tables
php spark database:build -overwrite
```

`php spark settings:admin [firstname] [lastname] [email] [password]` can be used anytime in the future. It will overwrite the admin user if it exists. 
_Just in the case that you lose your password, and you want to reset it._

## Bugs Reports
If you find an issue, let me know [here](https://github.com/khodakhah/nodcms/issues/new)!
