<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sqlpendencias=mysql_query("SELECT count( cp.cod ) as qt FROM cp inner join modelo on modelo.cod = cp.cod_modelo WHERE data_sai IS NULL and modelo.cod_fornecedor=$id")or die(mysql_error());
$sqlanalise=mysql_query("SELECT count(cod) as qt from cp where data_analize is null")or die(mysql_error());
$sqlltec1=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL GROUP BY cp.cod")or die(mysql_error());
$ltec=mysql_num_rows($sqlltec1);

$sqlorcAgAp=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc is null GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp=mysql_num_rows($sqlorcAgAp);
$sqlorcAp=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=1 GROUP BY cp.cod")or die(mysql_error());
$lorcAp=mysql_num_rows($sqlorcAp);
$sqlorcRp=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=0 GROUP BY cp.cod")or die(mysql_error());
$lorcRp=mysql_num_rows($sqlorcRp);
$pendencias=mysql_result($sqlpendencias,0,"qt");
$sqllcq=mysql_query("SELECT count(cod) as qt from cp where data_sai is null and data_pronto is not null")or die(mysql_error());
$analise=mysql_result($sqlanalise,0,"qt");
$lcq=mysql_result($sqllcq,0,"qt");
$sql20=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) <=20)")or die(mysql_error());
$p20=mysql_result($sql20,0,"qt");
$sql2030=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30)")or die(mysql_error());
$p2030=mysql_result($sql2030,0,"qt");
$sql30=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) >=30)")or die(mysql_error());
$p30=mysql_result($sql30,0,"qt");
/// Dias prazo para status
$sql20analise=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_analize IS NULL AND (DATEDIFF(now( ) , data_entra) <=20)")or die(mysql_error());
$p20analise=mysql_result($sql20analise,0,"qt");

$sql2030analise=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_analize IS NULL AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30)")or die(mysql_error());
$p2030analise=mysql_result($sql2030analise,0,"qt");

$sql30analise=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_analize IS NULL AND (DATEDIFF(now( ) , data_entra) >=30)")or die(mysql_error());
$p30analise=mysql_result($sql30analise,0,"qt");
///
$sql20pronto=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai is null and data_pronto is not null AND (DATEDIFF(now( ) , data_entra) <=20)")or die(mysql_error());
$p20pronto=mysql_result($sql20pronto,0,"qt");

$sql2030pronto=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai is null and data_pronto is not null AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30)")or die(mysql_error());
$p2030pronto=mysql_result($sql2030pronto,0,"qt");

$sql30pronto=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai is null and data_pronto is not null AND (DATEDIFF(now( ) , data_entra) >=30)")or die(mysql_error());
$p30pronto=mysql_result($sql30pronto,0,"qt");
//

$sqlltec20=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_entra) <=20) GROUP BY cp.cod")or die(mysql_error());
$ltec20=mysql_num_rows($sqlltec20);

$sqlltec2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$ltec2030=mysql_num_rows($sqlltec2030);

$sqlltec30=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND (DATEDIFF(now( ) , data_entra) >=30) GROUP BY cp.cod")or die(mysql_error());
$ltec30=mysql_num_rows($sqlltec30);
//
$sqlorcAgAp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc is null  AND (DATEDIFF(now( ) , data_entra) <=20) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp20=mysql_num_rows($sqlorcAgAp20);

$sqlorcAgAp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc is null AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp2030=mysql_num_rows($sqlorcAgAp2030);

$sqlorcAgAp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc is null  AND (DATEDIFF(now( ) , data_entra) >=30) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp30=mysql_num_rows($sqlorcAgAp30);
//

$sqlorcAp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=1 AND (DATEDIFF(now( ) , data_entra) <=20)  GROUP BY cp.cod")or die(mysql_error());
$lorcAp20=mysql_num_rows($sqlorcAp20);

$sqlorcAp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=1 AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcAp2030=mysql_num_rows($sqlorcAp2030);

$sqlorcAp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=1 AND (DATEDIFF(now( ) , data_entra) >=30)  GROUP BY cp.cod")or die(mysql_error());
$lorcAp30=mysql_num_rows($sqlorcAp30);
//
$sqlorcRp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=0 AND (DATEDIFF(now( ) , data_entra) <=20)  GROUP BY cp.cod")or die(mysql_error());
$lorcRp20=mysql_num_rows($sqlorcRp20);

$sqlorcRp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=0 AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcRp2030=mysql_num_rows($sqlorcRp2030);

$sqlorcRp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprp_orc=0 AND (DATEDIFF(now( ) , data_entra) >=30)  GROUP BY cp.cod")or die(mysql_error());
$lorcRp30=mysql_num_rows($sqlorcRp30);

?>
<html>
<head>
<title>Pendências</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style>
body {
	background-image: url(FUNDO.JPG);
}
</style>
</head>

<body>

      <p align="center" class="Titulo2">An&aacute;lise das pend&ecirc;ncias atuais na Penha Tv Color </p>
      <table width="800" border="1" align="center">
	  <tr class="Cabe&ccedil;alho">
        <td class="style1"><div align="center" class="style1"><span class="style1 style2">Pend&ecirc;ncias</span></div> </td>
		<td colspan="2" class="Cabe&ccedil;alho">
	    <div align="center"></div></td>
		<td width="62"><span class="style11">Até 20 dias</span></td>
		<td width="61"><span class="style11">20 e 30 dias</span></td>
		<td width="62"><span class="style11">Mais 30 dias</span></td>
	  </tr>
	  <tr>
          <td width="486"><div align="center"><a href="con_pendenciaana.php" title="Entradas de produtos ainda não triados pela equipe técnica" target="mainFrame" class="style5"><strong> Aguardando An&aacute;lise T&eacute;cnica</strong></a></div></td>
          <td width="28" class="Cabe&ccedil;alho">
<?print ($analise)?><div align="center"></div></td>
<td width="61" class="Cabe&ccedil;alho"><div align="center">
<?
$tot=$analise/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?>
</div>
<div align="center"></div></td>
<td><? print($p20analise);?></td>
<td><? print($p2030analise);?></td>
<td><? print($p30analise);?></td>
        </tr>
		<tr>
          <td><div align="center"><a href="con_prontoscq.php" title="Mostra todos os produtos parados na area de Controle de Qualidade" target="mainFrame" class="style5"><strong>Produtos PRONTOS Ag. Lib. do Controle de Qualidade Penha Tv Color
		 </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><? print ($lcq)?>
          <div align="center"></div></td>
<td class="Cabe&ccedil;alho"><div align="center">
<?
$tot=$lcq/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?>
</div>
<div align="center"></div></td>
<td><? print($p20pronto);?></td>
<td><? print($p2030pronto);?></td>
<td><? print($p30pronto);?></td>
        <tr bgcolor="#CCCCCC">
          <td><div align="center"><strong> <a href="con_pendenciapeca.php" title="Mostra peças necessárias para liberação destes produtos!" target="mainFrame" class="style5">Aguardando Pe&ccedil;as</a></strong></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><?print ($ltec)?></span></div></td>
		  <td rowspan="4" class="Cabe&ccedil;alho">
            <div align="center"><a href="../saatbahia/con_pendenciatecgeral.php" title="Mostra todos os produtos parados na area técnica!" target="mainFrame" class="style5">
            <?
	$tot=(($pendencias-$lcq-$analise)/$pendencias)*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?>
              </a>
		      </div></td>
		<td><? print($ltec20);?></td>
		<td><? print($ltec2030);?></td>
		<td><? print($ltec30);?></td>
        </tr>
        <tr bgcolor="#CCCCCC">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" target="mainFrame" class="style5"><strong>Or&ccedil;amentos Aguardando Aprova&ccedil;&atilde;o </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><? print ($lorcAgAp)?></span></div></td>		  
		<td><span class="style9"><? print ($lorcAgAp20)?></span></td>
		<td><span class="style9"><? print ($lorcAgAp2030)?></span></td>
		<td><span class="style9"><? print ($lorcAgAp30)?></span></td>
        </tr>
        <tr bgcolor="#CCCCCC">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" target="mainFrame" class="style5"><strong>Or&ccedil;amentos Aprovados </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><?print ($lorcAp)?></span></div></td>
		  <td><span class="style9"><? print ($lorcAp20)?></span></td>
		  <td><span class="style9"><? print ($lorcAp2030)?></span></td>
		  <td><span class="style9"><? print ($lorcAp30)?></span></td>		  		  
        </tr>	
        <tr bgcolor="#CCCCCC">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" target="mainFrame" class="style5"><strong>Or&ccedil;amentos Reprovados </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><?print ($lorcRp)?></span></div></td>		
		  <td><span class="style9"><? print ($lorcRp20)?></span></td>  
  		  <td><span class="style9"><? print ($lorcRp2030)?></span></td>  
  		  <td><span class="style9"><? print ($lorcRp30)?></span></td>  
        </tr>		
		<tr>
		<td class="Cabe&ccedil;alho">Total de Pend&ecirc;ncias</td>
		<td><span class="Cabe&ccedil;alho"><? print($pendencias);?></span></td>
		<td colspan="4"></td>
		</tr>
</table>
      <p>&nbsp;</p>
      <hr>      
      <table width="334" border="1" align="center">
        <tr class="Cabe&ccedil;alho">
          <td colspan="3"><div align="center" class="Titulo2"><strong>Prazos </strong></div></td>
        </tr>
        <tr class="Cabe&ccedil;alho">
          <td width="194">At&eacute; 20 dias </td>
          <td width="71"><? print ($p20);?></td>
          <td width="47">
            <?
$tot=$p20/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
        <tr class="Cabe&ccedil;alho">
          <td>Entre 20 e 30 dias </td>
          <td><? print ($p2030);?></td>
          <td>
            <?
$tot=$p2030/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
        <tr class="Cabe&ccedil;alho">
          <td>Mais de 30 dias </td>
          <td><? print ($p30);?></td>
          <td>
            <?
$tot=$p30/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
</table>
</body>
</html>
