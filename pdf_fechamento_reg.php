<?
// a inclusão de entrada sem custo ou com custo para estoque ou para saldo no sistema Casa Bahia, deve ser realizada após a liberação do 
// produto pelo controle de qualidade pois o técnico pode se equivocar ao definir o destino de um produto
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');

$folha=$_GET["txtFolha"];
$sql=mysql_query("SELECT fechamento_reg.cod,data_fecha,obs,fechamento_reg.descricao,destino.descricao as destino,
rh_user.nome as nome,qt_os,valor 
FROM fechamento_reg 
left join destino on destino.cod = fechamento_reg.tipo
left join rh_user on rh_user.cod = fechamento_reg.cod_colab_fecha
WHERE registro = '$folha'")or die(mysql_error());
$row=mysql_num_rows($sql);
if ($row==0){
	print("<h1>Não foi possivel gerar PDF. Nunhum resultado encontrado para o registro de saídas nº $folha");
	exit;
}

$dataf = mysql_result($sql,0,"data_fecha");
$codf = mysql_result($sql,0,"cod");
$descricao = mysql_result($sql,0,"descricao");
$obs = mysql_result($sql,0,"obs");
$qt = mysql_result($sql,0,"qt_os");
$vl = mysql_result($sql,0,"valor");
$nome = mysql_result($sql,0,"nome");
$destino  = mysql_result($sql,0,"destino");

if ($row>1){
	print("<h1>Não foi possivel gerar PDF. mais de um resultado encontrado para o registro de saídas nº $folha");
	exit;
}
if (empty($dataf) || $dataf==""){
	print("<h1>Não foi possivel gerar PDF. O registro de saidas nº $folha ainda não foi ENCERRADO!");
	exit;
}
$sql="select modelo.descricao as modelo,serie,barcode,destino.descricao as destino,rh_user.nome as tec, os_fornecedor as os,
item_os_fornecedor as itm, total_orc as orc
from cp 
inner join modelo on modelo.cod = cp.cod_modelo 
inner join destino on destino.cod = cp.cod_destino 
inner join rh_user on rh_user.cod = cp.cod_tec
where cod_fechamento_reg=$codf";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na SQL de consulta aos registros pendentes de salvar".mysql_error());
$regs=mysql_num_rows($res);
$totalpag=(ceil($regs/50));
$saldo="";//buscar esta info no BD
$estoque="";//buscar esta info no BD
$data=date("d/m/y - H:i");
//Tamanho das células em milimetros
$l1=5;$l2=35;$l3=25;$l4=22;$l5=14;$l6=45;$l7=17;$l8=30;
$a1=7;$a2=3.8;
//Fim Tamanho das céluas
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
	$pdf->Cell($lt,$a1,"Registro de saída de Produtos (FECHAMENTO)",1,"","C");// O alinhamento é feito na p´ropria cécula
	$pdf->Ln();
$pdf->SetFont('Arial','',7);
	$pdf->Cell($lt,$a1,"Descricão: $descricao",1,"","L");// O alinhamento é feito na p´ropria cécula
	$pdf->Ln();
	$pdf->Cell($lt,$a1,"Observações: $obs",1,"","L");// O alinhamento é feito na p´ropria cécula
	$pdf->Ln();
$pdf->SetFont('Arial','b',10);
	$pdf->Cell($lC,$a1,"Registro nº: $folha",1);
	$pdf->Cell($lC*0.75,$a1,"Data da Encerra: $dataf",1);
	$pdf->Cell($lC*0.25,$a1,"",1);
	$pdf->Ln();

	$pdf->Cell($lt*0.28,$a1,"Destino: $destino",1);
	$pdf->Cell($lt*0.16,$a1,"Qdade OS: $qt",1);
	$pdf->Cell($lt*0.18,$a1,"R$ Total: $vl",1);
	$pdf->Cell($lt*0.38,$a1,"Digit.: $nome",1);

		$pdf->Ln();
		$pdf->SetFont('Arial','',10);
	$pdf->Cell($l1,$a1,"Qt",1);
		$pdf->SetFont('Arial','b',10);
    $pdf->Cell($l2,$a1,"Modelo",1);
    $pdf->Cell($l3,$a1,"Série",1);
    $pdf->Cell($l4,$a1,"Etiq.",1);
    $pdf->Cell($l5,$a1,"S/E",1);
	$pdf->Cell($l6,$a1,"Técnico",1);
	$pdf->Cell($l7,$a1,"O.S.",1);
	$pdf->Cell($l8,$a1,"Observações",1);
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
		if($linha["os"]==0){$os="";}else{$os=$linha["os"];}
		if($linha["itm"]==0){$Ios="";}else{$Ios="-".$linha["itm"];}
		$pdf->Cell($l7,$a2,$os.$Ios,1);
		$pdf->Cell($l8,$a2,$linha["orc"],1);	
     $pdf->Ln();
	 if($count==50 || $count==100 || $count==150 || $count==200){
	 	$pdf->Cell($lt,$a1,"Página $pag de $totalpag",0,"","C");
		$pag++;
	 	$pdf->AddPage();
 	 }
}
$pdf->Cell($lt,$a1,"Página $pag de $totalpag",0,"","C");
$pdf->Output();
?>