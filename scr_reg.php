<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$codf=$_POST["codf"];

if (isset($_POST["txtBarcode"]) && !$_POST["txtBarcode"]==""){
	$barcode=$_POST["txtBarcode"];
	$sqlfind="and barcode='$barcode' and folha_cq is not null";
    $sqlfind1="barcode='$barcode' and folha_cq is not null";
	$whereincluir="and cp.barcode='$barcode'";
	$foco="barcode";
//	print($barcode);exit;
}else{
	if (isset($_POST["txtFolha"]) && !$_POST["txtFolha"]==""){
		$folha=$_POST["txtFolha"];
		$sqlfind="and folha_cq='$folha'";
		$sqlfind1="folha_cq='$folha'";
		$whereincluir="and cp.folha_cq='$folha'";
		$foco="folha";
	}else{
		$erro="ERRO: Campos Barcode e Folha n�o preenchidos concomitantemente!";
		$foco="barcode";
	}
}
if (isset($erro)){
	Header("Location:frm_reg.php?erro=$erro&codf=$codf&foco=$foco");
	exit;
}
// Ao incluir um Barcode ele pode:
//	N�o Existir =//	Existir mas n�o ter uma folha de cq ou seja n�o estar disponivel para registrar
//	Estar com fechamento
//	Estar sem fechamento porem � de destino diferente do fechamento
//
// Ao incluir uma Folha ela pode:
//	N�o existir
//	Existir porem seus produtos todos s�o de destino diferente da saida		//aplicarei somente a consistencia de quantidade 
//			no fechamento e deixarei a consistencia de destinos para o usuario fazer
//	Existir e apenas alguns produtos serem de destino compativel com o fechamento
//	Existir e todos os seus produtos estarem em outra folha
// Apos incluir alguns registros em um fechamento deve-se impedir a mudan�a de tipo de fechamento.
// Mas � na finaliza�ao do fechamento que se realizar� uma consistencia para verificar se os tipos "batem"

//////////////////////////////////////////INICIO DO C�DIGO//////////////////////////////////////////////////
	//pesquisa o destino do registro de saida para adicionar somente produtos equivalentes ao tipo de registro se n�o houver destino adiciona e conta tudo que for null
		$res=mysql_query("select tipo from fechamento_reg where cod=$codf") or die ("Linha 39".mysql_error());
		$tipo=mysql_result($res,0,"tipo");
		if ($tipo==0){
			$wheretipo = "";
			$whereNtipo = "";
		}else{
			$wheretipo = "and cod_destino=$tipo";
			$whereNtipo = "and cod_destino<>$tipo";
		}
	//CONTA QUANTOS RESULTADOS FORAM ENCONTRADOS NO TOTAL COM AS INFORMA��ES PASSADAS PELO FORM
	$find=mysql_query("select count(cod) as tot from cp where $sqlfind1") or die ("Linha 44".mysql_error());
$qtTot=mysql_result($find,0,"tot");
	if ($qtTot==0){
		Header("Location:frm_reg.php?erro=Nenhum+resultado+encontrado+para+esta+opera��o!&codf=$codf&foco=$foco");
		exit;
	}
	//CONTA QUANTOS EST�O EM OUTRO FECHAMENTO 
	$find=mysql_query("select count(cod) as tot from cp where cp.cod_fechamento_reg is not null $sqlfind") or die ("Linha 51".mysql_error());
$qtOutroF=mysql_result($find,0,"tot");

	//CONTA QUANTOS S�O DE DESTINO DIFERENTE DO REGISTRO SELECIONADO
	$find=mysql_query("select count(cod) as tot from cp where $sqlfind1 $whereNtipo") or die ("Linha 55".mysql_error());
$qtOutroT=mysql_result($find,0,"tot");

	//CONTA QUANTOS SER�O INCLUSOS --- MESMO DESTINO E SEM FECHAMENTO
	$find=mysql_query("select count(cod) as tot from cp where cp.cod_fechamento_reg is null $sqlfind $wheretipo") or die ("Linha 59".mysql_error());
$qtInclusos=mysql_result($find,0,"tot");

if ($qtInclusos==0){
		Header("Location:frm_reg.php?codf=$codf&erro=$qtTot registro(s) encontrado(s) porem nenhum foi incluso! Porque $qtOutroT s�o de destino diferente deste tipo de Finaliza��o e $qtOutroF Est�o em outro fechamento&foco=$foco");
		exit;
}
if("$qtTot"=="$qtInclusos"){$erro="<font color='blue'>$qtInclusos Registros incluido(s) neste Fechamento!</font>";}
if("$qtInclusos"<"$qtTot"){$erro="<font color='blue'>$qtTot Registro(s) encontrados(s) porem somente $qtInclusos Registros incluido(s) neste Fechamento!  Porque $qtOutroT s�o de destino diferente deste tipo de Finaliza��o e $qtOutroF Est�o em outro fechamento</font>";}

	//incluindo somente as folhas ou Barcodes cujo numero de fechamento seja null e o tipo seja o mesmo do reistro de sa�da "fechamento"
	$sql="update cp set cod_fechamento_reg=$codf where cp.cod_fechamento_reg is null $whereincluir $wheretipo";
	mysql_db_query ("$bd",$sql,$Link) or die ("Ultima 	Linha".mysql_error());		
	Header("Location:frm_reg.php?erro=$erro&codf=$codf&foco=$foco");
?>