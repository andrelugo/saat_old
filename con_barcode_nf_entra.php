<? 
require_once("sis_valida.php");
require_once("sis_conn.php");
require_once("includes/code128.php");
$cod128 = new code128();

if (isset($_GET["txtNota"])){$nota=$_GET["txtNota"];}else{die("<h1>ERRO: Nota Fiscal não informada!");}

$nf=mysql_query("select gerar_barcode from nf_entrada where descricao = $nota");
$tot=mysql_num_rows($nf);
if ($tot==0){die("<h1>ERRO: Nenhum resultado encontrado!");}
if ($tot>1){die("<h1>ERRO: INCONSISTÊNCIA NOS DADOS DO SISTEMA MAIS DE UM REGISTRO ENCONTRADO PARA A NOTA $nota pág con_barcode_nf_entrada.php!");}
$gb=mysql_result($nf,0,"gerar_barcode");
if ($gb==0) {die("ERRO: Não é possivel gerar etiquetas para esta entrada pois o cadastro da nota fiscal diz que seus produtos já possuem Barcode!");}

$itens=mysql_query("select modelo.descricao as modelo, cp.barcode as barcode,day(data_emissao) as dia,month(data_emissao) as mes,year(data_emissao) as ano
	  from cp inner join modelo on modelo.cod = cp.cod_modelo inner join nf_entrada on nf_entrada.cod = cp.cod_nf_entrada
	  where nf_entrada.descricao = $nota");	  
$tot = mysql_num_rows ($itens);
?><html>
<head>
<title>Etiquetas de Código de Barras</title>
</head>
<body>Etiquetas de Cód. de Barras para a nota <? print($nota);?>
<table width="800" height="30" border="1" align="center">
<?
$conta=0;
while ($linha = mysql_fetch_array($itens)){
	$barcode=$linha["barcode"];
	$modelo=$linha["modelo"];
	$dia=$linha["dia"];
	$mes=$linha["mes"];
	$ano=$linha["ano"];
	$data=$dia."/".$mes."/".$ano;
 	if ($conta==0){
		print("<tr><td width='200'><center> <font size='-3'>".$modelo." -- NF.:".$nota."</font><br>");
		print("<center> <font size='-3'>".$barcode." -- Data.:".$data."</font>");
		print $cod128->produceHTML("$barcode",0,20);print("</td>");
 	}
	if ($conta==1 || $conta==2){
		print("<td width='200'><center> <font size='-3'>".$modelo." -- NF.:".$nota."</font><br>");
		print("<center> <font size='-3'>".$barcode." -- Data.:".$data."</font>");
		print $cod128->produceHTML("$barcode",0,20);print("</td>");
 	}
 	if ($conta==3){
		print("<td width='200'><center> <font size='-3'>".$modelo." -- NF.:".$nota."</font><br>");
		print("<center> <font size='-3'>".$barcode." -- Data.:".$data."</font>");
		print $cod128->produceHTML("$barcode",0,20);print("</td></tr>");
 	}
	if ($conta==3){$conta=0;}else{$conta++;}
}
?>
</table>
</body>
</html>