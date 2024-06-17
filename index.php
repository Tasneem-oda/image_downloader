<?php
$conn = mysqli_connect('localhost','root','','image_uploader')or die('connection error');
$statues = $statuesMsg = '';
if(isset($_POST['submit'])){ // when click on the submit button
    $statues = 'error';
    if(!empty($_FILES['image']['name'])){ // check if the user have piced image
        $filename = basename(($_FILES['image']['name'])); //get the image name
        $fieltype = pathinfo($filename, PATHINFO_EXTENSION); //get the extinton by the path of the image
        $allowtypes = array('jpg','png','jpeg','gif');
        //check if the type is allowed
        if(in_array($fieltype,$allowtypes)){ 
            $image = $_FILES['image']['tmp_name']; // get the temprary filename
            //get the content of the image and convert it into string then add slashes for proper handling
            $imgcontent = addslashes(file_get_contents($image));
             
            $insert = $conn->query("INSERT INTO images (image , created) VALUES ('$imgcontent', NOW())");
            if($insert){ 
                $statues = 'success';
                $statuesMsg = 'file uploaded successfully';
            }else{
                $statuesMsg = "file upload failed";
            }
        }else{
            $statuesMsg = 'file type is not allowed';
        }
    }else{
        $statues = 'select an image to upload';
    }

}
echo $statuesMsg;
$result = $conn->query("SELECT image FROM images ORDER BY id DESC"); // get the image from the db

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="image" >
        <button type="submit" name="submit">submit</button>
    </form>
    <div class="gellery">
        <?php
        if($result->num_rows > 0){//if get reults
            $row =  mysqli_fetch_assoc($result); // fetch the data
                     
        ?>
                <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($row['image']); ?>"/>
        <?php ;?>
    </div>
   <?php
        }else{
            echo 'image not found';
        };
   ?>
</body>
</html>