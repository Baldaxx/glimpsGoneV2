
# Setup development

```shell
cp ./config/.config_example.php ./config/config.php
```

Set the app configuration in the copied file :
  * DB: The database configuration
  * APP_NAME: the name of the project


Install the database :
```shell
php database/resetDb.php
```

Setup dependencies :
```shell
composer install
```
