<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="SELECT count( cp.cod ) AS qt, posicao.descricao AS posicao
FROM cp
INNER JOIN posicao ON posicao.cod = cp.cod_posicao
WHERE data_sai IS NULL
GROUP BY posicao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
$tot=0;
?>
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<body>
  <p align="center"><span class="Titulo2">Posi&ccedil;&atilde;o (f&iacute;sica) atual dos produtos.</span></p>
  <table width="615" border="1" align="center">
<tr>
	<td width="473"><div align="center"><strong>Posi&ccedil;&atilde;o</strong></div></td>
	<td width="126"><div align="center"><strong>Qtdade</strong></div></td>
</tr>
<?
while ($linha = mysql_fetch_array($res)){
		$posicao=$linha["posicao"];
		$qt=$linha["qt"];
		$tot=$tot+$qt;
?>
<tr>
<td><? print($posicao);?></td>
<td><? print($qt);?></td>
</tr>
<?
}
?>
<tr class="style1">
<td>Total</td>
<td colspan="2"><? print ($tot);?></td>
</table>
  
  </div>
  
  <p align="center"><span class="style3"><strong>Detalhado<br>
</strong></span><em>Os dias parados abaixo contam a partir da data de entrada no box e n&atilde;o a partir da data do barcode</em> </p>
<div align="center">
  <div align="center">
    <table width="999" border="1">
        <tr>
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
and posicao.inventario = 1
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
      <tr class="style3"><td class="style3">TOTAL</td><td colspan="8" class="style3"><span class="style3"><?print("$count");?></span></td></tr>
    </table></div>

</body>
</html>
