<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	background-image: url(img/FUNDO.GIF);
}
-->
</style><body>
 <p align="center">	Cadastro de Ordens de Servi&ccedil;os administradas autom&aacute;ticamente pelo sistema SAAT II
</p>
 <p>&nbsp; </p>
<div align="center">
  <form name="form1" method="post" action="scr_os.php">
    <p>Fornecedor:
      <select name="cmbFornecedor" class="style5" id="select6"  tabindex="5" >
              <option value="0"></option>
              <?	  
$sql="select * from fornecedor where os_auto=1";
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
</p>
    <p>Unidade: 
      <input name="txtOsIndividual" type="text" id="txtOsIndividual">
      </p>
    <p>ou    </p>
    <p> Range de:
      <input name="txtOsIni" type="text" id="txtOsIni">
&agrave;:
<input name="txtOsFim" type="text" id="txtOsFim">
</p>
    <p>Cuidado:</p>
    <p>Tenha aten&ccedil;&atilde;o ao pressionar o bot&atilde;o cadastrar ESTA OPERA&Ccedil;&Atilde;O &Eacute; IRREVERS&Iacute;VEL !!! </p>
    <p>
      <input type="submit" name="Submit" value="Cadastrar">    
        </p>
  </form><p align="center">Ordens Atualmente SEM USO No Sistema!</p>
<table width="258" border="1" align="center">
    <tr class="Cabe&ccedil;alho">
      <td width="139"><div align="center">OS </div></td>
      <td width="103"><div align="center">Fornecedor</div></td>
  </tr>
<?
$count = 0;
$dia = date("d m Y");	  
$sql="select os, fornecedor.descricao as fornecedor
from os_fornecedor inner
join fornecedor on fornecedor.cod = os_fornecedor.cod_fornecedor
where usada=0
order by fornecedor";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[os] </td><td> $linha[fornecedor] </td></tr>");
		$count++;
}
?>
  <tr><td class="style3">TOTAL</td><td class="style3"><? print("$count");?></td></tr>
</table><p>  <a href="frm_os.php" tabindex="1" accesskey="N">Novo Cadastro</a> * 

</div>
</body>
</html>

