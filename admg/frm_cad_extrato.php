<?
require_once("sis_valida.php");
require_once("sis_conn.php");
//if ($id<>1){die("Não logado!");}

if (isset($_GET["cod"])){
	$cod=$_GET["cod"];
	$msg="Alteração do Produto de código $cod <input name='codigo' type='hidden' value='$cod'>";
	$btn="Alterar";
	$sql=mysql_query("select * from extrato_mo where extrato_mo.cod = $cod");

	$descricao = mysql_result($sql,0,"descricao");
	$nota = mysql_result($sql,0,"nota_fiscal");
	$dataRecebe = mysql_result($sql,0,"data_pgto");
	$dataExtrato = mysql_result($sql,0,"data_extrato");
	$dataNota = mysql_result($sql,0,"data_nota");
	$cod_fornecedor = mysql_result($sql,0,"cod_fornecedor");
	$obs = mysql_result($sql,0,"obs");	
}else{
	$msg="Cadastro do Produto";
	$btn="Cadastrar";
}
?><html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-image: url(img/fundoadm.gif);
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
-->
</style></head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><span class="style1">Cadastro / Altera&ccedil;&atilde;o de extrato de m&atilde;o de Obra</span>:
</p>
<form name="upload" action="scr_cad_extrato.php" method="post">
<table width="800" border="1" align="center">
  <tr>
    <td width="119">Descri&ccedil;&atilde;o:</td>
    <td width="241"><input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<?if(isset($descricao)){print($descricao);}?>" size="30" maxlength="50"></td>
    <td width="125">Data do Extrato:</td>
    <td width="287"><input type="text" name="txtDataExtrato" value="<?if(isset($dataExtrato)){print($dataExtrato);}?>" ></td>
  </tr>
  <tr>
    <td>Nota Fiscal: </td>
    <td><input type="text" name="txtNota" value="<?if(isset($nota)){print($nota);}?>" ></td>
    <td>Data da Nota: </td>
    <td><input type="text" name="txtDataNota" value="<?if(isset($dataNota)){print($dataNota);}?>" ></td>
  </tr>
  <tr>
    <td>Data Recebimento: </td>
    <td><input type="text" name="txtDataRecebe" value="<?if(isset($dataRecebe)){print($dataRecebe);}?>" ></td>
    <td>Fornecedor</td>
    <td><select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linha = mysql_fetch_array($res)){
	if (isset($cod_fornecedor)){
		if ($cod_fornecedor==$linha[cod]){
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
    <td colspan="4"><div align="center">Observa&ccedil;&otilde;es:</div></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center">
      <textarea name="txtObs" cols="90" rows="4" id="txtObs"><? if(isset($obs)){print($obs);}?>
    </textarea>	
    </div></td>
  </tr>
</table>
<p>
  <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
  <? if (isset($cod)){print("<input type='hidden' name='codigo' value='$cod'>");}?>

</p>
</form>
<p align="center">&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
