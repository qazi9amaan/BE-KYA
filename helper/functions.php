
<?php

include('../connection.php');
session_start();

$msg = '';



// FUNCTIONS
function present_in_db($channel,$conn){
    
  $sql = "SELECT * FROM admins WHERE username = '$channel'";
     $result = mysqli_query($conn, $sql);
       if (mysqli_num_rows($result) == 0) { 
         return false;
        }else{
            $msg = "Already taken";
            header('Location:../admin.php?m='.$msg);
          return true;
        }     

}
function create_account($channelname,$auth,$conn){
  $sql = "INSERT INTO admins (username,password)
    VALUES ('$channelname', '$auth')";

    if (mysqli_query($conn, $sql)) {
      return true;
    } else {
        return false;
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}



// ADMIN FUNCTIONS
if(isset($_GET['logout']))
{
    session_destroy();
    header("location:../index.html");
}
if(isset($_POST['getadminlogined']))
{
        
    if(empty($_POST['username']) || empty($_POST['password']))
    {
            header("location:../404.html");
    }
    else
    {	    $username = $_POST['username'];
            $password = $_POST['password'];
            $query="select * from admins where username= '$username' and password='$password'";
            $result=mysqli_query($conn,$query);

            if(mysqli_fetch_assoc($result))
            {
                $_SESSION['admin']=$_POST['username'];
                $_SESSION['status']='active';
                header('Location:../adminsuit/admin_util.php');
                
                
            }
            else
            {
                $msg =  mysqli_error($conn) ;
                header('Location:../admin.php?m='.$msg);
            }
    }

}
if(isset($_POST['registerausernow']))
{
        
    if(empty($_POST['username']) || empty($_POST['password']))
    {
      $msg =  "Provide details!";
    }
    else
    {	    $username = $_POST['username'];
            $password = $_POST['password'];

            if(present_in_db($username,$password))
            {
                $msg = "Sorry this username has been already taken";
                header('Location:../admin.php?m='.$msg);
                
            }
            else{
         if(create_account($username,$password,$conn))
         {
            $msg = "Successfully created";
            header('Location:../adminsuit/admin_util.php?m='.$msg);
         }
         else
         {
            $msg = "Some error has been encountered!";
            header('Location:../admin.php?m='.$msg);
         }
        }
    }
    
}




function  add_the_visitor($conn,$fname,$lname,$bday,$sex )
{
  
  $fname=mysqli_real_escape_string($conn, $fname);
  $lname=mysqli_real_escape_string($conn, $lname);
  $sql = "INSERT INTO visitors (fname,lname,sex,bday) VALUES ('$fname', '$lname', '$sex','$bday')";
  mysqli_query($conn, $sql);

}



// VIEW FRIENDS COMPLEMET HANDLER
function  getcomplimenentformfriend($conn,$sex)
{
  $sql = "select value from compliments where sex = '$sex' order by rand() limit 1 ";
  if($result = mysqli_query($conn, $sql)){
      if(mysqli_num_rows($result) > 0){                
          while($row = mysqli_fetch_array($result)){
            echo ''.$row['value'];
          }
        }
      }
}



// HOLDER
if(isset($_GET['getfrienddetail']))
{
  $sex = $_GET['sex'];
  $fname = $_GET['fname'];
  $lname = $_GET['lname'];
  $bday = $_GET['bday'];
  if(empty($sex))
  {
    
  }else{    
    add_the_visitor($conn,$fname,$lname,$bday ,$sex );
    getcomplimenentformfriend($conn,$sex);
  }
}


?>



