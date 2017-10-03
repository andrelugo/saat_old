<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Pendência de Peças</title>
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
<p align="center"> <span class="style1">EM OR&Ccedil;AMENTO </span></p>
<table width="677" border="1" align="center">
    <tr>
      <td width="70"><div align="center">Código</div></td>
      <td width="510"><div align="center">Pe&ccedil;a</div></td>
      <td width="75"><div align="center">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$sql="select sum(orc.qt) as qt,peca.descricao, peca.cod_fabrica as cod
from orc
inner join peca on peca.cod = orc.cod_peca
inner join cp on cp.cod = orc.cod_cp
where cp.data_sai is null
group by peca.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à produtos sem analise técnica");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[cod]</td><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td><strong>TOTAL</strong></td>
  <td colspan="2"><strong><strong><?print("$count");?></strong></strong></td></tr>
</table>
<hr>
<p align="center"><span class="style3"><strong>Detalhado<br>
</strong></span><em>Os dias parados abaixo contam a partir da data de entrada no box e n&atilde;o a partir da data do barcode</em> </p>
<div align="center">
  <div align="center">
    <table width="710" border="1">
        <tr>
          <td width="80">O.S.</td>
          <td width="288">Modelo</td>
          <td width="123">Código de Barras</td>
	      <td width="132">Data Cód Barras</td>
	      <td width="53"><span class="style2">Dias Parado</span></td>
      </tr>
      <?
$count = 0;
$sql="select cp.os_fornecedor as os,cp.item_os_fornecedor as osi, modelo.descricao as mode,barcode,date_format(data_barcode,'%d/%m/%Y') as data_barcode,DATEDIFF(now(),data_entra) as dd
from cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join orc on orc.cod_cp = cp.cod
where data_pronto is null
group by mode,barcode,data_barcode,dd,os,osi
order by dd desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print("<tr><td> $linha[os]-$linha[osi]</td><td> $linha[mode] </td><td> $linha[barcode] </td> <td> $linha[data_barcode] </td><td> $linha[dd] </td></tr>");
		$count++;
}
?>
      <tr class="style3"><td class="style3">TOTAL</td><td colspan="4" class="style3"><span class="style3"><?print("$count");?></span></td></tr>
    </table>
  </div>
</body>
</html>
