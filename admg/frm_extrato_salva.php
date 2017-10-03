<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$extrato=$_GET["cod"];
?><html>
<head>
<title>SAAT</title>
<body>
<p align="center" class="style1">Confirme o envio dos REGISTROS DE SA&Iacute;DA abaixo para savlar este extrato </p>
  <table width="254" border="1" align="center">
    <tr>
      <td width="130"><strong>Registro</strong></td>
      <td width="81"><strong>Envio</strong></td>
    </tr>
    <?
$sql="SELECT fechamento_reg.registro as registro,extrato_mo.descricao as envio,fechamento_reg.cod as codregistro
FROM cp
inner join fechamento_reg on fechamento_reg.cod = cp.cod_fechamento_reg
left join extrato_mo on extrato_mo.cod = fechamento_reg.cod_extrato_mo_envio
where cp.cod_extrato_mo = $extrato
GROUP BY registro,envio,codregistro
order by registro";
$res=mysql_query($sql) or die(mysql_error()."<br>$sql");
while($linha=mysql_fetch_array($res)){
	?><tr><td><? 
		print($linha["registro"]);
	?></td><td><?
		if($linha['envio']==""){
			print("<a href='scr_salva_extrato_envio.php?extrato=$extrato&registro=$linha[codregistro]'>Confirmar Neste!</a>");
		}else{
			print($linha["envio"]);
		}
	?></td></tr><?
}
?>
</table>
</body>
</html>