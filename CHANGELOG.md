# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- PHP web server with spark for `composer start` command.
- The command interface descriptions.

### Added
- Docker to the project.
- [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- Composer command``composer checkout``

### Removed
- Composer command``composer env-development``

## [3.4.1] 20-09-13
## Fixed
- The bundle pipeline

## [3.4.0] 20-09-13
### Added
- Database parameters will be saved to env file instead of database config class.
- Activate CodeIgniter CLI 'spark'
- New console commands to save database connection build tables
### Removed
- DEMO Live deploy from GitHub action.

## [3.3.1] - 2022-08-07
### Added
- Composer scripts
  - ``composer env-production`` to build ``.env`` file from ``.env.production``
  - ``composer env-development`` to build ``.env`` file from ``.env.development``
### Changed
- Environment definition moved from ``/public/index.php`` to ``/.env`` file.
### Fixed
- [NodCMS-Bundle](https://github.com/khodakhah/nodcms-bundle) issue [#87](https://github.com/khodakhah/nodcms/issues/87)

## [3.3.0] - 2022-07-23
### Fixed
- Installation issue
### Security
- Upgrade codeigniter to version v4.2

