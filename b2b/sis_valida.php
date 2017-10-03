<?
if (!isset($_COOKIE["idf"]))
{
	Header("Location:index.php");
}else{
	$id=$_COOKIE["idf"];
}
?>