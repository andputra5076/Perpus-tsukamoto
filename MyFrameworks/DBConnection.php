<?php
class DBConnection {
    public static function getDBConnection() {
        $con = mysqli_connect("localhost", "root", "", "perpus_tsukamoto_db");
        return $con;
    }
}

// Usage
$con = DBConnection::getDBConnection();
