<?php
    namespace App;

    class Entorno {
        public static function getConex2() { 
            return 'host='.env('DB_HOST_SECOND', '127.0.0.1').' user='.env('DB_USERNAME_SECOND', 'forge').' password='.env('DB_PASSWORD_SECOND', '').' dbname='.env('DB_DATABASE_SECOND', 'forge'); 
        } 
    } 
?>