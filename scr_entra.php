<?
require_once("sis_valida.php");
//if ($_COOKIE["adm"]==1) TENTAR BLOQUEAR SE N�O FOR UM CONTROLER DE ENTRADA
	$modelo=$_POST["cmbModelo"];
	$dtbarcode=$_POST["txtDataBarcode"];
	$defeito=$_POST["cmbDefeito"];
	$barcode=$_POST["txtBarcode"];
	$acao=$_POST["cmdEntra"];
	if ($modelo=="" || $barcode==""){
		die ("Modelo ou n�mero de C�d Barras N�O PREENCHIDOS<br><br><br><br>CLIQUE EM VOLTAR NO NAVEGADOR");
	}
	require_once("sis_conn.php");  // Cuidado! O nome dos objetos do formulario s�o sensitive case!

		$sql=mysql_query("SELECT barcode from cp
		where barcode='$barcode'")or die("Erro no Camando SQL p�g src_aut.php".mysql_error());
		$row=mysql_num_rows($sql);
	if ($row>0){die ("Registro j� existe, Fa�a uma consulta para verificar o que houve!");}
	
	$sql="insert into cp (cod_modelo,data_barcode,cod_defeito,barcode)
	values ('$modelo','$dtbarcode','$defeito','$barcode')";
	
	mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inser��o $sql".mysql_error());	
	Header("Location:frm_entrarg.php?codModelo=$modelo&codDefeito=$defeito&dataBarcode=$dtbarcode");
?>