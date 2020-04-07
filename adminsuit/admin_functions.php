<?php
    include('../connection.php');
    session_start();




    // GLOBALLY USED FUNCTIONS
    function add_compliment($conn,$sex,$compliment){
        $compliment = mysqli_real_escape_string($conn, $compliment);
        if($sex=='both')
        {   
            $sql = "INSERT INTO compliments (sex,value) VALUES ('male', '$compliment');";
            $sql .= "INSERT INTO compliments (sex,value) VALUES ('female', '$compliment');";
            if (mysqli_multi_query($conn, $sql)) {
                echo '<div class="alert alert-success" role="alert">
               success!
              </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                '. mysqli_error($conn).'
               </div>' ;
            }
        }else{
        $sql = "INSERT INTO compliments (sex,value) VALUES ('$sex', '$compliment')";
        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">
            success !
          </div>';
        } else {
                echo '<div class="alert alert-danger" role="alert">
               '. mysqli_error($conn).'
              </div>' ;
        }
    }
    } 

    function remove_request($conn,$id){
        $sql = "DELETE FROM requests WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error deleting record: ".mysqli_error($conn);
        }
    }

    function remove_compliment($conn,$id){
        $sql = "DELETE FROM compliments WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error deleting record: ".mysqli_error($conn);
        }
    }

    function edit_complement($conn,$value,$id){
        $sql = "UPDATE compliments  SET value = '$value' WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error updating record: ".mysqli_error($conn);
        }
    }

    function remove_contributor($conn,$id){
        $sql = "DELETE FROM contributors WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error deleting record: ".mysqli_error($conn);
        }
    }

    function edit_contributor($conn,$value,$id){
        $sql = "UPDATE contributors  SET instagram_id = '$value' WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error updating record: ".mysqli_error($conn);
        }
    }

    function remove_heading($conn,$id){
        $sql = "DELETE FROM headings WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error deleting record: ".mysqli_error($conn);
        }
    }
    
    function add_heading($conn,$title,$desc){
        $sql = "INSERT INTO headings (title,description) VALUES ('$title', '$desc')";
        if (mysqli_query($conn, $sql)) {
            echo 'success';
        } else {
                echo "Error compliments: " . mysqli_error($conn);
        }
    } 
    


    function remove_visitor($conn,$id){
        $sql = "DELETE FROM visitors WHERE id=".$id;
        if (mysqli_query($conn, $sql)) {
            
        } else {
            echo "Error deleting record: ".mysqli_error($conn);
        }
    }



    
    // ADMIN PANEL COMPONENTS
    function get_request_count($conn)
    {
        $sql = "SELECT count(*) as total_requests from requests";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                $row = mysqli_fetch_array($result);
                return $row['total_requests'];
            }else{
                return 0;
            }
        }
    }

    function get_contributors($conn)
    {
        $sql = "SELECT count(*) as total_contributors from contributors";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                $row = mysqli_fetch_array($result);
                return $row['total_contributors'];
            }else{
                return 0;
            }
        }
    }

    function get_compliments($conn)
    {
        $sql = "SELECT count(*) as total_compliments from compliments";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                $row = mysqli_fetch_array($result);
                return $row['total_compliments'];
            }else{
                return 0;
            }
        }
    }


    function get_visitors($conn)
    {
        $sql = "SELECT count(*) as total_visitors from visitors";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                $row = mysqli_fetch_array($result);
                return $row['total_visitors'];
            }else{
                return 0;
            }
        }
    }


    // INITIALISE COMPONENTS
    if(isset($_GET['getalldata'])){
        $request_count = get_request_count($conn);
        $contributor_count =get_contributors($conn);
        $compliments_count= get_compliments($conn);
        $visitors_count=    get_visitors($conn);    
       
            echo '
            <div class="card bg-dark">
            <div class="card-body text-center">
                <h1 class="display-2 p-3">'.$visitors_count.'</h1>
                <h5 class="card-title">VISITORS</h5>
            </div>
            <a href="visitor_handler.php" class="stretched-link"></a>
        </div>
                <div class="card bg-dark">
                    <div class="card-body text-center">
                        <h1 class="display-2 p-3">'.$contributor_count.'</h1>
                        <h5 class="card-title">CONTRIBUTORS</h5>
                    </div>
                    <a href="contributor_handler.php" class="stretched-link"></a>
                </div>
                <div class="card bg-dark">
                    <div class="card-body text-center">
                        <h1 class="display-2 p-3">'.$compliments_count.'</h1>
                        <h5 class="card-title">COMPLEMENTS</h5>
                    </div>
                    <a href="compliment_handler.php" class="stretched-link"></a>
                </div>
           
            ';
    }




    // REQUEST HANDLER

    // INITIALISE  REQUEST COMPONENTS
    if(isset($_GET['getrequests'])){
        $sql = "SELECT * FROM requests ORDER BY upload_date DESC ";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                while($row = mysqli_fetch_array($result)){
                    echo '
                    <a style = "border-bottom: 1px solid black" href="#" class="list-group-item list-group-item-action flex-column align-items-start bg-dark">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">'.$row['user'].'</h5>
                            <small>'.$row['upload_date'].' days ago</small>
                        </div>
                        <p class="mb-1 text-left">'.$row['compliment'].'</p>
                        <div class="form-inline text-right pull-right col-xs-6">
                            <select name="sex"  style = "width:115px;" id="" class = "form-control form-control-lg btn-outline-amber pull-right" >
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <button id = "accept_request_btn" class="btn btn-outline-amber sm pull-right" data-request_id = "'.$row['id'].'" >Add</button>
                            <button id = "remove_request_btn" class="btn btn-outline-amber sm pull-right" data-request_id = "'.$row['id'].'" >Remove</button>
                         </div>
                    </a>

                    ';
                }
            }
        
            }else{
                echo '
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">
                    <p class="mb-1 text-left">No requests available</p>
                </a>
                ';

            }
                    


       
           
    }

    //ACCEPTING REQUEST FUNCTIONS
    function add_user_to_contributors($conn,$user,$instagram){
        $sql = "INSERT INTO contributors (name,instagram_id) VALUES ('$user', '$instagram')";
        if (mysqli_query($conn, $sql)) {
            echo 'success in contributors';
        } else {
                echo "Error contributors:" . mysqli_error($conn);
        }
        
    }


    function add_request_to_compliment($conn,$id,$sex){
        $request_sql = "SELECT * from requests where id = ".$id;
        if($request_result = mysqli_query($conn, $request_sql)){
            if(mysqli_num_rows($request_result) > 0){                
                $request = mysqli_fetch_array($request_result);
                $compliment =  $request['compliment'];
                $user =  $request['user'];
                $instagram_id =  $request['instagramid'];
                
                add_compliment($conn,$sex,$compliment);
                add_user_to_contributors($conn,$user,$instagram_id);
            }else{
                echo "Error requests:" . mysqli_error($conn);
            }
        }

    }
   
    //DELETING REQUEST
    if(isset($_POST['deleterequest'])){

        $id = $_POST['id'];
        remove_request($conn,$id);
    }
    //ACCEPTING REQUEST
    if(isset($_POST['acceptrequest'])){

        $id = $_POST['req_id'];
        $sex = $_POST['sex'];
       
        add_request_to_compliment($conn,$id,$sex);
        remove_request($conn,$id);
        
    }
  



    // COMPLIMENT HANDLER

    // INITIALISE  COMPLIMENT COMPONENTS
    if(isset($_GET['getcompliments'])){
        $sql = "SELECT * FROM compliments ";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                while($row = mysqli_fetch_array($result)){
                    echo '
                    <a style = "border-bottom: 1px solid black" href="#" class="list-group-item list-group-item-action flex-column align-items-start bg-dark">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">'.$row['sex'].'</h5>
                            
                        </div>
                        <p class="mb-1 text-left">'.$row['value'].'</p>
                        <div class="form-inline text-right pull-right col-xs-6">
                            <button id = "edit_complement_btn" class="btn btn-outline-amber sm pull-right" data-compliment_id = "'.$row['id'].'" >EDIT</button>
                            <button id = "remove_complement_btn" class="btn btn-outline-amber sm pull-right" data-compliment_id = "'.$row['id'].'" >REMOVE</button>
                         </div>
                    </a>

                    ';
                }
            }
        
            }else{
                echo '
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">
                    <p class="mb-1 text-left">No compliments available</p>
                </a>
                ';

            }
                    


       
           
    }

    // ADD COMPLEMENT
    if(isset($_POST['uploadcompliment'])){

        $compliment = $_POST['compliment'];
        $sex = $_POST['sex'];
        add_compliment($conn,$sex,$compliment);
    
        
    }

      //DELETING  COMPLIMENT
      if(isset($_POST['deletecomplement'])){
        $id = $_POST['id'];
        remove_compliment($conn,$id);
    }

      //EDITING  COMPLIMENT
      if(isset($_POST['editcomplement'])){
        $id = $_POST['id'];
        $value = $_POST['value'];
        edit_complement($conn,$value,$id);
    }





    // CONTRIBUTOR HANDLER

    // INITIALIZE  CONTRIBUTOR COMPONENTS
    if(isset($_GET['getcontributors'])){
        $sql = "SELECT * FROM contributors ";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                while($row = mysqli_fetch_array($result)){
                    echo '
                    <a style = "border-bottom: 1px solid black" href="#" class="list-group-item list-group-item-action flex-column align-items-start bg-dark">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">'.$row['name'].'</h5>
                            
                        </div>
                        <p class="mb-1 text-left">'.$row['instagram_id'].'</p>
                        <div class="form-inline text-right pull-right col-xs-6">
                            <button id = "edit_contributor_btn" class="btn btn-outline-amber sm pull-right" data-contributor_id = "'.$row['id'].'" >EDIT</button>
                            <button id = "remove_contributor_btn" class="btn btn-outline-amber sm pull-right" data-contributor_id = "'.$row['id'].'" >REMOVE</button>
                         </div>
                    </a>

                    ';
                }
            }
        
            }else{
                echo '
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">
                    <p class="mb-1 text-left">No Contributors available</p>
                </a>
                ';

            }
                    


       
           
    }


     //DELETING  CONTRIBUTOR
     if(isset($_POST['deletecontributor'])){
        $id = $_POST['id'];
        remove_contributor($conn,$id);
    }

      //EDITING  CONTRIBUTOR
      if(isset($_POST['editcontributor'])){
        $id = $_POST['id'];
        $value = $_POST['value'];
        edit_contributor($conn,$value,$id);
    }





     // HEADINGS HANDLER

    // INITIALIZE  HEADINGS COMPONENTS
    if(isset($_GET['getheadings'])){
        $sql = "SELECT * FROM headings ";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                while($row = mysqli_fetch_array($result)){
                    echo '
                    <a style = "border-bottom: 1px solid black" href="#" class="list-group-item list-group-item-action flex-column align-items-start bg-dark">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">'.$row['title'].'</h5>
                            
                        </div>
                        <p class="mb-1 text-left">'.$row['description'].'</p>
                        <div class="form-inline text-right pull-right col-xs-6">
                            <button id = "remove_heading_btn" class="btn btn-outline-amber sm pull-right" data-heading_id = "'.$row['id'].'" >REMOVE</button>
                         </div>
                    </a>

                    ';
                }
            }
            
             
        }


       
           
    }


     //DELETING  HEADINGS
     if(isset($_POST['deleteheading'])){
        $id = $_POST['id'];
        remove_heading($conn,$id);
    }

    //ADD HEADING
    if(isset($_POST['uploadheadings'])){

        $title = $_POST['title'];
        $desc = $_POST['description'];
        add_heading($conn,$title,$desc);
    
        
    }


    //VISITOR HANDLER


         //DELETING  VISITOR
         if(isset($_POST['deletevisitor'])){
            $id = $_POST['id'];
            remove_visitor($conn,$id);
        }


    if(isset($_GET['getvisitors'])){
        $sql = "SELECT * FROM visitors ORDER BY upload_date DESC LIMIT 250 ";
        if($result = mysqli_query($conn, $sql)){
            if(mysqli_num_rows($result) > 0){                
                while($row = mysqli_fetch_array($result)){
                    echo '
                    <a style = "border-bottom: 1px solid black" href="#" class="list-group-item list-group-item-action flex-column align-items-start bg-dark">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">'.$row['sex'].' &nbsp;&nbsp; <span class="text-right">'.$row['upload_date'].' </span></h5>
                            
                        </div>
                        <p class="mb-1 text-left text-captalise">'.$row['fname']. '&nbsp;'.$row['lname'].'</p>
                        <p class="mb-1 text-left text-captalise">'.$row['bday'].'</p>
                        <div class="form-inline text-right pull-right col-xs-6">
                            <button id = "remove_visitor_btn" class="btn btn-outline-amber sm pull-right" data-visitor_id = "'.$row['id'].'" >REMOVE</button>
                         </div>
                    </a>

                    ';
                }
            }
        
            }else{
                echo '
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start ">
                    <p class="mb-1 text-left">No compliments available</p>
                </a>
                ';

            }
                    


       
           
    }


   

      //DELETING  VISITOR
      if(isset($_GET['getvisitorscount'])){
       
         $visitors_count=    (string)get_visitors($conn);   

         echo '
         <div class="card bg-dark">
         <div class="card-body text-center">
             <h1 class="display-2 p-3">'.$visitors_count[0].'</h1>
             <h5 class="card-title"></h5>
         </div>
         <a href="request_handler.php" class="stretched-link"></a>
     </div>
     <div class="card bg-dark">
         <div class="card-body text-center">
             <h1 class="display-2 p-3">'.$visitors_count[1].'</h1>
             <h5 class="card-title"></h5>
         </div>
         <a href="contributor_handler.php" class="stretched-link"></a>
     </div>
     <div class="card bg-dark">
         <div class="card-body text-center">
             <h1 class="display-2 p-3">'.$visitors_count[2].'</h1>
             <h5 class="card-title"></h5>
         </div>
         <a href="compliment_handler.php" class="stretched-link"></a>
     </div>
     
     <div class="card bg-dark">
     <div class="card-body text-center">
         <h1 class="display-2 p-3">'.$visitors_count[3].'</h1>
         <h5 class="card-title"></h5>
     </div>
     <a href="visitor_handler.php" class="stretched-link"></a>
 </div>
 ';
        }


?>