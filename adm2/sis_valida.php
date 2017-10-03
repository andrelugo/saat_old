<?
//if ((!isset($_COOKIE["nome"])) AND (!isset($_COOKIE["id"])))
if (!isset($_COOKIE["id"])){
	Header("Location:index.php");
}else{
	$id=$_COOKIE["id"];
//	$bgcolor=$_COOKIE["bgcolor"];
//	$linhatec=$_COOKIE["linhatec"];
//}


//Tentando utilizar sessões em 03/06/2007 !Fernando.
//session_start(); 
//SE NÃO TIVER VARIÁVEIS REGISTRADAS RETORNA PARA A TELA DE LOGIN 
//if(!isset($_SESSION["id"])) {
//	Header("Location: index.php"); 
//}else{
//	$id=$_SESSION["id"];
//
//	$nome=$_SESSION["nome"];
//	$adm=$_SESSION["adm"];
//	$cargo=$_SESSION["cargo"];
//	$bgcolor=$_SESSION["bgcolor"];
//	$linhatec=$_SESSION["linhatec"];
}
?>