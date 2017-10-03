<?
require_once("sis_valida.php");
require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario são sensitive case!
//if ($_COOKIE["adm"]==1) TENTAR BLOQUEAR SE NÃO FOR UM CONTROLER DE ENTRADA
	$modelo=$_POST["cmbModelo"];
	$qt=$_POST["txtQt"];
	//$acao=$_POST["cmdEntra"];
	if ($modelo=="" || $qt==""){
		die ("Modelo ou Quantidade NÃO PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
$count=0;	
while ($count<$qt){	
	$dia = date("Y/m/d H:i:s");
	$sql="insert into cp (cod_modelo,data_entra,cod_colab_entra)
	values ('$modelo','$dia','$id')";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());	
	$count++;
}	
	Header("Location:frm_entraqt.php?codModelo=$modelo");
?>
