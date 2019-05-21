<?php

$ports = [
  'mysql' => '3306',
  'pgsql' => '',
  'sqlsrv' => '1433',
];

// Server DATABASE_URL
$db_url = ($db_url = env('DATABASE_URL')) ? parse_url( $db_url ) : null;

$db_host = $db_url ? $db_url['host']  : env('DB_HOST', '127.0.0.1');
$db_port = $db_url ? $db_url['port']  : env('DB_PORT', '');
$db_name = $db_url ? $db_url['host']  : env('DB_DATABASE', 'forge');
$db_user = $db_url ? $db_url['user']  : env('DB_USERNAME', 'forge');
$db_pass = $db_url ? $db_url['pass']  : env('DB_PASSWORD', '');


// Server REDIS_URL
$redis_url = ($redis_url = env('REDIS_URL')) ? parse_url( $redis_url ) : null;

$redis_host = $redis_url ? $redis_url['host']  : env('REDIS_HOST', '127.0.0.1');
$redis_port = $redis_url ? $redis_url['port']  : env('REDIS_PORT', 6379);
$redis_pass = $redis_url ? $redis_url['pass']  : env('REDIS_PASSWORD', null);


return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => $db_host,
            'port' => $db_port ?: '3306',
            'database' => $db_name,
            'username' => $db_user,
            'password' => $db_pass,
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $db_host,
            'port' => $db_port ?: '5432',
            'database' => $db_name,
            'username' => $db_user,
            'password' => $db_pass,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => $db_host ?: 'localhost',
            'port' => $db_port ?: '1433',
            'database' => $db_name,
            'username' => $db_user,
            'password' => $db_pass,
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'options' => [
          'cluster' => env('REDIS_CLUSTER', 'predis'),
        ],

        'default' => [
            'host' => $redis_host,
            'password' => $redis_pass ?: null,
            'port' => $redis_port ?: 6379,
            'database' => env('REDIS_redis', 0),
        ],

        'cache' => [
            'host' => $redis_host,
            'password' => $redis_pass ?: null,
            'port' => $redis_port ?: 6379,
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
