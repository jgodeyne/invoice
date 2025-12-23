<?php
include_once '../ppa/database_class.php';
session_start();
$db = new Database();
$conn = $db->connect();

// Basic input check
if (!isset($_POST['user']) || !isset($_POST['pass'])) {
    $_SESSION['authorized'] = false;
    $_SESSION['userlevel'] = 0;
    header("Location: ../login/login.php?login_error=Missing credentials.");
    exit;
}

$username = $_POST['user'];
$password = $_POST['pass'];

// Use prepared statement to fetch user record
if ($stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1')) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows != 0) {
        $user = $res->fetch_assoc();
        $stored = $user['password'];
        $password_ok = false;

        // If stored password looks like a modern hash, use password_verify
        if (strpos($stored, '$') === 0) {
            if (password_verify($password, $stored)) {
                $password_ok = true;
                // Rehash if algorithm changed
                if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                    $newhash = password_hash($password, PASSWORD_DEFAULT);
                    if ($upd = $conn->prepare('UPDATE users SET password = ? WHERE id = ?')) {
                        $upd->bind_param('si', $newhash, $user['id']);
                        $upd->execute();
                    }
                }
            }
        } else {
            // Legacy MD5 check — if it matches, upgrade to password_hash
            if ($stored === md5($password)) {
                $password_ok = true;
                $newhash = password_hash($password, PASSWORD_DEFAULT);
                if ($upd = $conn->prepare('UPDATE users SET password = ? WHERE id = ?')) {
                    $upd->bind_param('si', $newhash, $user['id']);
                    $upd->execute();
                }
            }
        }

        if ($password_ok) {
            $_SESSION['authorized'] = true;
            $_SESSION['userlevel'] = $user['userlevel'];
            header("Location: ../job/job_list.php");
            exit;
        }
    }
}

// Authentication failed
$_SESSION['authorized'] = false;
$_SESSION['userlevel'] = 0;
header("Location: ../login/login.php?login_error=Gebruikersnaam of wachtwoord ongekend.");
exit;
?>
