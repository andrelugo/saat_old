<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body>

<div align="center" class="style1">
  <p class="Titulo1">Menu de Sa&iacute;das </p>
  <table width="471" border="0">
<?
$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$tot = mysql_num_rows ($sqlCliente);
if ($tot>0){
	$codCliente=mysql_result($sqlCliente,0,"cod");
}
if($codCliente==2){
?>	
    <tr>
      <td width="465" class="Cabe&ccedil;alho"><div align="left"><a href="frm_sairg_orc.php"><img src="img/botoes/s_okay.png" width="16" height="16" border='0'></a> Marcar Produtos PRONTOS / APROVADOS (Vendas) </div></td>
    </tr>
<?
}else{
?>
    <tr>
      <td width="465" class="Cabe&ccedil;alho"><div align="left"><a href="frm_sairg.php"><img src="img/botoes/s_okay.png" width="16" height="16" border='0'></a> Marcar Produtos PRONTOS / APROVADOS</div></td>
    </tr>
<?
}
?>	
    <tr>
      <td class="Cabe&ccedil;alho"><div align="left"><a href="frm_reprg.php" title="Devolver um produto sem condições de entrega ao técnico"><img src="img/botoes/s_error.png" width="16" height="16" border='0'></a> Reprovar um produto</div></td>
    </tr>
    <tr>
      <td class="Cabe&ccedil;alho"><div align="left"><a href="con_prontoscq.php" title="Produtos prontos para Teste, Limpeza e Embalagem, ainda não liberados!"><img src="img/botoes/s_status.png" width="16" height="16" border='0'></a> Consultar produtos prontos para CQ</div></td>
    </tr>
    <tr>
      <td class="Cabe&ccedil;alho"><div align="left"><a href="scr_fecha_cq.php" title="Salva uma planilha possibilitando a visualização dos códigos de barras APROVADOS pelo CQ ao dpto administrativo!"><img src="img/botoes/b_save.png" width="16" height="16" border='0'></a> SALVAR PLANILHA com 
<font color=red>
<?
$res=mysql_query("select cp.cod as cp, modelo.descricao as modelo,barcode,destino.descricao as destino	
from cp inner join
modelo on modelo.cod = cp.cod_modelo inner join
destino on destino.cod = cp.cod_destino
where cp.data_sai is not null and cp.folha_cq is null and cp.cod_cq=$id");
$tot=mysql_num_rows($res);
print($tot);
?></font> produto(s) </div></td>
    </tr>
  </table>
  Produtos a serem salvos na proxima planilha
  <table border="1">
  <tr>
  <td width="176">Cód Barras</td>
  <td width="380">Modelo</td>
  <td width="165">Destino</td>
  </tr>
<? while ($linha = mysql_fetch_array($res)){
?>
    <tr>
  <td><? print ($linha["barcode"]);?></td>
  <td><? print ($linha["modelo"]);?></td>
  <td><? print ($linha["destino"]);?></td>
  <td width="28"><a href="scr_excluircq.php?cp=<? print ($linha["cp"]);?>"><img src='img/botoes/b_drop.png' width='16' height='16' border='0'></a></td>
  </tr>
  <? }?>
  </table>
  <hr>
  <hr>
  <span class="Titulo2">Sua produ&ccedil;&atilde;o de Hoje <?print(date("d/m/Y"))?><br>
  Detalhado</span></div>
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
where DATE_FORMAT(data_sai, '%d %m %Y') = '$dia' and cod_cq = $id";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print("<tr><td> $linha[mode] </td><td> $linha[barcode] </td> <td> $linha[data_barcode] </td></tr>");
		$count++;
}
?>
    <tr class="style3"><td class="Cabe&ccedil;alho">TOTAL</td><td class="style3"><span class="style3"><?print("$count");?></span></td></tr>
  </table>
  <p class="Titulo2">Sua produ&ccedil;&atilde;o neste M&ecirc;s<br>
    <span class="style6">Agrupado por modelo</span> </p>
  <table width="258" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="139"><div align="center">Modelo</div></td>
      <td width="103"><div align="center">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$dia = date("m Y");	  
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where DATE_FORMAT(data_sai, '%m %Y') = '$dia' and cod_cq = $id
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td class="Cabe&ccedil;alho">TOTAL</td><td class="style3"><?print("$count");?></td></tr>
</table>


</div>
</body>
</html>
