<?
require_once("sis_valida.php");
require_once("sis_conn.php"); 

$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];

if(empty($_POST["rdT"])){die("Erro: Informe qual campo será pesquisado Barcode, CP ou OS");}else{$campo=$_POST["rdT"];}
switch($campo){
	case"os";
		$titulo="Ordem de Serviço";	
		$campo="os_fornecedor";
	break;
	case"cp";
		$titulo="Controle de Produção";
		$campo="cp.cod";
	break;
	case"ba";
		$titulo="Código de Barras";
		$campo="barcode";		
	break;
}

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
<td width="92"><? print($titulo);?> Informado </td>
<td width="97"><? print($titulo);?> Pesquisado</td>
<TD width="52">Marca</TD>
<td width="84">Modelo</td>
<td width="85">Série</td>
<td width="89">Status</td>
</tr>
<?
$contE=0;
$contS=0;
$contReg=0;
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$nlinha=$n+1;
	$linha=explode("\t",$arquivo[$n]);
	$numcolunas=count($linha);
// Consistencia dos dados preenchidos nos Campos
	if ($linha[0]==""){$erro="Código de Barras não Preenchido!!!";}
	$tamanho=strlen($linha["0"]);
	//if ($tamanho<>11){$erro="Tamanho do Barcode é $tamanho diferente de 11";}
// FIM Consistencia dos dados preenchidos nos Campos
	if (empty($erro)){
		//$barcode=substr($linha[0],0,9);
		//teste com a linha abaixo
		$pes1=trim($linha[0]);
		//se  delimitar os campos
		if ($_POST["cIni"]<>"" && $_POST["cTot"]<>""){
			$pes=substr($pes1,$_POST["cIni"],$_POST["cTot"]);
		}else{
			$pes=$pes1;
		}
		//
		$sql="select cp.cod as cp,data_entra,data_pronto,date_format(data_sai,'%d/%m/%y %H:%i') as data_sai,modelo.marca as marca,modelo.descricao as modelo,cp.serie as serie from cp inner join modelo on modelo.cod = cp.cod_modelo where $campo like '%$pes%'";
		$res=mysql_query($sql) or die($sql." <br>".mysql_error());
		$row=mysql_num_rows($res);
		if ($row==0){
			$status="<font color=red>Não há entrada! Registro não encontrado!</font>";
			$contS++;
			$marca=" - ";
			$modelo=" - ";
			$serie=" - ";
		}else{
		//TESTE
		$cp=mysql_result($res,0,"cp");
		$res2=mysql_query("update cp set cod_posicao = 3 where cod = $cp") or die(mysql_error());
		
		//
			$entra=mysql_result($res,0,"data_entra");
			$pronto=mysql_result($res,0,"data_pronto");
			$sai=mysql_result($res,0,"data_sai");
			$marca=mysql_result($res,0,"marca");
			$modelo=mysql_result($res,0,"modelo");
			$serie=mysql_result($res,0,"serie");
			if($sai==NULL || empty($sai)){
				if($pronto==NULL || empty($sai)){
					$status="Há entrada, porém, Produto não pronto!!!";
					$contE++;
				}else{
					$status="PRONTO, mas, não entregue!!!";
					$contE++;
				}
			}else{
				$status="<font color=blue>Entregue em $sai</font>";
				$contReg++;
			}
		}
?>
<tr>
<TD><? print($linha[0]);?></TD>
<TD><? print($pes);?></TD>
<td><? print($marca);?></td>
<td><? print($modelo);?></td>
<td><? print($serie);?></td>
<TD><? print($status);?></TD>
<?
	}else{
?>
<tr><td colspan="11"><font color="#FF0000"><? print ("Erro Linha $nlinha: $erro");?></font></td></tr>
<?
	}
}
?>
</table>
<p align="center">Resumo do relat&oacute;rio de status</p>
<table width="259" border="1" align="center">
  <tr>
    <td width="197">Descri&ccedil;&atilde;o Status </td>
    <td width="46">Qtdade</td>
  </tr>
  <tr>
    <td>N&atilde;o encontrados </td>
    <td><? print($contS);?></td>
  </tr>
  <tr>
    <td>Com Entrada - N&atilde;o Prontos </td>
    <td><? print($contE);?></td>
  </tr>
  <tr>
    <td>Entregues - Finalizados </td>
    <td><? print($contReg);?></td>
  </tr>
  <tr>
    <td>Total</td>
    <td><? 
	$tot=$contE+$contS+$contReg;
	print($tot);?></td>
  </tr>
</table>

<hr>
<p><a href="frm_conprod.php">Voltar</a></p>
</body>
</html>