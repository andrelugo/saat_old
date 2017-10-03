<?
require_once("sis_conn.php");
require_once("sis_valida.php");

	if (isset($_POST["cod"])){$cod=$_POST["cod"];}else{$cod="";}	
	
	$cliente=$_POST["cmbCliente"];
	$descricao=$_POST["txtNota"];
	$cnpj=$_POST["txtCnpj"];
	$diaEmi=$_POST["txtDiaAdm"];
	$mesEmi=$_POST["txtMesAdm"];
	$anoEmi=$_POST["txtAnoAdm"];
	$diaRec=$_POST["txtDiaDem"];
	$mesRec=$_POST["txtMesDem"];
	$anoRec=$_POST["txtAnoDem"];
	$responsavel=$_POST["txtResponsavel"];
	$valor=$_POST["txtValor"];
	$obs=$_POST["txtObs"];
	$emissao="$anoEmi/$mesEmi/$diaEmi";
	$recebe="$anoRec/$mesRec/$diaRec";
if ($cliente=="" || $cliente==0){$erro="ERRO! Cliente não selecionado";}
if ($descricao==""){$erro="ERRO! Nota Fiscal não preenchida";}
if ($cnpj==""){$erro="ERRO! CNPJ não preenchido";}
if ($diaEmi>31 || $diaEmi<1){$erro="ERRO! Dia da Emissão da nota inválido $diaEmi";}
if ($mesEmi>12 || $mesEmi<1){$erro="ERRO! Mês da Emissão da nota inválido $mesEmi";}
if ($anoEmi>2300 || $anoEmi<2000){$erro="ERRO! Ano da Emissão da nota inválido $anoEmi";}
if ($diaRec>31 || $diaRec<1){$erro="ERRO! Dia do Recebimento da nota inválido $diaRec";}
if ($mesRec>12 || $mesRec<1){$erro="ERRO! Mês do Recebimento da nota inválido $mesRec";}
if ($anoRec>2300 || $anoRec<2000){$erro="ERRO! Ano do Recebimento da nota inválido $anoRec";}
if ($responsavel==""){$erro="Nome do responsável pelo transporte da mercadoria não preenchido!";}
if ($valor=="" || $valor==0){$erro="Valor total da Nota não preenchido";}
if (isset($_POST["rd"])){$rd=$_POST["rd"];}else{$rd="";$erro="Pergunta 'Produtos com Barcode?' não foi respondida";}

if ($cod==""){
	$sql=mysql_query("SELECT cod from nf_entrada where descricao='$descricao'")or die(mysql_error());
	$row=mysql_num_rows($sql);
	if ($row>0){
		$erro="<H3>IMPOSSÍVEL CADASTRAR! Nota Fiscal repetida, tente outra! ROW = $row nota = $descricao";
	}
	$sqlFinal="insert into nf_entrada(cod_cliente,descricao,data_emissao,data_recebe,data_cadastra,cod_colab_cadastra,cnpj,transportador,vl_tot,obs,gerar_barcode)
	values ('$cliente','$descricao','$emissao','$recebe',now(),'$id','$cnpj','$responsavel','$valor','$obs','$rd')";
}else{	
	$sqlFinal="update nf_entrada set cod_cliente=$cliente,descricao='$descricao',data_emissao='$emissao',data_recebe='$recebe',
	cnpj='$cnpj',transportador='$responsavel',vl_tot='$valor',obs='$obs',gerar_barcode='$rd' where nf_entrada.cod=$cod";
}
if (isset($erro)){
	die($erro." - <<< Clique em voltar no Navegador");
	exit;
}
	mysql_db_query ("$bd",$sqlFinal,$Link) or die ("Erro na Inserção $sqlFinal <br>".mysql_error());
	Header("Location:frm_nf_entrada.php?nota=$descricao");
?>