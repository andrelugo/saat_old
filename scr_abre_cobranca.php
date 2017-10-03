<? 
require_once("sis_valida.php");
require_once("sis_conn.php");
$sqlF=mysql_query("select max(fechamento) as fechamento from orc");
$fechamento=mysql_result($sqlF,0,"fechamento"); 
if($fechamento==NULL){$fechamento=1;}else{$fechamento++;}
Header("Location:frm_pre_nota_individual.php?fechamento=$fechamento");
?>