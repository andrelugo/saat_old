<? 
require_once("sis_valida.php"); 
require_once("sis_conn.php"); 

$extrato=$_GET["extrato"];
$registro=$_GET["registro"];
$sql="update fechamento_reg set cod_extrato_mo_envio = $extrato where cod = $registro";
$res=mysql_query($sql) or die (mysql_error()."<br> $sql");

Header("Location:frm_extrato_salva.php?cod=$extrato");
?>
