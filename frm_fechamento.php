<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["cod"]) && $_GET["cod"]<>""){
	$cod = $_GET["cod"];
	$btn = "Alterar";
	$sql = mysql_query("select valor,obs,descricao,registro,tipo,qt_os,day(data_registro)as dia,month(data_registro)as mes,year(data_registro)as ano from fechamento_reg where cod = $cod");
	$descricao = mysql_result($sql,0,"descricao");
	$registro = mysql_result($sql,0,"registro");
	$tipo = mysql_result($sql,0,"tipo");
	$qtOs = mysql_result($sql,0,"qt_os");
	$diaReg = mysql_result($sql,0,"dia");
	$mesReg = mysql_result($sql,0,"mes");
	$anoReg = mysql_result($sql,0,"ano");
	$valor = mysql_result($sql,0,"valor");
	$obs=mysql_result($sql,0,"obs");
}else{
	$btn="Cadastrar";
}
if (isset($_GET["order"])){$order=$_GET["order"];}else{$order="order by fechamento_reg.cod desc";}
if (isset($_GET["msg"])){print($_GET["msg"]);}
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
.style1 {font-size: 24px}
-->
</style></head>

<body>
<form name="form1" method="post" action="scr_fechamento.php">
<p align="center" class="style1"> Cadastro/Altera&ccedil;&atilde;o e Encerramento de Fechamentos</p>
  <table width="801" border="1">
    <tr>
      <td width="150">*Descri&ccedil;&atilde;o:</td>
      <td colspan="3"><input name="txtDescricao" type="text" id="txtDescricao3" tabindex="1" value="<?if(isset($descricao)){print($descricao);}?>" size="100" maxlength="100"></td>
    </tr>
    <tr>
      <td>Registro:</td>
      <td width="301"><input name="txtRegistro" type="text" id="txtRegistro" tabindex="2" size="13" maxlength="10" value="<?if(isset($registro)){print($registro);}?>" ></td>
      <td width="168">Data do Registro </td>
      <td width="154">
<input name="txtDiaReg" type="text" tabindex="19" id="txtDiaReg"  value="<?if(isset($diaReg) && $diaReg <>'0'){print($diaReg);}?>" size="1" maxlength="2">
/
<input name="txtMesReg" type="text" tabindex="20" id="txtMesReg"  value="<?if(isset($mesReg) && $mesReg <>'0'){print($mesReg);}?>" size="1" maxlength="2">
/
<input name="txtAnoReg" type="text" tabindex="21" id="txtAnoReg"  value="<?if(isset($anoReg) && $anoReg <>'0'){print($anoReg);}?>" size="1" maxlength="2">
</td>
    </tr>
    <tr>
      <td>Tipo de Registro</td>
      <td><span class="style4">
        <select name="cmbTipo" class="caixaAZ1" id="select3"  tabindex="5" >
          <option value="Z"></option>
          <option value="0">Todos</option>
          <?	  
$sql="select * from destino";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta &agrave; tabela Destino");
while ($linha = mysql_fetch_array($res)){
	if (isset($tipo)){
		if ($tipo==$linha[cod]){
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
      <td>Qtdade Prodts Registro</td>
      <td><input name="txtQtOs" type="text" id="txtQtOs" tabindex="2" size="5" maxlength="4" value="<?if(isset($qtOs)){print($qtOs);}?>" ></td>
    </tr>
<tr>
<td></td>
<td></td>
<td>R$ Total do Registro</td>
<td><input name="txtValor" type="text" id="txtValor" tabindex="3" size="8" maxlength="10" value="<?if(isset($valor)){print($valor);}?>" ></td>
</tr>




    <tr>
      <td height="31">Numero do Fechamento </td>
      <td><span class="style4">
      </span></td>
      <td>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
	<tr>
		<td>Observa&ccedil;&otilde;es</td>
		<td colspan="3"><textarea name="txtObs" cols="85" rows="3" id="txtObs"><? if(isset($obs)){print($obs);}?></textarea></td>
	</tr>
  </table>
  <p align="center">  
    <input name="Envia" type="submit" id="Envia" value="<? print($btn);?>">
	<? if ($btn=="Alterar"){?>
    <input name="Envia" type="submit" id="Envia" value="Encerrar">
	<input type="hidden" name="cod" value="<? print ($cod);?>">
	<? }?>
  </p>
</form>
<hr>
<p align="center"><span class="style1">Fechamentos atualmente n&atilde;o encerrados!</span><br>
Para encerrar ou alterar um fechamento clique sobre seu c&oacute;digo. </p>
<table width="856" border="1" align="center">
  <tr>
    <td width="166">Fechamento</td>
    <td width="56">Data</td>
    <td width="69">Registro</td>
    <td width="68">Destino </td>
    <td width="90">Qt Cadastro</td>
    <td width="104">Qt Realizado </td>
    <td colspan="3"><a href="frm_fechamento.php?order=order+by+cod"<? if (isset($cod)){print("&cod=".$cod);}?>>Folha</a></td>
  </tr>
  <?
$sql="select * from fechamento_reg 
where data_fecha is null $order";
$res=mysql_query($sql) or die(mysql_error());
while ($linha=mysql_fetch_array($res)){
	$cod=$linha["cod"];
	$res2=mysql_query("select count(cod) as tot from cp where cod_fechamento_reg=$cod");
	$tot=mysql_result($res2,0,"tot");
?>
  <tr>
    <td><? print($linha["descricao"]);?></td>
    <td><? print($linha["data_abre"]);?></td>
    <td><? print($linha["registro"]);?></td>
    <td><? 
	if ($linha["tipo"]<>0){
		$coddes=$linha["tipo"];
		$res2=mysql_query("select descricao from destino where cod=$coddes");
		$desc=mysql_result($res2,0,"descricao");
		print($desc);
	}else{
		print("Varios");
	}?></td>
    <td><? print($linha["qt_os"]);?></td>
    <td><? print($tot);?> </td>
    <td width="46"><a href="frm_fechamento.php?cod=<? print($linha["cod"]);?>"> Alterar/Encerrar </a></td>
    <td width="145"><a href="frm_reg.php?codf=<? print($linha["cod"]);?>">Realizar Incluir Excluir</a>  </td>
	<? if ($tot<>0){?>
    <td width="54"><a href="frm_reg_dig.php?codf=<? print($linha["cod"]);?>">Digitar</a></td>
	<? }?>
  </tr>
  <?
}
?>
</table>
</body>
</html>