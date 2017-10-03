<? 
require_once("sis_valida.php"); 
require_once("sis_conn.php"); 
$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];

$extrato=$_POST["txtCod"];
if (isset($_POST["radiobutton"])){$layout=$_POST["radiobutton"];}else{die("<h1>Lay Out do arquivo não seletionado!");}

$txtIni=$_POST["txtIni"];
$txtFim=$_POST["txtFim"];

$sql="select * from extrato_mo where cod = $extrato";
$res=mysql_query($sql);
$fornecedorEX=mysql_result($res,0,"cod_fornecedor");

$arquivo=file($nometemp);
$linhas=count($arquivo);

$sqlCliente=mysql_query("select cliente_exclusivo as cod from base");
$codCliente=mysql_result($sqlCliente,0,"cod");

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
<table width="662" border="1" align="center">
<tr>
<td width="27">Item</td>
<td width="101">OS</td>
<td width="512">Status</td>
</tr>
<?
$errados = array("-","!","'","*","(",")","_","+","[","]","{","}","^","~",";",":"," ","%","/","//","\\",'"',"R","r","$");// Limpar do valor
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$nlinha=$n+1;
	$linha=explode("\t",$arquivo[$n]);
	$numcolunas=count($linha);
	// Consistencia dos dados preenchidos nos Campos
	if($layout=="os"){
		if ($numcolunas==2){
			if ($linha[0]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$tamOSA = strlen($osA); // Tamanho da Os inteira

			$os1 = strtok($osA,"-");// Corta a OS separado por traço exibindo somente a primira parte da OS
			
			$tamOS1 = strlen($os1); // Tamanho da primeira parte da OS
			$tamOS1++; //aumenta o traço para cortar corretamente abaixo
			$os2 = substr($osA,$tamOS1,$tamOSA);
			
			$os1 = substr($os1,$txtIni,$txtFim);// CORTA O.S. PARA OS CASOS NOVA DATA		
			
			$valor1=trim($linha[1]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor like '%$os1%'";
			$msg=" OS $os1";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}

	if($layout=="osi"){
		if ($numcolunas==2){
			if ($linha[0]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$tamOSA = strlen($osA); // Tamanho da Os inteira

			$os1 = strtok($osA,"-");// Corta a OS separado por traço exibindo somente a primira parte da OS
			
			$tamOS1 = strlen($os1); // Tamanho da primeira parte da OS
			$tamOS1++; //aumenta o traço para cortar corretamente abaixo
			$os2 = substr($osA,$tamOS1,$tamOSA);
			
			$os1 = substr($os1,$txtIni,$txtFim);// CORTA O.S. PARA OS CASOS NOVA DATA		
			
			$valor1=trim($linha[1]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor like '%$os1%' and item_os_fornecedor='$os2'";
			$msg=" OS $os1 e Item $os2";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}


	if($layout=="barcode"){
		if ($numcolunas==2){
			if ($linha[0]==""){$erro="ERRO: Colna BARCODE vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$os = substr($osA,$txtIni,$txtFim);
			
			$valor1=trim($linha[1]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="barcode like '$os'";
			$msg=" Barcode $os";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}
	if($layout=="item"){
		if ($numcolunas==3){
			if ($linha[0]==""){$erro="ERRO: Colna Item vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[2]==""){$erro="ERRO: Colna Valor vazia!";}

			$itm = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa			
			$os1 = trim($linha[1]); // Elimina possíveis espaços antes e depois da variavel os completa
			$valor1=trim($linha[2]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor='$os1' and item_os_fornecedor='$itm'";
			$msg=" OS $os1 e Item $itm";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}

	if (empty($erro)){
		$sql="select cp.cod as cp, cp.cod_extrato_mo as extrato, data_sai, modelo.cod_fornecedor as fornecedor
		from cp 
		inner join modelo on modelo.cod = cp.cod_modelo
		where $where";

		$res=mysql_query($sql) or die(mysql_error()."<br> $sql");
		$row=mysql_num_rows($res);
		if ($row==1){
			$extratoS=mysql_result($res,0,"extrato");
			$cp=mysql_result($res,0,"cp");
			$dtsai=mysql_result($res,0,"data_sai");
			$fornecedorOS=mysql_result($res,0,"fornecedor");
			if($fornecedorOS<>$fornecedorEX){
				$status="Esta Ordem de Serviços foi localizada contudo é de fornecedor diferente do cadastrado no extrato!";
			}else{
				if ($dtsai == NULL && $codCliente==1) {//NÃO PERMITE GRAVAR EXTRATO APENAS PARA OS ATENDIMENTOS CASA BAHIA. PARA OS OUTROS CLIENTE VOU COLOCAR A DATA DE SAÍDA LOGO ABAIXO
					$status="<font color=red>ERRO GRAVE(P/ CLIENTE CASA BAHIA)!!!  OS sem data de saída não poderia aparecer em extrato .Analise este caso cautelozamente e informe a Gerência Urgentemente! Antes de enviar o extrato! Pois não haverá registro de saída para este caso <font>";
					// Não possso cadastrar extrato agora.... se a OS não saiu ainda não tem registro de saída então não poderá 
					// ser comprovado o consero do produto para devida cobrança APENAS NOS CASOS CASA BAHIA.
				}else{
					if ($extratoS==NULL){
						$status="CADASTRADA!";
					}else{
						if ($extratoS==$extrato){
							$status="OK Anterior - Mesmo extrato";
						}else{
							$status="ATENÇÃO:  Extrato para esta OS ATUALIZADO de $extratoS para $extrato! verifique o porque desta OS aparecer em mais de um extrato!";
						}
					}
					if($dtsai == NULL){
						$status="FECHADA E CADASTRADA!";
						$sql="update cp set cod_extrato_mo='$extrato',valor_gar='$valor',
						data_sai=now(),cod_fechamento_reg=1,folha_cq=1,cod_colab_reg_sai=$id,cod_destino=11,cod_cq=$id,
						obs='ESTA ORDEM DE SERVIÇOS FOI FINALIZADA NA SAAT NO MOMENTO DA CARGA DO EXTRATO DO FORNECEDOR. ISTO SIGNIFICA QUE POR ALGUM ERRO OPERACIONAL ELA FOI FINALIZADA SOMENTE NO SISTEMA DO FABRICANTE!'
						where cp.cod='$cp'";
						mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());					
					}else{
						$sql="update cp set cod_extrato_mo='$extrato',valor_gar='$valor' where cp.cod='$cp'";
						mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
					}
				}
			}
		}else{
			if ($row==0){
					$status="<font color=blue>ERRO: Nenhum resultado encontrado para $msg!!!<br> $sql </font>";
			}else{
				if ($row>1){
					//Colocar aqui código que trate de duas ordens com o mesmo numero porem para fornecedores diferentes....
					// Por enquanto não há tratamento para isto 02/11/2006
					$status="<font color=red>ERRO: $row Encontrados para a os $os1 corrija o arquivo de carga e recarregue!!!</font>";
				}
			}
		}
?>
<tr>
<td><? print($nlinha);?></td>
<TD><? print($linha[0]);?></TD>
<TD><? print($status." ".$valor);?></TD>
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
<p><a href="frm_extrato_carga.php?cod=<? print($extrato);?>">Voltar</a></p>
</body>
</html>