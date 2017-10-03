<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><html>
<head>
<title></title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {color: #FF0000}
-->
</style>
</head>
<body>
<p>
<center> 
<h1>CARGA DA TABELA DE PE&Ccedil;AS </h1>
<p>Utilize este m&eacute;todo somente para fornecedor que utilizem o TELECONTROL </p>
<form name="upload" action="scr_peca_carga.php" method="post" enctype="multipart/form-data" onsubmit=""> 
  <p class="style1">Para Casas Bahia considere perda de 1% e margem de contribui&ccedil;&atilde;o de 10%. Demais clientes considere valores default. </p>
  <table width="800" border="1">
    <tr>
      <td width="195">Aliquota SIMPLES</td>
      <td width="226"><input name="txtSimples" type="text" id="txtSimples2" value="11.7" size="8" maxlength="6">
%</td>
      <td width="357">
          <div align="left">Imposto progressivo majorado em 50% para Servi&ccedil;os </div></td>
    </tr>
    <tr>
      <td>Aliquota ICMS S&atilde;o Paulo</td>
      <td><input name="txtIcms" type="text" id="txtIcms" value="3.1008" size="8" maxlength="6">
%</td>
      <td>
        <div align="left">Imposto sobre vendas (Circula&ccedil;&atilde;o de Mercadorias)</div></td>
    </tr>
    <tr>
      <td>Margem de Lucro </td>
      <td><input name="txtLucro" type="text" id="txtLucro2" value="35" size="8" maxlength="6">
% </td>
      <td>
        <div align="left">Margem de Contribui&ccedil;&atilde;o do Produto           </div></td>
    </tr>
    <tr>
      <td>Diferen&ccedil;a de ICMS</td>
      <td><input name="txtDifIcms" type="text" id="txtDifIcms3" value="6" size="8" maxlength="6">
%</td>
      <td>Diferencial de imposto entre S&atilde;o Paulo e outros estados </td>
    </tr>
    <tr>
      <td>CPMF</td>
      <td><input name="txtCpmf" type="text" id="txtCpmf2" value="0.38" size="8" maxlength="6">
% </td>
      <td>
        <div align="left">Imposto sobre transa&ccedil;&otilde;es financeiras           </div></td>
    </tr>
	<tr>
	<td>Perda/Incrementos/Transporte</td>
	<td><input name="txtPerda" type="text" id="txtPerda" value="5" size="8" maxlength="6">
%</td>
	<td>Margem de perda de materiais e quebra de estoque </td>
	</tr>
    <tr>
      <td>Linha</td>
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
      <td>
        <div align="left">Linha de produtos a que se aplica a tabela carregada</div></td>
    </tr>
	    <tr>
      <td>Forcenedor</td>
      <td><select name="cmbFornecedor" class="style5" id="select2"  tabindex="5" >
        <option value="0"></option>
        <?	  
$sql="select * from fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
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
      <td>Fabricante do produto representado </td>
    </tr>
  </table>
  <p>Digite o caminho, nome (com extensão) do arquivo:
    <input name="txtArquivo" type="file" id="txtArquivo" size="40"> 
    </p>
  <p>
    <input name="mup" type="checkbox" value="1" checked>
    Refazer tabela de relacionamento entre modelo e pe&ccedil;as (mup) para a tabela carregada no arquivo acima. <br>
      <br> 
      <input type="submit" name="enviar" value="Enviar arquivo"> 
    </p>
</form> 
<p>Lay-Out do Arquivo (Carga completa) </p>
<table width="858" border="1">
  <tr>
    <td width="92"><strong>C&oacute;d. Produto na F&aacute;brica</strong></td>
    <td width="83"><strong>C&oacute;digo da Pe&ccedil;a </strong></td>
    <td width="77"><strong>Descri&ccedil;&atilde;o da Pe&ccedil;a </strong></td>
    <td width="59"><strong>Pre&ccedil;o </strong></td>
    <td width="91"><strong>IPI</strong></td>
    <td width="68"><strong>Pr&eacute;-Aprovado</strong></td>
    <td width="79"><strong>Or&ccedil;amento</strong></td>
    <td width="63"><strong>Garantia</strong></td>
    <td width="77"><strong>Retorn&aacute;vel</strong></td>
    <td width="105"><strong>N Linha</strong></td>

  </tr>
  <tr>
    <td>Vide tabela Fabricante </td>
    <td>Tabela Fabricante </td>
    <td>&nbsp;</td>
    <td>Custo da Pe&ccedil;a </td>
    <td>IPI aplicado sobre o custo </td>
    <td colspan="4">Se sim preencher com SIM ou X se n&atilde;o deixar em Branco </td>
    <td colspan="4">Deve conter o numero das linhas em ordem crescente </td>
    </tr>
</table>
<p>Lay-Out do Arquivo (ATUALIZA&Ccedil;&Atilde;O DE PRE&Ccedil;OS) </p>
<table width="558" border="1">
  <tr>
    <td width="90"><strong>C&oacute;d. Produto na F&aacute;brica</strong></td>
    <td width="81"><strong>C&oacute;digo da Pe&ccedil;a </strong></td>
    <td width="77"><strong>Descri&ccedil;&atilde;o da Pe&ccedil;a </strong></td>
    <td width="57"><strong>Pre&ccedil;o </strong></td>
    <td width="88"><strong>IPI</strong></td>
    <td width="125"><strong>N Linha</strong></td>
    </tr>
  <tr>
    <td>Vide tabela Fabricante </td>
    <td>Tabela Fabricante </td>
    <td>&nbsp;</td>
    <td>Custo da Pe&ccedil;a </td>
    <td>IPI aplicado sobre o custo </td>
    <td colspan="4">Deve conter o numero das linhas em ordem crescente </td>
    </tr>
</table>
<p>&nbsp;</p>
</center> 

</body>
</html>
