<?
require_once("sis_conn.php");
if (empty($_POST["txtUser"])){die("Nome não preenchido!");}else{$usuario=$_POST["txtUser"];}
$senha=md5($_POST["txtSenha"]);
$frase=md5($_POST["txtFrase"]);
$sqlU=mysql_query("SELECT nome,cod from rh_user where senha='$senha' and frase_adm='$frase'")or die("Erro no Camando SQL pág sis_aut.php<br>".mysql_error());
$rowU=mysql_num_rows($sqlU);
if ($rowU==1){//É administrador e tem a frase secreta portanto tem acesso a adm geral.
	$user = mysql_result($sqlU,0,"nome");
	$id = mysql_result($sqlU,0,"cod");
	setcookie("id",$id);
	setcookie("admg",1);
	setcookie("nome","ADMG $user");
	Header("Location:frame.php");
}else{
	die("Senha ou frase Inválida");
}
?>