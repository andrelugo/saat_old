<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["chkPendentes"])){$pendentes=$_GET["chkPendentes"];}else{$pendentes=0;}
if ($pendentes==1){
	$msg="DE TODOS OS ATENDIMENTOS SEM CHAMADO";
	$dia="";
	$mes="";
	$ano="";

	$where="WHERE os_fornecedor = '0'
	AND cod_fornecedor =3
	AND linha.cortesia =0
	order by marca";
}else{
	$diah=date("d");
	$mesh=date("m");
	$anoh=date("Y");
	if (isset($_GET["txtDiaIni"])){$dia=$_GET["txtDiaIni"];}else{$dia=0;}
	if (isset($_GET["txtMesIni"])){$mes=$_GET["txtMesIni"];}else{$mes=$mesh;}
	if (isset($_GET["txtAnoIni"])){$ano=$_GET["txtAnoIni"];}else{$ano=$anoh;}
	if($dia==0){
		$msg="";
	}else{
		$msg="Abertos em $dia/$mes/$ano";
	}
	$tamanho=strlen($ano);
	if ($tamanho<4){
		die("ERRO: Ano com menos que três caracteres! Redigite o ano com os quatro caracteres!");
	}
	if ($diah==$dia && $mesh==$mes && $anoh==$ano){
		die("Impossível enviar ordens abertas hoje! Somente um dia após a analise de um produto as peças devem ser solicitadas
		ao fabricante por motivos de consistências no sistema SAAT II");
	}
	$where="WHERE day(data_analize)=$dia and
	month(data_analize)=$mes and
	year(data_analize)=$ano
	and modelo.cod_fornecedor = 3";
}	


$sql="select cp.cod as cod,modelo.descricao as modelo, cp.serie as serie, defeito.descricao as defeito,defeito_reclamado,
solucao.descricao as solucao, date_format(data_sai,'%d/%m/%y %H:%i') as data_sai, rh_user.nome as tecnico,os_fornecedor,data_pronto
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner 
join defeito on defeito.cod = cp.cod_defeito inner 
join solucao on solucao.cod = cp.cod_solucao inner
join rh_user on rh_user.cod = cp.cod_tec inner
join linha on linha.cod = modelo.linha
$where";


	$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod, cliente.endereco as endereco,
	cliente.telefone as telefone from cliente inner join base on base.cliente_exclusivo = cliente.cod");
	$tot = mysql_num_rows ($sqlCliente);
	if ($tot>0){
		$cliente=mysql_result($sqlCliente,0,"cliente");
		$endereco=mysql_result($sqlCliente,0,"endereco");
		$telefone=mysql_result($sqlCliente,0,"telefone");
	}

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
<form name="form1" method="get" action="con_os_novadata.php">
  <div align="center">
    <p>
      <input type="submit" name="Submit" value="Pesquisar"> 
      <br>
      Por 
      Data:
        <input name="txtDiaIni" type="text" id="txtDiaIni" value="<? print ($dia);?>" size="3" maxlength="2">
    /
    <input name="txtMesIni" type="text" id="txtMesIni" size="3" maxlength="2" value="<? print ($mes);?>" >
    /
    <input name="txtAnoIni" type="text" id="txtAnoIni" size="5" maxlength="4" value="<? print ($ano);?>" >   
    <input type="hidden" name="order" value="<? print($order);?>">
<br>
Ou Todos os 
<?
$sqlP="SELECT count( cp.cod ) AS qt
FROM cp
INNER JOIN modelo ON modelo.cod = cp.cod_modelo
INNER JOIN linha ON linha.cod = modelo.linha
WHERE os_fornecedor = '0'
AND cod_fornecedor =3
AND linha.cortesia =0";
$sqlQt=mysql_query($sqlP);
$cont=mysql_result($sqlQt,0,"qt");
print($cont);?>
Chamados 

Pendentes 
<input type="checkbox" name="chkPendentes" value="1">    
</form>
<br>
    <table width="619" border="1" align="center">
	<tr>
        <td colspan="2"><p></p>
        <p align="center" class="caixaPR1 style1">Penha Tv Color </p></td>
      </tr>
      <tr>
        <td colspan="2"><p></p>
        <p align="center">Envio de Chamados Nova Data Penha Tv Color <? print($msg);?></p></td>
      </tr>
      <tr>
        <td width="194">Solicitante:</td>
        <td width="409"><? print("$cliente");?></td>
      </tr>
      <tr>
        <td>Endere&ccedil;o do Solitante: </td>
        <td><? print("$endereco");?></td>
      </tr>
      <tr>
        <td>Tel. Solicitante:</td>
        <td><? print("$telefone");?></td>
      </tr>
    </table>
      <table width="1360" border="1">
      <tr class="Cabe&ccedil;alho">
        <td width="57">CP</td>
        <td width="131">Modelo</td>
        <td width="175">S&eacute;rie</td>
		<td width="156">Defeito Reclamado</td>
	    <td width="156">Defeito Constatado</td>
		<td width="183">Solução</td>						
	    <td width="214">Peças Garantia</td>
	    <td width="101">Chamado</td>
		<td width="148">Finalização</td>
		<td width="137">Técnico</td>
    </tr>
<?
$count = 0;

$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql<BR>".mysql_error());
while ($linha = mysql_fetch_array($res)){
?>
<tr>
<td><? print($linha["cod"]);?></td>
<td><? print($linha["modelo"]);?></td>
<td><? print($linha["serie"]);?></td>
<td><?
 $def=$linha["defeito_reclamado"];
if ($def==""){
	$defeitoR="Não Definido! (anterior a 22/08/06)";
}else{
	$defeitoR=$linha["defeito_reclamado"];
}
print($defeitoR);?>
</td>
<td><? print($linha["defeito"]);?></td>
<td><? print($linha["solucao"]);?></td>
<td><? 
$cp=$linha["cod"];
$sql2="select descricao 
from pedido inner
join peca on peca.cod = pedido.cod_peca
where pedido.cod_cp = $cp";
$res2=mysql_db_query ("$bd",$sql2,$Link) or die ("Erro na string SQL de consulta à entrada de produtos".mysql_error());
$row=mysql_num_rows($res2);
if ($row==0){
	$res3=mysql_query("select cod from orc where cod_cp=$cp");
	$row1=mysql_num_rows($res3);
	if ($row1==0){
		print("Não há pedido de peça!");
	}else{
		print("Há $row1 peça(s) trocada(s) em ORÇAMENTO!");
	}
}else{
	while ($linha2 = mysql_fetch_array($res2)){
		print($linha2["descricao"]."<br>");
	}
}
?></td>


<td>
<? $osf=$linha["os_fornecedor"];
if ($osf=="0"){$osf="&nbsp;";}
print($osf);
?></td>
<td>
<? 
$dtsai=$linha["data_sai"];
$dtpronto=$linha["data_pronto"];
if ($dtsai=="" || $dtsai==NULL){
	if ($dtpronto=="" || $dtpronto==NULL){
		$dtsai="Não pronto";
	}else{
		$dtsai="Pronto mas não entregue!";
	}
}
print($dtsai);
?></td>
<td>
<?
$nomea = $linha["tecnico"];
$nomeb = trim($nomea); // Elimina possíveis espaços antes e depois da variavel nome completo
$nome = strtok($nomeb," ");// Corta o sobrenome separado por espaços exibindo somente a primira parte do nome
print($nome);
?></td>
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
