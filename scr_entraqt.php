<?
require_once("sis_valida.php");
require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario s�o sensitive case!
//if ($_COOKIE["adm"]==1) TENTAR BLOQUEAR SE N�O FOR UM CONTROLER DE ENTRADA
	$modelo=$_POST["cmbModelo"];
	$qt=$_POST["txtQt"];
	//$acao=$_POST["cmdEntra"];
	if ($modelo=="" || $qt==""){
		die ("Modelo ou Quantidade N�O PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
$count=0;	
while ($count<$qt){	
	$dia = date("Y/m/d H:i:s");
	$sql="insert into cp (cod_modelo,data_entra,cod_colab_entra)
	values ('$modelo','$dia','$id')";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());	
	$count++;
}	
	Header("Location:frm_entraqt.php?codModelo=$modelo");
?>
