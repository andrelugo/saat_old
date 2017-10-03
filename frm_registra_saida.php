<? //Este formulário deve mostrar somente folhas cjos numeros de Barcode ainda não tenham sido digitados
// no sistema do cliente ou seja não tenham uma data de Registro de saída.
// em 18 09 05 alterei este script para permitir a marcação dos barcodes no sistema do cliente sem a necessidade prévia de um n umero de registro
$jvA="this.bgColor='#99ffff';" ;
$jvB="this.bgColor='#ffffff';" ;
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
}
body {
	background-image: url(img/fundo.gif);
}
.style7 {font-size: 12px}
-->
</style>
</head>
<body>
<p align="center"><span class="style1">Controles sem registro no Sistema do cliente. </span></p>
<p align="center" class="style1 style7">As qantidades abaixo s&atilde;o de produtos ainda n&atilde;o registrados no sistema do cliente <br>
  A quntidade de produtos nas folhas descritas pode ser diferente caso algum controle j&aacute; tenha sido digitado! </p>
<div align="center">
  <table width="346" border="1">
    <tr>
      <td width="71"><div align="center">Folha</div></td>
      <td width="158"><div align="center">Controler de Qualiade </div></td>
      <td width="95"><div align="center">Qtdade</div></td>
    </tr>
<?
$count=0;
$sql="select folha_cq,nome, count(folha_cq) as qt
from cp
inner join rh_user on rh_user.cod = cp.cod_cq
where data_registro_saida is null and folha_cq is not null
group by folha_cq
order by folha_cq";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta folhas disponiveis".mysql_error());
while ($linha = mysql_fetch_array($res)){
		$count=$count+$linha["qt"];
		$jv="frm_registra_saida2.php?folha=$linha[folha_cq]";
		print ("<tr onMouseOver=$jvA onMouseOut=$jvB><a href=$jv>
		<td>$linha[folha_cq]</td> <td>$linha[nome]</td> <td>$linha[qt]</a> </td></tr>");
}
?>
    <tr>
      <td><strong>TOTAL</strong></td>
      <td colspan="2"><strong><?print("$count");?> produtos aguardando registro </strong></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
</html>