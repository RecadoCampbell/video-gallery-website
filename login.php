<?php require('inc/common.php');

$error_list = [];

if(!empty($_POST)) { 
    // Get user info
    $username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
    $raw_password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');
    
    $query = " 
        SELECT 
            uid, 
            username, 
            password, 
            salt
        FROM user 
        WHERE 
            username = :username
    ";
    $query_params = array(
        ':username' => $username
    );
    try { 
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params); 
    } 
    catch(PDOException $ex) {
        error_log("login.php: Failed to fetch user data: " . $ex->getMessage(), 0);
        header('Location: error.php');
        die('error');
    }

    $login_ok = false; 
    $row = $stmt->fetch();

    // Check password with hash/salt
    if($row) { 
        $check_password = hash('sha256', $raw_password . $row['salt']); 
        for($round = 0; $round < 65536; $round++) { 
            $check_password = hash('sha256', $check_password . $row['salt']); 
        } 

        if($check_password === $row['password']) { 
            $login_ok = true; 
        } 
    } 

    if($login_ok) { 
        // Remove pass and salt from $_SESSION just to be safe
        unset($row['salt']);
        unset($row['password']);

        // Store user data in $_SESSION
        $_SESSION['user'] = $row;

        header("Location: admin.php");
        die("Login successful");
    } 
    else { 
        $error_list[] = 'Wrong username or password'; 
    }
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <?php if(!empty($error_list)) { echo "<div class='error'>Wrong username or password</div>"; } ?>
        <form action="" method="post">
            Username:
            <input type="text" name="username"><br>
            Password:
            <input type="password" name="password"><br><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>