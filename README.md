# nhtsa-importer
Import tools to gather NHTSA automotive data using nhtsa.gov APIs found on:

[NHTSA Comlplaints and Recall API](https://webapi.nhtsa.gov/api/metadata)

[NHTSA VPIC VIN and Vehicle Database](https://vpic.nhtsa.dot.gov/api/)

### Installation
Clone or download the repository

Configure the .env.example file with your database settings and save as .env

Run these commands

``` bash
php artisan key:generate
php artisan migrate
```

### Usage

Import makes for each model year from NHTSA API:
``` bash
php artisan import:mfmy
```

Import recalls for year/make/model from NHTSA API:
``` bash
php artisan import:rymm
```

Import makes vPIC NHTSA API:
``` bash
php artisan import:vpic-makes
```

Import Models For Each Make ID vPIC NHTSA API:
``` bash
php artisan import:vpic-models
```

Import Vehicle Types For Each Make ID vPIC NHTSA API:
``` bash
php artisan import:vpic-types
```