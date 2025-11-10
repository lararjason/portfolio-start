<?php
    $conn = mysqli_connect($_POST["host"], $_POST["dbuser"], $_POST["dbpass"]);
    if(!$conn) {
        displayMsg("error", "Wrong passowrd for database");
        exit();
    } else {
        
        $sql = "CREATE DATABASE " . $_POST["dbtabel"];
        try {
            $conn->query($sql);
            displayMsg("success", "Database created successfully");
        } catch(mysqli_sql_exception $e){
            displayMsg("error", "Database already exists");
        }
        
    }
    $conn->close();    
    $conn = mysqli_connect($_POST["host"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbtabel"]);
    if(!$conn) {
        displayMsg("error", "Wrong passowrd for database");
        exit();
    } else {    
        displayMsg("success", "make tabels plz");


        $sql = "CREATE TABLE project (
            project_id INT AUTO_INCREMENT PRIMARY KEY,
            namn VARCHAR(100) NOT NULL,
            info VARCHAR(500),
            external_link VARCHAR(100),
            thumbnail VARCHAR(100)
        );";
        makeTabel($conn, $sql, "project");


        $sql = "CREATE TABLE images (
            image_id INT AUTO_INCREMENT PRIMARY KEY,
            project_id INT,
            url VARCHAR(100)
        );";

        makeTabel($conn, $sql, "images");


        $sql = "CREATE TABLE categories (
            cat_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100)
        );";
        makeTabel($conn, $sql, "categories");

        $sql = "CREATE TABLE cat_relations (
            cat_id INT,
            project_id INT,
        );";
        makeTabel($conn, $sql, "cat_relations");

        $sql = "CREATE TABLE info (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            url VARCHAR(50),
            telefon VARCHAR(50),
            email VARCHAR(50),
            discord VARCHAR(50),
            about VARCHAR(500),
            image VARCHAR(100),
            welcome VARCHAR(500)
        );";

        makeTabel($conn, $sql, "info");


        $sql = "CREATE TABLE users (
            username VARCHAR(100) PRIMARY KEY,
            password VARCHAR(200) NOT NULL,
            role VARCHAR(100)
        );";

        makeTabel($conn, $sql, "users");

        $stmt = $conn->prepare("
            INSERT INTO info (name, url, telefon, email, discord, about, image, welcome)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $name = "Namn på sidan";
        $url = $_SERVER["HTTP_HOST"];
        $telefon = "070 111 22 33";
        $email = "namn@mail.com";
        $discord = "";
        $about = "Om text";
        $image = "";
        $welcome = "Kort underrubrik";

        $stmt->bind_param("ssssssss", $name, $url, $telefon, $email, $discord, $about, $image, $welcome);

        if ($stmt->execute()) {
            displayMsg("success", "Data tillagd i info. Uppdatera sen");
        } else {
            displayMsg("error", "Kunde inte lägga till info");
        }

        $stmt = $conn->prepare("
            INSERT INTO users (username, password, role)
            VALUES (?, ?, ?)
        ");

        $user = $_POST["admin"];
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $role = "admin";

        $stmt->bind_param("sss", $user, $pass, $role);

        if ($stmt->execute()) {
            displayMsg("success", "Användare tillagd.");
        } else {
            displayMsg("error", "Kunde inte lägga till användare");
        }

        $stmt = $conn->prepare("
            INSERT INTO projects (name, info, external_link, thumbnail)
            VALUES (?, ?, ?, ?)
        ");

        $name = "placeholder"
        $info = "Lorem Ipsum"
        $external_link = "";
        $thexternal_linkumbnail = "/assets/images/default.jpg";

        $stmt->bind_param("ssss", $name, $info, $external_link, $external_link);

        if ($stmt->execute()) {
            displayMsg("success", "Dummyprojekt tillagd.");
        } else {
            displayMsg("error", "Kunde inte lägga till Dummyprojekt");
        }
        $stmt->close();
        makeEnv();

    }

    function makeTabel($conn, $sql, $name) {
        try {
            $conn->query($sql);
            displayMsg("success", "Tabel " . $name . " created successfully");
        } catch(mysqli_sql_exception $e){
            displayMsg("error",  $name . " already exists");
        }
    }

    function makeEnv(){
        $env = [
            'DB_HOST' => $_POST["host"],
            'DB_PORT' => '3306',
            'DB_DATABASE' => $_POST["dbtabel"],
            'DB_USER' => $_POST["dbuser"],
            'DB_PASSWORD' => $_POST["dbpass"],
        ];
        $content = "";
        foreach ($env as $key => $value) {
            $content .= "{$key}={$value}\n";
        }

        $file = __DIR__ . '/.env';
        if (file_put_contents($file, $content)) {
            displayMsg("success", "All done");
        } else {
            echo "Något knas";
        }

        echo '<a href="/"> Gå tillbaka</a>';
    }
?>
