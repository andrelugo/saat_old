<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cod"])){
	$cod=$_GET["cod"];
	$msg="Alteração do Produto de código $cod <input name='codigo' type='hidden' value='$cod'>";
	$btn="Alterar";
	$sql=mysql_query("select * from modelo where modelo.cod = $cod");

	$descricao = mysql_result($sql,0,"descricao");	
	$tipo = mysql_result($sql,0,"tipo");
	$cod_fornecedor = mysql_result($sql,0,"cod_fornecedor");	
	$marca = mysql_result($sql,0,"marca");
	$linhap = mysql_result($sql,0,"linha");
	$linhaer = mysql_result($sql,0,"cod_expressao_regular");
	$meta = number_format(mysql_result($sql,0,"meta"), 2, ',', '.');
	
	$tx_index = number_format(mysql_result($sql,0,"tx_mo"), 2, ',', '.');

	$codnoFornecedor = mysql_result($sql,0,"cod_produto_fornecedor");
	$codnoCliente = mysql_result($sql,0,"cod_produto_cliente");
	$ean = mysql_result($sql,0,"ean");
	$ativo = mysql_result($sql,0,"ativo");
	$percentual = number_format(mysql_result($sql,0,"perc_aprova"), 2, ',', '.');
	$vlProduto = number_format(mysql_result($sql,0,"custo_cliente"), 2, ',', '.');
	$txTec = number_format(mysql_result($sql,0,"tx_tec"), 2, ',', '.');
	$txCq = number_format(mysql_result($sql,0,"tx_cq"), 2, ',', '.');
//$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de modelos");	

}else{
$msg="Cadastro de Produto";
$btn="Cadastrar";
}
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
	background-image: url(img/fundoadm.gif);
}
-->
</style></head>

<body>
<form name="form1" method="post" action="scr_modelo.php">
<p><?print($msg)?></p>
  <table width="1192" border="1">
    <tr>
      <td width="139">*Modelo:</td>
      <td width="1037"><input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<?if(isset($descricao)){print($descricao);}?>" size="20" maxlength="13"> 
      Use sempre maiusculas e copie exatamente como descrito na embalagem do fabricante </td>
      
    </tr>
    <tr>
      <td>*Tipo:</td>
      <td><input name="txtTipo" type="text" id="txtTipo" tabindex="2" size="60" maxlength="60" value="<?if(isset($tipo)){print($tipo);}?>" > 
      Ex.: AUTO-R&Aacute;DIO, RADIO AM/FM, MICROSYSTEM, MICROCOMPUTADOR, ETC </td>
    </tr>
    <tr>
      <td>*Fornecedor</td>
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
          </select> 
      Fabricante / Importador do produto </td>
    </tr>
    <tr>
      <td height="31">*Marca</td>
      <td><input name="txtMarca" type="text" id="txtMarca" value="<?if(isset($marca)){print($marca);}?>" size="20" maxlength="12"> 
      Marca do produto </td>
    </tr>
	<tr>
	<td>*Linha</td>
      <td><select name="cmbLinha" class="style5" id="select6"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from linha";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Linha");
while ($linha = mysql_fetch_array($res)){
	if (isset($linhap)){
		if ($linhap==$linha[cod]){
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
	<td>*C&oacute;digo no Fornecedor </td>
      <td><input name="txtCodFornecedor" type="text" id="txtCodFornecedor" size="25" maxlength="30" value="<?if(isset($codnoFornecedor)){print($codnoFornecedor);}?>">       
         C&oacute;digo usado pela f&aacute;brica para identificar o produto </td>
		</tr>
	<tr>
	  <td>*C&oacute;digo no Cliente</td>
	  <td><input name="txtCodCliente" type="text" id="txtCodCliente2" size="10" maxlength="20" value="<?if(isset($codnoCliente)){print($codnoCliente);}?>"> 
	  C&oacute;digo usado pela revenda para identificar o produto </td>
	</tr>
	
	<tr>
	  <td>EAN</td>
	  <td><input name="txtEan" type="text" id="txtEan2" size="50" maxlength="50" value="<? if(isset($ean)){print($ean);}?>"> 
	  Encontrado na embalagem do produto </td>
	</tr>
	<tr>
	<td>Ativo</td>
	<td><input type="checkbox" name="ativo" value="1" <? if(isset($ativo)){if ($ativo==1){print("checked");}} ?>></td>
	</tr>
	<tr>
		<td>Validação do Número de Série</td>
		<td><select name="cmbExpressao" class="style5" id="cmbExpressao"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from expressao_regular";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Linha");
while ($linha = mysql_fetch_array($res)){
	if (isset($linhaer)){
		if ($linhaer==$linha[cod]){
		print ("<option value= $linha[cod] selected> $linha[descricao] </option>");
		}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
		}
	}else{
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
	}
}
?>
          </select> 
		Define o padr&atilde;o de digitos do n&uacute;mero de s&eacute;rie deste modelo. &Eacute; por aqui que as consist&ecirc;ncias de vers&atilde;o de produto ser&atilde;o realizadas. Preencha com consci&ecirc;ncia.</td>
</tr>

<tr>
<td>Valor do Produto</td>
<td><input name="txtVlProduto" type="text" id="txtVlProduto" size="11" maxlength="10" value="<? if(isset($vlProduto)){print($vlProduto);}?>"> 
Valor pago pelo cliente na negocia&ccedil;&atilde;o do produto - (Participa do c&aacute;lculo de viabilidade de aprova&ccedil;&atilde;o do Or&ccedil;amento) </td>
</tr>

<tr>
<td>Percentual de Aprovação</td>
<td><input name="txtPercentual" type="text" id="txtPercentual" size="5" maxlength="10" value="<? if(isset($percentual)){print($percentual);}?>">
% 
Define o limete em %(percentagem) viável para o clinte de aprovação do orçamento</td>
</tr>


<tr>
<td>Ponto de Equilíbrio Técnico</td>
<td><input name="txtTec" type="text" id="txtTec" size="10" maxlength="10" value="<? if(isset($txTec)){print($txTec);}?>"> 
Parametriza indice de ponto de equilibrio técnico</td>
</tr><tr>
<td>Ponto de Equilíbrio C.Q.</td>
<td><input name="txtCq" type="text" id="txtCq" size="10" maxlength="10" value="<? if(isset($txCq)){print($txCq);}?>"> 
Parametriza indice de ponto de equilibrio de Controle de Qualidade</td>
</tr>

	<tr>
	  <td>M.O.</td>
	  <td><input name="txtIndex" type="text" id="txtIndex" value="<? if(isset($tx_index)){print($tx_index);}?>"> 
	  Taxa de m&atilde;o-de-obra paga pelo fornecedor </td>
	</tr>
	
	<tr>
	  <td>Pontos</td>
	  <td><input name="txtMeta" type="text" id="txtMeta"  value="<?if(isset($meta)){print($meta);}else{print("1");}?>"> 
	  Usado para definir o grau de dificuldade de reparo do produto</td>
	</tr>
  </table>
  <p>
    <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
     <? if (isset($cod)){print("<input type='hidden' name='codigo' value='$cod'>");}?>
</p>
</form>
<p>Campos marcados com &quot;*&quot; (asterisco) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
</body>
</html>