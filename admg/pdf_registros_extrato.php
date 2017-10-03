<?
require_once("sis_valida.php");
require_once("sis_conn.php");
require('../includes/fpdf.php');

$extrato=$_GET["cod"];
$res=mysql_query("select descricao from extrato_mo where cod = $extrato") or die (mysql_error());
$desExtrato=mysql_result($res,0,"descricao");

$data=date("d/m/y - H:i");
//Tamanho das células em milimetros
	$a1=7;$a2=3.8;
	$l1=30;$l2=30;$l3=30;$l4=20;$l5=30;
	$lt=$l1+$l2+$l3+$l4+$l5;
	$lC=$lt/2;
	$margem=25;
//Fim Tamanho das céluas
$count=0;
$cont1=0;//contador especial para colocar o numero do extrato na primeira pág do documento
$pag=0;
$total=0;
class PDF extends FPDF{
	// Inserir Header e Footer
	function Header()
	{
		$this->Image('../img/timbre1.JPG',15,5,180,20);    //Logo
		$this->SetFont('Arial','B',10);    //Arial bold 10
		$this->Ln(18);//Line break
	
		$this->Cell(25,7,"",0,"","");
		$this->Cell(140,7,"Relatório de Ordens de Serviço - Controle de Extratos",1,"","C");// O alinhamento é feito na própria cécula
			$this->Ln();
			$this->SetFont('Arial','b',10);
			$this->Cell(25,7,"",0,"","");
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
//$sqlE="select cod_fechamento_reg from cp where cod_extrato_mo=$extrato and cod_fechamento_reg is not null group by cod_fechamento_reg"; // alterei p/ a de baixo em 27/04/2007
$sqlE="select cod_fechamento_reg from cp where cod_extrato_mo=$extrato and cod_fechamento_reg is not null and cod_fechamento_reg <> 0 group by cod_fechamento_reg";
// and cod_fechamento_reg is not null adicionado em 14/12/06 para filtrar OS que não possuiam reg. de saída afim de sair dos erros abaixo
$resE=mysql_db_query ("$bd",$sqlE,$Link) or die (mysql_error()."<br>$sqlE");
while ($linhaE = mysql_fetch_array($resE)){
	if($linhaE['cod_fechamento_reg']==""){
	die("ERRO: EXISTEM ORDENS DE SERVIÇO SEM REGISTRO DE SAÍDA PARA ESTE EXTRATO!!!");}

	$pdf->AddPage();
	$total=0;$vlTot=0;

	$resEnvio=mysql_query("select fechamento_reg.registro as registro,extrato_mo.descricao as envio,extrato_mo.cod as codExtrato
	from fechamento_reg 
	left join extrato_mo on extrato_mo.cod = fechamento_reg.cod_extrato_mo_envio 
	where fechamento_reg.cod = $linhaE[cod_fechamento_reg]");
	$registro=mysql_result($resEnvio,0,"registro");
	$codExtratoR=mysql_result($resEnvio,0,"codExtrato");
	$Eenvio=mysql_result($resEnvio,0,"envio");
	if ($codExtratoR==$extrato){
		$envio="ORIGINAL ACOMPANHA ESTE EXTRATO $Eenvio";
	}else{
		$envio="O original foi enviado no extrato $Eenvio";
	}
	$sql="SELECT os_fornecedor AS os, item_os_fornecedor AS item, barcode, serie, sum( orc.valor ) AS orc,cod_extrato_mo,
	extrato_mo.descricao as extrato
	FROM cp	
	LEFT JOIN orc ON orc.cod_cp = cp.cod 
	left join extrato_mo on extrato_mo.cod = cp.cod_extrato_mo
	where cod_fechamento_reg=$linhaE[cod_fechamento_reg] GROUP BY os, item, barcode, serie ORDER BY os_fornecedor";
	$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error()."<br>$sql<br><br>$sqlE");
	while ($linha = mysql_fetch_array($res)){
		$pdf->Cell($margem,$a1,"",0,"","");//LARGURA DA MARGEM ESQUERDA
		if($cont1==0){
			$cont1++;
			$pdf->Cell($lt,$a1,"CONTROLE DO EXTRATO $desExtrato",1,"","C");
			$pdf->Ln();
			$pdf->Cell($margem,$a1,"",0,"","");//LARGURA DA MARGEM ESQUERDA
		}
		if ($total==0){
			$pdf->Cell($lt,$a1,"REGISTRO DE SAÍDAS Nº $registro",1,"","C");
			$pdf->Ln();
			$pdf->Cell($margem,$a1,"",0,"","");//LARGURA DA MARGEM ESQUERDA
			$pdf->Cell($lt,$a1,"$envio",1,"","C");
			$pdf->Ln();
			$pdf->Cell($margem,$a1,"",0,"","");//LARGURA DA MARGEM ESQUERDA
		}
		if ($linha['cod_extrato_mo']==$extrato){// se o registro pertencer ao extrato em questão então NEGRITO
			$pdf->SetFont('Arial','b',9);
		}else{
			$pdf->SetFont('Arial','',8);
		}
		
		$os=$linha["os"]."-".$linha["item"];
		$pdf->Cell($l1,$a2,$os,1);
		$pdf->Cell($l2,$a2,$linha["barcode"],1);
		$pdf->Cell($l3,$a2,$linha["serie"],1);
		$vlT=number_format($linha["orc"], 2, ',', '.');
		if ($linha["orc"]==0){
			$pdf->Cell($l3,$a2,"",1);
		}else{
			$pdf->Cell($l3,$a2,"R$ ".$vlT,1);
		}
		$pdf->Cell($l4,$a2,$linha["extrato"],1);
		$pdf->Ln();
		$total++;$vlTot=$vlTot+$linha["orc"];
	}
		$pdf->SetFont('Arial','b',10);
		$pdf->Cell($margem,$a1,"",0,"","");
		$pdf->Cell($lt,$a1,"TOTAL de $total produtos no registro de saídas nº $registro",0,"","C");
		$vlT=number_format($vlTot, 2, ',', '.');
		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$pdf->Cell($margem,$a1,"",0,"","");
		$pdf->Cell($lt,$a2,"OBS.: O total de R$ $vlT em orçamentos para este registro se referem a ",0,"","C");
		$pdf->Ln();
		$pdf->Cell($margem,$a1,"",0,"","");
		$pdf->Cell($lt,$a2,"itens estéticos e/ou acessórios comprados pelo cliente",0,"","C");
}
$pdf->Output();
?>