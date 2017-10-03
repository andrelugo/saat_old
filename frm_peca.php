<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cod"])){
	$cod=$_GET["cod"];
	$msg="Alteração da Peça de código $cod <input name='codigo' type='hidden' value='$cod'>";
	$btn="Alterar";
	$sql=mysql_query("select * from peca where peca.cod = $cod");
	$descricao = mysql_result($sql,0,"descricao");	
	$descricao = str_replace('"','',$descricao);
	$codFab = mysql_result($sql,0,"cod_fabrica");
	$codFab = str_replace('"','',$codFab);
	$codfornecedor = mysql_result($sql,0,"cod_fornecedor");	
	$linhap = mysql_result($sql,0,"linha");
	$custo = mysql_result($sql,0,"custo");	
	$ipi = mysql_result($sql,0,"ipi");

	$simples = mysql_result($sql,0,"simples");
	$icms = mysql_result($sql,0,"icms");
	$lucro = mysql_result($sql,0,"lucro");
	$perda = mysql_result($sql,0,"perda");
	$cpmf = mysql_result($sql,0,"cpmf");
	$difIcms = mysql_result($sql,0,"dif_icms");
//$venda = mysql_result($sql,0,"venda");	
	$orcamento = mysql_result($sql,0,"orcamento");	
	$garantia = mysql_result($sql,0,"garantia");	
	$cortesia = mysql_result($sql,0,"cortesia");	
	$retornavel = mysql_result($sql,0,"retornavel");
	$pre = mysql_result($sql,0,"pre_aprova");
}else{
$sql=mysql_query("select simples,icms,lucro,perda,cpmf,dif_icms from base");
	$simples = mysql_result($sql,0,"simples");
	$icms = mysql_result($sql,0,"icms");
	$lucro = mysql_result($sql,0,"lucro");
	$perda = mysql_result($sql,0,"perda");
	$cpmf = mysql_result($sql,0,"cpmf");
	$difIcms = mysql_result($sql,0,"dif_icms");

$msg="Cadastro de Peças em Garantia";
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
.style2 {color: #000000}
.style1 {color: #FF0000}
.style3 {
	font-size: 12px;
	font-style: italic;
}
-->
</style></head>

<body>
<form name="form1" method="post" action="scr_peca.php">
<p><? print($msg)?><span class="style1"><br>
Para Casas Bahia considere perda de 1% e margem de contribui&ccedil;&atilde;o de 10%. Demais clientes considere valores default. </span></p>
  <table width="826" border="1">
    <tr>
      <td width="179">*Descri&ccedil;&atilde;o:</td>
      <td width="329"><input name="txtDescricao" type="text" id="txtDescricao" tabindex="1" value="<? if(isset($descricao)){print($descricao);}?>" size="50" maxlength="50"></td>
      <td width="184" bgcolor="#D3E2DF"><span class="style2">Or&ccedil;amento</span></td>
      <td width="106" bgcolor="#D3E2DF">
	  <input name="rdOrcamento" type="checkbox" value="1" <?if (isset($orcamento)){if ($orcamento==1){print("checked");}}?> ></td>
    </tr>
    <tr>
      <td>C&oacute;digo no Fabricante:</td>
      <td><span class="style3">
        <input name="txtCodFab" type="text" id="txtCodFab" tabindex="2" size="11" maxlength="11" value="<?if(isset($codFab)){print($codFab);}?>" > 
      Ao CADASTRAR, se a pe&ccedil;a for Or&ccedil;ada para todos os fornedores, deixe em branco ! </span></td>
      <td bgcolor="#D3E2DF"><span class="style2">Garantia </span></td>
      <td bgcolor="#D3E2DF">      <input name="rdGarantia" type="checkbox" value="1" <?if (isset($garantia)){if ($garantia==1){print ("checked");}}?>></td>
    </tr>
    <tr>
      <td>*Fabrica</td>
      <td><select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
            <option value=""></option>
<?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
		if ($codfornecedor=="0"){
			print ("<option value=0 selected> TODOS </option>");
		}else{
			print ("<option value=0> TODOS </option>");
		}
while ($linha = mysql_fetch_array($res)){
	if (isset($codfornecedor)){
		if ($codfornecedor==$linha[cod]){
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
      <td bgcolor="#D3E2DF">Retorn&aacute;vel </td>
      <td bgcolor="#D3E2DF"><input name="chkRetornavel" type="checkbox" id="chkRetornavel2" value="1" <?if (isset($retornavel)){if ($retornavel==1){print ("checked");}}?>></td>
    </tr>
    <tr>
      <td height="31">Linha</td>
      <td><select name="cmbLinha" class="style5" id="select"  tabindex="5" >
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
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
	<td>*Pre&ccedil;o Custo </td>
      <td><input name="txtCusto" type="text" id="txtCusto2" value="<?if(isset($custo)){print($custo);}?>"></td>
      <td>Orc. PR&Eacute;-APROVADO</td>
      <td><input name="chkPre" type="checkbox" id="chkPre" value="1" <?if (isset($pre)){if ($pre==1){print ("checked");}}?>></td>
	</tr>
		<tr>
	<td>*IPI</td>
      <td><input name="txtIpi" type="text" id="txtIpi" value="<?if(isset($ipi)){print($ipi);}?>"></td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
<tr bgcolor="#FFFFCC">
<td>Aliquota SIMPLES</td>
<td><input name="txtSimples" type="text" id="txtSimples" value="<? if(isset($simples)){print($simples);}?>" size="8" maxlength="6">
%</td>
<td>Perda/Incrementos/Transporte</td>
<td><input name="txtPerda" type="text" id="txtPerda" value="<? if(isset($perda)){print($perda);}?>" size="8" maxlength="6">
%</td>
</tr>
<tr bgcolor="#FFFFCC">
<td>Aliquota ICMS S&atilde;o Paulo</td>
<td><input name="txtIcms" type="text" id="txtIcms2" value="<? if(isset($icms)){print($icms);}?>" size="8" maxlength="6">
%</td>
<td>CPMF</td>
<td><input name="txtCpmf" type="text" id="txtCpmf" value="<? if(isset($cpmf)){print($cpmf);}?>" size="8" maxlength="6">
% </td>
</tr>
<tr bgcolor="#FFFFCC">
  <td>Margem de Lucro </td>
  <td><input name="txtLucro" type="text" id="txtLucro" value="<? if(isset($lucro)){print($lucro);}?>" size="8" maxlength="6">
% </td>
  <td>Diferen&ccedil;a de ICMS</td>
<td><input name="txtDifIcms" type="text" id="txtDifIcms" value="<? if(isset($difIcms)){print($difIcms);}?>" size="8" maxlength="6">
%</td>
</tr>
</table>
  <p>
    <input name="cmdEnviar" type="submit" id="cmdEnviar2" value="<?print($btn)?>">
     <? if (isset($cod)){print("<input type='hidden' name='codigo' value='$cod'>");}?>
</p>
</form>
<p>Campos marcados com &quot;*&quot; (asterisco) s&atilde;o de preenchimento obrigat&oacute;rio!</p>
<p>ATEN&Ccedil;&Atilde;O: Pense antes de concluir o cadastro de uma pe&ccedil;a pois esta tabela possuir&aacute; um numero elevado de itens inviabilizando a corre&ccedil;&atilde;o futura de itens mal cadastrados!</p>
<p>Todos os campos devem ser cuidadosamente preenchidos </p>
<p>OR&Ccedil;AMENTO: deve ser usado somente para partes pl&aacute;sticas(gabinetes), controle remoto, cabos, antenas e acess&oacute;rios<br>
  GARANTIA: deve ser marcado para pe&ccedil;as funcionais que n&atilde;o s&atilde;o cobradas do cliente como resistores capacitores, diodos, unidade optica, etc <br>
  RETORN&Aacute;NEL: deve ser marcado em pe&ccedil;as que o fabricante exije devolu&ccedil;&atilde;o fisica da pe&ccedil;a usada como unidade otica e pci </p>
<p>PR&Eacute;-APROVADO: &Eacute; marcado para todos os itens cujo valor &eacute; automaticamente aprovado pelo cliente, facilitando o processo de libera&ccedil;&atilde;o de produtos no sistema SAAT II </p>
<p>O estoque minimo &eacute; a m&eacute;dia de consumo da pe&ccedil;a em 20 dias. (tempo m&aacute;ximo de ressuprimento X consumo m&eacute;dio di&aacute;rio dos ultimos 30 dias) </p>
</body>
</html>
