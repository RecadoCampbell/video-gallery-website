<?php require('inc/common.php');

if(empty($_SESSION['user'])) { 
    header("Location: login.php"); 
    die("Redirecting to login.php");
}

$raw_password = isset($_POST['password']) ? htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8') : "";
$raw_password_conf = isset($_POST['passwordconf']) ? htmlentities($_POST['passwordconf'], ENT_QUOTES, 'UTF-8') : "";
$username = isset($_POST['username']) ? htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8') : "";

$password_match = true;
$error_list=[];

$query = "SELECT * FROM user WHERE uid = 1";
try {
    $stmt = $db->prepare($query); 
    $result = $stmt->execute(); 
} 
catch(PDOException $ex) {
    die($ex->getMessage());
}
$user = $stmt->fetchAll();

if(!empty($_POST)){

    if(!empty($raw_password)) {
        // Check if passwords match
        if($raw_password != $raw_password_conf){
            $password_match = false;
        }
        else{
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
            $password = hash('sha256', $raw_password . $salt); 
            for($round = 0; $round < 65536; $round++) { 
                $password = hash('sha256', $password . $salt); 
            } 
        }
    } 
    else { 
        // If the user did not enter a new password we will not update their old one. 
        $password = null; 
        $salt = null; 
    }

    $query = "UPDATE user SET username = :username";

    if($password !== null && password_match == true) { 
        $query .= ", password = :password, salt = :salt"; 
    } elseif($password_match == false) {
        $error_list[] = "Passwords do not match";
    }

    $query .= " WHERE uid = 1";

    // Initial query parameter values 
    $query_params = array(  
        ':username' => $username
    );

    if($password !== null && password_match == true) { 
        $query_params[':password'] = $password; 
        $query_params[':salt'] = $salt; 
    } 

    try {
        $stmt = $db->prepare($query); 
        $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex) {
        die($ex->getMessage());
    }
    
    header("Location: admin-settings.php");
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Mike Boris</title>
        <link rel="shortcut icon" href="http://faviconist.com/icons/029cf779921987daaad49d9662e39800/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    
        <script src="js/main.js"></script>
    </head>
    <body>
        <div id="admin-header"><a href="logout.php" class="admin-menu-link">Logout</a><a href="admin.php" class="admin-menu-link">Page Editor</a><a href="index.php" class="admin-menu-link">Back to website</a><input id="submit-button" type="submit" value="Submit" form="admin-form"></div>
        <br><br><br><br>
        <?php if(!empty($error_list)) { echo "<div class='error'>Passwords do not match</div>"; } ?>
        <form id="admin-settings-form" action="" method="post">
            Account settings:
            <table id="account-settings">
                <tr>
                    <td class="left-column">Username</td>
                    <td><input type="text" name="username" value="<?php echo $user[0]['username'];?>"></td>
                </tr>
                <tr>
                    <td class="left-column">Password<br><i>Leave blank to keep current password</i></td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td class="left-column">Confirm Password</td>
                    <td><input type="password" name="passwordconf"></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="Submit">
        </form>
        <br>
        <br><br><br>
    </body>
</html>