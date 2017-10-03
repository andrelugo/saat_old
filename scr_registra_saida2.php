<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$cp=$_GET["cp"];
$folha=$_GET["folha"];
$sql="update cp set data_registro_saida=now(),cod_posicao=2,
cod_colab_reg_sai=$id where cp.cod=$cp";
mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserзгo do registro de saнda $sql ".mysql_error());		
Header("Location:frm_registra_saida2.php?folha=$folha");
?>