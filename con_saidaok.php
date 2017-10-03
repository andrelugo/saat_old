<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="select folha_cq,destino.descricao as destino,rh_user.nome as nome, count(cp.cod) as qt
from cp 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_cq
where folha_cq is not null and cod_fechamento_reg is null 
group by nome,destino,folha_cq
order by folha_cq,cod_destino";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
$tot=0;
$total=mysql_num_rows($res);
?>
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<body>
  <p align="center"><span class="style1">Controles de Produ&ccedil;&atilde;o Salvos AGUARDANDO FECHAMENTO!</span></p>
  <p align="center">Existem <? print($total);?> fechamentos à serem realizados!</p>
  <table width="615" border="1" align="center">
<tr>
	<td width="54"><div align="center"><strong>Folha</strong></div></td>
	<td width="399"><div align="center"><strong>Controler</strong></div></td>
	<td width="55"><div align="center"><strong>Destino</strong></div></td>
    <td width="79"><div align="center"><strong>Quantidade</strong></div></td>
</tr>
<?
while ($linha = mysql_fetch_array($res)){
//		print ("<tr> <td>$linha[nome]</td><td>$linha[destino]</td> <td>$linha[qt]</td> </tr>");
		$tot=$tot+$linha["qt"];
		$nome=$linha["nome"];
		$destino=$linha["destino"];
		$qt=$linha["qt"];
		$folha=$linha["folha_cq"];
?>
<tr>
<td><?print($folha);?></td>
<td><?print($nome);?></td>
<td><?print($destino);?></td>
<td><?print($qt);?></td>
</tr>
<?
}
?>
<tr class="style1">
<td>Total</td>
<td colspan="2"><?print ($tot);?></td>
</table>
  
  </div>
</body>
</html>
