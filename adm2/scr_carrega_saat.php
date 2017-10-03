<? require_once("sis_valida.php");
require_once("sis_conn.php"); 
$nome=$_FILES['txtArquivo']['name'];
$tipo=$_FILES['txtArquivo']['type'];
$tamanho=$_FILES['txtArquivo']['size'];
$nometemp=$_FILES['txtArquivo']['tmp_name'];
//$arq=$_FILES['arquivo'];
$arquivo=file($nometemp);// função que aribui à variável o nome do arquivo com extenção
$linhas=count($arquivo);// função que atribui a variavel o numero de linhas que o arquivo contem
?>
<html><head><title></title><link href="estilo.css" rel="stylesheet" type="text/css"></head><body>
<hr>
<table width="590" border="1" align="center">
  <tr><td width="159"> Nome do Arquivo :</td><td width="415"><? print($nome);?></td></tr>
  <tr><td> Nome Temporário :</td><td><? print($nometemp);?></td></tr>
  <tr><td>Tipo do Arquivo :</td><td><? print($tipo);?></td></tr>
  <tr><td>Tamanho do Arquivo : </td><td><? print($tamanho);?></td></tr>
  <tr><td>Numero de linhas :</td><td><? print($linhas);?></td></tr>
</table>
<hr>
<table width="1007" border="1" align="center">
	<tr><td width="37">Linha</td>
	<td width="258">Descri&ccedil;&atilde;o</td>
	<td width="690">Status</td>
</tr>
<?
for ($n=0;$n<$linhas;$n++){
	$erro="";
	$coluna=explode("\t",$arquivo[$n]);
	$ncolunas=count($coluna);
	if($n==0){
		if($coluna[0]=="base"){
			$base=$coluna['1'];
			$descBase=$coluna['2'];
			$descricao=$descBase;
			$status="Carregando informações da base de dados $descricao";
		}else{
			die("<h1> Arquivo inválido! L0C0=$coluna[0]</h1>");
		}
	}
	if($coluna[0]=="f"){
		if($ncolunas<>13){
			$erro="Esta linha do arquivo está com <strong>$ncolunas</strong> colunas. Tamanho diferente do Lay-Out";
			$descricao=$coluna["3"];
		}else{
			$codl=$coluna["1"];
			$registro=$coluna["2"];
			$descricao=$coluna["3"];
			$data_registro=$coluna["4"];
			$tipo=$coluna["5"];
			$qt_os=$coluna["6"];
			$obs=$coluna["7"];
			$data_abre=$coluna["8"];
			$data_fecha=$coluna["9"];
			//$cpf_colab_abre=$coluna["10"];
				$res=mysql_query("select cod from rh_user where cpf = '$coluna[10]'")or die(mysql_error());$row=mysql_num_rows($res);
				if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[10]";}else{$cod_colab_abre=mysql_result($res,0,"cod");}
			//$cpf_colab_fecha=$coluna["11"];
				$res=mysql_query("select cod from rh_user where cpf = '$coluna[11]'")or die(mysql_error());$row=mysql_num_rows($res);
				if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[11]";}else{$cod_colab_fecha=mysql_result($res,0,"cod");}
			$valor=$coluna["12"];
		}
		if($erro<>""){
			$status="<font color='red'>".$erro."</font>";
		}else{
			$res=mysql_query("select cod from fechamento_reg where registro = '$registro' and codl = $codl")or die(mysql_error());
			$row=mysql_num_rows($res);
				if($row==0){
					$sql="insert into fechamento_reg (codl,descricao,registro,data_registro,tipo,qt_os,obs,data_abre,data_fecha,cod_colab_abre,cod_colab_fecha,valor)
					 values ('$codl','$descricao','$registro','$data_registro','$tipo','$qt_os','$obs','$data_abre','$data_fecha','$cod_colab_abre','$cod_colab_fecha','$valor')";
					mysql_query($sql) or die("Erro na inserção a tabela fechamento_reg <br>Comando: $sql".mysql_error());
					$status="REGISTRO DE SAÍDAS $registro CADASTRADO!";
				}else{
					if($row==1){
						$cod=mysql_result($res,0,"cod");
						$sql="update fechamento_reg set descricao='$descricao',data_registro='$data_registro',tipo='$tipo',qt_os='$qt_os',obs='$obs',data_abre='$data_abre',
						data_fecha='$data_fecha',cod_colab_abre=$cod_colab_abre, cod_colab_fecha=$cod_colab_fecha  where cod=$cod";
						mysql_query($sql) or die(mysql_error()."<br>Erro na atualização da tabela fechamento_reg <br>Comando: $sql");
						$status="REGISTRO DE SAÍDAS $registro ATUALIZADO!";
					}else{
						if($row>1){
							die("<h1>ATENÇÃO ERRO GRAVÍSSIMO. AVISE AO ANALISTA DO SISTEMA URGENTEMENTE QUE EXISTEM REGISTROS DUPLICADOS NA BASE DE DADOS NA TABELA FECHAMENTO_REG</h1>");
						}
					}
				}
		}
	}
	if($coluna[0]=="c"){// PROCESSA APENAS O CONTEUDO DA TABELA CP
		if($ncolunas<>36){
			$erro="Esta linha do arquivo está com <strong>$ncolunas</strong> colunas. Tamanho diferente do Lay-Out";
		}else{
			$cpl=$coluna['1'];
			$barcode=$coluna['2'];
			$cod_extrato_mo=$coluna['3'];// estes dados serão definidos pelo site adm2
			$valor_gar=$coluna['4'];// estes dados serão definidos pelo site adm2
			$cod_posicao=$coluna['5'];// estes dados serão definidos pelo site adm2 CARREGAR APENAS A PRIMEIRA VEZ
			$cod_nf_entrada=$coluna['6'];
			$data_entra=$coluna['7'];
			$data_barcode=$coluna['8'];if($data_barcode==""){$data_barcode="NULL";}else{$data_barcode="'$data_barcode'";}
			$filial=$coluna['9'];
			$data_analize=$coluna['10'];if($data_analize==""){$data_analize="NULL";}else{$data_analize="'$data_analize'";}
			$serie=$coluna['11'];
			$certificado=$coluna['12'];
			$obs=$coluna['13'];
			$data_pronto=$coluna['14'];if($data_pronto==""){$data_pronto="NULL";}else{$data_pronto="'$data_pronto'";}
			$data_sai=$coluna['15'];if($data_sai==""){$data_sai="NULL";}else{$data_sai="'$data_sai'";}
			$defeito_reclamado=$coluna['16'];
			$folha_cq=$coluna['17'];
			$os_fornecedor=$coluna['18'];
			$item_os_fornecedor=$coluna['19'];
			$carencia=$coluna['20'];
			$reprova_cq=$coluna['21'];
			$orc_cliente=$coluna['22'];
			$data_orc=$coluna['23'];if($data_orc==""){$data_orc="NULL";}else{$data_orc="'$data_orc'";}
			$total_orc=$coluna['24'];
			//$cod_colab_entra=$coluna['25'];
				$res=mysql_query("select cod from rh_user where cpf = '$coluna[25]'")or die(mysql_error());$row=mysql_num_rows($res);
				if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[25]";}else{$cod_colab_entra=mysql_result($res,0,"cod");}
			//cod_modelo=$coluna['26'];
				$res=mysql_query("select cod from modelo where descricao = '$coluna[26]'")or die(mysql_error());$row=mysql_num_rows($res);
				if($row==0){$erro="Modelo não encontrado pela decrição: $coluna[26]";}else{$cod_modelo=mysql_result($res,0,"cod");}
			//$cod_tec=$coluna['27'];
				if($coluna[27]==NULL){
					$cod_tec="";
				}else{
					$res=mysql_query("select cod from rh_user where cpf = '$coluna[27]'")or die(mysql_error());$row=mysql_num_rows($res);
					if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[27]";}else{$cod_tec=mysql_result($res,0,"cod");}
				}
			//$cod_cq=$coluna['28'];
				if($coluna[28]==NULL){
					$cod_cq="";
				}else{
					$res=mysql_query("select cod from rh_user where cpf = '$coluna[28]'")or die(mysql_error());$row=mysql_num_rows($res);
					if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[28]";}else{$cod_cq=mysql_result($res,0,"cod");}
				}
			//$cod_colab_reg_sai=$coluna['29'];
				if($coluna[29]==NULL){
					$cod_colab_reg_sai="";
				}else{
					$res=mysql_query("select cod from rh_user where cpf = '$coluna[29]'")or die(mysql_error());$row=mysql_num_rows($res);
					if($row==0){$erro="Colaborador não encontrado com o CPF.: $coluna[29]";}else{$cod_colab_reg_sai=mysql_result($res,0,"cod");}
				}
			//$cnpj_cpf_cliente=$coluna['30'];
				$res=mysql_query("select cod from cliente where cpf_cnpj = '$coluna[30]'")or die(mysql_error());$row=mysql_num_rows($res);
				if($row==0){$erro="Cliente não encontrado com o CNPJ_CPF.: $coluna[30]";}else{$cod_cliente=mysql_result($res,0,"cod");}
			//$desc_fechamento_reg=$coluna['31'];
				if($coluna[31]==NULL){
					$cod_fechamento_reg="";
				}else{
					$res=mysql_query("select cod from fechamento_reg where registro = '$coluna[31]'")or die(mysql_error());$row=mysql_num_rows($res);
					if($row==0){$erro="Registro de saídas não encontrado com a descricao: $coluna[31]";}else{$cod_fechamento_reg=mysql_result($res,0,"cod");}
				}
			$itm_fechamento_reg=$coluna['32'];
			$cod_defeito=$coluna['33'];
			$cod_solucao=$coluna['34'];
			$cod_destino=$coluna['35'];
		}
		if($erro<>""){
			$status="<font color='red'>".$erro."</font>";
		}else{
			$sqlcp="select cod from cp where codl='$coluna[1]' and barcode='$coluna[2]' and cod_baselocal='$base'";
			$rescp=mysql_query($sqlcp) or die(mysql_error()."<br>Erro na consulta a tabela cp <br>Comando: $sqlcp");
			$rowscp=mysql_num_rows($rescp);

			if($rowscp==0){
				$sql="insert into cp (codl,cod_baselocal,barcode,cod_extrato_mo,valor_gar,cod_posicao,cod_nf_entrada,data_entra,data_barcode,filial,data_analize,serie,certificado,
				obs,data_pronto,data_sai,defeito_reclamado,folha_cq,os_fornecedor,item_os_fornecedor,carencia,reprova_cq,orc_cliente,data_orc,total_orc,
				cod_colab_entra,cod_modelo,cod_tec,cod_cq,cod_colab_reg_sai,cod_cliente,cod_fechamento_reg,itm_fechamento_reg,cod_defeito,cod_solucao,cod_destino) 
				values ('$cpl','$base','$barcode','$cod_extrato_mo','$valor_gar','$cod_posicao','$cod_nf_entrada','$data_entra',$data_barcode,'$filial',$data_analize,'$serie','$certificado',
				'$obs',$data_pronto,$data_sai,'$defeito_reclamado','$folha_cq','$os_fornecedor','$item_os_fornecedor','$carencia','$reprova_cq','$orc_cliente',$data_orc,'$total_orc',
				'$cod_colab_entra','$cod_modelo','$cod_tec','$cod_cq','$cod_colab_reg_sai','$cod_cliente','$cod_fechamento_reg','$itm_fechamento_reg','$cod_defeito','$cod_solucao','$cod_destino')";
				mysql_query($sql) or die(mysql_error()."<br>Erro na inserção a tabela cp <br>Comando: $sql");
				$cp="DEFINIR";//definir aqui o numero do ultimo cp incluido na base para poder cadastrar peças e orçamentos
				$descricao=$barcode;
				$status="O.S. registrada com sucesso!";
			}else{
				if($rowscp==1){
					$cp=mysql_result($rescp,0,"cod");
					$sql="update cp set cod_extrato_mo='$cod_extrato_mo',valor_gar='$valor_gar',cod_posicao='$cod_posicao',cod_nf_entrada='$cod_nf_entrada',data_entra='$data_entra',
					data_barcode=$data_barcode,filial='$filial',data_analize=$data_analize,serie='$serie',certificado='$certificado',
					obs='$obs',data_pronto=$data_pronto,data_sai=$data_sai,defeito_reclamado='$defeito_reclamado',folha_cq='$folha_cq',os_fornecedor='$os_fornecedor',
					item_os_fornecedor='$item_os_fornecedor',carencia='$carencia',reprova_cq='$reprova_cq',orc_cliente='$orc_cliente',data_orc=$data_orc,total_orc='$total_orc',
					cod_colab_entra='$cod_colab_entra',cod_modelo='$cod_modelo',cod_tec='$cod_tec',cod_cq='$cod_cq',cod_colab_reg_sai='$cod_colab_reg_sai',cod_cliente='$cod_cliente',
					cod_fechamento_reg='$cod_fechamento_reg',itm_fechamento_reg='$itm_fechamento_reg',cod_defeito='$cod_defeito',cod_solucao='$cod_solucao',cod_destino='$cod_destino'
					where cod=$cp";
					mysql_query($sql) or die(mysql_error()."<br>Erro na atualização da tabela cp <br>Comando: $sql");
					$descricao=$barcode;
					$status="O.S. atualizada com sucesso!";
				}else{
					if($rowscp>1){die("<h1>ATENÇÃO ERRO GRAVÍSSIMO. AVISE AO ANALISTA DO SISTEMA URGENTEMENTE QUE EXISTEM REGISTROS DUPLICADOS NA TABELA CP</h1>");}
				}
			}
		}//erro...
	}
	///////////
	///////////
	///////////
	///////////CADASTRAR PEÇAS E ORÇAMENTOS AQUI!!!!!!!!!!!!!!!!!!!!!!!!
	///////////
	///////////
	///////////
	?><tr>
	<TD><? print($n);?></TD>
	<TD><? print($descricao);$descricao="";?></TD>
	<TD><? print($status);$status="";?></TD>
<? 
}
?>
</table>
<hr>
<p><a href="frm_carga_saat.php">Voltar</a></p>
</body>
</html>