<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$codExtrato = $_GET['cod'];
$res=mysql_query("select descricao from extrato_mo where cod = $codExtrato");
$extrato=mysql_result($res,0,"descricao");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
.style2 {font-size: 16}
-->
</style>
</head>
<body>
<table width="695" border="1" align="center">
	<TR>
	<td colspan="7"><div align="center"><strong>Relat&oacute;rio de registros de sa&iacute;da do extrato <? print($extrato);?></strong></div></td>
	</TR>
    <tr>
      <td width="136" height="27"><strong>Registro de Saída</strong></td>
      <td width="72"><strong>Qt. Total</strong></td>
      <td colspan="2"><strong>Qt neste extrato - % </strong></td>
      <td width="238"><strong>Qt outros extratos</strong></td>
      <td width="238"><strong>Qt sem extrato</strong></td>
      <td width="238"><strong>Envio</strong></td>
    </tr>
<? $totR=0;$totD=0;$totG=0;$tse=0;
// Seleciona todos os registros que possuem OS no extrato em questão!
// E o total de os neste registro para este extrato
$sql="select fechamento_reg.registro as registro, count(cp.cod) as tot, cod_fechamento_reg as codF,extrato_mo.descricao as envio,extrato_mo.cod as codE
from cp left
join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
left join extrato_mo on extrato_mo.cod = fechamento_reg.cod_extrato_mo_envio
where cp.cod_extrato_mo = '$codExtrato' group by registro order by registro";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error()."<br> $sql");
while ($linha = mysql_fetch_array($res)){
	$se=0;
	$registro=$linha["registro"];//Descrião do registro de saída!
	$codE=$linha["codE"];//Código do extrato em que o registro foi enviado
	if ($codE==$codExtrato){
		$envio="<strong>Neste</strong>";
	}else{
		$envio=$linha["envio"];//Descrião do registro de saída!
	}
	$tot=$linha["tot"];//Total deste extrato neste registro
	$codF=$linha["codF"];//código do registro de saída
	//Conta o total geral de os para o registro de saída em questão
	$res2=mysql_query("select count(cod) as tot from cp where cod_fechamento_reg='$codF'") or die(mysql_error());
	$totF=mysql_result($res2,0,"tot");
	//Fim Contagem reg saida
	$totR=$totR+$tot;// Total de Ordens de serviços neste extrato
	$totG=$totG+$totF;// Total Geral de Ordens de Serviços nos registros de saída envolvidos neste fechamento ?>
  <tr>
    <td><? if($registro==""){print("Indefinido");}else{print("<a href=pdf_res_fechamento_reg.php?txtFolha=$registro target='_blank'>$registro</a>");}?></td>
    <td><? print($totF);?></td>
    <td width="61"><? print($tot);?></td>
<?	if ($tot<>$totF){// Se a qt no extrato for diferente de qt no registro SENÃO 100% neste...
?>		<td width="84"><? if ($totF<>0){$per=($tot/$totF)*100;print(number_format($per, 2, ',', '.')." %");}?></td>
	    <td><?
		$sql2="SELECT count( cp.cod ) AS tot, extrato_mo.descricao as descExtrato,cp.cod_extrato_mo as codExtrato
		FROM cp left join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo
		WHERE cod_fechamento_reg = '$codF' AND cod_extrato_mo <> '$codExtrato' 
		GROUP BY descExtrato 
		ORDER BY descExtrato";

		$sql2="SELECT count( cp.cod ) AS tot, extrato_mo.descricao as descExtrato,cp.cod_extrato_mo as codExtrato
		FROM cp left join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo
		WHERE cod_fechamento_reg = '$codF'
		GROUP BY descExtrato 
		ORDER BY descExtrato";

		$res2=mysql_query($sql2) or die (mysql_error()."<br> $sql2");
		$e=0;
		$erow=mysql_num_rows($res2);
		while ($linha2 = mysql_fetch_array($res2)){
			if($linha2["codExtrato"]<>NULL){
				if ($linha2["codExtrato"]<>$codExtrato){
					print("$linha2[descExtrato] - $linha2[tot]<br>");
				}
			}else{
				$se=$linha2["tot"];
				$tse=$tse+$se;
				if($e==0 && $erow==1){print("-");}
			}
			$e++;
		}?></td><? 
		if(isset($se)){
			print("<td> $se</td><td>$envio</td>");
		}
	}else{?>
  	    <td width="57">100 %</td>
		<td width="8">-</td>
		<td width="8">-</td>
		<td><? print("$envio");?></td>
<?	}?>
  </tr><?	
}?>	    <tr>
	      <td><strong>Total</strong></td>
	      <td><strong><? print($totG);?></strong></td>
	      <td><strong><? print($totR);?></strong></td>
  	      <td>&nbsp;</td>
	      <td><strong>
          <? $totD=$totG-$totR-$tse; print($totD);?>
	      </strong></td>
		  <td><strong><? print($tse);?></strong></td>
		  <td>&nbsp;</td>
	    </tr>
</table>
</div>
</body>
</html>