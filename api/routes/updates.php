<?php

function getUserUpdates() {
    $sql = "SELECT A.user_id, A.username, A.name, A.profile_pic, B.update_id, B.user_update, B.created FROM users A, updates B WHERE A.user_id=B.user_id_fk  ORDER BY B.update_id DESC";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $updates = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"updates": ' . json_encode($updates) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}

function deleteUpdate($update_id)
{
    $sql = "DELETE FROM updates WHERE update_id = ?";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute(array($update_id));
        $db = null;
        echo true;
    } catch(PDOException $e) {
        echo '{"error":{"message":'. $e->getMessage() .'}}';
    }
}


function insertUpdate(){
    echo json_encode(getBody());
}