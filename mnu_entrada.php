<?
require_once("sis_valida.php");
require_once("sis_conn.php");
session_start();
print("Id de sessão: ".session_id()."<br>");


?>
<html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>
<body>
<?
$res=mysql_query("SELECT entrada FROM rh_cargo INNER JOIN rh_user ON rh_user.cargo = rh_cargo.cod WHERE rh_user.cod = $id");
$entrada=mysql_result($res,0,"entrada");
if ($entrada==1){
	$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
	$tot = mysql_num_rows ($sqlCliente);
	if ($tot>0){
		$cliente=mysql_result($sqlCliente,0,"cliente");
?>
		<p align="center" class="caixaAZ1"><a href="frm_entrarg.php" class="Titulo2"><strong>Entrada Coletiva de Produtos <? print($cliente);?></strong></a></p>
<?
	}else{ 
?>
		<p align="center" class="caixaAZ1"><a href="frm_entraconsumidor.php" class=""><strong>Entrada Individual (Consumidor) </strong></a></p>
		<p align="center" class="caixaAZ1"><a href="frm_nf_entrada.php">Entrada Coletiva (Nota Fiscal)</a></p>
<?
	}
}
?>
<hr>


<hr>
<p align="center" class="Cabe&ccedil;alho">Entrada de Hoje <?print(date("d/m/Y"))?></p>
<table width="258" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="139"><div align="center">Modelo</div></td>
      <td width="103"><div align="center">Quantidade</div></td>
  </tr>
<?
$count = 0;
$dia = date("d m Y");	  
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where DATE_FORMAT(data_entra, '%d %m %Y') = '$dia'
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql<br>".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td class="style3">TOTAL</td><td class="style3"><?print("$count");?></td></tr>
</table>
<p align="center" class="Cabe&ccedil;alho">Detalhado</p>
<div align="center">
  <table width="583" border="1">
      <tr class="Cabe&ccedil;alho">
        <td width="172">Modelo</td>
        <td width="250">Cód Barras</td>
	    <td width="139">Data Cód Barras</td>
    </tr>
<?
$count = 0;
$dia = date("d m Y");	  
$sql="select modelo.descricao as mode,barcode,data_barcode
from cp inner join modelo on modelo.cod = cp.cod_modelo
where DATE_FORMAT(data_entra, '%d %m %Y') = '$dia'";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print("<tr><td> $linha[mode] </td><td> $linha[barcode] </td> <td> $linha[data_barcode] </td></tr>");
		$count++;
}
?>
    <tr class="style3"><td class="Cabe&ccedil;alho">TOTAL</td>
    <td class="style3"><span class="style3"><?print("$count");?></span></td></tr>
  </table>
</div>
</body>
</html>
