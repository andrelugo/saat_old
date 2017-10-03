<?
$mes=$_GET["m"];
$ano=$_GET["a"];

require_once("sis_valida.php");
require_once("sis_conn.php");
include('../includes/phplot/phplot.php');  // here we include the PHPlot code 

$graph =& new PHPlot(700,500);   // tamanho da imagem  está definido entre parenteses
$sql="SELECT DAY (data_entra) AS dia,count(cod) as qtent , 
(SELECT count(cod)FROM cp WHERE day( data_sai )=dia and month(data_sai)=$mes and year(data_sai)=$ano ) as qtsai
FROM cp 
WHERE month( data_entra ) =$mes and year(data_entra)=$ano
GROUP BY dia";
$res=mysql_query($sql) or die($sql);
$rows=mysql_num_rows($res);
if ($rows==0) {die ("<h2>Nenhum resultado encontrado para a consulta do mês $mes de $ano");}
while ($linha = mysql_fetch_array($res)){
	$grafico[] = (array($linha["dia"],$linha["qtent"],$linha["qtsai"]));
}
$graph->SetDataValues($grafico);
$graph->SetDataType("text-data");  //Must be called before SetDataValues
$graph->SetTitle("Produtividade do Mês $mes de $ano");
$graph->SetXTitle('Dia do Mês');
$graph->SetYTitle('Quantidade de Produtos por dia');
$graph->SetYTickIncrement(10);
$graph->SetPlotType("bars");
//$graph->SetNewPlotAreaPixels(70,120,375,220);
//$graph->SetPlotAreaWorld(0,0,7,80);
$graph->DrawGraph(); // remember, since in this example we have one graph, PHPlot// does the PrintImage part for you
?>