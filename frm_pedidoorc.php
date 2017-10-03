<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title></title>
</head>
<body>
<p align="center"><strong>Pedido de compra de peças aprovadas em orçamento</strong></p>
<p align="center"><strong>Parametriza&ccedil;&atilde;o</strong></p>
<form name="form1" method="post" action="con_pedidoorc.php">	
  <table width="799" border="1" align="center">
    <tr>
      <td width="165">Cliente</td>
      <td width="321"><select name="cmbCliente" class="style5" id="select3"  tabindex="5" >
        <option value="0">Selecione</option>
        <?	  
$sql="select * from cliente where revenda = 1";//seleciona todos os clientes cadastrados como REVENDA
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
      </select></td>
      <td width="53">Linha</td>
      <td width="232"><select name="cmbLinha" class="style5" id="select2"  tabindex="5" >
        <option value="0">Selecione</option>
        <?	  
$sql="select * from linha where ativo = 1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
      </select></td>
    </tr>
    <tr>
      <td>Fornecedor</td>
      <td>        <select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
          <option value="0">Selecione</option>
          <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linhaF = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
        </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="Submit" value="Pesquisar pedidos aprovados">
  </p>
</form>
<p>Pedidos pendentes de compra:</p>
<table width="812" border="1" align="center">
  <tr>
    <td width="280">Cliente</td>
    <td width="114">Fornecedor</td>
    <td width="230">Linha</td>
    <td width="62">Qtdade</td>
    <td width="92">Valor</td>
  </tr>
<?
$sql="select fornecedor.descricao as fornecedor, linha.descricao as linha, sum(orc.qt)as qt, sum(orc.qt * orc.valor) as vl, cliente.descricao as cliente
from orc
inner join cp on cp.cod = orc.cod_cp
inner join cliente on cliente.cod = cp.cod_cliente
inner join modelo on modelo.cod = cp.cod_modelo
inner join fornecedor on fornecedor.cod = modelo.cod_fornecedor
inner join linha on linha.cod = modelo.linha
where orc.cod_orc_compra is null
group by fornecedor.descricao, linha.descricao";

$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
  <tr>
    <td><? print ($linha["cliente"]);?></td>
    <td><? print ($linha["fornecedor"]);?></td>
    <td><? print ($linha["linha"]);?></td>
    <td><? print ($linha["qt"]);?></td>
    <td><? print ($linha["vl"]);?></td>
  </tr>
<?
}
?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
