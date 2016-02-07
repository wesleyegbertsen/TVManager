<?php

function getSettings () {
    $sql = "SELECT setting_name,setting_value FROM settings";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"settings": ' . json_encode($settings) . '}';
    } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}

function getSetting($value) {
    $sql = "SELECT setting_value FROM settings WHERE setting_name = ?";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute(array($value));
        $setting = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if(sizeof($setting) > 0) {
            echo '{"setting":"' . $setting[0]->setting_value . '"}';
        } else {
            echo '{"error":{"message":"Setting not found"}}';
        }
    } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}