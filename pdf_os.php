<?
// Relat�rio de ORDENS DE SERVI�OS INICIO DA CONTRU��O 18/08/2006
// Objetivo Facilitar a impress�o geranto aumento de desempenho e imagem na qualidade do rel�torio entregue � Lenoxx e outros que precisarem
require_once("sis_valida.php");
require_once("sis_conn.php");
require('includes/fpdf.php');

$fornecedor=$_GET["cmbFornecedor"];
$mes=$_GET["txtMes"];
$ano=$_GET["txtAno"];
$anoH=date("Y");
$mesH=date("m");
$sql=mysql_query("SELECT os_auto, max_item_os,descricao from fornecedor where cod=$fornecedor")or die(mysql_error());
//////////////INICIO DO SCRIPT PARA GERAR OS PARA O FORNECEDOR OS_AUTO=3 E CUJO M�S J� TENHA ENCERRADO E EXISTAM OS SUFICIENTES 
//////////////PARA CONCLUIR OS CADASTROS
$os_auto=mysql_result($sql,0,"os_auto");
$descricaoF=mysql_result($sql,0,"descricao");
// VERIFICA SE � PARA GRAVAR NUMEROSDE OS NA TABELA CP *** OS_AUTO = 3
if($os_auto==3){
	$sqlOsGerar=mysql_query("SELECT cp.cod AS cod FROM cp INNER JOIN modelo ON modelo.cod = cp.cod_modelo
	WHERE month(data_sai)=$mes and year(data_sai)=$ano AND os_fornecedor = '0' AND cod_fornecedor = $fornecedor order by cp.cod");
	$qtGerar=mysql_num_rows($sqlOsGerar);
	// VERIFICA SE NO M�S ESCOLHIDO EXISTEM OS PARA GERAR se n�o h� ent�o parte para o PDF...
	if($qtGerar<>0){
		// VERIFICA SE N�O � DO M�S CORRENTE caso existam qtGerar!!!
		if($ano==$anoH && $mes==$mesH){
			die("<h1>INFELIZMENTE N�o � possivel gerar este relat�rio antes do t�rmino deste M�s</h1>");
		}
		$itens=mysql_result($sql,0,"max_item_os");
		$sqlOs=mysql_query("select os from os_fornecedor where cod_fornecedor=$fornecedor and usada=0 order by os");
		$qtOs=mysql_num_rows($sqlOs);
		// CONTA QUANTAS ORDENS EST�O DISPONIVEIS PARA CADASTRO
		if($qtOs<>0){
			$qtDisponivel=$qtOs*$itens;
			// SE A QUANTIDADE DEISPONIVEL � MENOR QUE A PARA GERAR ENT�O ERRO SEN�O GRAVA...
			if($qtDisponivel>$qtGerar){
				//INICIO GRAVANDO OS NA TABELA CP
				$i=0;
				$o=0;
				while($linha=mysql_fetch_array($sqlOsGerar)){
					$cp=$linha["cod"];
					$os=mysql_result($sqlOs,$o,"os");
					$itm=$i+1;
					mysql_query("update cp set os_fornecedor=$os,item_os_fornecedor=$itm where cod=$cp");
					if($i==0){
						$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os=$os")or die(mysql_error());
					}
					if($i==$itens){
						$i=-1;
						$o++;
					}
					$i++;
				}
				//FIM GRAVANDO OS NA TABELA CP
			}else{
				die("<h1>Existem apenas $qtDisponivel OS disponiveis para o fornecedor $descricaoF <br> Ser�o necess�rias $qtGerar <br>Cadastre a numera��o fornecida pelo fabricante e execute novamente este comando!!!</h1>");
			}
		}else{
			die("<h1>N�o h� ordens de servi�o disponiveis para o fornecedor $descricaoF <br> Cadastre a numera��o fornecida pelo fabricante e execute novamente este comando!!!</h1>");
		}
	}
}



////////////////////////////////////////////////////////////////////////////////////////
////////////INICIO DA CONTRU��O DO RELAT�RIO PDF////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
$sql="select os_fornecedor,item_os_fornecedor,modelo.marca as marca,modelo.descricao as modelo,serie,defeito.descricao as defeito,
date_format(data_sai,'%d/%m/%y %H:%i') as data_sai
from cp inner 
join modelo on modelo.cod = cp.cod_modelo inner
join defeito on defeito.cod=cp.cod_defeito
WHERE month(data_sai)=$mes and year(data_sai)=$ano AND cod_fornecedor = $fornecedor 
order by cp.cod";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
$data=date("d/m/y H:i");
$sqlN=mysql_query("select nome from rh_user where cod = $id");
$nome=mysql_result($sqlN,0,"nome");
//Tamanho das c�lulas em milimetros
$l1=17;$l2=10;$l3=25;$l4=20;$l5=25;$l6=50;$l7=27;$l8=0;
$a1=5;$a2=3.8;$a3=$a2;
//
$lt=$l1+$l2+$l3+$l4+$l5+$l6+$l7+$l8;
$lC=$lt/2;
$lt2=$l6+$l7+$l8;
$lp2=$l1+$l2+$l3+$l4+$l5;
//Fim Tamanho das c�luas
class PDF extends FPDF{
// Inserir Header e Footer
function Header()
{
    $this->Image('img/timbre1.JPG',15,5,180,20);    //Logo
    $this->SetFont('Arial','B',10);    //Arial bold 10
	$this->Ln(18);//Line break

	$this->Cell(17,5,"OS",1);
    $this->Cell(10,5,"Item",1);
    $this->Cell(25,5,"Marca",1);
    $this->Cell(20,5,"Modelo",1);
    $this->Cell(25,5,"S�rie",1);
	$this->Cell(50,5,"Defeito",1);
	$this->Cell(27,5,"Data Sa�da",1);
    $this->Ln();    //Line break 

}
function Footer()
{
    $this->SetY(-15);    //Position at 1.5 cm from bottom
    $this->SetFont('Arial','I',8);    //Arial italic 8
    $this->Cell(0,10,'P�gina '.$this->PageNo().'	 de {nb}',0,0,'C');    //Page number
}
}
$pdf=new PDF();
	$pdf->AliasNbPages(); // Gera um alias para o numero total de pagnas que substitui {nb} na fun��o footer
	$pdf->AddPage(); // Inicia o dicumento com a primeira p�gina esta fun��o tambem vai chamaras func�s footer e Header automaticamente ao final de cada p�gina
    $pdf->SetFont('Arial','B',10);    //Arial bold 10
	$pdf->Cell($lt,$a1,"Ordens de Serv�o $descricaoF M�s $mes de $ano",1,"","C");// O alinhamento � feito na p�ropria c�lula
	$pdf->Ln();
	$pdf->SetFont('Arial','',7); // Fonte que ser� utilizada pelo c�digo abaixo
$t=0;
$tt=0;
$ttt=0;
while ($linha = mysql_fetch_array($res)){
	$t++;
	$tt++;
	$ttt++;
	$pdf->Cell($l1,$a2,$linha["os_fornecedor"],1);
    $pdf->Cell($l2,$a2,$linha["item_os_fornecedor"],1);
	$pdf->Cell($l3,$a2,$linha["marca"],1);
	$pdf->Cell($l4,$a2,$linha["modelo"],1);
	$pdf->Cell($l5,$a2,$linha["serie"],1);
	$pdf->Cell($l6,$a2,$linha["defeito"],1);
	$pdf->Cell($l7,$a2,$linha["data_sai"],1);	
    $pdf->Ln();
	if($ttt==20){
		$ttt=0;
	    $pdf->Ln(5);
	}

	if($tt==60){
		$tt=0;
		$pdf->AddPage();
	}
}
$pdf->Ln(1);
$pdf->SetTextColor(200,200,200);
$pdf->Cell($lt,$a3," Total $t Produtos",0,"","C");
$pdf->Ln();
$pdf->Cell($lt,$a3,"Impresso em $data  -  Respons�vel $nome ",0,"","C");	
$pdf->Output();
?>