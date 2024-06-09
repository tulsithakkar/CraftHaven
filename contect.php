<!-- Tulsi Thakkar-->
<?php
$con = mysqli_connect('localhost','root','','crafthaven_project');
if($con){
    echo '<script>console.log("Database connected!");</script>';
}
else
{
    die(mysqli_error($con));
}
?>

