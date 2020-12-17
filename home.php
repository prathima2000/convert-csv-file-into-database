
<html>
<!-- uses html for develop an UI -->
<head>
<title>Convert csv to database</title>
<style>
    h1 {text-align: center;}
    p {text-align: center;}
    body {
  background-image: url('background.jpeg');
  background-repeat: no-repeat;
  background-size: cover;
}
.center {
  margin: auto;
  width: 30%;
  border: 5px solid blue;
  padding: 10px;
  text-align: center;
}
</style>
</head>
<body>
   
    <img src="logo.png" alt="Smiley face" style="float:left;width:180px;height:180px;">
    <br>

<h1 style="font-family:verdana;color:blue;">Convert csv file to database </h1>

<br><br>
<div class="center">
<p> Upload a csv File  </p> 
<form method="post"  > 
<p> <input type="file" id="file"  onchange="return fileValidation()" /> 
</p>


        <input type="submit" name="button1"
                class="button" value="Convert to DB"  >
                </form> 
</div>
<script>
  //Javascript to vaidate whether the file uploaded is csv or not 
    function fileValidation() { 
        
        var fileInput =  
            document.getElementById('file'); 
          
        var filePath = fileInput.value; 
      
      
        var allowedExtensions =   /(\.csv)$/i; 
          
        if (!allowedExtensions.exec(filePath)) { 
            alert('Invalid file type'); 
            fileInput.value = ''; 
            return false; 
            }
        else
        {
            alert('Successfully uploaded file'); 
        }
    }
</script>

<?php     
  //php to insert in database
 

  if(array_key_exists('button1', $_POST))
  {
    echo "list of columns in csv File<br />\n";
$time=1;
$column=array();
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
if (($handle = fopen($target_file, 'r')) !== FALSE) {
     
   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $time==1) { 
      
     for ($i = 0; $i < count($data); $i++)
         { 
             //read first column in file to create database table
            echo  $data[$i] . "<br />\n";
            $column[$i]=$data[$i] ;
        }
        echo "<br />\n" ;
        $time=0;
       
    }
   
    fclose($handle);
}

}

$servername = 'localhost';
$username = 'root';
$password = '';


// Create connection
$conn = new mysqli($servername, $username, $password)or die("Failed to connect to MySQL: " . mysqli_error()); 
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Create database
$sql = "CREATE DATABASE myDataBase4";
if ($conn->query($sql) === TRUE) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . $conn->error;
}
//create table

$sql = "CREATE TABLE Mydata (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "Table Mydata created successfully";
    } else {
      echo "Error creating table: " . $conn->error;
    }

    //alter table to insert all column
for($j=0;$j<$i;$j++)
{   
$sql="ALTER TABLE Mydata  ADD $column[$i] varchar(255)";
$conn->query($sql);
}
if ($conn->query($sql) === TRUE) {
    echo "Created all column of csv file into database successfully";

//insert all data into database

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $count=0;
if (($handle = fopen($target_file, 'r')) !== FALSE) { // Check the resource is valid
   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE && $time==1) { // Check opening the file is OK!

 
       for ($i = 0; $i < count($data); $i++)
         { // Loop over the data using $i as index pointer
          
           $sql="INSERT INTO Mydata ($column[$i])VALUES ($data[$i])";
           $conn->query($sql); 
         
        }
        $count++;   
    }
    
   if ($conn->query($sql) === TRUE) {
      echo "Inserted all data from csv file into database successfully";
   }
    fclose($handle);
}
         }
echo "There are $count data in Csv file";
//close connection
$conn->close();
?>
  
</body>
</html>