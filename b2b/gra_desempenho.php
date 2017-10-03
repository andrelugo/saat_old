<?
require_once("sis_valida.php");
require_once("sis_conn.php");
include('../includes/phplot/phplot.php');  // here we include the PHPlot code 
$mes=$_GET["m"];
$ano=$_GET["a"];
$f=$_GET["f"];
if ($f==0){
	$for="";
	$descFor="TODOS";
}else{
	$for="AND modelo.cod_fornecedor=$f";
	$res=mysql_query("select descricao from fornecedor where cod=$f");
	$descFor=mysql_result($res,0,"descricao");
}

$graph =& new PHPlot(700,500);   // tamanho da imagem  está definido entre parenteses
$sql="SELECT DAY (data_entra) AS dia,count(cp.cod) as qtent , 
(SELECT count(cp.cod)FROM cp inner join modelo on modelo.cod = cp.cod_modelo WHERE day( data_sai )=dia and month(data_sai)=$mes and year(data_sai)=$ano 
$for
) as qtsai
FROM cp inner join modelo on modelo.cod = cp.cod_modelo
WHERE month( data_entra ) =$mes and year(data_entra)=$ano
$for
GROUP BY dia";
$res=mysql_query($sql) or die($sql.mysql_error());
$rows=mysql_num_rows($res);
if ($rows==0) {die ("<h2>Nenhum resultado encontrado para a consulta do mês $mes de $ano");}
while ($linha = mysql_fetch_array($res)){
	$grafico[] = (array($linha["dia"],$linha["qtent"],$linha["qtsai"]));
}
$graph->SetDataValues($grafico);
$graph->SetDataType("text-data");  //Must be called before SetDataValues
$graph->SetTitle("Produtividade do Mês $mes de $ano fornecedor $descFor");
$graph->SetXTitle('Dia do Mês');
$graph->SetYTitle('Quantidade de Produtos por dia');
$graph->SetYTickIncrement(10);
$graph->SetPlotType("bars");
//$graph->SetNewPlotAreaPixels(70,120,375,220);
//$graph->SetPlotAreaWorld(0,0,7,80);
$graph->DrawGraph(); // remember, since in this example we have one graph, PHPlot// does the PrintImage part for you
?>