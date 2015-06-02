<?php require('inc/common.php');

if(empty($_SESSION['user'])) { 
    header("Location: login.php"); 
    die("Redirecting to login.php");
}

$main_title = isset($_POST['main-title']) ? htmlentities($_POST['main-title'], ENT_QUOTES, 'UTF-8') : "";
$main_subtitle = isset($_POST['sub-title']) ? htmlentities($_POST['sub-title'], ENT_QUOTES, 'UTF-8') : "";
$bullet1 = isset($_POST['bullet1']) ? htmlentities($_POST['bullet1'], ENT_QUOTES, 'UTF-8') : "";
$bullet2 = isset($_POST['bullet2']) ? htmlentities($_POST['bullet2'], ENT_QUOTES, 'UTF-8') : "";
$bullet3 = isset($_POST['bullet3']) ? htmlentities($_POST['bullet3'], ENT_QUOTES, 'UTF-8') : "";
$video_title = isset($_POST['video-section-title']) ? htmlentities($_POST['video-section-title'], ENT_QUOTES, 'UTF-8') : "";
$video_content = isset($_POST['video-section-content']) ? $_POST['video-section-content'] : "";
$about_title = isset($_POST['about-section-title']) ? htmlentities($_POST['about-section-title'], ENT_QUOTES, 'UTF-8') : "";
$about_content = isset($_POST['about-section-content']) ? $_POST['about-section-content'] : "";
$bio_title = isset($_POST['bio-section-title']) ? htmlentities($_POST['bio-section-title'], ENT_QUOTES, 'UTF-8') : "";
$bio_content = isset($_POST['bio-section-content']) ? $_POST['bio-section-content'] : "";
$contact_title = isset($_POST['contact-section-title']) ? htmlentities($_POST['contact-section-title'], ENT_QUOTES, 'UTF-8') : "";
$contact_subtitle = isset($_POST['contact-section-subtitle']) ? htmlentities($_POST['contact-section-subtitle'], ENT_QUOTES, 'UTF-8') : "";
$new_vid = isset($_POST['new_vid']) ? htmlentities($_POST['new_vid'], ENT_QUOTES, 'UTF-8') : "";
$new_title = isset($_POST['new_title']) ? htmlentities($_POST['new_title'], ENT_QUOTES, 'UTF-8') : "";
$facebook_link = isset($_POST['facebook_link']) ? htmlentities($_POST['facebook_link'], ENT_QUOTES, 'UTF-8') : "";
$twitter_link = isset($_POST['twitter_link']) ? htmlentities($_POST['twitter_link'], ENT_QUOTES, 'UTF-8') : "";
$linkedin_link = isset($_POST['linkedin_link']) ? htmlentities($_POST['linkedin_link'], ENT_QUOTES, 'UTF-8') : "";


if(!empty($_POST)){
    $query = "
        UPDATE
            titles
        SET
            main_title = :main_title,
            main_subtitle = :main_subtitle,
            bullet1 = :bullet1,
            bullet2 = :bullet2,
            bullet3 = :bullet3,
            video_title = :video_title,
            video_content = :video_content,
            about_title = :about_title,
            about_content = :about_content,
            bio_title = :bio_title,
            bio_content = :bio_content,
            contact_title = :contact_title,
            contact_subtitle = :contact_subtitle,
            facebook_link = :facebook_link,
            twitter_link = :twitter_link,
            linkedin_link = :linkedin_link";
    $query_params = array(
        ":main_title" => $main_title,
        ":main_subtitle" => $main_subtitle,
        ":bullet1" => $bullet1,
        ":bullet2" => $bullet2,
        ":bullet3" => $bullet3,
        ":video_title" => $video_title,
        ":video_content" => $video_content,
        ":about_title" => $about_title,
        ":about_content" => $about_content,
        ":bio_title" => $bio_title,
        ":bio_content" => $bio_content,
        ":contact_title" => $contact_title,
        ":contact_subtitle" => $contact_subtitle,
        ":facebook_link" => $facebook_link,
        ":twitter_link" => $twitter_link,
        ":linkedin_link" => $linkedin_link
    );
    try { 
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex) {
        die($ex->getMessage());
    }
    for ($i = 0; $i < $num_of_videos; $i++) {
        $vid = htmlentities($_POST["id_".$row[$i]['id']."_vid"], ENT_QUOTES, 'UTF-8');
        $title = htmlentities($_POST["id_".$row[$i]['id']."_title"], ENT_QUOTES, 'UTF-8');
        $id = $row[$i]['id'];
        $query = "UPDATE videos SET vid = :vid, title = :title WHERE id = :id";
        $query_params = array(
            ":vid" => $vid,
            ":title" => $title,
            ":id" => $id
        );
        try { 
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            die($ex->getMessage());
        }
        
        if (isset($_POST['delete_id_'.$id])) {
            $query = "DELETE FROM videos WHERE id = :id";
            $query_params = array(
                ":id" => $id
            );
            try { 
                $stmt = $db->prepare($query);
                $result = $stmt->execute($query_params);
            }
            catch(PDOException $ex) {
                die($ex->getMessage());
            }
            
            $key = array_search($id, $order);
            unset($order[$key]);
            $order_combined = implode(',', $order);
        }
        
    }
    
    $query = "UPDATE video_order SET data = :data WHERE id = 1";
    $query_params = array(
        ":data" => $order_combined
    );
    try { 
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex) {
        die($ex->getMessage());
    }
    
    if ($_POST['new_vid'] != "") {
        $query = "INSERT INTO videos SET vid = :vid, title = :title";
        $query_params = array(
            ":vid" => $new_vid,
            ":title" => $new_title
        );
        try { 
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
            $added_id = $db->lastInsertId();
        }
        catch(PDOException $ex) {
            die($ex->getMessage());
        }
        
        $query = "UPDATE video_order SET data = :data WHERE id = 1";
        $query_params = array(
            ":data" => strval($added_id).",".$order_combined
        );
        try { 
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch(PDOException $ex) {
            die($ex->getMessage());
        }
    }
    
    header("Location: admin.php");
}

?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Mike Boris</title>
        <link rel="shortcut icon" href="http://faviconist.com/icons/029cf779921987daaad49d9662e39800/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    
        <script src="js/jquery-1.11.3.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>
        <div id="admin-header"><a href="logout.php" class="admin-menu-link">Logout</a><a href="admin-settings.php" class="admin-menu-link">Account</a><a href="index.php" class="admin-menu-link">Back to website</a><input id="submit-button" type="submit" value="Submit" form="admin-form"></div>
        <form id="admin-form" action="" method="post">
            Main title: <input type="text" name="main-title" value="<?php echo $titles[0]['main_title']; ?>"><br><br>
            Subtitle: <input type="text" name="sub-title" value="<?php echo $titles[0]['main_subtitle']; ?>"><br><br>
            <hr>
            About section title: <input type="text" name="about-section-title" value="<?php echo $titles[0]['about_title']; ?>"><br><br>
            About section content: <textarea name="about-section-content"><?php echo $titles[0]['about_content']; ?></textarea><br>
            Bulletpoints:
            <div class="section group">
                <div class="col span_1_of_3">
                    <input type="text" name="bullet1" value="<?php echo $titles[0]['bullet1']; ?>">
                </div>
                <div class="col span_1_of_3">
                    <input type="text" name="bullet2" value="<?php echo $titles[0]['bullet2']; ?>">
                </div>
                <div class="col span_1_of_3">
                    <input type="text" name="bullet3" value="<?php echo $titles[0]['bullet3']; ?>">
                </div>
            </div><hr>
            Video section title: <input type="text" name="video-section-title" value="<?php echo $titles[0]['video_title']; ?>"><br><br>
            Video section content: <textarea name="video-section-content"><?php echo $titles[0]['video_content']; ?></textarea>
            <br>
            <ul id="sortable">
                <?php

                for ($i=0; $i < count($order); $i++) {
                    for ($j=0; $j < count($order); $j++) {
                        if ($row[$j]['id'] == $order[$i]) {
                            echo "<li id='id_".$row[$j]['id']."'>";
                            echo "<img class ='drag-arrow' src='images/dragarrow.png'/>";
                            echo "<input class = 'video-id' type='text' name='id_".$row[$j]['id']."_vid' value='".$row[$j]['vid']."'>";
                            echo "<input class = 'video-title' type='text' name='id_".$row[$j]['id']."_title' value='".$row[$j]['title']."'>";
                            echo "<div class='delete-box'><input type='checkbox' label = 'Delete' name='delete_id_".$row[$j]['id']."' value='delete'> Delete</div></li>";
                        }
                    }
                }

                  ?>
            </ul>
            <br>
            Add video:
            <input class="video-id" type="text" name="new_vid" placeholder="Video ID">
            <input class="video-title" type="text" name="new_title" placeholder="Title">
            <hr>
            Bio section title:
            <input type="text" name="bio-section-title" value="<?php echo $titles[0]['bio_title']; ?>"><br><br>
            Bio section content:
            <textarea name="bio-section-content"><?php echo $titles[0]['bio_content']; ?></textarea>
            <hr>
            Contact section title:
            <input type="text" name="contact-section-title" value="<?php echo $titles[0]['contact_title']; ?>"><br><br>
            Contact section subtitle:
            <input type="text" name="contact-section-subtitle" value="<?php echo $titles[0]['contact_subtitle']; ?>">
            <hr>
            Facebook link:
            <input type="text" name="facebook_link" value="<?php echo $titles[0]['facebook_link']; ?>">
            Twitter link:
            <input type="text" name="twitter_link" value="<?php echo $titles[0]['twitter_link']; ?>">
            LinkedIn link:
            <input type="text" name="linkedin_link" value="<?php echo $titles[0]['linkedin_link']; ?>">
        </form>
        <br><br><br>

        <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
        
        <script type="text/javascript">
        tinymce.init({
            selector: "textarea",
            menubar : false,
            plugins: ["textcolor emoticons image link preview code"],
            toolbar: "code | undo redo | fontsizeselect | alignleft aligncenter alignright alignjustify  | bold italic underline | emoticons | link image | preview"
        });
        </script>
    </body>
</html>