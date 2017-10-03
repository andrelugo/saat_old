<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');
$fechamento=$_GET["fechamento"];
if($fechamento==""){
	die("Numero da cobranчa nуo informado!");
}
$sql=mysql_query("SELECT fechamento FROM orc WHERE orc.fechamento = $fechamento")or die(mysql_error());
$row1=mysql_num_rows($sql);
if ($row1==0){
	print("<h1>Nуo foi possivel gerar PDF. Nunhum resultado encontrado para Cobranчa nК $fechamento!");
	exit;
}
$data=date("d/m/y H:i");
$sqlN=mysql_query("select nome from rh_user where cod = $id");
$nome=mysql_result($sqlN,0,"nome");
//Tamanho das cщlulas em milimetros
$l1=20;$l2=30;
$a1=5;$a2=3.8;
$lt=$l1+$l2;
$lc=(210-$lt)/2;
//Fim Tamanho das cщluas
class PDF extends FPDF{
	function Header(){
	    $this->Image('img/timbre1.JPG',15,5,180,20);    //Logo
	    $this->SetFont('Arial','B',10);    //Arial bold 10
		$this->Ln(18);//Line break
		$this->Cell(80,5,"",0);
		$this->Cell(20,5,"Filial",1,"","C");
	    $this->Cell(30,5,"Valor",1,"","C");
	    $this->Ln();    //Line break 
	}
	function Footer(){
	    $this->SetY(-15);    //Position at 1.5 cm from bottom
	    $this->SetFont('Arial','I',8);    //Arial italic 8
	    $this->Cell(0,10,'Pсgina '.$this->PageNo().'	 de {nb}',0,0,'C');    //Page number
	}
}
$pdf=new PDF();
$pdf->AliasNbPages(); // Gera um alias para o numero total de pagnas que substitui {nb} na funчуo footer
$sql="SELECT cod_orc_pre_nota, nota
FROM orc inner join orc_pre_nota on orc_pre_nota.cod = orc.cod_orc_pre_nota
WHERE fechamento = $fechamento
GROUP BY cod_orc_pre_nota
ORDER BY cod_orc_pre_nota";
$res1=mysql_query($sql);
$rows=mysql_num_rows($res1);
$vlTotG=0;
if($rows==0){die("Nenhum resultado encontrado  para a cobranчa $fechamento !");}
while ($linha=mysql_fetch_array($res1)){
	$pre=$linha["cod_orc_pre_nota"];
	$nota=$linha["nota"];
	$sql="SELECT filial, sum( orc.valor * orc.qt ) AS tot
	FROM cp	INNER 
	JOIN orc ON orc.cod_cp = cp.cod inner
	join filial_cbd on filial_cbd.descricao = cp.filial
	WHERE cod_orc_pre_nota = $pre
	GROUP BY filial
	ORDER BY filial desc";
	$res2=mysql_query($sql);
	$vlTot=0;
	$pdf->AddPage(); // Inicia o dicumento com a primeira pсgina esta funчуo tambem vai chamaras funcѕs footer e Header automaticamente ao final de cada pсgina
    $pdf->SetFont('Arial','B',10);    //Arial bold 10
$y=$pdf->GetY();
	$pdf->Cell($lc,$a1,"Rateio de Lojas para a cobranчa nК $fechamento",0,"","C");// O alinhamento щ feito na pДropria cщlula
	$pdf->Ln();
	$pdf->Cell($lc,$a1,"Nota Fiscal nК $nota da Prщ-Nota nК $pre",0,"","C");// O alinhamento щ feito na pДropria cщlula
	$pdf->Ln();
	$pdf->SetFont('Arial','',7); // Fonte que serс utilizada pelo cѓdigo abaixo
	$pdf->Ln(1);
$pdf->SetY($y);
	$vlTot=0;
	while ($linha2=mysql_fetch_array($res2)){
		$pdf->Cell($lc,$a1,"",0);
		$pdf->Cell($l1,$a2,$linha2["filial"],1,"","C");
		$total="R$ ".number_format($linha2["tot"], 2, ',', '.');
		$pdf->Cell($l2,$a2,$total,1,"","C");
		$pdf->Ln();
		$vlTot=$vlTot+$linha2["tot"];
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell($lc,$a1,"",0);
	$pdf->Cell($l1,$a2,"TOTAL",1,"","C");
	$totalG="R$ ".number_format($vlTot, 2, ',', '.');
	$pdf->Cell($l2,$a2,$totalG,1,"","C");
	$pdf->Ln();
	$pdf->SetFont('Arial','',7); 
	$vlTotG=$vlTotG+$vlTot;
}
$pdf->Ln(2);
$vlTotG2="R$ ".number_format($vlTotG, 2, ',', '.');
$pdf->Cell($lc,$a1,"",0);
$pdf->Cell($l2,$a2,"Impresso em $data  -  Responsсvel $nome total $vlTotG2",0,"","C");	
$pdf->Output();
?>