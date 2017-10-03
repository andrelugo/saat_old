<?
$gmtDate = gmdate("D, d M Y H:i:s"); 
header("Expires: {$gmtDate} GMT"); 
header("Last-Modified: {$gmtDate} GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");
// O objetivo deste formulário é cadastrsr única e exclusivamente produtos das companhias (clientes) que possuem 
//código de barras em seu processo de administração de estoque em AST 
// Para o cadastro de produtos sem código de barras e sem nota fiscal de entrada, por enqunto o sistema fará somente através
// do formulário de entrada individual de produtos (consumidor Balcão)
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
	//Consistências de Cadastro
	if ($modelo==""){$erro="<font color ='red'>Modelo não selecionado</font>";}
	//Caso um barcode já tenha sido cadastrado ou esteja em branco erro independente de qualquer outra condição pois é chave primária
	if ($barcode==""){
		$erro="<font color ='red'>Código de Barras não preenchido</font>";
	}else{
		$sql=mysql_query("SELECT barcode,DATE_FORMAT(data_entra, '%d/%m/%Y') AS ddH ,datediff(NOW(),data_entra) as mdias,data_sai,
		DATE_FORMAT(data_entra, 'as %k:%i e %ssegundos') AS hh from cp
		where barcode='$barcode'")or die("Erro no Camando SQL pág src_aut.php".mysql_error());
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
					$erro="<font color ='red'>Código de Barras cadastrado a $diasB dias O minimo permitido para recadastro é de $minDiasB dias.</font>";
				}
				//die(" teste data nula $dtsai");
				if($dtsai==NULL){
					$erro="<font color ='red'>Impossível recadastrar o barcode $barcode não foi finalizado em seu último cadastro. Consulte-o!</font>";
				}
				//$erro="<font color ='red'>O registro $barcode já foi cadastrado em $saiH as $hora, Impossivel recadastrar rows=$row</font>";
			}
		}
	}
// Consistências para o cliente 1 - CASAS BAHIA
if ($codCliente==1){
	if ($tamanho<>8){$erro="<font color ='red'>Tamanho do barcode diferente de 8. Provavelmente você clicou em lugar errado!</font>";}
	if ($letra<>"P"){
		// se a letra não começa com P então verifica na tabela modelo se é o código de um modelo a ser alterado no cadastro coletivo
		$sql=mysql_query("select cod,descricao from modelo where cod_produto_cliente = '$barcode'")
		or die("Erro na busca por um modelo não começa com P mas tem 8 digitos!".$sql.mysql_error());
		$row=mysql_num_rows($sql);
		if ($row==0){
			$erro="<font color ='red'>Este não é um Barcode válido. E não foi encontrada nenhuma referência para o código $barcode na tabela MODELO! 
					<br>Obs.: Este erro só ocorre ao cadastrar incorretamente produtos do Cliente Casas Bahia</font>";
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
	if ($tamanho<>11){$erro="<font color ='red'>Tamanho do barcode diferente de 11. Provavelmente você clicou em lugar errado! <br> tamanho= $tamanho<br>$barcode</font>";}
//Procura por um modelo na coluna ean quando um barcode de tamanho 14 for digitado
//if ($tamanho==14){
	$sql=mysql_query("select cod,descricao from modelo where ean = '$barcode'")
	or die("Erro na busca por um modelopela coluna ean!".$sql.mysql_error());
//	$sqlA="select cod,descricao from modelo where ean = '$barcode'";
//	die($sqlA);
	$row=mysql_num_rows($sql);
//	if ($row==0){
//		$erro="<font color ='red'>Não foi encontrado nenhum modelo para o EAN $barcode na tabela MODELO! 
//				<br>Obs.: Este erro só ocorre ao cadastrar um Barcode com $tamanho digitos</font>";
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
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());	
	$erro="<font color='grey'>Cadastro do barcode $barcode realizado com sucesso</font>";
	$cadastro=1;
//	Header("Location:frm_entrarg.php?codCliente=$codCliente&erro=$erro&codModelo=$modelo");
}
print "$erro,$modelo,$cadastro";
?>