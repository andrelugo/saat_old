<?
// Existe um problema neste script, não sanado em Março de 2006:
// Não foi permitida a inclusão de duas notas repetidas, contudo isso é possível, pois dois clientes podem coincidentemente 
// encaminhar produtos com notas iguais, porem isto nunca vai ocorrer se a nota for do mesmo cliente!
//
// EM 11 DE MAIO DE 2006 DECIDI QUE AO CADASTRAR UMA NOTA COMO A DA FIX NET EM QUE OS PRODUTOS NÃO POSSUEM BARCODE, ENTÃO
// O USUARIO APONTA ISTO NO PREENCHIMENTO DA NOTA E AO SALVA-LA O SISTEMA VAI CRIAR TODOS OS REGISTROS NA TABELA CP COM O
// CÓDIGO DO CLIENTE DATA NF E BARCODE IGUAL A CP.COD 
// CASO ESTEJAMOS CADASTRANDO UMA NOTA DE UMA REVENDA QUE COLOU BARCODE NO PRODUTO ENTÃO GERAMOS AS ORDENS NA TABELA CP
// COM BARCODE EM BRANCO PARA QUE O TÉCNICO POSSA CADASTRAR O BARCODE DO CLIENTE NO FRM_CP
require_once("sis_valida.php");
require_once("sis_conn.php");
$nota=$_GET["nota"];
$rsTot=$_GET["rsTot"];
$cod_cliente=$_GET["codCliente"];

	$sql="select cod,vl_tot,gerar_barcode,data_salva from nf_entrada where descricao = '$nota'";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());
	$vl_tot=mysql_result($res,0,"vl_tot");
	$gb=mysql_result($res,0,"gerar_barcode");
	$codNota=mysql_result($res,0,"cod");
	$salva=mysql_result($res,0,"data_salva");
if($salva<>NULL){
	$erro="<font color=red> Nota Salva em $salva";
}
if ($rsTot<>$vl_tot){
	$erro="<font color=red>Impossível salvar a nota $nota pois o valor total no cabeçalho e o valor total dos itens é diferente! 
	<br>Corrija esta divergência antes de salvar!";
}
if (isset($erro)){
	Header("Location:frm_nf_entrada.php?nota=$nota&erroSalvar=$erro&erro=$erro");	
	exit;
}
/// inserindo itens da nf na tabela cp
	$sql="select cod_modelo,qt from nf_entrada_itens where cod_nf_entrada = $codNota";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela nf_entrada_itens");
	while ($linha = mysql_fetch_array($res)){
		$dia = date("Y/m/d H:i:s");
		$modelo = $linha["cod_modelo"];
		$qt = $linha["qt"];
		$count = 0;	
		while ($count<$qt){	
			if ($gb==1){
				$res2=mysql_db_query ("$bd","select max(cod) as cod from cp",$Link) or die ("$sql <br>".mysql_error());
				$maxCp=mysql_result($res2,0,"cod");
				$maxCp++;
				$sqlCp="insert into cp (cod,cod_modelo,data_entra,cod_colab_entra,cod_nf_entrada,barcode,cod_cliente)
				values ('$maxCp','$modelo','$dia','$id','$codNota','$maxCp','$cod_cliente')";
			}else{
				$sqlCp="insert into cp (cod_modelo,data_entra,cod_colab_entra,cod_nf_entrada,cod_cliente)
				values ('$modelo','$dia','$id','$codNota','$cod_cliente')";
			}
			mysql_db_query ("$bd",$sqlCp,$Link) or die ("Erro na Inserção $sqlCp".mysql_error());	
			$count++;
		}	
	}	
/// fim inserindo itens
	mysql_query("update nf_entrada set data_salva=now() where descricao='$nota'");
	Header("Location:mnu_entrada.php");
?>