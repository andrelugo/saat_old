<?
// a inclusão de entrada sem custo ou com custo para estoque ou para saldo no sistema Casa Bahia, deve ser realizada após a liberação do 
// produto pelo controle de qualidade pois o técnico pode se equivocar ao definir o destino de um produto
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
//////////////////////////////////////////1 folha//////////////////////////////////////////////////////
$sql="select count(cp.cod) as qt,rh_user.nome as nome from cp inner join rh_user on rh_user.cod=cp.cod_tec where cod_fechamento_reg=$codf group by nome order by nome";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$regs=mysql_num_rows($res);
$totalpag=(ceil($regs/60));
$data=date("d/m/y - H:i");
//Tamanho das células em milimetros
$l1=5;$l2=35;$l3=25;$l4=18;$l5=14;$l6=45;$l7=17;$l8=30;
$a1=7;$a2=3.8;
//Fim Tamanho das céluas
$lt=$l1+$l2+$l3+$l4+$l5+$l6+$l7+$l8;
$lC=$lt/2;
$lt2=$l6+$l7+$l8;
$count=0;
$pag=0;
$total=0;
class PDF extends FPDF{
function Header()
{
    $this->Image('img/timbre1.JPG',15,5,180,20);    //Logo
    $this->SetFont('Arial','B',10);    //Arial bold 10
	$this->Ln(18);//Line break
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
//$pdf->Open();
$pdf->SetFont('Arial','b',10);
	$pag++;
	$pdf->AddPage();
	$pdf->Cell($lC,$a1,"Registro nº: $folha",1);
	$pdf->Cell($lC,$a1,"Data da Impressão: $data",1);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lt*0.5,$a1,"Relatório de Técnicos",1,"","C");// O alinhamento é feito na p´ropria cécula
		$pdf->Ln();
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"Técnico",1);
	$pdf->Cell($lC/2,$a1,"Quantidade de Produtos",1);
		$pdf->Ln();
	$pdf->SetFont('Arial','',8);
while ($linha = mysql_fetch_array($res)){
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
		$pdf->Cell($lC/2,$a2,$linha["nome"],1);
		$pdf->Cell($lC/2,$a2,$linha["qt"],1,"","R");	
     $pdf->Ln();
 	 $total=$total+$linha["qt"];
}
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"TOTAL",1,"","C");
	$pdf->Cell($lC/2,$a1,$total,1,"","C");
//////////////////////////////////////////2 folha//////////////////////////////////////////////////////
$sql="select count(cp.cod) as qt,rh_user.nome as nome from cp inner join rh_user on rh_user.cod=cp.cod_cq where cod_fechamento_reg=$codf group by nome order by nome";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$total=0;
	$pdf->AddPage();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lt*0.5,$a1,"Relatório de Controladores de Qualidade",1,"","C");// O alinhamento é feito na p´ropria cécula
		$pdf->Ln();
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"Controlador",1);
	$pdf->Cell($lC/2,$a1,"Quantidade de Produtos",1);
		$pdf->Ln();
	$pdf->SetFont('Arial','',8);
while ($linha = mysql_fetch_array($res)){
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
		$pdf->Cell($lC/2,$a2,$linha["nome"],1);
		$pdf->Cell($lC/2,$a2,$linha["qt"],1,"","R");	
     $pdf->Ln();
	 $total=$total+$linha["qt"];
}
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"TOTAL",1,"","C");
	$pdf->Cell($lC/2,$a1,$total,1,"","C");
/////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////3 folha//////////////////////////////////////////////////////
$sql="select count(cp.cod) as qt,modelo.descricao as modelo from cp inner join modelo on modelo.cod=cp.cod_modelo where cod_fechamento_reg=$codf group by modelo order by modelo";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$total=0;
	$pdf->AddPage();
	$pdf->Ln(15);
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lt*0.5,$a1,"Resumo de Modelos",1,"","C");// O alinhamento é feito na p´ropria cécula
		$pdf->Ln();
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"Modelo",1);
	$pdf->Cell($lC/2,$a1,"Qtdade",1);
		$pdf->Ln();
	$pdf->SetFont('Arial','',8);
while ($linha = mysql_fetch_array($res)){
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
		$pdf->Cell($lC/2,$a2,$linha["modelo"],1);
		$pdf->Cell($lC/2,$a2,$linha["qt"],1,"","R");	
     $pdf->Ln();
	 $total=$total+$linha["qt"];
}
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lt*0.25,$a1,"",0,"","");
	$pdf->Cell($lC/2,$a1,"TOTAL",1,"","C");
	$pdf->Cell($lC/2,$a1,$total,1,"","C");
/////////////////////////////////////////////////////////////////////////////////////////////
$pdf->Output();
?>