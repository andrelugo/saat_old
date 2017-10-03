<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {	font-size: 24px;
	font-weight: bold;
}
.style2 {font-size: 9px}
.style3 {font-weight: bold}
body {
	background-image: url(img/fundo.gif);
}
-->
</style>
</head>

<body>
<p align="center"> <span class="style1">AGUARDANDO ANALISE T&Eacute;CNICA </span></p>
<table width="258" border="1" align="center">
    <tr>
      <td width="139"><div align="center">Modelo</div></td>
      <td width="103"><div align="center">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where data_analize is null
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à produtos sem analise técnica");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td><strong>TOTAL</strong></td>
  <td><strong><strong><?print("$count");?></strong></strong></td></tr>
</table>
<hr>
<p align="center"><span class="style3"><strong>Detalhado<br>
</strong></span><em>Os dias parados abaixo contam a partir da data de entrada no box e n&atilde;o a partir da data do barcode</em> </p>
<div align="center">
  <div align="center">
    <table width="583" border="1">
        <tr>
          <td width="137">Modelo</td>
          <td width="194">Código de Barras</td>
	      <td width="169">Data Cód Barras</td>
	      <td width="55"><span class="style2">Dias Parado</span></td>
      </tr>
      <?
$count = 0;
$sql="select modelo.descricao as mode,barcode,date_format(data_barcode,'%d/%m/%Y') as data_barcode,DATEDIFF(now(),data_entra) as dd
from cp inner join modelo on modelo.cod = cp.cod_modelo
where data_analize is null
order by dd desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print("<tr><td> $linha[mode] </td><td> $linha[barcode] </td> <td> $linha[data_barcode] </td><td> $linha[dd] </td></tr>");
		$count++;
}
?>
      <tr class="style3"><td class="style3">TOTAL</td><td class="style3"><span class="style3"><?print("$count");?></span></td></tr>
    </table>
  </div>
</body>
</html>
