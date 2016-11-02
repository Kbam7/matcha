<?php

    include 'database.php';

    try {

        // Create new PDO object. i.e. Connection to the database
        $db_conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

        // Set attributes/options for this connection
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($argc === 2 && $argv[1] == 'purge') {
            $db_conn->exec('DROP DATABASE IF EXISTS camagru;');
            echo "\nThe database was DROPPED!\n";
        } else {

            echo "\nCreating camagru database and adding tables if the do not already exist\n";

            // Create the database
            $db_conn->exec('CREATE DATABASE IF NOT EXISTS camagru;');

            // Use this database
            $db_conn->exec('USE camagru;');

            // Make user table
            $db_conn->exec('CREATE TABLE IF NOT EXISTS `users` (
        		`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        		`active` TINYINT(1) NOT NULL DEFAULT 0,
                `hash` VARCHAR(32),
        		`firstname` VARCHAR(32) NOT NULL,
        		`lastname` VARCHAR(32) NOT NULL,
        		`username` VARCHAR(24) NOT NULL,
        		`password` VARCHAR(255) NOT NULL,
        		`email` VARCHAR(64) NOT NULL);');

            // Make images table
            $db_conn->exec('CREATE TABLE IF NOT EXISTS `images` (
    		`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		`userid` INT(6) UNSIGNED NOT NULL,
    		`title` VARCHAR(128),
            `path` VARCHAR(255) NOT NULL,
    		`date` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL);');

            // Make likes table
            $db_conn->exec('CREATE TABLE IF NOT EXISTS `likes` (
    		`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		`imgid` INT(6) NOT NULL,
    		`userid` INT(6) NOT NULL);');

            // Make comments table
            $db_conn->exec('CREATE TABLE IF NOT EXISTS `comments` (
    		`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		`imgid` INT(6) NOT NULL,
    		`userid` INT(6) NOT NULL,
    		`comment` VARCHAR(255) NOT NULL,
    		`date` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL);');
        }
    } catch (PDOException $e) {
        echo "<b><u>Error Message :</u></b><br /> '.$e.' <br /><br /> <b><u>For error details, check :</u></b><br /> ".dirname(__DIR__).'/log/errors.log'.'</p>';
        error_log($e, 3, dirname(__DIR__).'/log/errors.log');
    }
    $db_conn = null;
