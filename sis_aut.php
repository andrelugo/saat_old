<?
require_once("sis_conn.php");
if (empty($_POST["txtUser"])){die("Nome n�o preenchido!");}else{$usuario=$_POST["txtUser"];}
$senha=$_POST["txtSenha"];
$senha=md5($senha);

$sql=mysql_query("SELECT rh_user.nome,rh_user.cod,rh_cargo.adm,rh_user.cargo,bgcolor,linhatec 
from rh_user
inner join rh_cargo on rh_user.cargo = rh_cargo.cod
where rh_user.login='$usuario'
and rh_user.senha='$senha'")or die("Erro no Camando SQL p�gi sis_aut.php $sql");
$row=mysql_num_rows($sql);

if($row==0){
	echo "Usu�rio ou Senha Inv�lidos";
}
else{
	$id = mysql_result($sql,0,"cod");
//	$nome = mysql_result($sql,0,"nome");
//	$adm = mysql_result($sql,0,"adm");
//	$cargo = mysql_result($sql,0,"cargo");
//	$bgcolor = mysql_result($sql,0,"bgcolor");
//	$linhatec = mysql_result($sql,0,"linhatec");

	session_start();         //N�o consegui utilizar sess�es! Fernando 03/07/2005
	$_SESSION["id"]=$id;


//	$_SESSION["nome"]=$nome;
//	$_SESSION["adm"]=$adm;
//	$_SESSION["cargo"]=$cargo;
//	$_SESSION["bgcolor"]=$bgcolor;
//	$_SESSION["linhatec"]=$linhatec;
	
	//Tentando utilizar sessões novamente! FERNANDO 03/06//2007
	setcookie("id",$id);

/////////////////////////////////////////////COOKIE BASE � SETADO NO ARQUIVO INDEX.PHP DENTROA DAS PASTAS DO M�DULOS SAAT
//	setcookie("nome",$nome);
//	setcookie("adm",$adm);
//	setcookie("cargo",$cargo);
//	setcookie("bgcolor",$bgcolor);
//	setcookie("linhatec",$linhatec);
	
	Header("Location:frame.php");
}
?>