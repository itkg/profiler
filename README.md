Itkg Profiler library
==================

[![Build Status](https://travis-ci.org/itkg/profiler.svg?branch=master)](https://travis-ci.org/itkg/profiler)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itkg/profiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/itkg/profiler/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/itkg/profiler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/itkg/profiler/?branch=master)

## Installation

### Installation by Composer

If you use composer, add itkg/profiler as a dependency to the composer.json of your application

```json
    "require": {
        ...
        "itkg/profiler": "dev-master"
        ...
    },

```

To install assets in your web directory with composer, you can add this lines :

```json
    "extra": {
        "itkg_profiler_asset_dir": "path/to/your/web/directory"
    },
    "scripts": {
        "post-update-cmd":  "Itkg\\Profiler\\Composer\\Installer::copyAssets",
        "post-install-cmd": "Itkg\\Profiler\\Composer\\Installer::copyAssets"
    }
```
