<script>
function foco(){
document.form1.txtBarcode.focus();
}
function focodia(){
	if(document.form1.txtDiaBarcode.value.length==2){
	document.form1.txtMesBarcode.focus();
	}		
	if((document.form1.txtDiaBarcode.value<1 || document.form1.txtDiaBarcode.value>31)&&document.form1.txtDiaBarcode.value.length!=1){
	alert("Valor de Dias incorrreto");
	document.form1.txtDiaBarcode.value="";
	document.form1.txtDiaBarcode.focus();
	}
}
contar=0
function submeter(){
	contar=contar+1;
	if (contar>1){
		alert("Aguarde o formulário ainda está sendo cadastrado! Pressione OK para continuar!");
		return false;
	}
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["codDefeito"])){
	$codDefeito=$_GET["codDefeito"];
	$codModelo=$_GET["codModelo"];
	$diaBarcode=$_GET["diaB"];
	$mesBarcode=$_GET["mesB"];
	$anoBarcode=$_GET["anoB"];
}
if (isset($_GET["contOk"])){$contOk=$_GET["contOk"];}else{$contOk=0;}
if (isset($_GET["contErro"])){$contErro=$_GET["contErro"];}else{$contErro=0;}
if (isset($_GET["contTot"])){$contTot=$_GET["contTot"];}else{$contTot=0;}
if (isset($_GET["contMod"])){$contMod=$_GET["contMod"];}else{$contMod=0;}
if (isset($_GET["erro"])){$erro=$_GET["erro"];}else{$erro="";}
if (isset($_GET["codCliente"])){$codCliente=$_GET["codCliente"];}
if (isset($_GET["nota"])){$nota=$_GET["nota"];}else{$nota="";}
?>
<html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
</head>
<body onLoad=foco() topmargin="0">
<p align="center" class="style1"><span class="caixaAZ1">Produtos de Consumidor </span>
<center>
<H1> <? print($erro);?></H1></center></p>
<form name="form1" method="post" action="scr_entrarg.php" onSubmit="submeter()">
  <div align="center">
  <table width="802" border="1">
  <tr>
  <td colspan="3">

<?
$sqlCliente=mysql_query("select cliente.descricao as cliente, cliente.cod as cod from cliente inner join rh_user on rh_user.cliente_exclusivo = cliente.cod where rh_user.cod=$id");
$tot = mysql_num_rows ($sqlCliente);
if ($tot>0){
	$cliente=mysql_result($sqlCliente,0,"cliente");
	//$cod_cliente=mysql_result($sqlCliente,0,"cod");	//print($cod_cliente);
	print("  Cliente: ".$cliente);
}else{
?>
	Nota Fiscal:
	<input name="txtNota" type="text" class="caixaAZ2" id="txtNota" tabindex="0" value="<? print ($nota);?>" size="8" maxlength="8">
	<a href="frm_nf_entrada.php">Cadastrar</a> / <a href="pes_nf_entrada.php">Pesquisar</a><?
}
?>
  </td>
  </tr>
      <tr>
        <td width="421">Data do Barcode<span class="caixaPR1">          <input name="txtDiaBarcode" type="text" class="caixaPR1" id="txtDiaBarcode"  value="<?if(isset($diaBarcode)){print($diaBarcode);}?>" size="1" maxlength="2" onKeyUp=focodia()>
		/
        <input name="txtMesBarcode" type="text" class="caixaPR1" id="txtMesBarcode"  value="<?if(isset($mesBarcode)){print($mesBarcode);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtMesBarcode.value.length==2){document.form1.txtAnoBarcode.focus();}">
        /
		<input name="txtAnoBarcode" type="text" class="caixaPR1" id="txtAnoBarcode"  value="<?if(isset($anoBarcode)){print($anoBarcode);}?>" size="1" maxlength="2" onKeyUp="if(document.form1.txtAnoBarcode.value.length==2){document.form1.cmbModelo.focus();}">
        </span> </td>
        <td width="224">* Modelo
          <select name="cmbModelo" class="caixaPR1" id="cmbModelo">
            <option value=""></option>
  <?	  
$sql="select * from modelo";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Modelo");
while ($linha = mysql_fetch_array($res)){
	if (isset($codModelo)){
		if ($codModelo==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
          </select></td>
        <td width="135">Defeito
          <select name="cmbDefeito" class="caixaPR1" id="cmbDefeito"  tabindex="2" >
            <option value=""></option>
            <?	  
$sql="select * from defeito";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela defeito");
while ($linha = mysql_fetch_array($res)){
	if (isset($codDefeito)){
		if ($codDefeito==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
          </select></td>
      </tr>
      <tr>
        <td colspan="3"><div align="center"><span class="style6">* C&oacute;digo de Barras</span>            
          <input name="txtBarcode" type="text" class="caixaAZ1" id="txtBarcode" tabindex="0" size="25" maxlength="20">
            <input name="cmdEntra" type="submit" class="Titulo2" id="cmdEntra" value="Entra">
        </div></td>
      </tr><tr>
	  <td colspan="3">	   	Cadastrados: <?print ($contOk);?>	  <input type="hidden" name="contErro" value="<?print ($contErro);?>">
	  
	  &nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Erros: <?print ("<font color='red'>".$contErro."</font>");?>
	<input type="hidden" name="contOk" value="<?print ($contOk);?>">
		&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Modelos:<?print ($contMod);?>
        <input type="hidden" name="contMod" value="<?print ($contMod);?>">        
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
		  Total de cliques: <?print ($contTot);?>
		  <input type="hidden" name="contTot" value="<?print ($contTot);?>">
	    </td>
	  </tr></table>
  </div>
  <div align="center">Os Campos marcados com * s&atilde;o de preenchimento obrigat&oacute;rio!<br>
  </div>
</form>
</body>
</html>
