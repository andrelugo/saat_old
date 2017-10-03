<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$modelo=$_GET["modelo"];
if($modelo=="todos"){
	$where="";
}else{
	$where="and cp.cod_modelo = $modelo";
}
$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$tot = mysql_num_rows ($sqlCliente);
if ($tot>0){
	$cliente=mysql_result($sqlCliente,0,"cod");
}

?>
<html>
<head>
<title>Pendência de Peças</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>

<body>
<p align="center"> <span class="Cabe&ccedil;alho">Resumo de  pe&ccedil;as funcionais (pedido di&aacute;rio)<br>
</span> Este relatório inclui apenas itens de produtos NÃO PRONTOS. </p>
<table width="677" border="1" align="center">
    <tr>
      <td width="70"><div align="center" class="Cabe&ccedil;alho">Código</div></td>
      <td width="510"><div align="center" class="Cabe&ccedil;alho">Pe&ccedil;a</div></td>
      <td width="75"><div align="center" class="Cabe&ccedil;alho">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$sql="select sum(pedido.qt) as qt,peca.descricao, peca.cod_fabrica as cod
from pedido
inner join peca on peca.cod = pedido.cod_peca
inner join cp on cp.cod = pedido.cod_cp
where cp.data_sai is null and cp.data_pronto is null
$where
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
<p align="center"> <span class="Cabe&ccedil;alho">Resumo de  pe&ccedil;as est&eacute;ticas e acess&oacute;rios (Or&ccedil;amento / Pedido de compra) <br>
</span>
</span> Este relatório inclui apenas itens de produtos NÃO PRONTOS.</p>
<table width="677" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
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
where cp.data_sai is null and cp.data_pronto is null
$where
group by peca.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL 2:".mysql_error());
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
    <table width="999" border="1">
        <tr class="Cabe&ccedil;alho">
          <td width="69">O.S.</td>
          <td width="121">Modelo</td>
          <td width="71">Código de Barras</td>
	      <td width="40">Data Cód Barras</td>
	      <td width="37"><span class="style2">Dias Estacmto </span></td>
		  <td width="222">Peças Garantia</td>
		  <td width="226">Peças Orçamento</td>
		  <td width="48">Posição</td>
		  <td width="107">Status</td>
      </tr>
      <?
$count = 0;
$sql="select cp.os_fornecedor as os,cp.item_os_fornecedor as osi, modelo.descricao as mode,barcode,date_format(data_barcode,'%d/%m/%Y') as data_barcode,DATEDIFF(now(),data_entra) as dd,
posicao.descricao as posicao,cp.cod as cod,data_analize,data_pronto,DATEDIFF(now(),data_pronto) as diasPronto,orc_cliente
from cp 
inner join modelo on modelo.cod = cp.cod_modelo
inner join posicao on posicao.cod = cp.cod_posicao
where data_sai is null
$where
order by dd desc";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>	<tr>
		<td><? print("$linha[os]-$linha[osi]");?></td>
		<td><? print("$linha[mode]");?></td>
		<td><? print("$linha[barcode]");?></td>
		<td><? print("$linha[data_barcode]");?></td>
		<td><? print("$linha[dd]");?></td>
		<td><? $sql="select peca.descricao as pc from pedido inner join peca on peca.cod = pedido.cod_peca where pedido.cod_cp = $linha[cod]";
			$res2=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta às peças garantia".mysql_error());
			$pecag="";
			while ($linha2 = mysql_fetch_array($res2)){
				$pecag = "$pecag $linha2[pc]<br>";
			}
				print ("<div align='left'>$pecag</div>");
		?></td>
		<td><? $sqlo="select peca.descricao as pc from orc inner join peca on peca.cod = orc.cod_peca where orc.cod_cp = $linha[cod]";
			$res3=mysql_db_query ("$bd",$sqlo,$Link) or die ("Erro na string SQL de consulta às peças orçamento".mysql_error());
			$pecao="";
			while ($linha3 = mysql_fetch_array($res3)){
				$pecao = "$pecao $linha3[pc]<br>";
			}
			print ("<div align='left'>$pecao</div>");
		?></td>
		<td><? print("$linha[posicao]");?></td>
		<td><? 
			$dtanalise=$linha["data_analize"];
			$dtpronto=$linha["data_pronto"];
			$numOrc=$linha["orc_cliente"];
			if($dtanalise==NULL){
				$status="Aguarda analise a $linha[dd] dias";
			}else{
				if($dtpronto==NULL){
					$sta=mysql_db_query ("$bd","select cod_decisao from orc where orc.cod_cp = $linha[cod]",$Link) or die (mysql_error());
					$rows=mysql_num_rows ($sta);
					if ($rows==0){
						$status="Analisado mas não Pronto - Orç. Vazio!";
					}else{
						$decisao=mysql_result($sta,0,"cod_decisao");
						// Se a coluna Numero do Orçamento no Cliente (orc_cliente)Orçamento for NULL e o cliente não foor Casa Bahia então 
						if ($numOrc==NULL && $cliente==1){
							$status = "Orçamento Ag. Dig.!";
						}else{
							if($decisao<>0){
								$sqlDesc=mysql_db_query ("$bd","select descricao from orc_decisao where cod=$decisao",$Link) or die (mysql_error());
								$status="Orçamento: ".mysql_result($sqlDesc,0,"descricao")." - não Pronto";
							}else{
								$status="Orçamento Ag. decisão do Cliente!";
							}
						}
					}
					
				}else{
					$status="Pronto a $linha[diasPronto] dias";
				}
			}
			print("$status");
		?></td>
	</tr>
<?		$count++;
}
?>
      <tr class="Cabe&ccedil;alho">
      <td class="style3">TOTAL</td><td colspan="8" class="style3"><span class="style3"><?print("$count");?></span></td></tr>
    </table>
  </div>
</body>
</html>
