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
4. Rename the env file in root folder to .env
5. Run ```composer start```
6. Open http://localhost:8000 in the browser.

### Adding NodCMS to an Existing Project
```
composer require khodakhah/nodcms
```
Copy the `public`, `writable`, and all folders with the prefix `nodcms-` from `vendor/khodakhah/nodcms` to your project root

## Database structure
NodCMS database structure will be created automatically from models throw a wizard CMS installation.

So you can only need to follow the below steps right now.

1. Create a new database on your host for NodCMS.
2. Open the project on the browser.
3. You will see installer wizard to build your database.

> In the further versions, the database structure will be provided as an SQL file or/and throw CLI.

[Learn more](https://nodcms.com/user-guide/)

## Bugs Reports
If you find an issue, let me know [here](https://github.com/khodakhah/nodcms/issues/new)!
