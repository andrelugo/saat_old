<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sqlentrada=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_entra) = MONTH(NOW()) and YEAR(data_entra) = YEAR(NOW())")or die(mysql_error());
$sqlsaida=mysql_query("SELECT count(cod) as qt from cp where MONTH(data_sai) = MONTH(NOW()) and YEAR(data_sai) = YEAR(NOW())")or die(mysql_error());

$sqlpendencias=mysql_query("SELECT count( cp.cod ) as qt FROM cp WHERE data_sai IS NULL ")or die(mysql_error());
$sqlanalise=mysql_query("SELECT count(cod) as qt from cp where data_analize is null")or die(mysql_error());
$sqlltec1=mysql_query("SELECT cp.cod FROM cp INNER JOIN pedido ON pedido.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL GROUP BY cp.cod")or die(mysql_error());
$ltec=mysql_num_rows($sqlltec1);

$sqlorcAgAp=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and cod_decisao=0 GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp=mysql_num_rows($sqlorcAgAp);

$sqlorcAp=mysql_query("SELECT cp.cod AS cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND aprova =1 GROUP BY cod")or die(mysql_error());
$lorcAp=mysql_num_rows($sqlorcAp);

$sqlorcRp=mysql_query("SELECT cp.cod AS cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod INNER JOIN orc_decisao ON orc_decisao.cod = orc.cod_decisao
WHERE data_pronto IS NULL AND data_analize IS NOT NULL AND reprova =1 GROUP BY cod")or die(mysql_error());
$lorcRp=mysql_num_rows($sqlorcRp);


$pendencias=mysql_result($sqlpendencias,0,"qt");
$sqllcq=mysql_query("SELECT count(cod) as qt from cp where data_sai is null and data_pronto is not null")or die(mysql_error());
$entrada=mysql_result($sqlentrada,0,"qt");
$saida=mysql_result($sqlsaida,0,"qt");
$analise=mysql_result($sqlanalise,0,"qt");
//$ltec=mysql_result($sqlltec,0,"qt");
$lcq=mysql_result($sqllcq,0,"qt");

$sql20=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) <=20)")or die(mysql_error());
$p20=mysql_result($sql20,0,"qt");

$sql2030=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30)")or die(mysql_error());
$p2030=mysql_result($sql2030,0,"qt");

$sql30=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_sai IS NULL AND (DATEDIFF(now( ) , data_entra) >=30)")or die(mysql_error());
$p30=mysql_result($sql30,0,"qt");


/// Dias prazo para status
$sql4analise=mysql_query("SELECT count(cod) AS qt FROM cp WHERE data_analize IS NULL AND (DATEDIFF(now( ) , data_entra) >= 4)")or die(mysql_error());
$p4analise=mysql_result($sql4analise,0,"qt");

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
$sqlorcAgAp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and cod_decisao=0  AND (DATEDIFF(now( ) , data_entra) <=20) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp20=mysql_num_rows($sqlorcAgAp20);

$sqlorcAgAp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and cod_decisao=0 AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp2030=mysql_num_rows($sqlorcAgAp2030);

$sqlorcAgAp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod WHERE data_pronto IS NULL AND data_analize IS NOT NULL and cod_decisao=0  AND (DATEDIFF(now( ) , data_entra) >=30) GROUP BY cp.cod")or die(mysql_error());
$lorcAgAp30=mysql_num_rows($sqlorcAgAp30);
//

$sqlorcAp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=1 AND (DATEDIFF(now( ) , data_entra) <=20)  GROUP BY cp.cod")or die(mysql_error());
$lorcAp20=mysql_num_rows($sqlorcAp20);

$sqlorcAp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=1 AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcAp2030=mysql_num_rows($sqlorcAp2030);

$sqlorcAp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=1 AND (DATEDIFF(now( ) , data_entra) >=30)  GROUP BY cp.cod")or die(mysql_error());
$lorcAp30=mysql_num_rows($sqlorcAp30);
//
$sqlorcRp20=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=0 AND (DATEDIFF(now( ) , data_entra) <=20)  GROUP BY cp.cod")or die(mysql_error());
$lorcRp20=mysql_num_rows($sqlorcRp20);

$sqlorcRp2030=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=0 AND (DATEDIFF(now( ) , data_entra) >20) AND (DATEDIFF(now( ) , data_entra) <30) GROUP BY cp.cod")or die(mysql_error());
$lorcRp2030=mysql_num_rows($sqlorcRp2030);

$sqlorcRp30=mysql_query("SELECT cp.cod FROM cp INNER JOIN orc ON orc.cod_cp = cp.cod inner join orc_decisao on orc_decisao.cod = orc.cod_decisao WHERE data_pronto IS NULL AND data_analize IS NOT NULL and aprova=0 AND (DATEDIFF(now( ) , data_entra) >=30)  GROUP BY cp.cod")or die(mysql_error());
$lorcRp30=mysql_num_rows($sqlorcRp30);


?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {
	font-size: 18px;
	font-weight: bold;
}
body {
	background-image: url(img/fundo.gif);
}
.style2 {
	font-size: 24px;
	color: #0000FF;
}
.style3 {
	font-size: 24px;
	font-weight: bold;
	color: #0000FF;
}
.style4 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style5 {color: #000000}
.style6 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000000; }
.style9 {color: #0000FF; font-weight: bold; }
.style10 {
	font-size: 24px;
	font-weight: bold;
}
.style12 {color: #FF0000}
.style13 {color: #FF0000; font-weight: bold; }
-->
</style>
</head>

<body topmargin="0">
<td><p align="center" class="style3">Indicador de Demanda X Vaz&atilde;o </p>
      <div align="center">
        <table width="534" border="1">
          <tr>
            <td width="480"><div align="center" class="style4 style5">ENTRADAS DESTE MÊS</a></div></td>
            <td width="38"><?print ($entrada)?></td>
          </tr>
          <tr>
            <td><div align="center" class="style6">SAIDAS DESTE MÊS</a></div></td>
            <td><?print ($saida)?></td>
          </tr>
		  <tr>
		  <td class="style1"><div align="center">Acumulado deste Mês</div></td>
		  <td class="style1"><? $ac=$entrada-$saida;
		print($ac);
		?></td>
		  </tr>
        </table>
      </div>
  <form name="form1" method="get" action="con_acu_mes.php">
        <p align="center">
    <input name="cmdAcuMes" type="submit" id="cmdAcuMes" value="Visualizar">
    Entradas e sa&iacute;das por modelo de
    <select name="txtMes" id="txtMes">
          <option value="1" <? if (date("n")==1){print ("selected");}?>>Janeiro</option>
          <option value="2"<? if (date("n")==2){print ("selected");}?>>Fevereiro</option>
          <option value="3"<? if (date("n")==3){print ("selected");}?>>Mar&ccedil;o</option>
          <option value="4"<? if (date("n")==4){print ("selected");}?>>Abril</option>
          <option value="5"<? if (date("n")==5){print ("selected");}?>>Maio</option>
          <option value="6"<? if (date("n")==6){print ("selected");}?>>Junho</option>
          <option value="7"<? if (date("n")==7){print ("selected");}?>>Julho</option>
          <option value="8"<? if (date("n")==8){print ("selected");}?>>Agosto</option>
          <option value="9" <? if (date("n")==9){print ("selected");}?>>Setembro</option>
          <option value="10"<? if (date("n")==10){print ("selected");}?>>Outubro</option>
          <option value="11"<? if (date("n")==11){print ("selected");}?>>Novembro</option>
          <option value="12"<? if (date("n")==12){print ("selected");}?>>Dezembro</option>
          </select>
          de
          <input name="txtAno" type="text" id="txtAno" value="<? print(date("Y"));?>" size="4" maxlength="4">
       do fornecedor:
       <select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
              <option value="0">TODOS</option>
              <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linha = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linha[cod]){
			print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
			print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
    </select> 
       ou 
       <input name="cmdAcuMes" type="submit" id="cmdAcuMes" value="Gerar Gr&aacute;fico">
    </p>
</form>
      <div align="center" class="style10">An&aacute;lise das pend&ecirc;ncias atuais na Penha Tv Color - TODAS AS MARCAS</div>  <table width="334" border="1" align="center">
        <tr>
          <td colspan="3"><div align="center" class="style3"><strong>Prazos</strong></div></td>
        </tr>
        <tr class="style1">
          <td width="194">At&eacute; 20 dias </td>
          <td width="71"><? print ($p20);?></td>
          <td width="47">
            <?
$tot=$p20/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
        <tr class="style1">
          <td>Entre 20 e 30 dias </td>
          <td><? print ($p2030);?></td>
          <td>
            <?
$tot=$p2030/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
        <tr class="style1">
          <td>Mais de 30 dias </td>
          <td><? print ($p30);?></td>
          <td>
            <?
$tot=$p30/$pendencias*100;
$Rvalor = number_format($tot, 2, ',', '.') . "%";
print($Rvalor); 
?></td>
        </tr>
      </table>      <table width="884" border="1" align="center">
	  <tr class="Cabe&ccedil;alho">
        <td class="style1"><div align="center" class="style1"><span class="style1 style2"><a href="con_pendencia_geral.php">Pend&ecirc;ncias</a></span></div> </td>
		<td colspan="2" class="Cabe&ccedil;alho">
	    <div align="center" class="style4"><? print($pendencias);?></div></td>
		<td width="146"><span class="style11">Até 20 dias</span></td>
		<td width="59"><span class="style11">20 e 30 dias</span></td>
		<td width="64"><span class="style11 style5">Mais 30 dias</span></td>
	  </tr>
	  <tr>
          <td width="486"><div align="center"><a href="con_pendenciaana.php" title="Entradas de produtos ainda não triados pela equipe técnica" class="style5"><strong> Aguardando An&aacute;lise T&eacute;cnica</strong></a> </div>
<?
if($p4analise<>0){
	print("<font color=red> <h3> Providencie soluções imediatas. Existem: ".$p4analise." produtos aguardando analise a mais de 4 dias</h3></font>");
}?>
		  </td>
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
<td><? print($p20analise);?> </td>
<td> <font color="#FF0000"> <h2><? print($p2030analise);?></h2></font></td>
<td><font color="#FF0000"> <h2><? print($p30analise);?></h2></font></td>
        </tr>
		<tr>
          <td><div align="center"><a href="con_prontoscq.php" title="Mostra todos os produtos parados na area de Controle de Qualidade" class="style5"><strong>Produtos PRONTOS Ag. Lib. do Controle de Qualidade Penha Tv Color
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
<td><font color="#FF0000"> <h2><? print($p2030pronto);?></h2></font></td>
<td><font color="#FF0000"> <h2><? print($p30pronto);?></h2></font></td>
        <tr bgcolor="#FFFFFF">
          <td><div align="center"><strong> <a href="con_pendenciapeca.php" title="Mostra peças necessárias para liberação destes produtos!" class="style5">Aguardando Pe&ccedil;as</a></strong></div></td>
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
		<td><span class="style12"><? print($ltec30);?></span></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" class="style5"><strong>Or&ccedil;amentos Aguardando Aprova&ccedil;&atilde;o </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><? print ($lorcAgAp)?></span></div></td>		  
		<td><span class="style9"><? print ($lorcAgAp20)?></span></td>
		<td><span class="style9"><? print ($lorcAgAp2030)?></span></td>
		<td><span class="style13"><? print ($lorcAgAp30)?></span></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" class="style5"><strong>Or&ccedil;amentos Aprovados </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><?print ($lorcAp)?></span></div></td>
		  <td><span class="style9"><? print ($lorcAp20)?></span></td>
		  <td><span class="style9"><? print ($lorcAp2030)?></span></td>
		  <td><span class="style13"><? print ($lorcAp30)?></span></td>		  		  
        </tr>	
        <tr bgcolor="#FFFFFF">
          <td><div align="center" class="style5"><a href="con_pendenciaorc.php" title="Mostra todos os produtos em orçamento!" class="style5"><strong>Or&ccedil;amentos Reprovados </strong></a></div></td>
          <td class="Cabe&ccedil;alho"><div align="center"><span class="style9"><?print ($lorcRp)?></span></div></td>		
		  <td><span class="style9"><? print ($lorcRp20)?></span></td>  
  		  <td><span class="style9"><? print ($lorcRp2030)?></span></td>  
  		  <td><span class="style13"><? print ($lorcRp30)?></span></td>  
        </tr>		
</table>
</body>
</html>
