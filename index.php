<?php
require('inc/common.php');

// If the user sent an e-mail...
if (!empty($_POST)) {
    // Set function parameters from POST data
    $firstName = isset($_POST['first-name']) ? htmlentities($_POST['first-name'], ENT_QUOTES, 'UTF-8') : "";
    $lastName = isset($_POST['last-name']) ? htmlentities($_POST['last-name'], ENT_QUOTES, 'UTF-8') : "";
    $email = isset($_POST['email']) ? htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8') : "";
    $company = isset($_POST['company']) ? htmlentities($_POST['company'], ENT_QUOTES, 'UTF-8') : "";
    $subject = isset($_POST['subject']) ? htmlentities($_POST['subject'], ENT_QUOTES, 'UTF-8') : "";
    $message = isset($_POST['message']) ? htmlentities($_POST['message'], ENT_QUOTES, 'UTF-8') : "";

    // Send the e-mail with parameters. Error checking is done via Javascript
    send_email($firstName, $lastName, $email, $company, $subject, $message);
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Mike Boris</title>
        <link rel="shortcut icon" href="http://faviconist.com/icons/029cf779921987daaad49d9662e39800/favicon.ico" />
        <meta name="description" content="">
        <link rel="stylesheet" type="text/css" href="css/prettyPhoto.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <header>
            <div id="social-link-container">
                <a href="<?php echo $titles[0]['facebook_link']; ?>" target="_blank"><img class="social-link" src="images/linkedin.png"/></a>
                <a href="<?php echo $titles[0]['twitter_link']; ?>" target="_blank"><img class="social-link" src="images/twitter.png"/></a>
                <a href="<?php echo $titles[0]['linkedin_link']; ?>" target="_blank"><img class="social-link" src="images/facebook.png"/></a>
            </div>
            <span id="title-font"><?php echo $titles[0]['main_title']; ?></span><br>
            <span id="subtitle-font"><?php echo $titles[0]['main_subtitle']; ?></span><br>
            <ul id="nav">
                <a href="#about"><li>About</li></a>
                <a href="#work"><li id="nav-mid-l">Work</li></a>
                <a href="#bio"><li id="nav-mid-r">Bio</li></a>
                <a href="#contact"><li>Contact</li></a>
            </ul>
        </header>
        <a class="anchor" name="about"></a>
        <div id="main-container">
            <img id="main-picture" src="images/MikeBorisPageFACE.jpg"/><br>
            <span class="label-font"><?php echo $titles[0]['about_title']; ?></span><br><br>
            <?php echo $titles[0]['about_content']; ?>
            <div class="section group bullets">
                <div class="col span_1_of_3">
                    <?php echo $titles[0]['bullet1']; ?><br>
                    <div class="circle"></div>
                </div>
                <div class="col span_1_of_3">
                    <?php echo $titles[0]['bullet2']; ?><br>
                    <div class="circle"></div>
                </div>
                <div class="col span_1_of_3">
                    <?php echo $titles[0]['bullet3']; ?><br>
                    <div class="circle"></div>
                </div>
            </div>
            <a class="anchor" name="work"></a>
            <span class="label-font"><?php echo $titles[0]['video_title']; ?></span><br><br>
            <?php echo $titles[0]['video_content']; ?><br><br>      
            
            <?php
            $order_key = 0;
            for ($i = 0; $i < $rows_required; $i++) {
                echo "<div class='section group'>";
                for ($j = 0; $j < 3; $j++) {
                    for ($k = 0; $k < count($order); $k++) {
                        if ($row[$k]['id'] == $order[$order_key]) {
                            $thumbnail_link = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$row[$k]['vid'].".php"));
                            echo "<div class='col span_1_of_3'>";
                            echo "<div class='video-container'>";
                            echo "<a href='http://www.vimeo.com/".$row[$k]['vid']."' rel='prettyPhoto'>";
                            echo "<div class='video-thumbnail' style='background-image: url(".$thumbnail_link[0]['thumbnail_large'].")'></div></a><br>";
                            echo $row[$k]['title'];
                            echo "</div></div>";
                        }
                    }
                    $order_key += 1;
                    if ($order_key == $num_of_videos) {
                        break;
                    }
                }
                echo "</div>";
            }
            ?><br>
            <a class="anchor" name="bio"></a>
            <span class="label-font"><?php echo $titles[0]['bio_title']; ?></span><br><br>
            <?php echo $titles[0]['bio_content']; ?><br><br> 
            
            <a class="anchor" name="contact"></a>
            <span class="label-font"><?php echo $titles[0]['contact_title']; ?></span><br>
            <span class="sublabel-font">I'd like to hear from you!</span><br><br>
            <form id="email-form" method="post">
                <div class="section group">
                    <div class="col span_1_of_2">
                        <input class="name-input" type="text" name="first-name" placeholder="First Name">
                    </div>
                    <div class="col span_1_of_2">
                        <input class="name-input" type="text" name="last-name" placeholder="Last Name">
                    </div>
                </div>
                <div class="section group">
                    <div class="col span_1_of_2">
                        <input type="text" name="email" placeholder="Email (required)">
                    </div>
                    <div class="col span_1_of_2">
                        <input type="text" name="company" placeholder="Company">
                    </div>
                </div>
                <div class="section group">
                    <div class="col span_2_of_2">
                        <input type="text" name="subject" placeholder="Subject">
                    </div>
                </div>
                <div class="section group">
                    <div class="col span_2_of_2">
                        <textarea name="message" placeholder="Type Message"></textarea>
                    </div>
                </div>
                <div id="email-send">Send</div>
            </form>
            <br><br><br><br><br><br>
        </div>
        <script type="text/javascript" charset="utf-8" src="js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/jquery.prettyPhoto.js"></script>
        <script type="text/javascript" charset="utf-8" src="js/main.js"></script>
    </body>
</html>