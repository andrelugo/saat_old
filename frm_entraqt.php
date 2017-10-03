<?
require_once("sis_valida.php");
require_once("sis_conn.php");

if (isset($_GET["codModelo"])){
		$codModelo=$_GET["codModelo"];
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style3 {color: #FF0000; font-weight: bold; }
.style6 {font-size: 24px}
body {
	background-image: url(img/fundoadm.gif);
}
-->
</style>
</head>

<body>
<div align="center">Entrada por Quantidade de Modelo
</div>
<form name="form1" method="post" action="scr_entraqt.php">
  <div align="center">
    <table width="524" border="1">
      <tr>
        <td width="167">Modelo</td>
        <td width="341"><select name="cmbModelo" id="cmbModelo">
          <option value=""></option>
<?	  
$sql="select * from modelo";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à tabela Modelo");
while ($linha = mysql_fetch_array($res)){
		print ("<option value= $linha[cod] > $linha[descricao] </option>");
}
?>
        </select></td>
      </tr>
      <tr>
        <td>Quantidade</td>
        <td><input name="txtQt" type="text" id="txtQt" size="4" maxlength="4"></td>
      </tr>
    </table>  
  </div>
  <p align="center">
    <input name="cmdEntra" type="submit" id="cmdEntra" value="Enviar">
  </p>
</form>
<div align="center">
  <hr>
  <br>
  <span class="style6">  Entrada de Hoje <?print(date("d/m/Y"))?></span>  <table width="200" border="1">
    <tr>
      <td>Modelo</td>
      <td>Quantidade</td>
    </tr>
  <?
$count = 0;
$dia = date("d m Y");	  
$sql="select count(cp.cod) as qt,modelo.descricao
from cp inner join modelo on modelo.cod = cp.cod_modelo
where DATE_FORMAT(data_entra, '%d %m %Y') = '$dia'
group by modelo.descricao";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de consulta à entrada de produtos");
while ($linha = mysql_fetch_array($res)){
		print ("<tr><td> $linha[descricao] </td><td> $linha[qt] </td></tr>");
		$count = $count+$linha["qt"];
}
?>
  <tr><td class="style3">TOTAL</td><td class="style3"><?print("$count");?></td></tr>
  </table>
</div>
</body>
</html>
