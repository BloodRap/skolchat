<?php

        session_start();
        include "classes.php";
        $users = new user();

?>
    <!DOCTYPE html>

    <html>

    <head>
        <link href="../Style/Style.css" type="text/css" rel="stylesheet" />
        <script language='javascript' src='ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
        <script type="text/javascript" src="../Js/jquery.js"></script>
        <title>Skol Chat Hem</title>
        <script type="text/javascript">
            $(document).ready(function() {

                $("#ChatText").keyup('submit', function(e) {
                    //enter press skiten

                    if (e.keyCode == 13) {
                        var ChatText = $("#ChatText").val();

                        if ($.trim(ChatText) == "") {
                            $("#ChatText").val("");
                            return false;
                        }

                        $.ajax({
                            type: 'POST',
                            url: 'InsertMessage.php',
                            data: {
                                ChatText: ChatText
                            },
                            success: function() {
                                $("#ChatMessages").load("DisplayMessages.php");
                                $("#ChatText").val("");
                                setTimeout(function(){
                                var textarea = document.getElementById('ChatMessages');
                                textarea.scrollTop = textarea.scrollHeight;}, 50);
                            }
                        });
                    }
                });
                setInterval(function() { //refreshar varje 500ms/0.5sec
                    $("#ChatMessages").load("DisplayMessages.php");
                    $("#rightBottom").load("OnlineUsers.php");
                }, 300);
            });

        </script>

    </head>
    <!--  Video Chat  -->
    <!--  <script src="http://iswebrtcready.appear.in/apiv2.js"></script>  -->
    <!--  <iframe src="https://appear.in/cyberchat" width="1336" height="280" frameborder="0"></iframe>  -->

    <body>
        <div class="leftSide">
            <div id="ChatMessages"></div>
            <textarea autofocus id="ChatText" name="ChatText" placeholder="Press Enter to send message..."></textarea>
        </div>
        <div class="rightSide">
            <div class="rightTop">
                <div class="user">
                    <h2 style="color:green"><?php echo $_SESSION['UserName'];?></h2><br>
                    <div class="avatar">
                        <?php
                            $users->uploadimage();
                        ?>
                    </div>
                </div>
                <div class="settings">
                    <a href="UserLogout.php" class="logout">Log Out</a>
                    <form action="" method="post" enctype="multipart/form-data" class="upload">
                        <label for="">Upload your image here</label>
                        <input type="file" name="file" onchange="this.form.submit()" /> <br>
                        <input type="submit" name="submit">
                    </form>
                </div>
            </div>
            <div class="OnlineUsersText">
                <h3>Online Users</h3>
            </div>
            <div class="rightBottom" id="rightBottom">
                <?php
                    $users->getOnlineUsers();
                ?>
            </div>
        </div>
    </body>

    </html>
