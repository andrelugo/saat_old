<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body>
<p align="center"><span class="Titulo1">Produtos Prontos </span></p>
<p align="center" class="Titulo2">AGUARDANDO CONTROLE DE QUALIDADE </p>
<table width="476" border="1" align="center">
    <tr>
      <td width="352"><div align="center">Modelo</div></td>
      <td width="108"><div align="center">Quantidade</div></td>
    </tr>
  <?
$count = 0;
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where data_pronto is not null
and data_sai is null
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à produtos sem analise técnica");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td><strong>TOTAL</strong></td>
  <td><strong><strong><?print("$count");?></strong></strong></td></tr>
</table>
  <p align="center" class="Titulo2">Ordenados pela data em que o t&eacute;cnico deixou PRONTO!
      </p>
  </p>
  <div align="center">
  <table width="460" border="1">
    <tr class="Cabe&ccedil;alho">
      <td width="198"><div align="center">Modelo</div></td>
      <td width="107"><div align="center">S&eacute;rie</div></td>
      <td width="133"><div align="center">Data Pronto</div></td>
      <td width="133"><div align="center">Dias Parado</div></td>
    </tr>
<?
$count = 0;
$sql="SELECT DATE_FORMAT(data_pronto, '%d/%m/%Y') AS dd, serie,modelo.descricao as descricao,DATEDIFF(now(),data_entra) as ddp
FROM cp 
inner join modelo on modelo.cod = cp.cod_modelo
where data_sai is null and data_pronto is not null
order by dd desc;";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos por Mês".mysql_error());
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[serie] </td><td> $linha[dd] </td><td> $linha[ddp] </td></tr>");
		$count++;
}
?>

    <tr>
      <td><strong class="Cabe&ccedil;alho">TOTAL</strong></td>
      <td colspan="2"><strong><?print("$count");?></strong></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
</html>
