<?	//* O que pode ocorrer nesta página:
	//*	RECEBER UM CP NOVO COM BARCODE PRÉ-CADASTRADO
	//*			FAZER CONSISTENCIA DO NUMERO DE SÉRIE (CONSISTENCIA DO BARCODE DESNECESSÁRIA POIS JÁ FOI FEITA PELO PRÉ-CADASTRO)
	//*			* GRAVAR NO BANCO DE DADOS
	//*	RECEBER UM CP NOVO SEM BRACODE PRÉ-CADASTRADO(SEM ENTRADA;SEM UM CP DEFINIDO Entrada sem o Casdastro do Bracode)
	//* em 10/05/06 receber um cp novo sem barcode - Entrada através de nota fiscal
	//*			FAZER CONSISTÊNCIA DO NUMERO DE SÉRIE
	//*			FAZER CONSISTÊNCIA DO BARCODE (IGUAL À FEITA NA PÁGINA DE PRÉ-CADASTRO(ENTRADA))
	//*	em 29/06/2007 acrescentei na consulta do barcode a possibilidade de recadastro se ele tiver sido cadastrado da ultima vez a mais de 360 dias e tiver finalizado
	//*			BUSCAR O CP MAIS ANTIGO DISPONIVEL PARA O MODELO INSERIDO (E RETORNAR ERRO CASO NÃO EXISTA PRÉ-CADASTRO)
	//*			* GRAVAR NO BANCO DE DADOS
	//*	RECEBER UM CP AINDA NÃO PRONTO PARA ATUALIZAÇÃO E FINALIZAÇÃO (MARCAR COMO PRONTO!)
	//*			VER SE HOUVE ALTERAÇÃO DO NUMERO DE SÉRE E SE SIM (FAZER CONSISTÊNCIA DO NUMERO DE SÉRIE)
	//*			Barcode não precisa de consistencia pois se manterá o mesmo .TRAVADO na alteração)
	//*			* ATUALIZAR NO BANCO DE DADOS			
	//* APÓS GRAVAR NO BANCO, MOSTRAR RESULTADO E DAR OPÇÕES DE ALTERAR, NOVO, PRONTO, PEDIR PEÇAS E GERAR ORÇAMENTO
	//*
	//* RECEBER UM CP DE UM EQUIPAMENTO DE LINHA DIFERENTE DA ATUAÇÃO DO TÉCNICO CADASTRADO
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////INICIO DO CÓDIGO/////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	// Alterado em 21 de Setembro de 2005 para iginorar a conssitência do numero de série caso o numero seja S/N
	//Alterado em 28 de Maio de 2006 para customizar para outros fornecedores e para outros clientes
require_once("sis_valida.php");
require_once("sis_conn.php");

$res=mysql_query("SELECT linhatec from rh_user where rh_user.cod=$id")or die(mysql_error());
$linhatec=mysql_result($res,0,"linhatec");

function validaSerie($serie,$modelo){
	global $msg;
	global $carencia;
	if ($serie<>"Sem Série"){
		$sql=mysql_query("SELECT MAX(COD) as cod FROM cp WHERE SERIE like '$serie' and cod_modelo = $modelo")
		or die("Erro no Camando de pesquisa do numero de série".mysql_error());
		$pesqcp=mysql_result($sql,0,"cod");
		 //verifica se existe uma repetição de numero de série
		if (isset($pesqcp)){
			$sql2=mysql_query("SELECT datediff(NOW(),data_sai) as dias,barcode,data_sai
			from cp where cp.cod='$pesqcp'")or die("Erro no Camando de pesquisa dos dados do CP".mysql_error());
			$datasai=mysql_result($sql2,0,"data_sai");
			$pesqdias=mysql_result($sql2,0,"dias");
			$pesqbarcode=mysql_result($sql2,0,"barcode");
			//Se existir uma repetição, a linha abaixo verifica se a data de saida ainda é nula, ou seja, o equipamento esta sendo redigitado!
			if (empty($pesqdias)){
				die("<h2>Atenção este NÚMERO DE SÉRIE não pode ser recadastrado pois:<br> 
		              Ainda consta no sistema como <h1><font color='red'>PRODUTO EM OFICINA!</font></h1>
		              Sob Código de Barras número:<font color='red'> $pesqbarcode </font><br>e Sob Controle de Produção :<font color='red'> $pesqcp ");
			}
			//Se existir uma repetição, e já foi entregue, esta linha verifica se o produto foi entregue a menos de 90 dias e se sim , o marca como em carencia 
			if ($pesqdias<90){
				$carencia="1";
				return 1;
				$msg="Produto em carência<h2> Entregue em $datasai à $pesqdias dias sob Código de Barras $pesqbarcode<BR> DADOS INSERIDOS!";
				print("<h2>Equipamento entregue a menos de 90 dias</h2>
				<h1><font color='red'>PRODUTO EM CARÊNCIA!</font></h1>
				<h2> Entregue em $datasai à $pesqdias dias sob Código de Barras $pesqbarcode<BR> DADOS INSERIDOS!");
			}
		}else{
			$carencia="0";
			return 0;
		}
	}else{
		$carencia="0";
		return 0;
	}
}//FIM DA FUNÇÃO DE VALIDAÇÃO DE NUMERO DE SERIE
// Não repeti as consistências de Barcode da página de cadastro, pois cada cliente requer uma consistencia diferente e esta página
// deverá ser comum para todos então deixarei que somente a primeira página de cadastro faça as validações corretamente para cada cliente
// pois haverá uma página dessas para cada um.
function validaBarcode($barcode,$codCliente){
	$minDiasB=360;//Qtade de dias permitido para o recadastro de um barcode(coloquei como variavel pois pode ser buscado do BD!)
	$tamanho=strlen($barcode);
	$letra=substr($barcode,0,1);
	$sql=mysql_query("SELECT cp.cod as cod,barcode,rh_user.nome as nome, datediff(NOW(),data_entra) as mdias,data_sai
	from cp inner join rh_user on rh_user.cod = cp.cod_tec
	where barcode='$barcode'")or die("Erro no Camando SQL pág scr_mnucp.php".mysql_error());
	//Verifica se há uma repetição de Barcode
	// * Atenção revisar este trecho pois há a possibilidade de repetir um barcode aqui!
	$rows=mysql_num_rows($sql);
	if ($rows>0){
		$dtsai=mysql_result($sql,$rows-1,"data_sai");
		$diasB=mysql_result($sql,$rows-1,"mdias");
		if($diasB<$minDiasB){
			$ccp=mysql_result($sql,$rows-1,"cod");
			$tec=mysql_result($sql,$rows-1,"nome");
			die("<H1>ERRO</H1>Código de Barras cadastrado a $diasB dias O minimo permitido para recadastro é de $minDiasB dias. <br> Controle de Produção n. $ccp e Técnico cód. $tec");
		}
		//die(" teste data nula $dtsai");
		if($dtsai==NULL){
			die("<H1>ERRO</H1>Impossível recadastrar o barcode $barcode não foi finalizado em seu último cadastro. Consulte-o!");
		}
	}
	// Consistências para o cliente 1 - CASAS BAHIA
	if ($codCliente=="1"){
		if ($tamanho<>8){
			die("<font color ='red'>Tamanho do barcode diferente de 8. Provavelmente você clicou em lugar errado!</font>");
		}

		if ($letra<>"P"){
			// se a letra não começa com P então verifica na tabela modelo se é o código de um modelo a ser alterado no cadastro coletivo
			die("<font color ='red'>Este não é um Barcode válido. E não foi encontrada nenhuma referencia para o código $barcode na tabela MODELO! 
			<br>Obs.: Este erro só ocorre ao cadastrar incorretamente produtos do Cliente Casas Bahia</font>");
		}
	}
}//FIM DA VALIDAÇÃO DO BARCODE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////
////////////////////
////////////////////////////INICIO DA VALIDAÇÃO DOS DADOS DO FORMULÁRIO
////////////////////
//////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$sqlCliente=mysql_query("select cliente.cod as cod from cliente inner join base on base.cliente_exclusivo = cliente.cod");
	$codCliente=mysql_result($sqlCliente,0,"cod");

	$cp=$_POST["txtcp"];
	$certificado=strtoupper($_POST["txtCertificado"]);
	$filial=$_POST["txtFilial"];
	$obs1=strtoupper($_POST["txtObs"]);
	$obs=str_replace("'",'"',$obs1);// Corrige o bug ao inserir aspas simples no campo obs substituindo a por aspas duplas
	$acao=$_POST["cmdSalvar"];

	if (!$_POST["cmbPosicao"]==0){$posicao=$_POST["cmbPosicao"];}else{$posicao=0;}//Não impede que o cadastro prossiga por falta desta informação
	if (!$_POST["cmbModelo"]==0){$modelo=$_POST["cmbModelo"];}else{$erro="Modelo não Selecionado!";$modelo=0;}	
	if (!$_POST["txtAnoBarcode"]==0){$anob=$_POST["txtAnoBarcode"];}else{$erro="Ano do Barcode não Preenchido!";$anob=0;}	
	if ($_POST["txtAnoBarcode"]>date("y")){$erro="Impossivel existir um barcode com ano maior que o ano Atual!";$anob=$_POST["txtAnoBarcode"];}	
	if (!$_POST["txtMesBarcode"]==0){$mesb=$_POST["txtMesBarcode"];}else{$erro="Mês do Barcode não Preenchido!";$mesb=0;}	
	if ($_POST["txtMesBarcode"]>12 || $_POST["txtMesBarcode"]<1){$erro="Mês do Barcode Incorreto!";$mesb=$_POST["txtMesBarcode"];}	
	if (!$_POST["txtDiaBarcode"]==0){$diab=$_POST["txtDiaBarcode"];}else{$erro="Dia do Barcode não Preenchido!";$diab=0;}	
	if ($_POST["txtDiaBarcode"]>31 || $_POST["txtDiaBarcode"]<1){$erro="Dia do Barcode Incorreto!";$diab=$_POST["txtDiaBarcode"];}	
	if (!$_POST["cmbSolucao"]==0){$solucao=$_POST["cmbSolucao"];}else{$erro="Solução não selecionada!";$solucao=0;}
	if (!$_POST["cmbDefeito"]==0){$defeito=$_POST["cmbDefeito"];}else{$erro="Defeito não selecionado!";$defeito=0;}
	if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="Código de Barras não Preenchido!";$barcode="";}
	if (!$_POST["txtSerie"]==""){
		$serie1=trim($_POST["txtSerie"]);
		
		$resEx=mysql_query("select expressao_regular.expressao as expressao
		from modelo inner join expressao_regular on expressao_regular.cod = modelo.cod_expressao_regular where modelo.cod = $modelo") or die(mysql_error());
		$rowsEx=mysql_num_rows($resEx);
		if ($rowsEx==1){
			$padrao="^".mysql_result($resEx,0,"expressao")."$";//^INICIAR COM  E  $ TERMINA COM
//			$padrao="^[A|F|Z]{2,3}[0-9]{6}[A-Z]{1}[5-9]{2}[A-Z|a-z]{1}$"; //[A ou Z ou F] de 2 a 3 caracteres, [0 a 9] 6 caracteres,[de A a Z] 1 caracter, [de 5 a 9] 2 caracteres , [de A a Z ou de a a z] um caracter.
			if(eregi($padrao,$serie1)){
				$serie=strtoupper($serie1);
			}else{
				$erro="O número de série $serie1 não é válido para este produto! (cód. Padrão $padrao)";
				$serie=$serie1;
			}
		}else{
			if ($serie1=="Sem Série"){
					$serie=$serie1;
			}else{
				$errados = array(".",",","-","!","'","*","(",")","_","+","[","]","{","}","^","~",";",":"," ","  ","%","/","//","\\",'"');
				$serie2=str_replace($errados,"",$serie1);//Substitui todos os caracteres errados por "" nada...
				$serie=strtoupper($serie2);// transforma todos os caracteres para maiusculos
			}
		}
	}else{
		$erro="Número de série não preenchido!";
		$serie="";
	}
	$dtbarcode="$anob/$mesb/$diab";// concatenação da data do cód de barras OBS.:ver se a data está correta	
// VERIFICANDO A LINHA TÉCNICA E BUSCANDO O CÓDIGO DO FORNECEDOR PARA VALIDAR O NUM DE SÉRIE NA PROX VALIDAÇÃO
	$sqlL=mysql_query("select linha, modelo.descricao as descricao,cod_fornecedor,cortesia from modelo 
	inner join linha on linha.cod=modelo.linha where modelo.cod=$modelo") or die (mysql_error());
	$rowL=mysql_num_rows($sqlL);
	if($rowL==0){die("Erro o produto cadastrado com o código $modelo não possui linha técnica ou algum outro dado imprescindivel no cadastro. <br> Dica: Informe ao Auxiliar administrativo");}
	$codL=mysql_result($sqlL,0,"linha");
	if ($codL<>$linhatec && $linhatec<>0){
		$des=mysql_result($sqlL,0,"descricao");
		$erro="O modelo $des pertence a uma linha de produtos diferete à que você está habilitado no sistema!";
	}
// CONSISTÊNCIAS NOVA DATA
	//Em 21/09/06 permiti que se colocasse qualquer tamanho de série para Nova Data em virtude de existirem vários tamanhos
	//	VERIFICANDO O TAMANHO DO NÚMERO DE SÉRIE// VALIDANDO A SÉRIE PARA O FORNECEDOR NOVA DATA (cod 3)
	$tamSerie=strlen($serie);
	$codFor=mysql_result($sqlL,0,"cod_fornecedor");
	$cortesia=mysql_result($sqlL,0,"cortesia");
//	if($codFor==3 && $tamSerie<>12 && $cortesia==0){
//		$erro="Tamanho do Número de Série para o fornecedor Nova Data cód $codFor no SAAT é diferente de 12!";
//	}
	//if($codFor==3 && $tamSerie<12 && $cortesia==1){
	//	$erro="Tamanho do Número de Série para o fornecedor Nova Data cód $codFor no SAAT é menor que 12!";
	//}
	// SE FOR NOVA DATA ENTÃO OBRIGA PREENCHER DEFEITO RECLAMADO
	$defeitoR=strtoupper($_POST["txtDefeito"]);
	$tamDefeito=strlen($defeitoR);
	
	if ($tamDefeito<5){
		$erro="Defeito Reclamado não preenchido! (Minimo de 5 caracteres!)<br> Verifique Motivo da troca preenchido manualmente na etiqueta do produto!";
	}
// FIM consistências NOVA DATA
// Consistência para as filiais do CBD!!!

// Por solicitações da Britânia as Filiais Casas Bahia deverão ser preenchidas para gerar relatório de estatistica de troca indevida por loja 
//	if ($codCliente==2){
		if ($filial=="" || $filial==0){
			$erro="Filial não preenchida!";
		}else{
			$sqlFilial=mysql_query("select descricao from filial_cbd where descricao='$filial'");
			$tot=mysql_num_rows($sqlFilial);
			if($tot==0){
				$erro="Filial $filial não Cadastrada! Caso ela realmente exista, Informe a Administração!";
			}
		}
//	}
if (isset($erro)){
	Header("Location:frm_cp.php?defeitoR=$defeitoR&erro=$erro&cmdEnvia=$acao&codModelo=$modelo&serie=$serie&codDefeito=$defeito&codSolucao=$solucao&diaB=$diab&mesB=$mesb&anoB=$anob&barcode=$barcode&cp=$cp&filial=$filial&certificado=$certificado&obs=$obs&posicao=$posicao");
	exit;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////
////////////////////
//////////////////////////////////////////////////////// FIM DA VALIDAÇÃO DOS DADOS DO FORMULÁRIO
////////////////////
//////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($acao=="Salvar"){
	$msg="Cadastro de ";
	$carencia=validaSerie($serie,$modelo);
	//Cadastrando OS sem Barcode pré cadastrado
	if ($cp=="Sem Entrada"){
		validaBarcode($barcode,$codCliente);
		//VERIFICANDO SE AINDA EXISTEM ENTRADAS DISPONIVEIS PARA O MODELO EM QUESTÃO
		$sql=mysql_query("SELECT min(cod) as cod from cp where (cod_modelo=$modelo and barcode is null )")or die("Erro no Camando consulta aos sem entrada disponiveis SQL pág frm_cp.php.php".mysql_error());
		$cp = mysql_result($sql,0,"cod");
		if (empty($cp)){die("NÃO HÁ ENTRADAS DISPONIVEIS PARA ESTE MODELO, <br>
			<h2>PROCURE A ADMINISTRAÇÃO PARA VERIFICAR PORQUE NÃO EXISTEM ENTRADAS SUFICIENTES!!!<br>
			<h1>Provavelmente, Algum Técnico finalizou a ultima entrada disponivel para este modelo");
		}//Fim verificando ultimo item cadastrado sem entrada
	}//Fim Cadastrando sem entrada
	
	///////////////////////////////////////////GERANDO o número de Ordem de serviços/////////////////////////////////////////////
	// Para evitar que erros no preenchimento da OS ocorram, só permitirei que o modelo seja alterado no mesmo dia da analise(frm_cp) ---ok
	// E caso neste dia o modelo alterado seja de outro fornecedor então uma nova OS deve substituir a do antigo fornecedor
	// o número substituido deve ser armazenado em uma tabela temporária para ser utilizado no próximo cadastro daquele fornecedor
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Descobrindo o fornecedor ** e se seu preenchimento de OS é AUTOMÁTICO OU MANUAL e o num. máx de itens por os
	$sql=mysql_query("select modelo.cod_fornecedor as cod_for,max_item_os,os_auto from modelo inner join 
	fornecedor on fornecedor.cod = modelo.cod_fornecedor where modelo.cod=$modelo")or die("Selecionanado cód fornecedor".mysql_error());
	$fornecedor=mysql_result($sql,0,"cod_for");
	$max_item_os=mysql_result($sql,0,"max_item_os");
	$os_auto=mysql_result($sql,0,"os_auto");
	if ($os_auto==1 || $os_auto==2){
		// Como cadastraremos centenas de OS de uma só vez então a busca pela disponivel deve ser pela menor ou primeira cadastrada.
		$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
		$os=mysql_result($sql,0,"os");
		if ($os==NULL){
			$sql=mysql_query("select descricao from fornecedor where cod=$fornecedor")or die(mysql_error());
			$forne=mysql_result($sql,0,"descricao");
			die("<h1><font color=red> IMPOSSIVEL CADASTRAR!</h1>
			<br><h4><center>As Ordens de Serviço Fornecedor $forne se esgotaram no Banco de Dados do SAAT II<br>
			AVISE URGENTEMENTE À ADMINISTRAÇÃO PARA PROVIDENCIAR O CADASTRO DE NOVAS ORDENS DE SERVIÇO!</h4>");
		}
		// Se o fornecedor utiliza itens para suas OS então max_item_os é diferente de zero senão entende-se que ele não usa itens para OS
		if ($max_item_os<>0){
			//Busca o maior item cadastrado para esta ordem 
			$sql=mysql_query("select max(item_os_fornecedor) as item from cp where os_fornecedor = $os")or die("Selecionando Item".mysql_error());
			$item=mysql_result($sql,0,"item");
			if ($item<>NULL){
				// Se o maior item cadastrado para esta ordem é igual ao limite de itens por OS deste fornecedor então reseta Item 
				if ($item==$max_item_os){
					$item=0;
					$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os=$os")or die(mysql_error());
				///*****¨¨¨¨Esta parte é repetida... não encontrei outra solução em 28/05/06
					$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
					$os=mysql_result($sql,0,"os");
					if ($os==NULL){
						$sql=mysql_query("select descricao from fornecedor where cod=$fornecedor")or die(mysql_error());
						$forne=mysql_result($sql,0,"descricao");
						die("<h1><font color=red> IMPOSSIVEL CADASTRAR!</h1>
						<br><h4><center>As Ordens de Serviço Fornecedor $forne se esgotaram no Banco de Dados do SAAT II<br>
						AVISE URGENTEMENTE À ADMINISTRAÇÃO PARA PROVIDENCIAR O CADASTRO DE NOVAS ORDENS DE SERVIÇO!</h4>");
					}
				///*****¨¨¨¨FIM da parte repetida acima
				}else{
					$item++;
				}
			}else{// Se a pesquisa de item retorna NULL e o fornecedor utiliza itens então esta é o primeiro item da OS a ser utilizada
				$item=0;
			}
		}else{// Se não usa itens então item é nulo e auomaticamente já seta mais uma OS como usada
			$item=NULL; 
			$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os='$os'")or die(mysql_error());
		}//Fim Fornecedor usa Itens os? maximo de itens <> 0
	}else{
		// O PREENCHIMENTO SERÁ REALIZADO MANUALMENTE  COMO NO CASO DA NOVADATA OU UMA VEZ POR MÊS COMO A AULIK
		// COM o NULL e a data de analize já preenchida filtramos as ordens que terão prechimento manual
		$os=0;
		$item=NULL;
	}//fim os_auto
    //FIM GERANDO Ordem de Serviços
	$sql3="update cp set defeito_reclamado='$defeitoR',cod_modelo='$modelo',barcode='$barcode',data_barcode='$dtbarcode', filial='$filial',data_analize=now(),cod_tec='$id',cod_defeito='$defeito',cod_solucao='$solucao',serie='$serie',	certificado='$certificado', obs='$obs', carencia='$carencia', os_fornecedor='$os',item_os_fornecedor='$item',cod_posicao=$posicao where cp.cod=$cp";		
}
	
if ($acao=="Alterar"){
	$msg="Alteração de ";
	$sqlA=mysql_query("select serie,cod_tec,carencia,barcode from cp where cod = $cp")or die("Erro na consulta de num de série em alterar scr_cp.php".mysql_error());	
	$pesqBarcode=mysql_result($sqlA,0,"barcode");

	if($pesqBarcode<>$barcode){// Se o valor de barcode no formulário foi alterado em relação ao cadastrado na base então revalida o barcode
		validaBarcode($barcode,$codCliente);
	}

	$pesqSerie=mysql_result($sqlA,0,"serie");
	$pesqTec=mysql_result($sqlA,0,"cod_tec");
	$pesqCarencia=mysql_result($sqlA,0,"carencia");
	//Se a série foi alterada, verifica se a nova série está em carencia ou se está no box - Faz todas as consistências da funcão Valida Série
	if ($serie<>$pesqSerie){
		$carencia=validaSerie($serie,$modelo);
		$carencia="carencia='$carencia',";// Substitui o valor de carencia(0 e 1) pois sera utilizado esta string na SQL3 abaixo
	}else{
		$carencia="carencia='$pesqCarencia',";
	}
	// Caso um gerente tente mudar um produto que esteja sob sua responsábilidade para a responsábilidade de outro técnico
	// sua ação sera ineficaz pois o sistema não preencherá a variavel $tecnico, conforme script abaixo.
	if ($id<>$pesqTec){
		$sqlAlt=mysql_query("select altera_cp as alt from rh_cargo inner join rh_user on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id");
		$Alt=mysql_result($sqlAlt,0,"alt");
		if ($Alt==1){
			$codTec=$_POST["cmbTec"];
			$tecnico="cod_tec='$codTec',";
		}else{
			die("<h1>Não foi possível realizar esta operação! Somente seu gerente pode realiza-lá!");
		}
	}else{
		$tecnico="";
		$erro2="A identificação do técnico não foi alterara por uma questão de consistência dos dados no sistema";
	}
	// Variaveis $carencia e $tecnico preenchidas nos scripts acima
	$sql3="update cp set defeito_reclamado='$defeitoR',cod_modelo='$modelo',barcode='$barcode',data_barcode='$dtbarcode', filial='$filial', 
	cod_defeito='$defeito',cod_solucao='$solucao',serie='$serie',$carencia $tecnico
	certificado='$certificado', obs='$obs',cod_posicao=$posicao
	where cp.cod=$cp";		
}
mysql_db_query ("$bd",$sql3,$Link) or die ("Erro na query de inserção dos dados do Controle de Produção no banco de dados! $sql3 ".mysql_error());		
if (empty($erro2)){$erro2="";}
Header("Location:con_cp.php?cp=$cp&msg=$msg&erro=$erro2");
?>