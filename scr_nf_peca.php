<? 
require_once("sis_valida.php"); 
require_once("sis_conn.php"); 
$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];

if ($_POST["cmbFornecedor"]){$fornecedor=$_POST["cmbFornecedor"];}else{die("<h1>Fornecedor não selecionado!");}
if ($_POST["txtNf"]){$desNf=$_POST["txtNf"];}else{die("<h1>Nota Fiscal não preenchida!");}

if (isset($_POST["radiobutton"])){$layout=$_POST["radiobutton"];}else{die("<h1>Lay Out do arquivo não seletionado!");}

$txtIni=$_POST["txtIni"];
$txtFim=$_POST["txtFim"];

$sql="select cod from nf_rec_peca where descricao = $desNf and cod_fornecedor = $fornecedor";
$res=mysql_query($sql);
$row=mysql_num_rows($res);
if($row==0){
	die("Nota Fiscal não cadastrada!");
}else{
	$nf=mysql_result($res,0,"cod");
}
$arquivo=file($nometemp);
$linhas=count($arquivo);

//die($layout);
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
<table width="799" border="1" align="center">
<tr>
<td width="27">Item</td>
<td width="79">Cod. Peça</td>
<td width="169">Descrição</td>
<td width="116">OS</td>
<td width="374">Status - Cad/O.S.</td>
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
		if ($numcolunas==3){
			if ($linha[0]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Código da peça vazia!";}else{$codPeca=$linha[1];}
			if ($linha[2]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$tamOSA = strlen($osA); // Tamanho da Os inteira

			$os1 = strtok($osA,"-");// Corta a OS separado por traço exibindo somente a primira parte da OS
			
			$tamOS1 = strlen($os1); // Tamanho da primeira parte da OS
			$tamOS1++; //aumenta o traço para cortar corretamente abaixo
			$os2 = substr($osA,$tamOS1,$tamOSA);
			
			$os1 = substr($os1,$txtIni,$txtFim);// CORTA O.S. PARA OS CASOS NOVA DATA		
			
			$valor1=trim($linha[2]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor like '%$os1%'";
			$msg=" OS $os1";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}

	if($layout=="osi"){
		if ($numcolunas==3){
			if ($linha[0]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Código da peça vazia!";}else{$codPeca=$linha[1];}
			if ($linha[2]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$tamOSA = strlen($osA); // Tamanho da Os inteira

			$os1 = strtok($osA,"-");// Corta a OS separado por traço exibindo somente a primira parte da OS
			
			$tamOS1 = strlen($os1); // Tamanho da primeira parte da OS
			$tamOS1++; //aumenta o traço para cortar corretamente abaixo
			$os2 = substr($osA,$tamOS1,$tamOSA);
			
			$os1 = substr($os1,$txtIni,$txtFim);// CORTA O.S. PARA OS CASOS NOVA DATA		
			
			$valor1=trim($linha[2]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor like '%$os1%' and item_os_fornecedor='$os2'";
			$msg=" OS $os1 e Item $os2";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}


	if($layout=="barcode"){
		if ($numcolunas==3){
			if ($linha[0]==""){$erro="ERRO: Colna BARCODE vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna Código da peça vazia!";}else{$codPeca=$linha[1];}
			if ($linha[2]==""){$erro="ERRO: Colna Valor vazia!";}
			$osA = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa
			$os = substr($osA,$txtIni,$txtFim);
			
			$valor1=trim($linha[2]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="barcode like '$os'";
			$msg=" Barcode $os";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}
	if($layout=="item"){
		if ($numcolunas==4){
			if ($linha[0]==""){$erro="ERRO: Colna Item vazia!";}
			if ($linha[1]==""){$erro="ERRO: Colna OS vazia!";}
			if ($linha[2]==""){$erro="ERRO: Colna Código da peça vazia!";}else{$codPeca=$linha[2];}
			if ($linha[3]==""){$erro="ERRO: Colna Valor vazia!";}

			$itm = trim($linha[0]); // Elimina possíveis espaços antes e depois da variavel os completa			
			$os1 = trim($linha[1]); // Elimina possíveis espaços antes e depois da variavel os completa
			$valor1=trim($linha[3]);
			$valor2=str_replace($errados,"",$valor1);//Substitui todos os caracteres errados por "" nada...
			$valor=str_replace(",",".",$valor2);//Substitui virgula por ponto
			$where="os_fornecedor='$os1' and item_os_fornecedor='$itm'";
			$msg=" OS $os1 e Item $itm";
		}else{	
			$erro="<h1>O arquivo $nome possui $numcolunas colunas <br>Tamanho diferente do Lay-out!";
		}
	}

	if (empty($erro)){
		$sql="select pedido.cod as pedido, pedido.cod_nf_rec_peca as nf,pedido.data_troca as data_troca,modelo.cod_fornecedor as fornecedor
		from pedido inner join
		cp on cp.cod = pedido.cod_cp inner join 
		peca on peca.cod = pedido.cod_peca inner join
		modelo on modelo.cod = cp.cod_modelo
		where $where and peca.cod_fabrica = $codPeca";

		$res=mysql_query($sql) or die(mysql_error()."<br> $sql");
		$row=mysql_num_rows($res);
		if ($row==0){
			$status="<font color=blue>ERRO: Nenhum resultado encontrado para $msg!!!<br> $sql </font>";
		}else{
			$cad="AG";
			$nn=0;// Como pode haver mais de um pedido da mesma peça para a mesma O.S. então vamos testar peça a peça para encontrar aque deve ser cadastrada agora!!!
			while($cad=="AG"){// Enquanto ele não cadastrar uma peça a variavel cad fica setada como AG após cadastrado ela muda, fazendo o script partir p/ a próx. peça
				$nfPesquisada=mysql_result($res,$nn,"nf");
				$codPedido=mysql_result($res,$nn,"pedido");
				$dtTroca=mysql_result($res,$nn,"data_troca");
				$fornecedorOS=mysql_result($res,$nn,"fornecedor");
				if ($nfPesquisada == NULL){
					$status="Cadastrada!";
					$cad="OK";
				}else{
					if($nn+1<$row){
						$cad="AG";//Continua no Looping pois ainda há chances de ser a próxima peça... já que tem o mesmo código
					}else{
						if ($nfPesquisada == $nf){
							$status="OK Anterior - Mesmo extrato";
							$cad="Não Precisa";//Sai do Looping sem reescrever no banco
						}else{
							$status="Erro: Esta peça já foi recebida através de Nota fiscal anterior";
							$cad="Erro";
						}
					}
				}
				if($fornecedorOS<>$fornecedor){
					$status="<H1>Esta Ordem de Serviços foi localizada contudo é de fornecedor diferente do cadastrado na Nota Fiscal (Situação rara, pois a peça tb tem o mesmo código)!</H1>";
					$cad="Erro";//SAI DO LOOPING... NÃO SALVA
				}
				if($dtTroca <> NULL){
					$status="$status <strong><br>Atenção o técnico informou em sua planilha que esta peça já foi trocada em $dtTroca</strong>";
				}
				if($cad=="OK"){
					$sql="update pedido set cod_nf_rec_peca='$nf',valor_nf='$valor' where pedido.cod='$codPedido'";
					mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
				}
				$nn++;
			}//fim while
		}
?>
<tr>
<td><? print($nlinha);?></td>
<TD><? print($linha[1]);?></TD>
<TD>&nbsp;</TD>
<td><? print($linha[0]);?></td>
<td><? print($status." ".$valor);?></td>
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
<p><a href="frm_nf_peca.php">Voltar</a></p>
</body>
</html>