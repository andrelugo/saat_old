<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=$_GET["cp"];
		$sql="update cp set data_sai=NULL,cod_cq=NULL,cod_destino=NULL where cod=$cp";
		mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de exclusão do CQ! $sql ".mysql_error());		
		Header("Location:mnu_cq.php");
?>