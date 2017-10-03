<?
// Relatório de Orçamentos da Linha Marrom CBD inicio da construção em 18/08/2006
// Alterações em 29/08/06.. Semi pronto só falta pensar nos casos onde a qt de peças transborde na coluna
// Objetivo Facilitar a impressão geranto aumento de desempenho e imagem na qualidade do relátorio entregue semanalmente.
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');

$orc=$_GET["txtOrc"];
$sql=mysql_query("SELECT cod FROM orc WHERE orc.cod_orc_coletivo = $orc")or die(mysql_error());
$row1=mysql_num_rows($sql);
if ($row1==0){
	print("<h1>Não foi possivel gerar PDF. Nunhum resultado encontrado para Orçamento Coletivo nº $orc");
	exit;
}
////VERIFICANDO SE JÁ POSSUI PRÉ-NOTAS
$sql="select cod_orc_pre_nota,data_abre,nota,data_nota,valor_tot 
from orc inner 
join orc_pre_nota on orc_pre_nota.cod = orc.cod_orc_pre_nota 
where cod_orc_coletivo = $orc
group by cod_orc_pre_nota";
$resPre=mysql_query($sql);
$rowPre=mysql_num_rows($resPre);
//// fFIM VERIFICANDO  SE ROW-PRE FOR DIFERENTE DE ZERO IMPRIMIRÁ AS PRÉNOTAS NA PRIMEIRA PÁGINA. VIDE CÓDIGO ABAIXO
$sql="select cp.cod as cp,barcode,filial,modelo.marca as marca,modelo.descricao as modelo,serie,cod_produto_cliente
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner
join orc on orc.cod_cp = cp.cod
where orc.cod_orc_coletivo=$orc
group by barcode,filial,marca,modelo,serie,cod_produto_cliente,cp
order by cod_produto_cliente";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$totalpag=(ceil($row1/60)); // Esta informação define "pág n de n"
$data=date("d/m/y H:i");
$sqlN=mysql_query("select nome from rh_user where cod = $id");
$nome=mysql_result($sqlN,0,"nome");
//Tamanho das células em milimetros
$l1=17;$l2=7.5;$l3=21;$l4=20;$l5=25;$l6=130;$l7=14;$l8=17;$l9=4;$l10=4;$l11=14;
$a1=5;$a2=3.8;$a3=$a2;
//
$lt=$l1+$l2+$l3+$l4+$l5+$l6+$l7+$l8+$l9+$l10+$l11;
$lC=$lt/2;
$lt2=$l6+$l7+$l8;
$lp2=$l1+$l2+$l3+$l4+$l5;
//Fim Tamanho das céluas
class PDF extends FPDF{
// Inserir Header e Footer
function Header()
{
    $this->Image('img/timbre1.JPG',58,5,180,20);    //Logo
    $this->SetFont('Arial','B',10);    //Arial bold 10
	$this->Ln(18);//Line break

	$this->Cell(17,5,"Barcode",1,"","C");
    $this->Cell(7.5,5,"Lj",1,"","C");
    $this->Cell(21,5,"Marca",1,"","C");
    $this->Cell(20,5,"Modelo",1,"","C");
    $this->Cell(25,5,"Série",1,"","C");
	$this->Cell(130,5,"Peças",1,"","C");// No modelo retrato estava com 65 com o ganho de 87mm (297-210)
	$this->Cell(14,5,"Valor",1,"","C");
	$this->Cell(17,5,"Frn.-Prd.",1,"","C");
	$this->Cell(4,5,"D",1,"","C");	
	$this->Cell(4,5,"A",1,"","C");
	$this->Cell(14,5,"OBS",1,"","C");
    $this->Ln();    //Line break 
}
function Footer()
{
    $this->SetY(-15);    //Position at 1.5 cm from bottom
    $this->SetFont('Arial','I',8);    //Arial italic 8
    $this->Cell(0,10,'Página '.$this->PageNo().'	 de {nb}'.'    --     A coluna D (destino) indica se o produto vai para S (Saldo) ou E (Estoque)',0,0,'C');    //Page number
}
}
$pdf=new PDF("L","mm");
	$pdf->AliasNbPages(); // Gera um alias para o numero total de pagnas que substitui {nb} na função footer
	$pdf->AddPage(); // Inicia o dicumento com a primeira página esta função tambem vai chamaras funcõs footer e Header automaticamente ao final de cada página
    $pdf->SetFont('Arial','B',10);    //Arial bold 10
	$pdf->Cell($lt,$a1,"ORÇAMENTO COLETIVO Nº $orc",1,"","C");// O alinhamento é feito na p´ropria célula
	$pdf->Ln();
	
	$pdf->SetFont('Arial','',7); // Fonte que será utilizada pelo código abaixo
//////// SE EXISTIREM PRÉ-NOTAS PARA ESTE ORÇAMENTO SERÃO IMPRESSAS SÓ NA PRIMEIRA PÁGINA!!!	
	if($rowPre<>0){
		$pdf->SetTextColor(255,0,0);
		$pdf->Cell($lt,$a1,"Este orçamento já possui Pré-Notas  --  Significa que deve esta ser uma 2ª VIA",1,"","C");
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln(1);
		$pdf->Ln();
		$lc=($lt-145)/2;
		$pdf->Cell($lc,5,"",0);
		$pdf->Cell(20,5,"Pré-Nota",1);
	    $pdf->Cell(40,5,"Data Pré-Nota",1);
	    $pdf->Cell(20,5,"Nota Fiscal",1);
	    $pdf->Cell(40,5,"Data Nota Fiscal",1);
	    $pdf->Cell(25,5,"Valor",1);
	    $pdf->Ln();    //Line break 
		while($linhapre=mysql_fetch_array($resPre)){
			$pre=$linhapre["cod_orc_pre_nota"];
			$dtA=$linhapre["data_abre"];
			$nota=$linhapre["nota"];
			$dtn=$linhapre["data_nota"];
			$vl=$linhapre["valor_tot"];
				$pdf->Cell($lc,5,"",0);
				$pdf->Cell(20,5,"$pre",1);
			    $pdf->Cell(40,5,"$dtA",1);
			    $pdf->Cell(20,5,"$nota",1);
			    $pdf->Cell(40,5,"$dtn",1);
	    		$pdf->Cell(25,5,"$vl",1);
			    $pdf->Ln();    //Line break
		}
	}
$pdf->Ln(1);
$t=0;
$r=0;
while ($linha = mysql_fetch_array($res)){
	$pdf->Cell($l1,$a2,$linha["barcode"],1,"","C");
    $pdf->Cell($l2,$a2,$linha["filial"],1,"","C");
	$pdf->Cell($l3,$a2,$linha["marca"],1,"","C");
	$pdf->Cell($l4,$a2,$linha["modelo"],1,"","C");
	$pdf->Cell($l5,$a2,$linha["serie"],1,"","C");
			$y=$pdf->GetY();// não consegui pegar a linha para imprimir o valor na altura certa em 20/08/2006
	$cp=$linha["cp"];
	$t++;
	$peca="";
	$vlT=0;
	$sql2="select peca.descricao as peca , orc.valor as valor,orc.qt as qt, orc_decisao.aprova as aprova, orc_decisao.descricao as decisao
	from peca inner
	join orc on orc.cod_peca = peca.cod left
	join orc_decisao on orc_decisao.cod = orc.cod_decisao
	where orc.cod_cp = $cp
	and cod_orc_coletivo = $orc"; 
	$res2=mysql_db_query ("$bd",$sql2,$Link) or die (mysql_error());
	$qtL=mysql_num_rows($res2);
	if ($qtL==0){
		$pdf->SetTextColor(255,0,0);
		$pdf->Cell($lt,$a3,"  ---  Nenhum Item encontrado para o cp $cp  ---  ",1,"","C");
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln();
	}else{
		$peca="";
		for($i=0;$i<($qtL);$i++){
			$valor=mysql_result($res2,$i,"valor");
			$qt=mysql_result($res2,$i,"qt");
			$vlT=$vlT+($valor*$qt);		
			$msg="";
			$aR=0;
			$aprova=mysql_result($res2,$i,"aprova");
			if($i+1==$qtL){// Se for a última linha do Orç então imprime o vltot
				$peca=$peca." ".mysql_result($res2,$i,"peca");
				$tamanho=$pdf->GetStringWidth($peca);
				if($aprova==0 && $aprova<>NULL){/// Se o orçamento foi reprovado entra aqui
					$decisao=mysql_result($res2,$i,"decisao");
					$peca=$decisao." ".$peca;
					$pdf->SetTextColor(255,0,0);
					if($tamanho>=$l6/2){
						$pdf->MultiCell($l6,$a2,$peca,0);////////ESCREVE NO PDF com font vermelha e com o motivo da reprovação
					}else{
						$pdf->Cell($l6,$a2,$peca,1);////////ESCREVE NO PDF - NORMAL
					}
					$pdf->SetTextColor(0,0,0);					
				}else{
					if($tamanho>=$l6){// Se o tamanho da string peça for maior que a largura da coluna então MultCell senão 
						//INICIO DO BLOCO COM VÁIAS LINHAS PARA O CAMPO PEÇAS
						$x=$pdf->GetX();
						$x=$x+$l6;
						$pdf->MultiCell($l6,$a2,$peca,0);////////ESCREVE NO PDF - COM MULTICELLULAS
						$y=$pdf->GetY();
						$y=$y-$a2;
						//FIM DO BLOCO COM VÁIAS LINHAS PARA O CAMPO PEÇAS
					}else{
						$pdf->Cell($l6,$a2,$peca,1);////////ESCREVE NO PDF - NORMAL
						$x=$pdf->GetX();$y=$pdf->GetY();//SE NÃO FOSSE O BLOCO COM VÁRIAS LINHAS (MULTCELL) NÃO PRECISARIAMOS DESTA LINHA
					}
				}
			}else{
					$peca=$peca." ".mysql_result($res2,$i,"peca").",";
			}
		}
//		$pdf->SetXY($lv,$y);// Y é LINHA  Setar primeiro a linha e depois a coluna// X é  COLUNA
		$pdf->SetY($y);
		$pdf->SetX($x);
		$r=$r+$vlT;
		$vlTot=number_format($vlT, 2, ',', '.');
		$pdf->Cell($l7,$a2,"R$ ".$vlTot,1,"","C");
		$pdf->Cell($l8,$a2,$linha["cod_produto_cliente"],1,"","C");	
			//SE EXISTE ALGUM ORC C/ DESTINO ESTOQUE ENTÃO SUBENTENDE-SE QUE O PRODUTO VAI P/ ESTOQUE!
			$sqlDestino="select cod from orc where orc.cod_cp = $cp	and cod_orc_coletivo = $orc	and cod_destino = 1"; 
			$resDestino=mysql_db_query ("$bd",$sqlDestino,$Link) or die (mysql_error());
			$qtDestino=mysql_num_rows($resDestino);
			if($qtDestino>=1){$destino="E";}else{$destino="S";}
			/////FIM DESTINO
		$pdf->Cell($l9,$a2,$destino,1,"","C");
		$pdf->Cell($l10,$a2,"",1,"","C");
		$pdf->Cell($l11,$a2,"",1,"","C");
	    $pdf->Ln();
	}
}
$pdf->Ln(1);
$pdf->SetTextColor(200,200,200);
$pdf->Cell($lt,$a3," Orçamento coletivo nº $orc   ---   Total R$ $r   ---   $t Produtos",0,"","C");
$pdf->Ln();
$pdf->Cell($lt,$a3,"Impresso em $data  -  Responsável $nome ",0,"","C");	
$pdf->Output();
?>