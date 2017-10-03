<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["txtDiaIni"])){$DiaIni=$_GET["txtDiaIni"];}else{$DiaIni=date("d");}
if (isset($_GET["txtMesIni"])){$MesIni=$_GET["txtMesIni"];}else{$MesIni=date("m");}
if (isset($_GET["txtAnoIni"])){$AnoIni=$_GET["txtAnoIni"];}else{$AnoIni=date("Y");}
if (isset($_GET["txtDiaFim"])){$DiaFim=$_GET["txtDiaFim"];}else{$DiaFim=date("d");}
if (isset($_GET["txtMesFim"])){$MesFim=$_GET["txtMesFim"];}else{$MesFim=date("m");}
if (isset($_GET["txtAnoFim"])){$AnoFim=$_GET["txtAnoFim"];}else{$AnoFim=date("Y");}
$dtini="$AnoIni-$MesIni-$DiaIni";
$dtfim="$AnoFim-$MesFim-$DiaFim";

$sql="select modelo.descricao as modelo, cp.serie as serie,date_format(data_sai,'%d/%m/%y %H:%i') as data_sai, rh_user.nome as tecnico,
os_fornecedor
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner 
join rh_user on rh_user.cod = cp.cod_tec
WHERE data_sai BETWEEN ('$dtini')AND('$dtfim')
and modelo.cod_fornecedor = 3";
?>
<html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-size: 24px}
-->
</style>
</head>
<body>
<? // print($sql);?>
<form name="form1" method="get" action="con_os_novadata_fin.php">
  <div align="center">
    <p align="center">
      De:
        <input name="txtDiaIni" type="text" id="txtDiaIni" value="<?print ($DiaIni);?>" size="3" maxlength="2">
/
<input name="txtMesIni" type="text" id="txtMesIni" size="3" maxlength="2" value="<?print ($MesIni);?>" >
/
<input name="txtAnoIni" type="text" id="txtAnoIni" size="5" maxlength="4" value="<?print ($AnoIni);?>" >
------ At&eacute;:
    <input name="txtDiaFim" type="text" id="txtDiaFim" size="3" maxlength="2" value="<?print ($DiaFim);?>" >
/
<input name="txtMesFim" type="text" id="txtMesFim" size="3" maxlength="2" value="<?print ($MesFim);?>" >
/
<input name="txtAnoFim" type="text" id="txtAnoFim" size="5" maxlength="4" value="<?print ($AnoFim);?>" >
-------
<input type="submit" name="Submit" value="Pesquisar">
<input type="hidden" name="order" value="<? print($order);?>">
</form>
<br>
    <div align="center">
      <table width="785" border="1">
	  <TR><TD colspan="7"><div align="center">FINALIZA&Ccedil;&Atilde;O DE CHAMADOS</div></TD>
	  </TR>
      <tr class="Cabe&ccedil;alho">
        <td width="110">Chamado</td>
        <td width="159">Modelo</td>
        <td width="147">S&eacute;rie</td>
		<td width="170">Finalização</td>
		<td width="740">Técnico</td>
    </tr>
<?
$count = 0;
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
<td><? $osf=$linha["os_fornecedor"];
if ($osf=="0"){$osf="&nbsp;";}
print($osf);
?></td>
<td><? print($linha["modelo"]);?></td>
<td><? print($linha["serie"]);?></td>
<td>
<? 
print($linha["data_sai"]);
?></td>
<td><? print($linha["tecnico"]);?></td>
</tr>
<?
	$count++;
}
?>

    <tr class="style3">
	<td colspan="4" class="Cabe&ccedil;alho"><div align="right">TOTAL</div></td>
    <td><?print("$count");?></td>
    </tr>
  </table>
</div>
</body>
</html>
