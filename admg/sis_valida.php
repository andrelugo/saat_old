<?
if ((!isset($_COOKIE["admg"])) AND (!isset($_COOKIE["id"]))){
	Header("Location:index.php");
}else{
$id=$_COOKIE["id"];
}
?>