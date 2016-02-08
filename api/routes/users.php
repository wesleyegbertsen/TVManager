<?php

// GET http://www.yourwebsite.com/api/users
function getUsers() {
    $sql = "SELECT user_id,username,name,password FROM users ORDER BY user_id DESC";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"users": ' . json_encode($users) . '}';
    } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}

// GET http://www.yourwebsite.com/api/users/search/sri
function getUserSearch($query) {
    $sql = "SELECT user_id,username,name,password FROM users WHERE username LIKE ? ORDER BY user_id DESC";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute(array("%$query%"));
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"users": ' . json_encode($users) . '}';
    } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}

function login () {

    $sql = "SELECT user_id, username, name, password FROM users WHERE username = ?";
    try {
        $body = getBody();
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute(array($body->username));
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        if(sizeof($users) > 0) {
            $user = reset($users);
            $salt = substr($user->password, 0, 10);
            $password = substr($user->password, 10);

            if($password == hash('sha256', $salt . $body->password)) {
                $_SESSION["user"]["isLoggedIn"] = true;
                $_SESSION["user"]["id"] = $user->user_id;
                $_SESSION["user"]["username"] = $user->username;
                $_SESSION["user"]["name"] = $user->name;
                echo '{"success": true, "user": ' . json_encode($_SESSION["user"]) . '}';
            } else {
                echo '{"success": false, "message": "Wrong password."}';
            }

        } else {
            echo '{"success": false, "message": "Username not found."}';
        }

    } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}

function logout () {
    unset($_SESSION["user"]);

    if (isset($_SESSION["user"])) {
        echo '{"success": false}';
    } else {
        echo '{"success": true}';
    }
}

function isLoggedIn () {
    if (isset($_SESSION["user"])) {
        echo '{"success": true, "user": ' . json_encode($_SESSION["user"]) . '}';
    } else {
        echo '{"success": false}';
    }
}

function register() {
    $salt = substr(uniqid(mt_rand(), true),10, 10);
    $password = $salt . hash('sha256', $salt . getBody()->password);
    echo '{"password": "'.$password.'"}';
}