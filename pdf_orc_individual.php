<?
// a inclusão de entrada sem custo ou com custo para estoque ou para saldo no sistema Casa Bahia, deve ser realizada após a liberação do 
// produto pelo controle de qualidade pois o técnico pode se equivocar ao definir o destino de um produto
require_once("sis_valida.php");
require_once("sis_conn.php");
require_once('includes/tcpdf4/config/lang/eng.php');
require('includes/tcpdf4/tcpdf.php');
require('includes/tcpdf4/barcode/barcode.php');
require('includes/tcpdf4/barcode/c128bobject.php');

$barcode=$_GET["txtBarcode"];
$sql=mysql_query("SELECT cp.cod as cp, data_barcode, rh_user.nome as nome, modelo.descricao as modelo, modelo.marca as marca,
serie, filial
FROM cp
inner join rh_user on rh_user.cod = cp.cod_tec
inner join modelo on modelo.cod = cp.cod_modelo
WHERE cp.barcode = $barcode")or die(mysql_error());
$row=mysql_num_rows($sql);
if ($row==0){
	print("<h1>Não foi possivel gerar PDF. Nunhum resultado encontrado para o barcode $barcode");
	exit;
}
if ($row>1){
	print("<h1>Não foi possivel gerar PDF. mais de um resultado encontrado para o registro de saídas nº $folha");
	exit;
}
$cp = mysql_result($sql,0,"cp");
$dtBarcode = mysql_result($sql,0,"data_barcode");
$tecnico = mysql_result($sql,0,"nome");
$modelo = mysql_result($sql,0,"modelo");
$marca = mysql_result($sql,0,"marca");
$serie = mysql_result($sql,0,"serie");
$filial = mysql_result($sql,0,"filial");

$sql="select peca.descricao as peca, venda, orc.qt as qt, orc_motivo.descricao as motivo
from peca inner
join orc on orc.cod_peca = peca.cod inner
join orc_motivo on orc_motivo.cod = orc.cod_motivo
where orc.cod_cp= $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
//Tamanho das células em milimetros
$l1=5;$l2=35;$l3=25;$l4=22;$l5=14;$l6=45;$l7=17;$l8=30;
$a1=7;$a2=3.8;
//Fim Tamanho das céluas
$lt=$l1+$l2+$l3+$l4+$l5+$l6+$l7+$l8;
$lC=$lt/2;
$lt2=$l6+$l7+$l8;
$count=0;
$pag=0;
class PDF extends TCPDF{}
$pdf=new PDF();
//$pdf->Open();
$pdf->setLanguageArray($l); //set language items
$pdf->SetFont('vera','b',10);

	$pag++;
	$pdf->AddPage();
	$pdf->Cell($lt,$a1,"Orçamento Individual",1,"","C");// O alinhamento é feito na p´ropria cécula

//	$pdf->SetBarcode(date("Y-m-d H:i:s", time()));


	$pdf->Ln();
$pdf->SetFont('vera','',7);
	$pdf->Cell($lt,$a1,"Barcode: $barcode",1,"","L");// O alinhamento é feito na p´ropria cécula
	$pdf->Ln();
	$pdf->Cell($lt,$a1,"Observações: ",1,"","L");// O alinhamento é feito na p´ropria cécula
	$pdf->Ln();
$pdf->SetFont('vera','b',10);
	$pdf->Cell($lC,$a1,"Registro nº: ",1);
	$pdf->Cell($lC*0.75,$a1,"Data da Encerra: ",1);
	$pdf->Cell($lC*0.25,$a1,"",1);
	$pdf->Ln();

	$pdf->Cell($lt*0.28,$a1,"Destino: ",1);
	$pdf->Cell($lt*0.16,$a1,"Qdade OS: ",1);
	$pdf->Cell($lt*0.18,$a1,"R$ Total: ",1);
	$pdf->Cell($lt*0.38,$a1,"Digit.: ",1);

		$pdf->Ln();
		$pdf->SetFont('vera','',10);
	$pdf->Cell($l1,$a1,"Qt",1);
		$pdf->SetFont('vera','b',10);
    $pdf->Cell($l2,$a1,"Item",1);
    $pdf->Cell($l3,$a1,"Motivo",1);
    $pdf->Cell($l4,$a1,"Qt",1);
    $pdf->Cell($l5,$a1,"Vl. Unit",1);
	$pdf->Cell($l6,$a1,"Vl Tot.",1);
		$pdf->Ln();
	$pdf->SetFont('vera','',8);
while ($linha = mysql_fetch_array($res)){
		$count++;
		$pdf->Cell($l1,$a2,$count,1);
        $pdf->Cell($l2,$a2,$linha["peca"],1);
		$pdf->Cell($l3,$a2,$linha["motivo"],1);
		$pdf->Cell($l4,$a2,$linha["qt"],1);
		$pdf->Cell($l5,$a2,$linha["venda"],1);
		$tot=$linha["qt"]*$linha["venda"];
		$pdf->Cell($l6,$a2,$tot,1);
     $pdf->Ln();
}
//$pdf->EAN13(80,40,'123456789012');
$pdf->Output();
?>