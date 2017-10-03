<?
require_once("sis_valida.php");
require_once("sis_conn.php"); 

$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];

$arquivo=file($nometemp);
$linhas=count($arquivo);
?>
<html><head><title></title><link href="estilo.css" rel="stylesheet" type="text/css"></head><body>
<hr>
<table width="590" border="1" align="center">
  <tr>
    <td width="159"> Nome do Arquivo :</td>
    <td width="415"><? print($nome);?></td>
  </tr>
  <tr>
    <td> Nome Temporário :</td>
    <td><? print($nometemp);?></td>
  </tr>
  <tr>
    <td>Tipo do Arquivo :</td>
    <td><? print($tipo);?></td>
  </tr>
  <tr>
    <td>Tamanho do Arquivo : </td>
    <td><? print($tamanho);?></td>
  </tr>
  <tr>
    <td>Numero de linhas :</td>
    <td><? print($linhas);?></td>
  </tr>

</table>
<hr>
<table border="1" align="center">
<tr>
<td width="37">Status</td>
<td width="48">O.S.</td>
<td width="48">Barcode</td>
<td width="48">Série</td>
</tr>
<?
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$nlinha=$n+1;
	$linha=explode("\t",$arquivo[$n]);
	$numcolunas=count($linha);
	// Consistencia dos dados preenchidos nos Campos
	if ($numcolunas<3 || $numcolunas>4){$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";}
	if ($linha[0]==""){$erro="O.S. não preenchido";$osC="";}else{$osC=trim($linha[0]);}
	if ($linha[1]==""){$erro="Barcode não preenchido";$barcodeC="";}else{$barcodeC=trim($linha[1]);}
	if ($linha[2]==""){$erro="Série não preenchido";$serieC="";}else{$serieC=trim($linha[2]);}
	if ($numcolunas==4){ 
		if ($linha[3]==""){
			$itemOs="";
			$where="os_fornecedor='$osC'";
			$update="";
		}else{
			$itemOs=trim($linha[3]);
			$where="os_fornecedor='$osC' and item_os_fornecedor=$itemOs";
			$update=", item_os_fornecedor='$itemOs'";
		}
	}else{
		$itemOs="";
		$where="os_fornecedor='$osC'";
		$update="";
	}


	$tamanho=strlen($osC);
	if ($tamanho>15){$erro="Tamanho da O.S. maior que 15 provavelmente esta seja uma mensagem de erro enviada pelo Fornecedor<br>
	Mensagem : <font color=black>$osC</font> Entre em contato para saber o que houve e recarrege esta OS em outro arquivo ou manualmente!";}

	if (empty($erro)){
//	Pode ocorrer de o numero de um chamado repetir, ou seja, estar cadastrado em outro cp
//	atualizar cadastrar
//	barcode e série não existir

		$res=mysql_query("select cp.cod as cp, barcode, serie from cp where $where");//Busca pelo mesmo numero de OS no sistema
		$row=mysql_num_rows($res);
		if ($row==0){//Se nao achou então mesma OS
			$sqlteste="select cod from cp where barcode like '%$barcodeC' and serie like '%$serieC'";
			$res2=mysql_query($sqlteste);//Busca pelos dados informados
			$row2=mysql_num_rows($res2);
			if($row2==1){// Se achou os dados Grava número de OS
				$cp=mysql_result($res2,0,"cod");
				$status="CADASTRADA!";
				$sql="update cp set os_fornecedor='$osC' $update where cod='$cp'";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
			}else{// Senão achou os dados informa erro
				$status="ERRO: A busca por uma OS contendo o Barcode $barcodeC e série $serieC concomitantemente obteve $row2 registro!!!<br>$sqlteste";
			}
		}else{// Se acho OS repetida verifica se é o mesmo registro
			$barcode=mysql_result($res,0,"barcode");
			$serie=mysql_result($res,0,"serie");			
			if ($barcode==$barcodeC && $serie==$serieC){
				$status="OK Cadastro anterior!";
			}else{
				$status="<font color=red>ERRO: A O.S. $osC já está cadastrada em um registro com o Barcode = $barcode e Série = $serie</font>";
			}
		}
?>
<tr>
<TD><? print($status);?></TD>
<TD>
<?
if ($numcolunas==4){ 
	print($osC."-".$itemOs);
}else{
	print($osC);
}
?></TD>
<TD><? print($barcodeC);?></TD>
<TD><? print($serieC);?></TD>
<?
	}else{
?>
<tr><td colspan="11"><font color="#FF0000"><? print ("Erro Linha $nlinha: $erro");?></font></td></tr>
<?
	}
}
?>
</table>
<hr>
<p><a href="frm_os_define.php">Voltar</a></p>
</body>
</html>