<?
$gmtDate = gmdate("D, d M Y H:i:s"); 
header("Expires: {$gmtDate} GMT"); 
header("Last-Modified: {$gmtDate} GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
// O objetivo deste formul�rio � cadastrsr �nica e exclusivamente produtos das companhias (clientes) que possuem 
//c�digo de barras em seu processo de administra��o de estoque em AST 
// Para o cadastro de produtos sem c�digo de barras e sem nota fiscal de entrada, por enqunto o sistema far� somente atrav�s
// do formul�rio de entrada individual de produtos (consumidor Balc�o)
require_once("sis_valida.php");
require_once("sis_conn.php");
$cadastro=0;
$modelo=$_GET["cmbModelo"];
$barcode=$_GET["txtBarcode"];
$tamanho=strlen($barcode);
$letra=substr($barcode,0,1);
$sqlCliente=mysql_query("select cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
$codCliente=mysql_result($sqlCliente,0,"cod");
$minDiasB=360;//Qtade de dias permitido para o recadastro de um barcode(coloquei como variavel pois pode ser buscado do BD!)
	//Consist�ncias de Cadastro
	if ($modelo==""){$erro="<font color ='red'>Modelo n�o selecionado</font>";}
	//Caso um barcode j� tenha sido cadastrado ou esteja em branco erro independente de qualquer outra condi��o pois � chave prim�ria
	if ($barcode==""){
		$erro="<font color ='red'>C�digo de Barras n�o preenchido</font>";
	}else{
		$sql=mysql_query("SELECT barcode,DATE_FORMAT(data_entra, '%d/%m/%Y') AS ddH ,datediff(NOW(),data_entra) as mdias,data_sai,
		DATE_FORMAT(data_entra, 'as %k:%i e %ssegundos') AS hh from cp
		where barcode='$barcode'")or die("Erro no Camando SQL p�g src_aut.php".mysql_error());
		$row=mysql_num_rows($sql);
		if ($row>0){
			$saiH=mysql_result($sql,0,"ddH");
			$hoje=date("d/m/Y");
			$hora=mysql_result($sql,0,"hh");
			if ($saiH==$hoje){
				$erro="<font color ='blue'>O registro $barcode foi cadastrado Hoje as $hora </font>";			
			}else{
				$dtsai=mysql_result($sql,$row-1,"data_sai");
				$diasB=mysql_result($sql,$row-1,"mdias");
				if($diasB<$minDiasB){
					$erro="<font color ='red'>C�digo de Barras cadastrado a $diasB dias O minimo permitido para recadastro � de $minDiasB dias.</font>";
				}
				//die(" teste data nula $dtsai");
				if($dtsai==NULL){
					$erro="<font color ='red'>Imposs�vel recadastrar o barcode $barcode n�o foi finalizado em seu �ltimo cadastro. Consulte-o!</font>";
				}
				//$erro="<font color ='red'>O registro $barcode j� foi cadastrado em $saiH as $hora, Impossivel recadastrar rows=$row</font>";
			}
		}
	}
// Consist�ncias para o cliente 1 - CASAS BAHIA
if ($codCliente==1){
	if ($tamanho<>8){$erro="<font color ='red'>Tamanho do barcode diferente de 8. Provavelmente voc� clicou em lugar errado!</font>";}
	if ($letra<>"P"){
		// se a letra n�o come�a com P ent�o verifica na tabela modelo se � o c�digo de um modelo a ser alterado no cadastro coletivo
		$sql=mysql_query("select cod,descricao from modelo where cod_produto_cliente = '$barcode'")
		or die("Erro na busca por um modelo n�o come�a com P mas tem 8 digitos!".$sql.mysql_error());
		$row=mysql_num_rows($sql);
		if ($row==0){
			$erro="<font color ='red'>Este n�o � um Barcode v�lido. E n�o foi encontrada nenhuma refer�ncia para o c�digo $barcode na tabela MODELO! 
					<br>Obs.: Este erro s� ocorre ao cadastrar incorretamente produtos do Cliente Casas Bahia</font>";
		}else{
			$modelo=mysql_result($sql,0,"cod");
			$modeloM=1;
			$descricao=mysql_result($sql,0,"descricao");
			$erro="<font color='grey'>Modelo alterado para $descricao</font>";
		}
	}
}
// Consistencias para o cliente CBD
if ($codCliente==2){
	if ($tamanho<>11){$erro="<font color ='red'>Tamanho do barcode diferente de 11. Provavelmente voc� clicou em lugar errado! <br> tamanho= $tamanho<br>$barcode</font>";}
//Procura por um modelo na coluna ean quando um barcode de tamanho 14 for digitado
//if ($tamanho==14){
	$sql=mysql_query("select cod,descricao from modelo where ean = '$barcode'")
	or die("Erro na busca por um modelopela coluna ean!".$sql.mysql_error());
//	$sqlA="select cod,descricao from modelo where ean = '$barcode'";
//	die($sqlA);
	$row=mysql_num_rows($sql);
//	if ($row==0){
//		$erro="<font color ='red'>N�o foi encontrado nenhum modelo para o EAN $barcode na tabela MODELO! 
//				<br>Obs.: Este erro s� ocorre ao cadastrar um Barcode com $tamanho digitos</font>";
//	}else{
	if ($row>0){ // Troca modelo pelo encontrado pelo ean 
		$modelo=mysql_result($sql,0,"cod");
		$modeloM=1;
		$descricao=mysql_result($sql,0,"descricao");
		$erro="<font color='grey'>Modelo alterado para $descricao</font>";
	}//fim troca modelo por ean
//	}
//}
//Fim procura EAN
}
if (isset($erro)){
//	Header("Location:frm_entrarg.php?codCliente=$codCliente&erro=$erro&codModelo=$modelo");
}else{
	$sql="insert into cp (cod_modelo,barcode,data_entra,cod_colab_entra,cod_cliente)
	values ('$modelo','$barcode',now(),'$id','$codCliente')";
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());	
	$erro="<font color='grey'>Cadastro do barcode $barcode realizado com sucesso</font>";
	$cadastro=1;
//	Header("Location:frm_entrarg.php?codCliente=$codCliente&erro=$erro&codModelo=$modelo");
}
print "$erro,$modelo,$cadastro";
?>