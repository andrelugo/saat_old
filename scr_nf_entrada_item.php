<?
require_once("sis_valida.php");
require_once("sis_conn.php");
	$nota=$_POST["nota"];
	$codNf=$_POST["codNf"];
	$codModelo=$_POST["cmbModelo"];
	$qt=$_POST["txtQt"];
	$valor=$_POST["txtValor"];
if($codModelo=="" || $codModelo==0){$erro=" Modelo não selecionado";}
if($qt=="" || $qt==0){$erro=" Quantidade não preenchida";}
if($valor=="" || $valor==0){$erro=" Valor unitário não preenchido";}

if (isset($erro)){
	Header("Location:frm_nf_entrada.php?nota=$nota&erro=$erro");
	exit;
}
	$sql="insert into nf_entrada_itens (cod_nf_entrada,cod_modelo,qt,vl_unit) values ('$codNf','$codModelo','$qt','$valor')";
	mysql_db_query ("$bd",$sql,$Link) or die ("$sql <br>".mysql_error());		
	Header("Location:frm_nf_entrada.php?nota=$nota");
?>