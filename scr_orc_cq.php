<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_POST["cp"])){
	$cp=$_POST["cp"];
	$forn=$_POST["forn"];
	$qt=$_POST["txtQt"];
	if (!$_POST["cmbDestino"]==""){$destino=$_POST["cmbDestino"];}else{$erro="Destino nгo preenchido";$destino="";}
	if (!$_POST["cmbCodPeca"]==0){$codP=$_POST["cmbCodPeca"];}else{$erro="Peзa nгo selecionada!";$codP=0;}	
	if (!$_POST["cmbMotivo"]==0){$motivo=$_POST["cmbMotivo"];}else{$erro="Motivo nгo preenchido!";$motivo=0;}	
}
if (isset($erro)){
	//	die("$erro $codP");
	Header("Location:frm_orc_cq.php?destino=$destino&motivo=$motivo&cp=$cp&erro=$erro&codPeca=$codP&forn=$forn");
}
$sql=mysql_query("select venda,orcamento,descricao,pre_aprova from peca where cod=$codP")or die("Erro no Camando SQL scr_peca.php".mysql_error());
$row=mysql_num_rows($sql);
if($row==0){
	$erro="Peзa nгo encontrada com o cуdigo $codPeca";
}else{
	$valor=mysql_result($sql,0,"venda");
	$orc=mysql_result($sql,0,"orcamento");
	$preAp=mysql_result($sql,0,"pre_aprova");
	if ($orc==0){
		$descPeca=mysql_result($sql,0,"descricao");
		$erro="A peзa $descPeca nгo estб cadastrada como componente estйtico ou acessуrio, portanto nгo pode ser vendida em equipamentos atendidos na garantia. Caso exista algum equivoco no cadastro ou alguma dъvida informe seu gerente!";
	}
}
if (isset($erro)){
	Header("Location:frm_orc_cq.php?destino=$destino&motivo=$motivo&cp=$cp&erro=$erro&codPeca=$codPeca&forn=$forn");
}else{
	if ($preAp==1){
		$sql="insert into orc(cod_peca,cod_cp,qt,cod_colab_cad,data_cad,cod_motivo,valor,cod_destino,cod_decisao,data_decisao,cod_colab_decide)
		values ('$codP','$cp','$qt',$id,now(),$motivo,'$valor','$destino',1,now(),$id)";
	}else{
		$sql="insert into orc(cod_peca,cod_cp,qt,cod_colab_cad,data_cad,cod_motivo,valor,cod_destino)
		values ('$codP','$cp','$qt',$id,now(),$motivo,'$valor','$destino')";
	}
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na query de inserзгo dos dados do ORЗAMENTO $sql ".mysql_error());		
//	$sqlC=mysql_query("update peca set qt=qt-1 where cod = $codP");  // esta query agoa й utilizada no momento da saнda do produto
//	$sqld=mysql_query("update cp set destino=$destino where cod = $codP");
	Header("Location:frm_orc_cq.php?cp=$cp&forn=$forn");
}
?>