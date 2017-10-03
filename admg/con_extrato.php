<?
require_once("sis_valida.php");
require_once("sis_conn.php");

$nome = $_COOKIE["nome"];

?><html>
<head>
<title>SAAT</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(fundo.jpg);
}
.style1 {font-weight: bold}
-->
</style></head>
<body>
<p align="center" class="style1">Extratos Cadastrados no sistema</p>
<div align="center">
  <table width="768" border="1">
    <tr>
      <td width="79"><strong>Extrato</strong></td>
        <td width="76"><strong>Fornecedor</strong></td>
      <td width="61"><strong>Qtdade OS </strong></td>
	  <td width="70"><strong>Valor Total</strong></td>
	  <td width="108"><strong>Data Recebimento </strong></td>
  <td width="87">Imprime</td>
  <td width="125">A&ccedil;&otilde;es</td>
    </tr>
    <?
$sql="SELECT extrato_mo.descricao as extrato,extrato_mo.cod as cod,extrato_mo.data_pgto as recebe,fornecedor.descricao as fornecedor,data_salva
FROM extrato_mo
left join fornecedor on fornecedor.cod = extrato_mo.cod_fornecedor";
$res=mysql_query($sql) or die(mysql_error()."<br>$sql");
$rows = mysql_num_rows($res);
if ($rows==0){
	print("Nenhum extrato no sistema neste momento");
}else{
	$tot=0;
	while($linha=mysql_fetch_array($res)){
		$salva=$linha["data_salva"];
		?>
    <tr><td><? print($linha["extrato"]);?></td>
		<td><? print($linha["fornecedor"]);?></td>
		<td><?
			$sql2="select count(cod)as qt,sum(valor_gar) as vl from cp where cod_extrato_mo=$linha[cod] ";
			$res2=mysql_query($sql2);
			$qt=mysql_result($res2,0,"qt");
			$vl=mysql_result($res2,0,"vl");
		 	print($qt);?></td>
		<td><? print($vl);?></td>
		<td><? print($linha["recebe"]);?></td>
		<td><a href="pdf_registros_extrato.php?cod=<? print($linha["cod"]);?>">Completo-OS/Registro</a><br>
		<a href="con_resumo_extrato.php?cod=<? print($linha["cod"]);?>">Resumo-Regsitros</a><br>
		<a href="pdf_os_extrato.php?cod=<? print($linha["cod"]);?>">O.S.</a></td>
		<td>
			<? if ($salva<>""){ 
					print("Salvo em: ".$salva);
			   }else{?>
				  <p><a href="frm_extrato_carga.php?cod=<? print($linha["cod"]);?>">Carrega</a><br>
				    <a href="frm_extrato_salva.php?cod=<? print($linha["cod"]);?>">Salva</a> <br>
			        <a href="frm_cad_extrato.php?cod=<? print($linha["cod"]);?>">Altera</a><br>
					Exclui <br> 
					Limpa 
				  </p>
		   <? }?>
		</td>
    </tr>
    <?
		$tot=$tot+$qt;
		
	}
}
?>
    <tr>
      <td><strong>Total</strong></td>
      <td><strong><? print($tot);?></strong></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
</html>