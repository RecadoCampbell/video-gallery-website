$(document).ready(function(){
    // Uploads the order of videos to the database as a string
    $("#submit-button").click(function(){
        var postData = $("#sortable").sortable('serialize');

            $.post('order.php', {list: postData}, function(data) {
                
            }).fail(function() {
                //alert("fail"); 
            });
    });
    
    // Activate PrettyPhoto
    $("a[rel^='prettyPhoto']").prettyPhoto();

    // Scroll to anchors when user clicks the menu
    $("a").click(function() {
        if (this.hash) {
            var hash = this.hash.substr(1);
            var $toElement = $("a[name="+hash+"]");
            var toPosition = $toElement.position().top;
            $("body,html").stop().animate({
                scrollTop : toPosition
            },2000,"easeOutExpo");
            return false; 
        }
    });
    $(window).scroll(function() {
        if ($(this).scrollTop() > 1){  
            $('#title-font, #subtitle-font').fadeOut();
            $('#nav').addClass("sticky");
            $('header').addClass("sticky");
        }
        else{
            $('header').removeClass("sticky");
            $('#nav').removeClass("sticky");
            $('#title-font, #subtitle-font').fadeIn();
        }
    });
    
    // When user sends an email, check if they entered a valid email address
    // If so, send the email
    $('#email-send').click(function() {
        var email = document.forms["email-form"]["email"].value;
        function validateEmail(x) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(x);
        }
        if (email == null || email == "") {
            alert("Email address required");
        } else if (!validateEmail(email)) {
            alert("Invalid email address");
        } else {
            document.getElementById("email-form").submit();
        }
    });
});