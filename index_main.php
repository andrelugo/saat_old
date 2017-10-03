<?
require_once("sis_valida.php");
require_once("sis_conn.php");
?><html>
<head>
<title>SAAT</title>
<link href="estilo.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--
.style1 {color: #FF0000}
.style2 {font-size: 9px}
-->
</style>
</head>
<body>
<? // MENSAGEM DE AVISO SOBRE REALIZAÇÃO DE BAK-UPS NO SISTEMA -- DESABILITADA EM NOVEMBRO DE 2007 POR ESTAR NA WEB NO KINGHOST
// $sql="select datediff(now(),ultimo_backup) as dias, ultimo_backup from base";
// $res=mysql_query($sql) or die ($sql."<br> ".mysql_error());
// $dias=mysql_result($res,0,"dias");
// $ult=mysql_result($res,0,"ultimo_backup");
// if ($dias>3){
?>
<!-- <p align="center" class="Titulo2 style1">ATEN&Ccedil;&Atilde;O: Avise &agrave; administra&ccedil;&atilde;o que &eacute; necess&aacute;rio fazer<br>
  um Back-Up do banco de dados.<br>
  &Uacute;ltimo Back-Up
-->
<?
// 	print($ult);
// }
?></p>


<p align="center" class="Titulo2 style1"><em>Ranking de Performance 1&ordm;<span class="style2">s</span> Colocados do M&Ecirc;S </em></p>
<table width="823" border="1" align="center">
  <tr>
    <td width="300"><div align="center"><strong>Linha</strong></div></td>
    <td width="250"><div align="center"><strong>T&eacute;cnico</strong></div></td>
    <td width="251"><div align="center"><strong>Controle de Qualidade </strong></div></td>
  </tr>
<? $sqlLinha=mysql_query("select cod,descricao from linha where ativo=1");
while($linha=mysql_fetch_array($sqlLinha)){
?>
  <tr>
    <td><? print($linha["descricao"]);?></td>
    <td><? 
$sqlT=mysql_query("SELECT nome, count( cp.cod ) AS tot
FROM cp INNER 
JOIN rh_user ON rh_user.cod = cp.cod_tec INNER 
JOIN modelo ON modelo.cod = cp.cod_modelo
WHERE month( data_pronto ) = month( now( ) ) 
and year( data_pronto ) = year( now( ) ) 
AND modelo.linha =$linha[cod]
GROUP BY nome
ORDER BY tot DESC 
LIMIT 0 , 30 ");
$rows=mysql_num_rows($sqlT);
if($rows==0){
	print("Vazio!");
}else{
	$nome=mysql_result($sqlT,0,"nome");
	print($nome);	
}?>
</td>
    <td><? 
$sqlT=mysql_query("SELECT nome, count( cp.cod ) AS tot
FROM cp INNER 
JOIN rh_user ON rh_user.cod = cp.cod_cq INNER 
JOIN modelo ON modelo.cod = cp.cod_modelo
WHERE month( data_sai ) = month( now( ) ) 
and year( data_sai ) = year( now( ) ) 
AND modelo.linha =$linha[cod]
GROUP BY nome
ORDER BY tot DESC 
LIMIT 0 , 30 ");
$rows=mysql_num_rows($sqlT);
if($rows==0){
	print("Vazio!");
}else{
	$nome=mysql_result($sqlT,0,"nome");
	print($nome);	
}?>
	</td>
  </tr>
<?
}?>
</table>
<table width="800" border="0" align="center">
  <tr>
    <td><div align="center"><span class="Cabe&ccedil;alho"><em>
        <?
$sqlRes = mysql_query("select l1 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
print($res);
?>
    </em></span></div></td>
  </tr>
  <tr>
    <td><div align="center"><span class="Titulo2">
      "
        <?
$sqlRes = mysql_query("select l2 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
print($res);
?>
    </span></div></td>
  </tr>
  <tr>
    <td><div align="center"><span class="Titulo2">
        <?
$sqlRes = mysql_query("select l3 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
print($res);
?>
    </span></div></td>
  </tr>
  <tr>
    <td><div align="center"><span class="Titulo2">
        <?
$sqlRes = mysql_query("select l4 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
print($res);
?>
    "</span></div></td>
  </tr>
  <tr>
    <td><div align="right"><em>
        <?
$sqlRes = mysql_query("select l5 as linha from base");
$res=mysql_result($sqlRes,0,"linha");
print($res);
?>
    </em></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
  <table width="763" border="10" align="center">
    <tr>
      <td width="150" rowspan="3" colspan=""><img src="img/tecnico.gif" width="150" height="126"></td>
      <td width="419"><span class="Cabe&ccedil;alho">
<? 
$res=mysql_query("select nome from rh_user where cod = $id");
$nomea=mysql_result($res,0,"nome");
//$nomea = $_COOKIE["nome"];
$nomeb = trim($nomea); // Elimina possíveis espaços antes e depois da variavel nome completo
$nome = strtok($nomeb," ");// Corta o sobrenome separado por espaços exibindo somente a primira parte do nome
print $nome
?>&nbsp;
voc&ecirc; est&aacute; conectado ao SAAT II</span> </td>
    </tr>
    <tr>
      <td height="49"> <div align="center">Aniversariantes do M&ecirc;s: <br>
<?
$diah=date("d");
$sqlAniver=mysql_query("select cod,nome, day(data_nasce) as dia from rh_user where month(data_nasce) = month(now()) and data_demissao='000-00-00' order by dia");
$rows=mysql_num_rows($sqlAniver);
if($rows==0){
	print("Infelizmente não há aniversariantes este Mês!<br>");
}else{
	while($linhaA=mysql_fetch_array($sqlAniver)){
		$dia=$linhaA["dia"];
		$nome2=$linhaA["nome"];
		$cod=$linhaA["cod"];
		if ($diah==$dia){
			if($cod==$id){
				print("<font color='blue'><h2>PARABÉNS! $nome hoje é um dia especial!!! Muitos anos de Vida!!!</font></h2><br>");
			}else{
				print("<font color='blue'><h2>Hoje é um dia especial para $nome2</font></h2><br>");
			}
		}else{
			$dif=$dia-$diah;
			if ($dif>-8 && $dif<0){
				print("<font color='red'>$nome2 dia $dia</font><br>");
			}else{
				if ($dif>0 && $dif<8){
					print("<font color='green'><h3>$nome2 dia $dia</font></h3><br>");
				}else{
					print("$nome2 dia $dia<br>");
				}
			}
		}
	}
}
$sqlAniver=mysql_query("select cod,nome from rh_user where data_nasce='000-00-00' and data_demissao='000-00-00'");
$rows=mysql_num_rows($sqlAniver);
if($rows<>0){
	print("<font color='red'>ERRO: Existem $rows colaboradores sem data de nascimento cadastrada no sistema!!!<br>");
	while($linhaA=mysql_fetch_array($sqlAniver)){
		print("$linhaA[nome]<br>");
	}
	print("</font>");
}
?> 
      </div></td>
    </tr>
</table>
</body>
</html>
