<?
require_once("sis_valida.php");
require_once("sis_conn.php"); 
$simples=number_format(($_POST["txtSimples"]), 2, '.','')/100;
$icms=number_format(($_POST["txtIcms"]), 2, '.','')/100;
$dificms=number_format(($_POST["txtDifIcms"]), 2, '.','')/100;
$cpmf=number_format(($_POST["txtCpmf"]), 2, '.','')/100;
$lucro=number_format(($_POST["txtLucro"]), 2, '.','')/100;
$perda=number_format(($_POST["txtPerda"]), 2, '.','')/100;
$linhapeca=$_POST["cmbLinha"];
$fornecedor=$_POST["cmbFornecedor"];

if (isset($_POST["mup"])){$mup=$_POST["mup"];$msgMup="Realizar a corre��o da Tabela MUP";}else{$mup=0;$msgMup="N�o realizar a corre��o da Tabela MUP";}

$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];
//$arq=$_FILES['arquivo'];
$arquivo=file($nometemp);
$linhas=count($arquivo);
if ($linhapeca==0){$erro="Linha n�o selecionada";}
if ($cpmf==""){$erro="Cpmf n�o preenchido";}
if ($lucro==""){$erro="Lucro n�o preenchido";}
if ($perda==""){$erro="Perda n�o preenchido";}
if ($fornecedor==0){$erro="Fornecedor n�o preenchido";}
if ($dificms==0){$erro="Diferen�a de Icms n�o preenchido";}
if ($icms==0){$erro="Icms n�o preenchido";}
if ($simples==""){$erro="Simples n�o preenchido";}
if ($nome==""){$erro="Arquivo n�o preenchido";}
if (isset($erro)){
	print("<h1>".$erro);
	exit;
}
?>
<html><head><title></title><link href="estilo.css" rel="stylesheet" type="text/css"></head><body>
<hr>
<table width="590" border="1" align="center">
  <tr>
    <td width="159"> Nome do Arquivo :</td>
    <td width="415"><? print($nome);?></td>
  </tr>
  <tr>
    <td> Nome Tempor�rio :</td>
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
  <tr>
    <td>Refazer MUP:</td>
    <td><? print($msgMup);?></td>
  </tr>

</table>
<hr>
<table border="1" align="center">
<tr><td width="37">Status</td><td width="48">Modelo</td><td width="61">Cod Pe�a</td><td width="95">Descri��o Pe�a</td>
<td width="18">PP</td>
<td width="19">IPI</td>
<td width="20">PC</td>
<td width="20">PV</td>
<td width="61">Pr&eacute;-Ap</td>
<td width="71">Or�amento</td>
<td width="51">Garantia</td><td width="65">Retorn�vel</td>
</tr>
<?
// NESTE LA�O "FOR" ABAIXO, DEVEMOS VERIFICAR QUANTOS CAMPOS DO ARQUIVO EST�O PREENCHIDOS E VERIFICAR A CONSISTENCIA DOS DADOS ENVIADOS ENT�O,
// O C�DIGO DO MODELO DEVE SER CONDULTADO CASO N�O EXISTA UM ERRO DEVE SER INVORMADO
// UMA PE�A DEVE SER PESQUISADA NO BANCO SOMENTE NA TABELA PE�A E 
//	SE ENCONTRADA DEVE TER SEUS CAMPOS ATUALIZADOS E EM SEGUIDA DEVE SER PESQUISADA SE JA EXISTE UMA ENTRADA NA TABELA MUP E SE N�O, 
//			DEVE SER ADICIONADA A TABELA MUP 
// SE N�O ENCONTRADA DEVE SER CADASTRADA NAS DUAS TABELAS PE�A E MUP
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$nlinha=$n+1;
	$linha=explode("\t",$arquivo[$n]);
	$numcolunas=count($linha);
	// Consistencia dos dados preenchidos nos Campos
	if ($numcolunas<>10 && $numcolunas<>10){$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente de 10 E 6 que s�o as quantidades de colunas possiveis!";	}
	if ($linha[0]==""){$erro="C�digo do Produto n�o preenchido";}
	if ($linha[1]==""){$erro="C�digo da Pe�a n�o preenchido";}
	if ($linha[2]==""){$erro="Descri��o n�o preenchida";}
	if ($linha[3]==""){$erro="Custo n�o preenchido";}
	if ($linha[4]==""){$erro="IPI n�o preenchido";}
	if ($linha[6]=="" && $linha[7]=="") {$erro="Coluna Or�amento e Garantia n�o preenchidas concomitantemente!";}

	if ($linha[5]=="" || $linha[5]=="n" || $linha[5]=="N"){$pre=0;}else{$pre=1;}
	if ($linha[6]=="" || $linha[6]=="n" || $linha[6]=="N"){$orcamento=0;}else{$orcamento=1;}
	if ($linha[7]=="" || $linha[7]=="n" || $linha[7]=="N"){$garantia=0;}else{$garantia=1;}
	if ($linha[8]=="" || $linha[8]=="n" || $linha[8]=="N"){$retornavel=0;}else{$retornavel=1;}
		
	$tamanho=strlen($linha[1]);
	if ($tamanho<6){$erro="Tamanho do C�digo no Fabricante menor que 6";}
	// Consist�ncia do modelo
	if ($linha[0]=="NT"){
		$codmodelo=NULL;
		$modelo="Todos para o fornecedor Selecionado";
	}else{
		$res=mysql_query("SELECT descricao,cod FROM modelo WHERE cod_produto_fornecedor='$linha[0]'");
//		die($linha[0]);
		$row=mysql_num_rows($res);
		if ($row==0){
			$erro="Modelo n�o encontrado com o c�digo $linha[0]";
			$modelo="";
		}else{
			$modelo=mysql_result($res,0,"descricao");
			$codmodelo=mysql_result($res,0,"cod");
		}
	}	
		
		
	if (empty($erro)){
	
	if ($mup==1 && $n==0){// Se a op��o excluir tabela mup para o modelo em uqest�o foir selecionada ent�o deleta as referencias existentes na MUP
		$res=mysql_query("select count(cod_modelo) as qt from mup where cod_modelo=$codmodelo");
		$totX=mysql_result($res,0,"qt");
		mysql_query("delete from mup where cod_modelo=$codmodelo");
		print("<tr><td colspan='11'><font color='blue' size='5'> $totX refer�ncias exclu�das para o modelo $modelo na tabela MUP. Aguarde a Atualiza��o! </font></td></tr>");
	}



		$ipi=number_format(($linha[4]), 2, '.','')/100;
		// CALCULO DO PRE�O DE VENDA
		$pp=sprintf(str_replace(',','.',$linha[3]));// SUBSTITUI  VIRGULA POR PONTO . ,
		$custo=(($pp+($ipi*$pp))*(1+$dificms))*(1+$cpmf);
		$pv=$custo/(1-($lucro+$icms+$simples+$perda));
		// FIM CALCULO PRE�O VENDA
		$res=mysql_query("select descricao,cod from peca where cod_fabrica='$linha[1]'");
		$row=mysql_num_rows($res);
		if ($row==0){
			if ($numcolunas==6){
				$status="<font color='red'>N�O FOI POSSIVEL ATUALIZAR O PRE�O DESTE COMPONENTE POIS SEU CADASTRO COMPLETO N�O FOI REALIZADO ANTERIORMENTE! <br>Dica: Realize uma carga deste componente com os dez campos indicando se � uma pe�a em Garantia, Or�amento, Retorn�vel e Cortesia</font>";
			}else{
				$status="CADASTRADA!";
				$sql="insert into peca (descricao, cod_fabrica, cod_fornecedor,custo,linha,
				venda,ipi,orcamento,garantia,pre_aprova,retornavel,data_ult_atualiz,colab_ult_atualiz)
				 values ('$linha[2]','$linha[1]','$fornecedor','$pp','$linhapeca',
				'$pv','$ipi','$orcamento','$garantia','$pre','$retornavel',now(),'$id')";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());
				// Se estiver cadastrando pe�as para um fornecedor que n�o precise da Tabela MUP, ent�o o campo codmodelo � setado como NULL acima
				if ($codmodelo<>NULL){
					$res=mysql_query("select max(cod) as cod from peca");
					$codpeca=mysql_result($res,0,"cod");
					$sql="insert into mup (cod_modelo,cod_peca) values ('$codmodelo','$codpeca')";
					mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());
				}
			}
		}else{
			$codpeca=mysql_result($res,0,"cod");;
			$status="ATUALIZADA";
			if ($numcolunas==5){
				$sql="update peca set descricao='$linha[2]' ,cod_fabrica='$linha[1]', cod_fornecedor='$fornecedor', custo='$pp',
				linha='$linhapeca',venda='$pv',ipi='$ipi',orcamento='$orcamento', garantia='$garantia',pre_aprova='$pre',
				retornavel='$retornavel',data_ult_atualiz=now(),colab_ult_atualiz='$id'
				where peca.cod = $codpeca";
			}else{
				$sql="update peca set descricao='$linha[2]' ,cod_fabrica='$linha[1]', cod_fornecedor='$fornecedor', custo='$pp',
				linha='$linhapeca',venda='$pv',ipi='$ipi',data_ult_atualiz=now(),colab_ult_atualiz='$id'
				where peca.cod = $codpeca";
			}
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());
			// Se estiver cadastrando pe�as para um fornecedor que n�o precise da Tabela MUP, ent�o o campo codmodelo � setado como NULL acima
			if ($codmodelo<>NULL){
				$res=mysql_query("select * from mup where cod_peca=$codpeca and cod_modelo=$codmodelo");
				$row=mysql_num_rows($res);
				if ($row==0){
					$sql="insert into mup (cod_modelo,cod_peca) values ('$codmodelo','$codpeca')";
					mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());
				}
			}
		}

?>
<tr>
<TD><? print($status);?></TD>
<TD><? print($modelo);?></TD>
<TD><? print($linha[1]);?></TD>
<TD><? print($linha[2]);?></TD>
<TD><? print($pp);?></TD>
<TD><? print($linha[4]);?></TD>
<TD><? print($custo);?></TD>
<TD><? print($pv);?></TD>
<TD><? if ($pre==1){print("C Sim");}else{print($pre);}?></TD>
<TD><? if ($orcamento==1){print("O Sim");}else{print($orcamento);}?></TD>
<TD><? if ($garantia==1){print("G Sim");}else{print($garantia);}?></TD>
<TD><? if ($retornavel==1){print("R Sim");}else{print($retornavel);}?></TD>
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
<p><a href="frm_peca_carga.php">Voltar</a></p>
</body>
</html>