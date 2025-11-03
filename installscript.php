<?php

    $conn = mysqli_connect($_POST["host"], $_POST["dbuser"], $_POST["dbpass"]);
    if(!$conn) {
        displayMsg("error", "Wrong passowrd for database");
        exit();
    } else {
        displayMsg("success", "Connected");
    }
    /**
     * Här vill jag att ni fortsätter, ni ska
     * 1. Skapa en databas.
     * 2. Skapa tabeller
     * 3. Lägga in eventuell dummy data som behövs direkt
     * 4. Skapa en användare i users-tabellen
     * 4. Skapa en .env som vi använder i fortsättningen.
     * https://www.w3schools.com/php/php_mysql_create_table.asp
     */
?>
