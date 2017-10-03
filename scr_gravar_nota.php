<? // Analizar 22/09/07
require_once("sis_valida.php");
require_once("sis_conn.php");

$fechamento=$_GET["fechamento"];
$sql="select cod_orc_pre_nota as pre from orc where fechamento = $fechamento AND cod_orc_pre_nota <>0 group by cod_orc_pre_nota";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
for($i=0;$i<$rows;$i++){// Verifica se há alguma caixa sem preenchimento
	$pre=mysql_result($res,$i,"pre");
		$nota=$_GET[$pre];
	if ($nota==""){
		die("<h1>Numero da Nota para a pré-nota $pre não preenchido!");
	}else{
		$res2=mysql_query("select nota, fechamento from orc_pre_nota inner join orc on orc.cod_orc_pre_nota = orc_pre_nota.cod where nota = $nota");
		$rowsNota=mysql_num_rows($res2);
		if($rowsNota==1){
			$fec=mysql_result($res2,0,"fechamento");
			if($fec<>$fechamento){
				die("ERRO:A nota fiscal $nota já foi cadastrada para a cobrança nº $fec");
			}
		}
	}
}
for($i=0;$i<$rows;$i++){//grava no banco o numero das notas 
	$pre=mysql_result($res,$i,"pre");
	$nota=$_GET[$pre];
	mysql_query("update orc_pre_nota set nota=$nota where cod = $pre");
	//print($nota);
}
//exibe consulta RATEIO
Header("Location:pdf_rateio.php?fechamento=$fechamento");
?>