<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host   = getenv('DB_HOST');
$user   = getenv('DB_USER');
$pwd    = getenv('DB_PASSWORD');
$sql_db = getenv('DB_NAME');
$port   = (int) getenv('DB_PORT');
$sslCa  = getenv('DB_SSL_CA');

function ceztech_db_connect()
{
    global $host, $user, $pwd, $sql_db, $port, $sslCa;

    try {
        $db = mysqli_init();

        if (!$db) {
            throw new Exception('Unable to initialize MySQL connection.');
        }

        $db->ssl_set(
            null,
            null,
            $sslCa,
            null,
            null
        );

        if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
            $db->options(
                MYSQLI_OPT_SSL_VERIFY_SERVER_CERT,
                true
            );
        }

        $db->real_connect(
            $host,
            $user,
            $pwd,
            $sql_db,
            $port,
            null,
            MYSQLI_CLIENT_SSL
        );

        $db->set_charset('utf8mb4');

        return $db;

    } catch (Throwable $e) {
        error_log('Database connection error: ' . $e->getMessage());

        http_response_code(500);
        exit('Database connection failed.');
    }
}
?>