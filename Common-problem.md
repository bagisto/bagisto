# Common Error in   ``` php artisan migrate``` 

## I got This error when i was running this command   
``` php artisan migrate ```

**Error code** <br/>
```
In Connection.php line 664:

     SQLSTATE[HY000] [1045] Access denied for user 'cmadmin'@'ec2_privateIP' (using password: YES) (SQL: select * from information_schema.tables where table_schema = dbname_cm and table_name = migrations)


In Connector.php line 67:

  SQLSTATE[HY000] [1045] Access denied for user 'cmadmin'@'ec2_privateIP' (using password: YES)
```

## Here is an excerpt of the .env file:
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=generatedkey
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=....rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=dbname_cm/bagisto
DB_USERNAME=cmadmin/simran
DB_PASSWORD=root
```

## Here's an excerpt of the config/database.php file:
```
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', ''),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', ''),
    'username' => env('DB_USERNAME', ''),
    'password' => env('DB_PASSWORD', ''),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_0900_ai_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
    'option'  => [
        PDO::MYSQL_ATTR_SSL_CA => base_path('/var/mysql-cert/dbname_cm-db.pem')
    ],
],
``` 
## I even went as far as to add the following to app/Providers/AppServiceProvider.php
```
public function boot()
    {
       Schema::defaultStringLength(191);
    }
```

## I have already ran: php artisan cache:clear php artisan config:clear php artisan config:cache

* PHP 7.2.24-0ubuntu0.18.04.4 Laravel Framework 5.5.49 AWS RDS: MySQL 8.0.17
```
mysql> SELECT USER(),CURRENT_USER();
+------------------------+----------------+
| USER()                 | CURRENT_USER() |
+------------------------+----------------+
| cmadmin@ec2_privateIP  | cmadmin@%      |
+------------------------+----------------+
mysql> show grants;
| Grants for cmadmin@% |
| GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, RELOAD, PROCESS, REFERENCES, INDEX, ALTER, SHOW DATABASES, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, REPLICATION SLAVE, REPLICATION CLIENT, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, CREATE USER, EVENT, TRIGGER ON *.* TO `cmadmin`@`%` WITH GRANT OPTION |
mysql> show databases;
+--------------------+
| Database           |
+--------------------+
|simran       |
| information_schema |
| mysql.sys              |
| performance_schema |
+--------------------+
```
  
