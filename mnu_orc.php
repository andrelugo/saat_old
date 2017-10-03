<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-size: 36px}
body {
	background-image: url(img/fundo.gif);
}
.style2 {color: #660000}
.style4 {font-size: 18px}
.style5 {color: #FF0000}
-->
</style>
</head>
<body>
<div align="center" class="style1">
  <p class="style2">(Digita&ccedil;&atilde;o)<br>
    <a href="frm_orcarnocliente.php">Lan&ccedil;amento manual de Or&ccedil;amento em outros sistemas</a></p>
  <hr>
  <p class="style2">Emiss&atilde;o de Or&ccedil;amentos </p>
  <form name="form2" method="get" action="con_orc_individual.php"  target="_blank">
    <div align="left">Imprimir Or&ccedil;amento Individual - Barcode n&ordm; 
      <input type="text" name="txtBarcode" value="">
      <input type="submit" name="Submit2" value="Imprimir">
    </div>
  </form>
  <hr>
  <p><?
$res=mysql_query("SELECT cp.cod AS tot
FROM orc INNER 
JOIN cp ON cp.cod = orc.cod_cp INNER 
JOIN modelo ON modelo.cod = cp.cod_modelo INNER 
JOIN linha ON linha.cod = modelo.linha inner
join cliente on cliente.cod = cp.cod_cliente
WHERE cod_orc_coletivo IS NULL 
AND linha.orc_coletivo =1 and cliente.revenda=1
group by tot");
//$tot=mysql_result($res,0,"tot");
$tot=mysql_num_rows($res);

if ($tot<>0){  
?>Existem <? print($tot);?> Produtos para gerar Or&ccedil;amento Coletivo</p>
  <p class="style4 style5">Aten&ccedil;&atilde;o: Utilize o bot&atilde;o abaixo apenas no per&iacute;odo desejado para entregar or&ccedil;amentos coletivos <br>
    procure realizar or&ccedil;amentos coletivos semanalmente as sextas-feiras ou quinzenalmente dias 01 e 15 de cada m&ecirc;s
  </p>
  <form name="form1" method="get" action="scr_orc_coletivo.php">
    <div align="left">GERAR Or&ccedil;amento Coletivo
        <input type="submit" name="Submit3" value="GERAR">
        <select name="cmbCliente" class="style5" id="select6"  tabindex="5" >
            <option value="0">Selecione uma Revenda...</option>
<?	  
$sqlF="select * from cliente where revenda=1";
$resF=mysql_db_query ("$bd",$sqlF,$Link) or die ("Erro na string SQL de consulta à tabela Fornecedor");
while ($linhaF = mysql_fetch_array($resF)){
	if (isset($codcliente)){
		if ($codcliente==$linhaF[cod]){
			print ("<option value= $linhaF[cod] selected> $linhaF[descricao] </option>");
		}else{
			print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
		}
	}else{
		print ("<option value= $linhaF[cod] > $linhaF[descricao] </option>");
	}
}
?>
      </select>
    </div>
  </form>
<?
}else{
?>
	Não existem Orçamentos Coletivos à realizar
<?
}
?>
</div>
</body>
</html>
