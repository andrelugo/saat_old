<?
// a inclus�o de entrada sem custo ou com custo para estoque ou para saldo no sistema Casa Bahia, deve ser realizada ap�s a libera��o do 
// produto pelo controle de qualidade pois o t�cnico pode se equivocar ao definir o destino de um produto
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');

$folha=$_GET["txtFolha"];
$sql=mysql_query("SELECT rh_user.nome as nome FROM rh_user INNER JOIN cp ON cp.cod_cq = rh_user.cod WHERE cp.folha_cq = $folha LIMIT 0 , 1 ")or die("Erro no Camando SQL p�g sis_aut.php");
$row=mysql_num_rows($sql);
if ($row==0){
	print("<h1>N�o foi possivel gerar PDF. Nunhum resultado encontrado para folha n� $folha");
	exit;
}
$controler = mysql_result($sql,0,"nome");
$sql="select modelo.descricao as modelo,serie,barcode,destino.descricao as destino,rh_user.nome as tec, os_fornecedor as os,
item_os_fornecedor as itm, total_orc as orc
from cp 
inner join modelo on modelo.cod = cp.cod_modelo 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_tec
where folha_cq=$folha";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
$regs=mysql_num_rows($res);
$totalpag=(ceil($regs/60));
$saldo="";//buscar esta info no BD
$estoque="";//buscar esta info no BD
$data=date("d/m/y - H:i");
//Tamanho das c�lulas em milimetros
$l1=5;$l2=35;$l3=25;$l4=18;$l5=14;$l6=45;$l7=17;$l8=30;
$a1=7;$a2=3.8;
//Fim Tamanho das c�luas
$lt=$l1+$l2+$l3+$l4+$l5+$l6+$l7+$l8;
$lC=$lt/2;
$lt2=$l6+$l7+$l8;
$count=0;
$pag=0;
class PDF extends FPDF{}
$pdf=new PDF();
//$pdf->Open();
$pdf->SetFont('Arial','b',10);
	$pag++;
	$pdf->AddPage();
	$pdf->Cell($lt,$a1,"CONTROLE DE PRODUTOS LIBERADOS",1,"","C");// O alinhamento � feito na p�ropria c�lula
	$pdf->Ln();
	$pdf->Cell($lC,$a1,"Nome do Controler: $controler",1);
	$pdf->Cell($lC*0.75,$a1,"Data da Impress�o: $data",1);
	$pdf->Cell($lC*0.25,$a1,"Folha n�: $folha",1);
	$pdf->Ln();
	$pdf->Cell($lC,$a1,"Registro de Sa�da Saldo: $saldo",1);
	$pdf->Cell($lC,$a1,"Registro de Sa�da  Estoque: $estoque",1);
		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
	$pdf->Cell($l1,$a1,"Qt",1);
		$pdf->SetFont('Arial','b',10);
    $pdf->Cell($l2,$a1,"Modelo",1);
    $pdf->Cell($l3,$a1,"S�rie",1);
    $pdf->Cell($l4,$a1,"Etiq.",1);
    $pdf->Cell($l5,$a1,"S/E",1);
	$pdf->Cell($l6,$a1,"T�cnico",1);
	$pdf->Cell($l7,$a1,"O.S.",1);
	$pdf->Cell($l8,$a1,"Observa��es",1);
		$pdf->Ln();
	$pdf->SetFont('Arial','',8);
while ($linha = mysql_fetch_array($res)){
		$count++;
		$pdf->Cell($l1,$a2,$count,1);
        $pdf->Cell($l2,$a2,$linha["modelo"],1);
		$pdf->Cell($l3,$a2,$linha["serie"],1);
		$pdf->Cell($l4,$a2,$linha["barcode"],1);
		$pdf->Cell($l5,$a2,$linha["destino"],1);
		$pdf->Cell($l6,$a2,$linha["tec"],1);
		$pdf->Cell($l7,$a2,$linha["os"]."-".$linha["itm"],1);
		$pdf->Cell($l8,$a2,$linha["orc"],1);	
     $pdf->Ln();
	 if($count==60 || $count==120){
	 	$pdf->Cell($lt,$a1,"P�gina $pag de $totalpag",0,"","C");
		$pag++;
	 	$pdf->AddPage();
 	 }
}
$pdf->Cell($lt,$a1,"P�gina $pag de $totalpag",0,"","C");
$pdf->Output();
?>