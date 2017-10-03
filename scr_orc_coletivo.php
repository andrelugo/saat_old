<?
require_once("sis_valida.php");
require_once("sis_conn.php");

$codCliente=$_GET["cmbCliente"];
if ($codCliente==0 || empty($codCliente)){die("<h1>Erro: Cliente não selecionado!");}

$res=mysql_query("SELECT cp.cod AS tot
FROM orc INNER 
JOIN cp ON cp.cod = orc.cod_cp INNER 
JOIN modelo ON modelo.cod = cp.cod_modelo INNER 
JOIN linha ON linha.cod = modelo.linha inner
join cliente on cliente.cod = cp.cod_cliente
WHERE cod_orc_coletivo IS NULL 
AND linha.orc_coletivo =1 
and cliente.revenda=1
and cp.cod_cliente = $codCliente
group by tot
");
$tot=mysql_num_rows($res);
if ($tot==0){die("<h1>ERRO: Não há orçamentos a realizar para este cliente");}

$res=mysql_query("select max(cod_orc_coletivo) as orc from orc");
$orc=mysql_result($res,0,"orc");
$orc++;

mysql_query(" update orc inner
join cp on cp.cod = orc.cod_cp inner
JOIN modelo ON modelo.cod = cp.cod_modelo INNER 
JOIN linha ON linha.cod = modelo.linha inner
join cliente on cliente.cod = cp.cod_cliente
set cod_orc_coletivo=$orc
WHERE cod_orc_coletivo IS NULL 
AND linha.orc_coletivo =1 
and cliente.revenda=1
and cp.cod_cliente = $codCliente");
print("<h2> $tot Produtos inclusos no orçamento $orc <a href='pdf_orc_coletivo.php?txtOrc=$orc' target='_blank'> Clique aqui para imprimir o Orçamento</a>");
?>