<?php
//fucking klasser Users Table
class user{
    private $UserId,$UserName,$UserMail,$UserPassword;
    public function uploadimage(){
        include "conn.php";
        if(isset($_POST['submit'])){
            move_uploaded_file($_FILES['file']['tmp_name'],"../images/".$_FILES['file']['name']);
            $data = $bdd -> query("UPDATE users SET UserImage = '".$_FILES['file']['name']."' WHERE UserName = '".$_SESSION['UserName']."'");
        }
        $data = $bdd ->query("SELECT UserImage, UserName FROM users WHERE UserName = '".$_SESSION['UserName']."'");
        while($row = $data ->fetch()){
            if(empty($row['UserImage'])){
                echo "<img width='120' height='120' src='../images/userdefault.jpg' alt='Default Profile Pic' style ='border-radius:50%;'>";
            }
            else {
                echo "<img width='120' height='120' src='../images/".$row['UserImage']."' alt='Profile Pic' style ='border-radius:50%;'>";
            }
            echo "<br>";
        }
    }


    public function getOnlineUsers() {
        include "conn.php";
        $data = $bdd->query("SELECT UserName, UserImage FROM users WHERE online = 1 AND UserName != '".$_SESSION['UserName'] ."'");
        while($row = $data->fetch()){
        ?>
        <div class="onlineUsersContainer">
            <span class="onlineUserImage">
            <?php
                if (empty($row['UserImage'])) {
                    echo "<img width='45' height='45' src='../images/userdefault.jpg' alt='Default Profile Pic' style ='border-radius:50%;'>";
                }
                else {
                    echo "<img width='45' height='45' src='../images/".$row['UserImage']."' alt='Profile Pic' style ='border-radius:50%;'>";
                }
           ?>
            </span>
            <a href="#"><?php echo $row['UserName'];?></a><br>
        </div>
        <?php
        }
    }
        // ^ online users ^
    public function getUserId(){
        return $this->UserId;
    }
    public function setUserId($UserId){
        $this->UserId=$UserId;
    }
    public function getUserName(){
        return $this->UserName;
    }
    public function setUserName($UserName){
        $this->UserName=$UserName;
    }
    public function getUserMail(){
        return $this->UserMail;
    }
    public function setUserMail($UserMail){
        $this->UserMail=$UserMail;
    }
    public function getUserPassword(){
        return $this->UserPassword;
    }
    public function setUserPassword($UserPassword){
        $this->UserPassword=$UserPassword;
    }

    public function InsertUser(){
        include "conn.php";
        $req=$bdd->prepare('SELECT * FROM users WHERE UserName=:UserName');
        $req->execute(array(
        'UserName'=>$this->getUserName()
        ));
        if ($req->rowCount()>0){
            $req=$bdd->prepare('SELECT * FROM users WHERE UserMail=:UserMail');
            $req->execute(array(
            'UserMail'=>$this->getUserMail()
            ));
            if ($req->rowCount()>0){
                header("Location: ../index.php?error4=1");
                return false;
            }
            header("Location: ../index.php?error2=1");
            return false;
        }
        $req=$bdd->prepare('SELECT * FROM users WHERE UserMail=:UserMail');
        $req->execute(array(
        'UserMail'=>$this->getUserMail()
        ));
        if ($req->rowCount()>0){
            header("Location: ../index.php?error3=1");
            return false;
        }
        else {
            $req=$bdd->prepare('INSERT INTO users(UserName,UserMail,UserPassword) VALUES(:UserName,:UserMail,:UserPassword)');
            $req->execute(array(
            'UserName'=>$this->getUserName(),
            'UserMail'=>$this->getUserMail(),
            'UserPassword'=>$this->getUserPassword()
            ));
            header("Location: ../index.php?success=1");
            }
        }

    public function UserLogin(){

        include "conn.php";
        $req=$bdd->prepare('SELECT * FROM users WHERE UserMail=:UserMail AND UserPassword=:UserPassword');
        $req->execute(array(
        'UserMail'=>$this->getUserMail(),
        'UserPassword'=>$this->getUserPassword()
        ));
    if ($req->rowCount()==0){
       header("Location: ../index.php?error1=1");
        return false;
    }
    else{

        while($data=$req->fetch()){
            $this->setUserId($data['UserId']);
            $this->setUserName($data['UserName']);
            $this->setUserPassword($data['UserPassword']);
            $this->setUserMail($data['UserMail']);
            $req=$bdd->prepare("UPDATE users SET online = '1' WHERE UserName =:UserName LIMIT 1");
            $req->execute(array('UserName' => $data['UserName']));
            header("Location: Home.php");
            return true;

          }
      }

   }
    }


//fucking klasser för chats table
class chat {
    private $ChatId,$ChatUserId,$ChatText;

    public function getChatId(){
        return $this->ChatId;
    }
    public function setChatId($ChatId){
        $this->ChatId = $ChatId;
    }
    public function getChatUserId(){
        return $this->ChatUserId;
    }
    public function setChatUserId($ChatUserId){
        $this->ChatUserId = $ChatUserId;
    }
    public function getChatText(){
        return $this->ChatText;
    }
    public function setChatText($ChatText){
        $this->ChatText = $ChatText;
    }
//Fuckin message insert code
    public function InsertChatMessage(){
        include "conn.php";

        $req=$bdd->prepare('INSERT INTO chats(ChatUserId,ChatText) VALUES(:ChatUserId,:ChatText)');
        $req->execute(array(
            'ChatUserId'=>$this->getChatUserId(),
            'ChatText'=>$this->getChatText()
        ));
    }

    public function DisplayMessage(){
        include "conn.php";
        $ChatReq=$bdd->prepare("SELECT * FROM chats ORDER BY ChatId*-1 DESC");
        $ChatReq->execute();

        while($DataChat= $ChatReq->fetch()){
            $UserReq=$bdd->prepare("SELECT * FROM users WHERE UserId=:UserId");
            $UserReq->execute(array(
                'UserId'=>$DataChat['ChatUserId']
            ));
            $DataUser = $UserReq->fetch();
            ?>
            <span class="mainSpan">
                <span class="UserImage">
                <?php
                    $data=$bdd->query("SELECT UserImage, UserName FROM users WHERE UserName ='".$DataUser['UserName']."'");
                    while($row = $data ->fetch()){
                        if(empty($row['UserImage'])){
                            echo "<img width='60' height='60' src='../images/userdefault.jpg' alt='Default Profile Pic' style ='border-radius:50%;'>";
                        }
                        else {
                            echo "<img width='60' height='60' src='../images/".$DataUser['UserImage']."' alt='Profile Pic' style ='border-radius:50%;'>";
                        }
                    }
                ?>
                </span>
                <span class="nameText">
                    <span class="UserName"><?php echo $DataUser['UserName']; ?></span><br>
                    <span class="ChatMessage"><?php echo $DataChat['ChatText']; ?></span>
                </span>
            </span>
        <?php
        }
    }
}




