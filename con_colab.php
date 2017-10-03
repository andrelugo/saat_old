<html>
<head>
<title></title>
<style type="text/css">
<!--
.style1 {
	font-size: 24px;
	font-weight: bold;
	color: #0000FF;
}
.style2 {color: #0000FF}
.style3 {
	color: #000000;
	font-weight: bold;
}
body {
	background-color: #CCCCCC;
	background-image: url(img/fundoadm.gif);
}
-->
</style>
</head>
<body>
<span class="style1">Clique sobre o cadastro de um colaborador para fazer altera&ccedil;&otilde;es</span>
<table width="800" border="1">
  <tr><td></td>
    <td class="style2"><span class="style3">Código</span></td>
    <td><strong> ADM</strong></td>	
    <td width="50"><strong>Nome</strong></td>
    <td><strong>Login</strong></td>
    <td><strong>Fone Residencia</strong></td>
    <td><strong>Fone Celular</strong></td>
    <td><strong>Cargo</strong></td>
    <td><strong>Tipo de Contrato</strong></td>
    <td><strong> Fundo</strong></td>
  </tr>
<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$sql="
select rh_user.cod as codi,nome,login,telresidencia,telcelular,rh_contrato.descricao as tipocontrato,rh_cargo.descricao as cargo,bgcolor,adm
from rh_user
inner join rh_contrato on rh_contrato.cod = rh_user.tipocontrato
inner join rh_cargo on rh_cargo.cod = rh_user.cargo
order by nome
";
$res=mysql_db_query ("$bd",$sql,$Link) or die ("Erro na string SQL de seleção de colaboradores");
$count=0;
while ($linha = mysql_fetch_array($res)){
	$count++;
	if ($linha["adm"]==1){
		$adm="<font color=red>Sim</font>";
	}else{
		$adm="Não";
	}
		print ("<tr>
			    <td>$count</td><td>$linha[codi]</td>");
		print ("<td>$adm</td>");		
		print ("<td><a href='frm_colab.php?cod=$linha[codi]'>$linha[nome]</a></td>");
		print ("<td>$linha[login]</td>");		
		print ("<td>$linha[telresidencia]</td>");		
		print ("<td>$linha[telcelular]</td>");		
		print ("<td>$linha[cargo]</td>");		
		print ("<td>$linha[tipocontrato]</td></a>");	
		print ("<td  bgcolor='$linha[bgcolor]'></td>");		
		print ("<tr>");
}	
?>

</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
