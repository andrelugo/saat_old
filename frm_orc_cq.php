<?
require_once("sis_valida.php");
require_once("sis_conn.php");
// A VARIAVEL CP SÓ É SETADA QUANDO ESTE FORM É ACINADO PELO SCRIPT ORC_CP 
// qaundo ela é nula então a vabravel Barcode deve estar setada. Barcode vem do formulario sai_orc.
if (isset($_GET["cp"])){
	$cp=$_GET["cp"];
	if (isset($_GET["erro"])){
		$erro=$_GET["erro"];
		$codPeca=$_GET["codPeca"];
		$codMotivo=$_GET["motivo"];
		$codDestino=$_GET["destino"];
	}
}else{
	if (isset($_GET["barcode"])){
		$barcode=$_GET["barcode"];
		$pes=mysql_query("select cod from cp where barcode=$barcode");
		$rows=mysql_num_rows($pes);
		if($rows>0){
			$cp=mysql_result($pes,0,"cod");
		}else{
			die("<h1>Erro: Nenhum resultado encontrado para o barcode $barcode!");
		}
	}else{
		die("ERRO: Campo barcode não preenchido!");
	}
}
$forn="";
$pes=mysql_query("select modelo.linha as linha, cp.orc_cliente as orc from modelo inner join cp on cp.cod_modelo = modelo.cod where cp.cod = $cp");
$linha=mysql_result($pes,0,"linha");
$orc=mysql_result($pes,0,"orc");
if($orc<>NULL){die("ESTE PRODUTO NÃO PODE RECEBER MAIS ORÇAMENTOS POIS JÁ POSSUI NUMERO O NUMERO DE ORÇAMENTO NO CLIENTE $orc");}
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 24px}
.style4 {font-size: 12px}
.style5 {
	font-size: 72px;
	color: #CC0033;
}
body {
	background-image: url(img/fundo.gif);
}
.style6 {	font-size: 24px;
	font-weight: bold;
}
-->
</style>
</head>
<body topmargin="0">
<div align="center"><span class="style6">Or&ccedil;amento</span> do Barcode n. <?print($cp);?><br>
  <? if (isset($erro)){print("<h2><font color='red'>".$erro);}?>
</div>
<form name="form1" method="post" action="scr_orc_cq.php">
<table width="462" border="0">
      <tr>
        <td width="238" class="style4">Descri&ccedil;&atilde;o</td>
		<td width="53"><input name="Submit" type="submit" value="Inserir"></td>
        <td width="55" class="style4">: Motivo       </td>
        <td width="59" class="style4">Destino</td>
        <td width="35" class="style4">Qtdade</td>
      </tr>
      <tr>
        <td colspan="2"><span class="style4">
          <select name="cmbCodPeca" id="cmbCodPeca"  tabindex="5" >
            <option value="0"></option>
            <?
	$sql="select * from peca where cod_fornecedor=0 and linha=$linha";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Destino");
	while ($linha = mysql_fetch_array($res)){
		if (isset($codPeca)){
			if ($codPeca==$linha[cod]){
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
        </span></td>
		<td><span class="style6">
          <select name="cmbMotivo" id="select12"  tabindex="5" >
            <option value="0"></option>
            <?	  
$sql="select * from orc_motivo where ativo=1";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Destino");
while ($linha = mysql_fetch_array($res)){
	if (isset($codMotivo)){
		if ($codMotivo==$linha[cod]){
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
        </span></td>
        <td><span class="style6">
          <select name="cmbDestino" id="select13"  tabindex="5" >
            <option value="0"></option>
            <?
	$sql="select * from destino where cq=1";
	$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Destino");
	while ($linha = mysql_fetch_array($res)){
		if (isset($codDestino)){
			if ($codDestino==$linha[cod]){
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
        </span></td>
        <td><span class="style6">          <input name="txtQt" type="text" value="1" size="1" maxlength="1">
</span></td>
      </tr>
  </table>
    <span class="style1">
    <input name="txtCod" type="hidden" class="style1" value="<?if (isset($codPeca)){print($codPeca);}?>" size="6" maxlength="6">
    </span>
    <input type="hidden" name="cp" value="<?print($cp);?>">
	<input type="hidden" name="forn" value="<?print($forn);?>">
<?if (empty($dest)){?>	
    
<?}?>
</form>
  <table width="472" border="1">
    <tr>
      <td width="294"><strong>Descri&ccedil;&atilde;o</strong></td>
      <td width="49"><strong>Motivo</strong></td>
      <td width="52"><strong>Destino</strong></td>
      <td width="49"><strong>Qtdade</strong></td>
    </tr> 
<?
//Caso um item já tenha sido defino então não permitir sua exclusão e mostrar sua definição
$sql="SELECT peca.cod_fabrica AS cod, peca.descricao AS descricao, orc.qt, orc.cod AS codorc, orc_motivo.descricao AS motivo, 
destino.descricao AS destino, orc.cod_decisao AS codDescisao, cod_colab_cad
FROM orc
INNER JOIN peca ON peca.cod = orc.cod_peca
LEFT JOIN orc_motivo ON orc_motivo.cod = orc.cod_motivo
LEFT JOIN destino ON destino.cod = orc.cod_destino
WHERE orc.cod_cp = $cp";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
while ($linha = mysql_fetch_array($res)){
	$codorc=$linha["codorc"];
	$codDecisao=$linha["codDescisao"];
	$colab=$linha["cod_colab_cad"];
	if (($codDecisao==NULL || $codDecisao==0 || $codDecisao==1) && $id==$colab){
		print ("<tr class='style11'><td>$linha[descricao]</td><td>$linha[motivo]</td><td>$linha[destino]</td><td>$linha[qt]</td>
		<td><a href='scr_excui.php?codped=$codorc&cp=$cp&forn=$forn&msg=&dest=frm_orc_cq.php&tabela=orc&modelo=0'>
		<img src='img/botoes/b_drop.png' width='16' height='16' border='0'></a></td></tr>");
	}else{
		print ("<tr><td>$linha[descricao]</td> <td>$linha[motivo]</td><td>$linha[destino]</td><td>$linha[qt]</td></tr>");
	}
}
?>
</table>
</body>
</html>