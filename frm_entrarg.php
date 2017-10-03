<script type="text/javascript" src="bibliotecaAjax.js"></script>
<script>
function foco(){document.form1.txtBarcode.focus();}
var envio = 0
var cliques = 0
var cadastrados = 0
function cadastra(codBarcode,codModelo){
	if (envio == 1){alert("Aguarde, Cadastro em processamento!");return false;}
	if (!codBarcode){alert("Código de barras não preenchido!");return false;}
	var url="scr_entrarg.php?txtBarcode="+codBarcode+"&cmbModelo="+codModelo;
	requisicaoHTTP("GET",url,true);
	envio = 1;
}
function trataDados(){
	var res = ajax.responseText;
	var splitres = res.split(",");// variavel res explodida pelas virgulas assim como na função explode(",",$stringinteira) do PHP

	var msg = splitres[0];
	var modelo = splitres[1];
	var cadastrado = splitres[2];
	document.getElementById("msg").innerHTML = msg;//IMPRIME RESPOSTA DO SCR_ENTRARG
	document.getElementById("cmbModelo").value = modelo;//ALTERA MODELO CASO TENHA SIDO COLETADO UM EAN OU COD CLIENTE RECONHECIDO
	envio = 0;
	document.getElementById("txtBarcode").value = '';// LIMPA CAIXA DE TEXTO
	// CONTADORES
	cliques++;
	document.getElementById("cliques").innerHTML = cliques;
	if (cadastrado==1) {
		cadastrados++;
		document.getElementById("cadastrados").innerHTML = cadastrados;//SE HOUVE CADASTRO ATUALIZA CONTADOR
	}
	// FIM CONTADORES
}
</script>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
if (isset($_GET["codModelo"])){
	$codModelo=$_GET["codModelo"];
}?>
<html>
<head>
<title>Entrada</title>
<link href="estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-color: #FFF2EC;
}
-->
</style></head>
<body onLoad=foco() topmargin="0">
<p align="center" class="style1"><span class="caixaAZ1">Entrada de Produtos </span><br>
<blockquote>
  <H1 id="msg">
    <H1 id="msg"> <? if (isset($_GET["erro"])){print($_GET["erro"]);}?>
    </H1>
  </H1>
  <p>
    </p>
  </p>
</blockquote>
<form name="form1" method="post" action="javascript:void%200" onSubmit="cadastra(this.txtBarcode.value,this.cmbModelo.value);return false">
<div align="center">
  <table width="802" border="1">
      <tr>
        <td width="176"> Modelo
          <select name="cmbModelo" class="caixaPR1" id="cmbModelo">
            <option value=""></option>
            <?	  
$sql="select * from modelo where ativo = 1 order by descricao";
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
          </select> </td>
        <td width="469"><span class="style6">C&oacute;digo de Barras</span>
          <input name="txtBarcode" type="text" class="caixaAZ1" id="txtBarcode" tabindex="0" size="20" maxlength="30"></td>
        <td width="135"><input name="cmdEntra" type="submit" class="Titulo2" id="cmdEntra2" value="Entra"></td>
      </tr>
  </table>
  <table width="802" border="1" bgcolor="#FFF0E1">
  <tr>
  	<td width="411">Cliques: <strong id="cliques"></strong></td>
	<td width="379">Cadastrados: <strong id="cadastrados"></strong></td>
  </tr>
  </table>
</div>
</form>
</body>
</html>