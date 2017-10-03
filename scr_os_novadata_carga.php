<?
require_once("sis_valida.php");
require_once("sis_conn.php"); 

$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];

$arquivo=file($nometemp);
$linhas=count($arquivo);
?>
<html><head><title></title><link href="estilo.css" rel="stylesheet" type="text/css"></head><body>
<hr>
<table width="590" border="1" align="center">
  <tr>
    <td width="159"> Nome do Arquivo :</td>
    <td width="415"><? print($nome);?></td>
  </tr>
  <tr>
    <td> Nome Temporário :</td>
    <td><? print($nometemp);?></td>
  </tr>
  <tr>
    <td>Tipo do Arquivo :</td>
    <td><? print($tipo);?></td>
  </tr>
  <tr>
    <td>Tamanho do Arquivo : </td>
    <td><? print($tamanho);?></td>
  </tr>
  <tr>
    <td>Numero de linhas :</td>
    <td><? print($linhas);?></td>
  </tr>

</table>
<hr>
<table border="1" align="center">
<tr>
<td width="37">Status</td>
<td width="48">CP</td>
<td width="48">Chamado</td>
</tr>
<?
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$nlinha=$n+1;
	$linha=explode("\t",$arquivo[$n]);
	$numcolunas=count($linha);
	// Consistencia dos dados preenchidos nos Campos
	if ($numcolunas<>2){$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";	}
	if ($linha[0]==""){$erro="Controle de produção não preenchido";}
	if ($linha[1]==""){$erro="Chamado não preenchido";}

	$tamanho=strlen($linha[1]);
	if ($tamanho>15){$erro="Tamanho do Chamado maior que 15 provavelmente esta seja uma mensagem de erro enviada pela Nova Data<br>
	Mensagem : <font color=black>$linha[1]</font> Entre em contato para saber o que houve e recarrege esta OS em outro arquivo ou manualmente!";}

	if (empty($erro)){
//	Pode ocorrer de o numero de um chamado repetir, ou seja, estar cadastrado em outro cp
//	atualizar cadastrar
//	cp não existir
		$res=mysql_query("select cp.cod as cp from cp where os_fornecedor='$linha[1]'");
		$row=mysql_num_rows($res);
		if ($row==0){
			$res=mysql_query("select cod from cp where cod='$linha[0]'");
			$row=mysql_num_rows($res);
			if ($row<>0){
				$status="CADASTRADA!";
				$sql="update cp set os_fornecedor='$linha[1]' where cp.cod='$linha[0]'";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
			}else{
				$status="ERRO: CP informado não exste!!!";
			}
		}else{
			$cp=mysql_result($res,0,"cp");;
			if ($cp==$linha[0]){
				$status="OK Cadastro anterior!";
			}else{
				$satus="ERRO: O Chamado $linha[1] já está cadastrado para o controle de produção $cp";
			}
		}
?>
<tr>
<TD><? print($status);?></TD>
<TD><? print($linha[0]);?></TD>
<TD><? print($linha[1]);?></TD>
<?
	}else{
?>
<tr><td colspan="11"><font color="#FF0000"><? print ("Erro Linha $nlinha: $erro");?></font></td></tr>
<?
	}
}
?>
</table>
<hr>
<p><a href="frm_os_novadata.php">Voltar</a></p>
</body>
</html>