# Talk & Play Marketplace Web Application

[![GitHub Issues](https://img.shields.io/github/issues/scify/Talk-And-Play-Marketplace)](https://img.shields.io/github/issues/scify/Talk-And-Play-Marketplace)
[![GitHub Stars](https://img.shields.io/github/stars/scify/Talk-And-Play-Marketplace)](https://img.shields.io/github/stars/scify/Talk-And-Play-Marketplace)
[![GitHub forks](https://img.shields.io/github/forks/scify/Talk-And-Play-Marketplace)](https://img.shields.io/github/forks/scify/Talk-And-Play-Marketplace)
[![JavaScript Style Guide: Good Parts](https://img.shields.io/badge/code%20style-goodparts-brightgreen.svg?style=flat)](https://github.com/dwyl/goodparts "JavaScript The Good Parts")
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/dwyl/esta/issues)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity)
[![Ask Me Anything !](https://img.shields.io/badge/Ask%20me-anything-1abc9c.svg)](https://GitHub.com/scify)

**Laravel 11** Web Application for Creating content for the Talk & Play application

[Project URL](https://crowdsourcing.scify.org/)

## Installation Instructions

1. Install [ddev](https://ddev.readthedocs.io/en/stable/users/install/ddev-installation/)
2. Clone the repository `git clone https://github.com/scify/Talk-And-Play-Marketplace.git`
3. Copy the `.env.example` file to `.env` and set the environment variables
4. Start the development server `ddev start`
5. Run the Laravel commands `ddev composer install` and `ddev npm install`
6. Run the Laravel commands `ddev artisan migrate` and `ddev artisan db:seed`
7. Run the Laravel commands `ddev npm run dev` (or `ddev npm run watch` for hot reloading)
8. Open the application at [https://talkandplay-marketplace.ddev.site:8443/](https://talkandplay-marketplace.ddev.site:8443/)

## Development

### Available Commands

- Start the environment: `ddev start`
- Stop the environment: `ddev stop`
- Run artisan commands: `ddev artisan [command]`
- Run composer commands: `ddev composer [command]`
- Run npm commands: `ddev npm [command]`

### URLs

- Main application: <https://talkandplay-marketplace.ddev.site:8443>
- Database admin: <https://talkandplay-marketplace.ddev.site:8443/phpmyadmin>

### Database Credentials

These are automatically configured by DDEV:

- Host: db
- Database: db
- Username: db
- Password: db

## How to run tests

```bash
php artisan test
```

## How to debug

- Install and configure Xdebug on your machine
- At Chrome install [Xdebug helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?utm_source=chrome-app-launcher-info-dialog)
- At PhpStorm/IntelliJ click the "Start listening for PHP debug connections"
