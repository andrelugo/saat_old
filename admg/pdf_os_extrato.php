<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require('../includes/fpdf.php');
$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$tot = mysql_num_rows ($sqlCliente);
if ($tot>0){
	$cliente=mysql_result($sqlCliente,0,"cliente");
}else{
	$cliente="DIVERSOS";
}
$extrato=$_GET["cod"];
$res=mysql_query("select descricao from extrato_mo where cod = $extrato") or die (mysql_error());
$extratoDesc=mysql_result($res,0,"descricao");

$sql="SELECT os_fornecedor AS os, item_os_fornecedor AS item, barcode, serie,modelo.marca as marca, modelo.descricao as modelo
FROM cp
inner join modelo on modelo.cod = cp.cod_modelo
where cod_extrato_mo=$extrato";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error()."<br> $sql");
//Tamanho das células em milimetros
$l0=10;$l1=20;$l2=20;$l3=30;$l4=35;$l5=30;
$lt=$l0+$l1+$l2+$l3+$l4+$l5;
$lC=$lt/2;
$a1=7;$a2=3.8;
$margem=20;
//Fim Tamanho das céluas
class PDF extends FPDF{
	function Header(){
		$this->Image('../img/timbre1.JPG',15,5,180,20);    //Logo
		$this->SetFont('Arial','B',10);    //Arial bold 10
		$this->Ln(18);//Line break
		$this->Cell(20,7,"",0,"","");//MARGEM
		$this->Cell(145,7,"Relatório de Ordens de Serviço",1,"","C");// O alinhamento é feito na própria célula
			$this->Ln();
			$this->SetFont('Arial','b',10);
			$this->Cell(20,7,"",0,"","");//MARGEM
		$this->Cell(10,7,"Itm",1);
		$this->Cell(20,7,"O. S. - Item",1);
		$this->Cell(20,7,"Marca",1);
		$this->Cell(30,7,"Modelo",1);
		$this->Cell(35,7,"Série",1);
		$this->Cell(30,7,"Barcode/N.F.",1);
		$this->Ln();
	}
	function Footer(){
		$this->SetY(-15);    //Position at 1.5 cm from bottom
		$this->SetFont('Arial','I',8);    //Arial italic 8
		$this->Cell(0,10,'Página '.$this->PageNo().'	 de {nb}',0,0,'C');    //Page number
	}
}
$pdf=new PDF();
$pdf->AliasNbPages(); // Gera um alias para o numero total de pagnas que substitui {nb} na função footer
//$pdf->SetFont('Arial','b',10);
	$pdf->AddPage();
//	$pdf->Cell($l1,$a1,"Registro nº: $extrato",0);
	$pdf->SetFont('Arial','',8);
	$total=0;
while ($linha = mysql_fetch_array($res)){
	$total++;
	$pdf->Cell($margem,$a1,"",0,"","");
	if($total==1){
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell($lt,7,"$cliente    -   EXTRATO: $extratoDesc",1,"","C");
		$pdf->Ln();
		$pdf->Cell($margem,$a1,"",0,"","");
		$pdf->SetFont('Arial','',8);
	}
	$osItem=$linha["item"];
	if($osItem==0){
		$os=$linha["os"];
	}else{
		$os=$linha["os"]."-".$osItem;
	}
		$pdf->Cell($l0,$a2,$total,1);
		$pdf->Cell($l1,$a2,$os,1);
		$pdf->Cell($l2,$a2,$linha["marca"],1);
		$pdf->Cell($l3,$a2,$linha["modelo"],1);
		$pdf->Cell($l4,$a2,$linha["serie"],1);
		$pdf->Cell($l5,$a2,$linha["barcode"],1);
     $pdf->Ln();

}
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($margem,$a1,"",0,"","");
	$pdf->Cell($lt/2,$a1,"TOTAL",1,"","C");
	$pdf->Cell($lt/2,$a1,$total." Produtos",1,"","C");
//ASSINATURA CLIENTE
    $pdf->Ln(25);
	$pdf->Cell($margem,$a1,"",0,"","");
	$pdf->Cell($lt,$a1,$cliente,0,"","");
//ASSINATURA PENHA TV COLOR
    $pdf->Ln(25);
	$pdf->Cell($margem,$a1,"",0,"","");
	$pdf->Cell($lt,$a1,"PENHA TV COLOR COM. LTDA",0,"","");


$pdf->Output();
?>