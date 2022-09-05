<?php 

error_reporting(0);
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "certificate_generate";

$conn=mysqli_connect("localhost","root","","certificate_generate");

require_once('plugin/php-excel-reader/excel_reader2.php');
require_once('plugin/SpreadsheetReader.php');

if (isset($_POST["import"]))
{
     $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
      if(in_array($_FILES["file"]["type"],$allowedFileType)){

	 // is uploaded file
	 $targetPath = 'uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        // end is uploaded file

        $Reader = new SpreadsheetReader($targetPath);
        
        $sheetCount = count($Reader->sheets());
        for($i=0;$i<$sheetCount;$i++)
        {
           $Reader->ChangeSheet($i);

           foreach ($Reader as $Row)
            {
                $Name = "";
                if(isset($Row[0])) {
                    $Name = mysqli_real_escape_string($conn,$Row[0]);
                }

                    $Date = "";
                    if(isset($Row[1])) {
                        $Date = mysqli_real_escape_string($conn,$Row[1]);
                   }
                if (!empty($Name) || !empty($Date)){


                    $query = "insert into generate(user_uname ,user_date) values('".$Name."','".$Date."')";
                    $result = mysqli_query($conn, $query);
                
                    if ($result) {
                        $type = "success";
                        $message = "Excel Data Imported into the Database";
                    } else {
                        $type = "error";
                        $message = "Problem in Importing Excel Data";
                    }
                }


            }

        }

        echo '<script type ="text/JavaScript">';  
        echo 'alert("Data import successfully")';  
        echo '</script>'; 

         header("location:CERTIFICATE.php");
        // echo "<script>confirm('done')</script>"; 

        //  header("location:EXCEL-FILE-.php");

        
    }


  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
  }



}




 ?> 