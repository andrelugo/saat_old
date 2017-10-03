<? require_once("sis_valida.php");require_once("sis_conn.php");require('includes/fpdf.php');
$prenota=$_GET["prenota"];
$cliente=1;//DEFINIR
$dtemissao="DD/MM/AAAA";//DEFINIR
$ml=2;// MARGEM LATERLA DE TODA NOTA FISCAL	
class PDF extends FPDF{}
$pdf=new PDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',8);// seta a fonte a ser utilizada na nota fical inteira

$sqlCliente="select * from cliente where cod = $cliente";
$resCliente=mysql_query ($sqlCliente) or die("Erro na SQL de consulta a tab cliente <br>$sqlCliente<br>".mysql_error());
$descricao=mysql_result($resCliente,0,"descricao");
$endereco=mysql_result($resCliente,0,"endereco");
$cep=mysql_result($resCliente,0,"cep");
$cidade=mysql_result($resCliente,0,"cidade");
$estado=mysql_result($resCliente,0,"estado");
$bairro=mysql_result($resCliente,0,"bairro");
$telefone=mysql_result($resCliente,0,"telefone");
$cnpj=mysql_result($resCliente,0,"cpf_cnpj");
$ie=mysql_result($resCliente,0,"rg_ie");
$a1=7;
$pdf->SetXY($ml,50);// Seta o POSICIONAMENTO INICIAL do texto a ser inserido (coluna,linha) em milimetros
$pdf->Cell(118,$a1,$descricao,0);
$pdf->Cell(48,$a1,$cnpj,0);
$pdf->Cell(28,$a1,$dtemissao,0);
$pdf->Ln();
$pdf->SetX($ml);//Seta margem do texto a ser impresso
$pdf->Cell(102,$a1,$endereco,0);
$pdf->Cell(37,$a1,$bairro,0);
$pdf->Cell(26,$a1,$cep,0);
$pdf->Ln();
$pdf->SetX($ml);//Seta margem do texto a ser impresso
$pdf->Cell(72,$a1,$cidade,0);
$pdf->Cell(35,$a1,$telefone,0);
$pdf->Cell(7,$a1,$estado,0);
$pdf->Cell(48,$a1,$ie,0);


$sqlPeças="SELECT peca.cod as cod, peca.descricao AS descricao, orc.valor as venda, sum( orc.qt ) AS qt
	FROM orc INNER JOIN peca ON peca.cod = orc.cod_peca	WHERE orc.cod_orc_pre_nota = $prenota
	GROUP BY peca.cod,peca.descricao, orc.valor	order by cod";
$res=mysql_query ($sqlPeças) or die("Erro na SQL de consulta aos registros pendentes de salvar <br>$sqlPeças<br>".mysql_error());
$a2=3.8;//Altura das células em milimetros do corpo de itens da nota
$pdf->SetXY($ml,95);// Seta o POSICIONAMENTO INICIAL do texto a ser inserido (coluna,linha) em milimetros
while ($linha = mysql_fetch_array($res)){
	$tot=$linha["qt"]*$linha["venda"];
	$pdf->SetX($ml);//Seta margem do texto a ser impresso
        $pdf->Cell(27,$a2,$linha["cod"],0);// Coluna CÓDIGO DO PRODUTO - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(71,$a2,$linha["descricao"],0);// Coluna DESCRIÇÃO DOS PRODUTOS - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(10,$a2,"",0);// Coluna SIT TRIB - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(8,$a2,"",0);// Coluna UNID - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(15,$a2,$linha["qt"],0);// Coluna QUANTIDADE - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(23,$a2,$linha["venda"],0);// Coluna VALOR UNITÁRIO - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(33,$a2,$tot,0);// Coluna VALOR TOTAL - o primeiro valor define a largura do campo em milimetros
		$pdf->Cell(9,$a2,"",0);// Coluna ALÍQ ICMS - o primeiro valor define a largura do campo em milimetros
		$pdf->Ln();
}
$pdf->Output();
?>