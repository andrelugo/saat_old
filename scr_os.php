<?
require_once("sis_valida.php");
require_once("sis_conn.php");
	$fornecedor = $_POST["cmbFornecedor"];
	$osInd = $_POST["txtOsIndividual"];
	$osIni = $_POST["txtOsIni"];
	$osFim = $_POST["txtOsFim"];
	if ($fornecedor=="" || $fornecedor==0){$erro="Fornecedor não Preenchido";}
	if ($osIni=="" && $osFim==""){
		if ($osInd==""){$erro="Nenhuma OS preenchida";}else{$tipo=1;}
	}else{
		if ($osFim==""){$erro="OS FINAL não Preenchida";}
		if ($osIni==""){$erro="OS INICIAL não Preenchida";}
		$tipo=2;
	}
	if (isset($erro)){
		die("$erro<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
?>
<html>
<head><title>Untitled Document</title>
<body>
<?
if ($tipo==1){
	$pesOs=mysql_query("select cod from os_fornecedor where os='$osInd' and cod_fornecedor=$fornecedor")or die(mysql_error());
	$row=mysql_num_rows($pesOs);
	if($row==0){
		$sql=mysql_query("insert into os_fornecedor (cod_fornecedor, os) values ('$fornecedor','$osInd')")or die(mysql_error());
		print("Os $osInd inserida com êxito no Banco de Dados");
	}else{
		print("Ordem de Serviço repetida Tente OUTRA!");
	}
}
if ($tipo==2){
	$count=$osIni;
	while ($count <= $osFim){
		$pesOs=mysql_query("select cod from os_fornecedor where os='$count' and cod_fornecedor=$fornecedor")or die(mysql_error());
		$row=mysql_num_rows($pesOs);
		if($row==0){
			$sql=mysql_query("insert into os_fornecedor (cod_fornecedor, os) values ('$fornecedor','$count')")or die(mysql_error());
			print("<font color='blue'>Os $count inserida com êxito no Banco de Dados</font><br>");
		}else{
			print("<font color='red'>ERRO: Ordem de Serviço $count   REPETIDA   Tente OUTRA!</font><bR>");
		}
		$count++;
	}
}
?>
  Alterar Dados </p>
</body>
</html>