<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');

$folha=$_GET["txtFolha"];
$sql=mysql_query("SELECT cod,data_fecha FROM fechamento_reg WHERE registro = $folha")or die(mysql_error());
$row=mysql_num_rows($sql);
if ($row==0){
	print("<h1>Não foi possivel gerar PDF. Nunhum resultado encontrado para o registro de saídas nº $folha");
	exit;
}
$dataf = mysql_result($sql,0,"data_fecha");
$codf = mysql_result($sql,0,"cod");
if ($row>1){
	print("<h1>Não foi possivel gerar PDF. mais de um resultado encontrado para o registro de saídas nº $folha");
	exit;
}
if (empty($dataf) || $dataf==""){
	print("<h1>Não foi possivel gerar PDF. O registro de saidas nº $folha ainda não foi ENCERRADO!");
	exit;
}
//$sql="select count(cod) as qt,os_fornecedor as os from cp where cod_fechamento_reg=$codf group by os_fornecedor order by os_fornecedor";
$sql="SELECT os_fornecedor AS os, item_os_fornecedor AS item, barcode, serie,cp.cod as cp,extrato_mo.descricao as extrato
FROM cp
LEFT JOIN orc ON orc.cod_cp = cp.cod
left join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo
where cod_fechamento_reg=$codf 
GROUP BY os, item, barcode, serie
ORDER BY barcode";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$regs=mysql_num_rows($res);
$totalpag=(ceil($regs/60));
$data=date("d/m/y - H:i");
//Tamanho das células em milimetros
$l1=30;$l2=30;$l3=30;$l4=20;$l5=30;
$a1=7;$a2=3.8;
//Fim Tamanho das céluas
$lt=$l1+$l2+$l3+$l4+$l5;
$lC=$lt/2;
//$lt2=$l6+$l7+$l8;
$count=0;
$pag=0;
$total=0;
class PDF extends FPDF{
// Inserir Header e Footer
function Header()
{
    $this->Image('img/timbre1.JPG',15,5,180,20);    //Logo
    $this->SetFont('Arial','B',10);    //Arial bold 10
	$this->Ln(18);//Line break

	$this->Cell(140*0.25,7,"",0,"","");
	$this->Cell(140,7,"Relatório de Ordens de Serviço",1,"","C");// O alinhamento é feito na própria cécula
		$this->Ln();
		$this->SetFont('Arial','b',10);
		$this->Cell(140*0.25,7,"",0,"","");
	$this->Cell(30,7,"O. S. - Item",1);
	$this->Cell(30,7,"Barcode/N.F.",1);
	$this->Cell(30,7,"Série",1);
	$this->Cell(30,7,"Orçamento",1);
	$this->Cell(20,7,"Finalizado",1);
	$this->Ln();
}
function Footer()
{
    $this->SetY(-15);    //Position at 1.5 cm from bottom
    $this->SetFont('Arial','I',8);    //Arial italic 8
    $this->Cell(0,10,'Página '.$this->PageNo().'	 de {nb}',0,0,'C');    //Page number
}

}
$pdf=new PDF();
$pdf->AliasNbPages(); // Gera um alias para o numero total de pagnas que substitui {nb} na função footer
$pdf->SetTitle("Registro de Saídas");
//$pdf->Open();
$pdf->SetFont('Arial','b',10);
	$pag++;
	$pdf->AddPage();
	$x=$pdf->GetX();
	$pdf->Cell($l1,$a1,"Registro nº: $folha",0);
	$pdf->SetX($x);
	//$pdf->Cell(80,7,"Data da Impressão: $data",1);
	$pdf->SetFont('Arial','',8);
$total=0;
$vlTot=0;
while ($linha = mysql_fetch_array($res)){
		$cp=$linha["cp"];
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
		$os=$linha["os"]."-".$linha["item"];
		$pdf->Cell($l1,$a2,$os,1);
		$pdf->Cell($l2,$a2,$linha["barcode"],1);
		$pdf->Cell($l3,$a2,$linha["serie"],1);
//// Corrigindo o valor orc

		$res2=mysql_query("select (orc.qt * orc.valor) as valor from orc where orc.cod_cp = $cp ") or die(mysql_error());
		$orc=0;
		while ($linha2 = mysql_fetch_array($res2)){
			$orc+=$linha2["valor"];
		}

		$vlT=number_format($orc, 2, ',', '.');
		if ($orc==0){
			$pdf->Cell($l3,$a2,"",1);
		}else{
			$pdf->Cell($l3,$a2,"R$ ".$vlT,1);
		}
		
		
		
//// Fim valor orc
		$pdf->Cell($l4,$a2,$linha["extrato"],1);
     $pdf->Ln();
	 $total++;
	 $vlTot=$vlTot+$orc;
}
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lt/3,$a1,"TOTAL",1,"","C");
	$pdf->Cell($lt/3,$a1,$total." Produtos",1,"","C");
	$vlT=number_format($vlTot, 2, ',', '.');
	$pdf->Cell($lt/3,$a1,"R$ $vlT",1,"","C");
/////////////////////////2 folha//Eliminei em 04/11/06 em virtude de já estar em um relatório de resumos///////
//$sql="select count(cp.cod) as qt,modelo.descricao as modelo from cp inner join modelo on modelo.cod=cp.cod_modelo where cod_fechamento_reg=$codf group by modelo order by modelo";
//$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
//$total=0;
//	//$pdf->AddPage(); //Tirei a página adicional em 04/Nov/2006 - Não sei qual será a reação dos usuários
//	$pdf->Ln(15);
//	$pdf->SetFont('Arial','b',10);
//	$pdf->Cell($lt*0.25,$a1,"",0,"","");
//	$pdf->Cell($lt*0.5,$a1,"Resumo de Modelos",1,"","C");// O alinhamento é feito na p´ropria cécula
//		$pdf->Ln();
//		$pdf->SetFont('Arial','b',10);
//		$pdf->Cell($lt*0.25,$a1,"",0,"","");
//	$pdf->Cell($lC/2,$a1,"Modelo",1);
//	$pdf->Cell($lC/2,$a1,"Qtdade",1);
//		$pdf->Ln();
//	$pdf->SetFont('Arial','',8);
//while ($linha = mysql_fetch_array($res)){
//	$pdf->Cell($lt*0.25,$a1,"",0,"","");
//		$pdf->Cell($lC/2,$a2,$linha["modelo"],1);
//		$pdf->Cell($lC/2,$a2,$linha["qt"],1,"","R");	
//     $pdf->Ln();
//	 $total=$total+$linha["qt"];
//}
//	$pdf->SetFont('Arial','b',10);
//	$pdf->Cell($lt*0.25,$a1,"",0,"","");
//	$pdf->Cell($lC/2,$a1,"TOTAL",1,"","C");
//	$pdf->Cell($lC/2,$a1,$total,1,"","C");
/////////////////////////////////////////////////////////////////////////////////////////////
$pdf->Output();
?>