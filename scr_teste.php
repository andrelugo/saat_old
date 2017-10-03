<?
//ESTE ARQUIVO FOI GERADO COM A FINALIDADE DE VALIDAR AS ORDENS DE SERVIÇOS
//ENVIADAS PELO FABRICIO BRITÂNIA PARA VALIDAR AS O.S. QUE DEVERIAM SER
//DESCONTADAS DO PRÓXIMO ESTRATO
require_once("sis_valida.php");
require_once("sis_conn.php");
$dias_carencia=90;
$res=mysql_query("select cod,serie,os,os_reincide,fechamento from osdig where serie<>'SEM SÉRIE' and serie<>'NÃO CONSTA'") or die("1 SQL".mysql_error());
$count=0;
while ($linha=mysql_fetch_array($res)){
	$count++;
	$cod=$linha["cod"];
	if($cod==0){
		mysql_query("update osdig set cod=$count");
		$serieP=$linha["serie"];
		$osP=$linha["os"];
		$fechaP=$linha["fechamento"];
		
		$sql="select os,abertura, DATEDIFF(abertura,'$fechaP') as dias 
		from osdig where os<>'$osP' and serie='$serieP' and os_reincide is null and
		((abertura between '$fechaP' and DATE_ADD('$fechaP',INTERVAL $dias_carencia DAY)))";
		$res2=mysql_query($sql) or die("2 SQL".mysql_error()."<BR> $sql");
		$rows=mysql_num_rows($res2);
		if ($rows<>0){
			while($linha2=mysql_fetch_array($res2)){
				$osE=$linha2["os"];
				$carencia=$linha2["dias"];
				mysql_query("update osdig set os_reincide='$osP', dias_carencia='$carencia', reincidencias=$rows where os='$osE'") or die("3 SQL<BR>".mysql_error());
			}
		}else{
			mysql_query("update osdig set os_reincide='UNICA'");
		}
	}else{
		$res3=mysql_query("select max(cod) as cod2 from osdig");
		$count=mysql_result($res3,0,"cod2");
	}
}
?>
<html>
<head></head>
<body><H1>FIM</body>
</html>
