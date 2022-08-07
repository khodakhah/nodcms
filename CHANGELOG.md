# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [3.3.1] - 22-08-07
### Added
- Composer scripts
  - ``composer env-production`` to build ``.env`` file from ``.env.production``
  - ``composer env-development`` to build ``.env`` file from ``.env.development``
### Changed
- Environment defination moved from ``/public/index.php`` to ``/.env`` file.
### Fixed
- [NodCMS-Bundle](https://github.com/khodakhah/nodcms-bundle) issue [#87](https://github.com/khodakhah/nodcms/issues/87)

## [3.3.0] - 2022-07-23
### Fixed
- Installation issue
### Security
- Upgrade codeigniter to version v4.2
