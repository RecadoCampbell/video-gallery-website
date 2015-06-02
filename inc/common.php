<?php
// Include database and email credentials
require('inc/credentials.php');

// Tell MySQL we want to communicate with UTF-8
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 

// Connect to database. If error, kill page and leave message
try { $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options); }
catch(PDOException $ex) { die("We are currently experiencing technical difficulties. Please try again later."); } 

// From PDO: Tells database how to handle errors
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

// From PDO: Tells database how to return fetched results
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// From PDO: Turns off prepare emulation, which is unnecessary with modern MySQL versions
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Undo magic quotes
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) { 
function undo_magic_quotes_gpc(&$array) { 
    foreach($array as &$value) 
    { 
        if(is_array($value)) 
        { 
            undo_magic_quotes_gpc($value); 
        } 
        else 
        { 
            $value = stripslashes($value); 
        } 
    } 
} 

undo_magic_quotes_gpc($_POST); 
undo_magic_quotes_gpc($_GET); 
undo_magic_quotes_gpc($_COOKIE); 
} 

// This tells the web browser that your content is encoded using UTF-8 
header('Content-Type: text/html; charset=utf-8'); 

// Initialize session
session_start();

// Get video information from database
$query = "SELECT * FROM videos";
try { 
    $stmt = $db->prepare($query);
    $result = $stmt->execute();
}
catch(PDOException $ex) {
    die($ex->getMessage());
}
$row = $stmt->fetchAll();

// Find number of videos
$num_of_videos = count($row);
// Determine how many rows are required
$rows_required = ceil($num_of_videos / 3);

// Get all text information from database
$query = "SELECT * FROM titles";
try { 
    $stmt = $db->prepare($query);
    $result = $stmt->execute();
}
catch(PDOException $ex) {
    die($ex->getMessage());
}
$titles = $stmt->fetchAll();

// Get the order of videos from the database
$query = "SELECT data FROM video_order WHERE id=1";
try {
    $stmt = $db->prepare($query);
    $result = $stmt->execute();
} catch(PDOException $ex) {
    die($ex->getMessage());
}
$order_combined = $stmt->fetchAll();
$order = explode(',', $order_combined[0]['data']);
$order_combined = implode(',', $order);