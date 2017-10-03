<?	//* O que pode ocorrer nesta p�gina:
	//*	RECEBER UM CP NOVO COM BARCODE PR�-CADASTRADO
	//*			FAZER CONSISTENCIA DO NUMERO DE S�RIE (CONSISTENCIA DO BARCODE DESNECESS�RIA POIS J� FOI FEITA PELO PR�-CADASTRO)
	//*			* GRAVAR NO BANCO DE DADOS
	//*	RECEBER UM CP NOVO SEM BRACODE PR�-CADASTRADO(SEM ENTRADA;SEM UM CP DEFINIDO Entrada sem o Casdastro do Bracode)
	//* em 10/05/06 receber um cp novo sem barcode - Entrada atrav�s de nota fiscal
	//*			FAZER CONSIST�NCIA DO NUMERO DE S�RIE
	//*			FAZER CONSIST�NCIA DO BARCODE (IGUAL � FEITA NA P�GINA DE PR�-CADASTRO(ENTRADA))
	//*	em 29/06/2007 acrescentei na consulta do barcode a possibilidade de recadastro se ele tiver sido cadastrado da ultima vez a mais de 360 dias e tiver finalizado
	//*			BUSCAR O CP MAIS ANTIGO DISPONIVEL PARA O MODELO INSERIDO (E RETORNAR ERRO CASO N�O EXISTA PR�-CADASTRO)
	//*			* GRAVAR NO BANCO DE DADOS
	//*	RECEBER UM CP AINDA N�O PRONTO PARA ATUALIZA��O E FINALIZA��O (MARCAR COMO PRONTO!)
	//*			VER SE HOUVE ALTERA��O DO NUMERO DE S�RE E SE SIM (FAZER CONSIST�NCIA DO NUMERO DE S�RIE)
	//*			Barcode n�o precisa de consistencia pois se manter� o mesmo .TRAVADO na altera��o)
	//*			* ATUALIZAR NO BANCO DE DADOS			
	//* AP�S GRAVAR NO BANCO, MOSTRAR RESULTADO E DAR OP��ES DE ALTERAR, NOVO, PRONTO, PEDIR PE�AS E GERAR OR�AMENTO
	//*
	//* RECEBER UM CP DE UM EQUIPAMENTO DE LINHA DIFERENTE DA ATUA��O DO T�CNICO CADASTRADO
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////INICIO DO C�DIGO/////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////////////
	// Alterado em 21 de Setembro de 2005 para iginorar a conssit�ncia do numero de s�rie caso o numero seja S/N
	//Alterado em 28 de Maio de 2006 para customizar para outros fornecedores e para outros clientes
require_once("sis_valida.php");
require_once("sis_conn.php");

$res=mysql_query("SELECT linhatec from rh_user where rh_user.cod=$id")or die(mysql_error());
$linhatec=mysql_result($res,0,"linhatec");

function validaSerie($serie,$modelo){
	global $msg;
	global $carencia;
	if ($serie<>"Sem S�rie"){
		$sql=mysql_query("SELECT MAX(COD) as cod FROM cp WHERE SERIE like '$serie' and cod_modelo = $modelo")
		or die("Erro no Camando de pesquisa do numero de s�rie".mysql_error());
		$pesqcp=mysql_result($sql,0,"cod");
		 //verifica se existe uma repeti��o de numero de s�rie
		if (isset($pesqcp)){
			$sql2=mysql_query("SELECT datediff(NOW(),data_sai) as dias,barcode,data_sai
			from cp where cp.cod='$pesqcp'")or die("Erro no Camando de pesquisa dos dados do CP".mysql_error());
			$datasai=mysql_result($sql2,0,"data_sai");
			$pesqdias=mysql_result($sql2,0,"dias");
			$pesqbarcode=mysql_result($sql2,0,"barcode");
			//Se existir uma repeti��o, a linha abaixo verifica se a data de saida ainda � nula, ou seja, o equipamento esta sendo redigitado!
			if (empty($pesqdias)){
				die("<h2>Aten��o este N�MERO DE S�RIE n�o pode ser recadastrado pois:<br> 
		              Ainda consta no sistema como <h1><font color='red'>PRODUTO EM OFICINA!</font></h1>
		              Sob C�digo de Barras n�mero:<font color='red'> $pesqbarcode </font><br>e Sob Controle de Produ��o :<font color='red'> $pesqcp ");
			}
			//Se existir uma repeti��o, e j� foi entregue, esta linha verifica se o produto foi entregue a menos de 90 dias e se sim , o marca como em carencia 
			if ($pesqdias<90){
				$carencia="1";
				return 1;
				$msg="Produto em car�ncia<h2> Entregue em $datasai � $pesqdias dias sob C�digo de Barras $pesqbarcode<BR> DADOS INSERIDOS!";
				print("<h2>Equipamento entregue a menos de 90 dias</h2>
				<h1><font color='red'>PRODUTO EM CAR�NCIA!</font></h1>
				<h2> Entregue em $datasai � $pesqdias dias sob C�digo de Barras $pesqbarcode<BR> DADOS INSERIDOS!");
			}
		}else{
			$carencia="0";
			return 0;
		}
	}else{
		$carencia="0";
		return 0;
	}
}//FIM DA FUN��O DE VALIDA��O DE NUMERO DE SERIE
// N�o repeti as consist�ncias de Barcode da p�gina de cadastro, pois cada cliente requer uma consistencia diferente e esta p�gina
// dever� ser comum para todos ent�o deixarei que somente a primeira p�gina de cadastro fa�a as valida��es corretamente para cada cliente
// pois haver� uma p�gina dessas para cada um.
function validaBarcode($barcode,$codCliente){
	$minDiasB=360;//Qtade de dias permitido para o recadastro de um barcode(coloquei como variavel pois pode ser buscado do BD!)
	$tamanho=strlen($barcode);
	$letra=substr($barcode,0,1);
	$sql=mysql_query("SELECT cp.cod as cod,barcode,rh_user.nome as nome, datediff(NOW(),data_entra) as mdias,data_sai
	from cp inner join rh_user on rh_user.cod = cp.cod_tec
	where barcode='$barcode'")or die("Erro no Camando SQL p�g scr_mnucp.php".mysql_error());
	//Verifica se h� uma repeti��o de Barcode
	// * Aten��o revisar este trecho pois h� a possibilidade de repetir um barcode aqui!
	$rows=mysql_num_rows($sql);
	if ($rows>0){
		$dtsai=mysql_result($sql,$rows-1,"data_sai");
		$diasB=mysql_result($sql,$rows-1,"mdias");
		if($diasB<$minDiasB){
			$ccp=mysql_result($sql,$rows-1,"cod");
			$tec=mysql_result($sql,$rows-1,"nome");
			die("<H1>ERRO</H1>C�digo de Barras cadastrado a $diasB dias O minimo permitido para recadastro � de $minDiasB dias. <br> Controle de Produ��o n. $ccp e T�cnico c�d. $tec");
		}
		//die(" teste data nula $dtsai");
		if($dtsai==NULL){
			die("<H1>ERRO</H1>Imposs�vel recadastrar o barcode $barcode n�o foi finalizado em seu �ltimo cadastro. Consulte-o!");
		}
	}
	// Consist�ncias para o cliente 1 - CASAS BAHIA
	if ($codCliente=="1"){
		if ($tamanho<>8){
			die("<font color ='red'>Tamanho do barcode diferente de 8. Provavelmente voc� clicou em lugar errado!</font>");
		}

		if ($letra<>"P"){
			// se a letra n�o come�a com P ent�o verifica na tabela modelo se � o c�digo de um modelo a ser alterado no cadastro coletivo
			die("<font color ='red'>Este n�o � um Barcode v�lido. E n�o foi encontrada nenhuma referencia para o c�digo $barcode na tabela MODELO! 
			<br>Obs.: Este erro s� ocorre ao cadastrar incorretamente produtos do Cliente Casas Bahia</font>");
		}
	}
}//FIM DA VALIDA��O DO BARCODE
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////
////////////////////
////////////////////////////INICIO DA VALIDA��O DOS DADOS DO FORMUL�RIO
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

	if (!$_POST["cmbPosicao"]==0){$posicao=$_POST["cmbPosicao"];}else{$posicao=0;}//N�o impede que o cadastro prossiga por falta desta informa��o
	if (!$_POST["cmbModelo"]==0){$modelo=$_POST["cmbModelo"];}else{$erro="Modelo n�o Selecionado!";$modelo=0;}	
	if (!$_POST["txtAnoBarcode"]==0){$anob=$_POST["txtAnoBarcode"];}else{$erro="Ano do Barcode n�o Preenchido!";$anob=0;}	
	if ($_POST["txtAnoBarcode"]>date("y")){$erro="Impossivel existir um barcode com ano maior que o ano Atual!";$anob=$_POST["txtAnoBarcode"];}	
	if (!$_POST["txtMesBarcode"]==0){$mesb=$_POST["txtMesBarcode"];}else{$erro="M�s do Barcode n�o Preenchido!";$mesb=0;}	
	if ($_POST["txtMesBarcode"]>12 || $_POST["txtMesBarcode"]<1){$erro="M�s do Barcode Incorreto!";$mesb=$_POST["txtMesBarcode"];}	
	if (!$_POST["txtDiaBarcode"]==0){$diab=$_POST["txtDiaBarcode"];}else{$erro="Dia do Barcode n�o Preenchido!";$diab=0;}	
	if ($_POST["txtDiaBarcode"]>31 || $_POST["txtDiaBarcode"]<1){$erro="Dia do Barcode Incorreto!";$diab=$_POST["txtDiaBarcode"];}	
	if (!$_POST["cmbSolucao"]==0){$solucao=$_POST["cmbSolucao"];}else{$erro="Solu��o n�o selecionada!";$solucao=0;}
	if (!$_POST["cmbDefeito"]==0){$defeito=$_POST["cmbDefeito"];}else{$erro="Defeito n�o selecionado!";$defeito=0;}
	if (!$_POST["txtBarcode"]==""){$barcode=$_POST["txtBarcode"];}else{$erro="C�digo de Barras n�o Preenchido!";$barcode="";}
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
				$erro="O n�mero de s�rie $serie1 n�o � v�lido para este produto! (c�d. Padr�o $padrao)";
				$serie=$serie1;
			}
		}else{
			if ($serie1=="Sem S�rie"){
					$serie=$serie1;
			}else{
				$errados = array(".",",","-","!","'","*","(",")","_","+","[","]","{","}","^","~",";",":"," ","  ","%","/","//","\\",'"');
				$serie2=str_replace($errados,"",$serie1);//Substitui todos os caracteres errados por "" nada...
				$serie=strtoupper($serie2);// transforma todos os caracteres para maiusculos
			}
		}
	}else{
		$erro="N�mero de s�rie n�o preenchido!";
		$serie="";
	}
	$dtbarcode="$anob/$mesb/$diab";// concatena��o da data do c�d de barras OBS.:ver se a data est� correta	
// VERIFICANDO A LINHA T�CNICA E BUSCANDO O C�DIGO DO FORNECEDOR PARA VALIDAR O NUM DE S�RIE NA PROX VALIDA��O
	$sqlL=mysql_query("select linha, modelo.descricao as descricao,cod_fornecedor,cortesia from modelo 
	inner join linha on linha.cod=modelo.linha where modelo.cod=$modelo") or die (mysql_error());
	$rowL=mysql_num_rows($sqlL);
	if($rowL==0){die("Erro o produto cadastrado com o c�digo $modelo n�o possui linha t�cnica ou algum outro dado imprescindivel no cadastro. <br> Dica: Informe ao Auxiliar administrativo");}
	$codL=mysql_result($sqlL,0,"linha");
	if ($codL<>$linhatec && $linhatec<>0){
		$des=mysql_result($sqlL,0,"descricao");
		$erro="O modelo $des pertence a uma linha de produtos diferete � que voc� est� habilitado no sistema!";
	}
// CONSIST�NCIAS NOVA DATA
	//Em 21/09/06 permiti que se colocasse qualquer tamanho de s�rie para Nova Data em virtude de existirem v�rios tamanhos
	//	VERIFICANDO O TAMANHO DO N�MERO DE S�RIE// VALIDANDO A S�RIE PARA O FORNECEDOR NOVA DATA (cod 3)
	$tamSerie=strlen($serie);
	$codFor=mysql_result($sqlL,0,"cod_fornecedor");
	$cortesia=mysql_result($sqlL,0,"cortesia");
//	if($codFor==3 && $tamSerie<>12 && $cortesia==0){
//		$erro="Tamanho do N�mero de S�rie para o fornecedor Nova Data c�d $codFor no SAAT � diferente de 12!";
//	}
	//if($codFor==3 && $tamSerie<12 && $cortesia==1){
	//	$erro="Tamanho do N�mero de S�rie para o fornecedor Nova Data c�d $codFor no SAAT � menor que 12!";
	//}
	// SE FOR NOVA DATA ENT�O OBRIGA PREENCHER DEFEITO RECLAMADO
	$defeitoR=strtoupper($_POST["txtDefeito"]);
	$tamDefeito=strlen($defeitoR);
	
	if ($tamDefeito<5){
		$erro="Defeito Reclamado n�o preenchido! (Minimo de 5 caracteres!)<br> Verifique Motivo da troca preenchido manualmente na etiqueta do produto!";
	}
// FIM consist�ncias NOVA DATA
// Consist�ncia para as filiais do CBD!!!

// Por solicita��es da Brit�nia as Filiais Casas Bahia dever�o ser preenchidas para gerar relat�rio de estatistica de troca indevida por loja 
//	if ($codCliente==2){
		if ($filial=="" || $filial==0){
			$erro="Filial n�o preenchida!";
		}else{
			$sqlFilial=mysql_query("select descricao from filial_cbd where descricao='$filial'");
			$tot=mysql_num_rows($sqlFilial);
			if($tot==0){
				$erro="Filial $filial n�o Cadastrada! Caso ela realmente exista, Informe a Administra��o!";
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
//////////////////////////////////////////////////////// FIM DA VALIDA��O DOS DADOS DO FORMUL�RIO
////////////////////
//////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($acao=="Salvar"){
	$msg="Cadastro de ";
	$carencia=validaSerie($serie,$modelo);
	//Cadastrando OS sem Barcode pr� cadastrado
	if ($cp=="Sem Entrada"){
		validaBarcode($barcode,$codCliente);
		//VERIFICANDO SE AINDA EXISTEM ENTRADAS DISPONIVEIS PARA O MODELO EM QUEST�O
		$sql=mysql_query("SELECT min(cod) as cod from cp where (cod_modelo=$modelo and barcode is null )")or die("Erro no Camando consulta aos sem entrada disponiveis SQL p�g frm_cp.php.php".mysql_error());
		$cp = mysql_result($sql,0,"cod");
		if (empty($cp)){die("N�O H� ENTRADAS DISPONIVEIS PARA ESTE MODELO, <br>
			<h2>PROCURE A ADMINISTRA��O PARA VERIFICAR PORQUE N�O EXISTEM ENTRADAS SUFICIENTES!!!<br>
			<h1>Provavelmente, Algum T�cnico finalizou a ultima entrada disponivel para este modelo");
		}//Fim verificando ultimo item cadastrado sem entrada
	}//Fim Cadastrando sem entrada
	
	///////////////////////////////////////////GERANDO o n�mero de Ordem de servi�os/////////////////////////////////////////////
	// Para evitar que erros no preenchimento da OS ocorram, s� permitirei que o modelo seja alterado no mesmo dia da analise(frm_cp) ---ok
	// E caso neste dia o modelo alterado seja de outro fornecedor ent�o uma nova OS deve substituir a do antigo fornecedor
	// o n�mero substituido deve ser armazenado em uma tabela tempor�ria para ser utilizado no pr�ximo cadastro daquele fornecedor
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//Descobrindo o fornecedor ** e se seu preenchimento de OS � AUTOM�TICO OU MANUAL e o num. m�x de itens por os
	$sql=mysql_query("select modelo.cod_fornecedor as cod_for,max_item_os,os_auto from modelo inner join 
	fornecedor on fornecedor.cod = modelo.cod_fornecedor where modelo.cod=$modelo")or die("Selecionanado c�d fornecedor".mysql_error());
	$fornecedor=mysql_result($sql,0,"cod_for");
	$max_item_os=mysql_result($sql,0,"max_item_os");
	$os_auto=mysql_result($sql,0,"os_auto");
	if ($os_auto==1 || $os_auto==2){
		// Como cadastraremos centenas de OS de uma s� vez ent�o a busca pela disponivel deve ser pela menor ou primeira cadastrada.
		$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
		$os=mysql_result($sql,0,"os");
		if ($os==NULL){
			$sql=mysql_query("select descricao from fornecedor where cod=$fornecedor")or die(mysql_error());
			$forne=mysql_result($sql,0,"descricao");
			die("<h1><font color=red> IMPOSSIVEL CADASTRAR!</h1>
			<br><h4><center>As Ordens de Servi�o Fornecedor $forne se esgotaram no Banco de Dados do SAAT II<br>
			AVISE URGENTEMENTE � ADMINISTRA��O PARA PROVIDENCIAR O CADASTRO DE NOVAS ORDENS DE SERVI�O!</h4>");
		}
		// Se o fornecedor utiliza itens para suas OS ent�o max_item_os � diferente de zero sen�o entende-se que ele n�o usa itens para OS
		if ($max_item_os<>0){
			//Busca o maior item cadastrado para esta ordem 
			$sql=mysql_query("select max(item_os_fornecedor) as item from cp where os_fornecedor = $os")or die("Selecionando Item".mysql_error());
			$item=mysql_result($sql,0,"item");
			if ($item<>NULL){
				// Se o maior item cadastrado para esta ordem � igual ao limite de itens por OS deste fornecedor ent�o reseta Item 
				if ($item==$max_item_os){
					$item=0;
					$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os=$os")or die(mysql_error());
				///*****����Esta parte � repetida... n�o encontrei outra solu��o em 28/05/06
					$sql=mysql_query("select min(os) as os from os_fornecedor where cod_fornecedor=$fornecedor and usada<>1")or die("Selecionando OS".mysql_error());
					$os=mysql_result($sql,0,"os");
					if ($os==NULL){
						$sql=mysql_query("select descricao from fornecedor where cod=$fornecedor")or die(mysql_error());
						$forne=mysql_result($sql,0,"descricao");
						die("<h1><font color=red> IMPOSSIVEL CADASTRAR!</h1>
						<br><h4><center>As Ordens de Servi�o Fornecedor $forne se esgotaram no Banco de Dados do SAAT II<br>
						AVISE URGENTEMENTE � ADMINISTRA��O PARA PROVIDENCIAR O CADASTRO DE NOVAS ORDENS DE SERVI�O!</h4>");
					}
				///*****����FIM da parte repetida acima
				}else{
					$item++;
				}
			}else{// Se a pesquisa de item retorna NULL e o fornecedor utiliza itens ent�o esta � o primeiro item da OS a ser utilizada
				$item=0;
			}
		}else{// Se n�o usa itens ent�o item � nulo e auomaticamente j� seta mais uma OS como usada
			$item=NULL; 
			$sql=mysql_query("update os_fornecedor set usada=1 where cod_fornecedor=$fornecedor and os='$os'")or die(mysql_error());
		}//Fim Fornecedor usa Itens os? maximo de itens <> 0
	}else{
		// O PREENCHIMENTO SER� REALIZADO MANUALMENTE  COMO NO CASO DA NOVADATA OU UMA VEZ POR M�S COMO A AULIK
		// COM o NULL e a data de analize j� preenchida filtramos as ordens que ter�o prechimento manual
		$os=0;
		$item=NULL;
	}//fim os_auto
    //FIM GERANDO Ordem de Servi�os
	$sql3="update cp set defeito_reclamado='$defeitoR',cod_modelo='$modelo',barcode='$barcode',data_barcode='$dtbarcode', filial='$filial',data_analize=now(),cod_tec='$id',cod_defeito='$defeito',cod_solucao='$solucao',serie='$serie',	certificado='$certificado', obs='$obs', carencia='$carencia', os_fornecedor='$os',item_os_fornecedor='$item',cod_posicao=$posicao where cp.cod=$cp";		
}
	
if ($acao=="Alterar"){
	$msg="Altera��o de ";
	$sqlA=mysql_query("select serie,cod_tec,carencia,barcode from cp where cod = $cp")or die("Erro na consulta de num de s�rie em alterar scr_cp.php".mysql_error());	
	$pesqBarcode=mysql_result($sqlA,0,"barcode");

	if($pesqBarcode<>$barcode){// Se o valor de barcode no formul�rio foi alterado em rela��o ao cadastrado na base ent�o revalida o barcode
		validaBarcode($barcode,$codCliente);
	}

	$pesqSerie=mysql_result($sqlA,0,"serie");
	$pesqTec=mysql_result($sqlA,0,"cod_tec");
	$pesqCarencia=mysql_result($sqlA,0,"carencia");
	//Se a s�rie foi alterada, verifica se a nova s�rie est� em carencia ou se est� no box - Faz todas as consist�ncias da func�o Valida S�rie
	if ($serie<>$pesqSerie){
		$carencia=validaSerie($serie,$modelo);
		$carencia="carencia='$carencia',";// Substitui o valor de carencia(0 e 1) pois sera utilizado esta string na SQL3 abaixo
	}else{
		$carencia="carencia='$pesqCarencia',";
	}
	// Caso um gerente tente mudar um produto que esteja sob sua respons�bilidade para a respons�bilidade de outro t�cnico
	// sua a��o sera ineficaz pois o sistema n�o preencher� a variavel $tecnico, conforme script abaixo.
	if ($id<>$pesqTec){
		$sqlAlt=mysql_query("select altera_cp as alt from rh_cargo inner join rh_user on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id");
		$Alt=mysql_result($sqlAlt,0,"alt");
		if ($Alt==1){
			$codTec=$_POST["cmbTec"];
			$tecnico="cod_tec='$codTec',";
		}else{
			die("<h1>N�o foi poss�vel realizar esta opera��o! Somente seu gerente pode realiza-l�!");
		}
	}else{
		$tecnico="";
		$erro2="A identifica��o do t�cnico n�o foi alterara por uma quest�o de consist�ncia dos dados no sistema";
	}
	// Variaveis $carencia e $tecnico preenchidas nos scripts acima
	$sql3="update cp set defeito_reclamado='$defeitoR',cod_modelo='$modelo',barcode='$barcode',data_barcode='$dtbarcode', filial='$filial', 
	cod_defeito='$defeito',cod_solucao='$solucao',serie='$serie',$carencia $tecnico
	certificado='$certificado', obs='$obs',cod_posicao=$posicao
	where cp.cod=$cp";		
}
mysql_db_query ("$bd",$sql3,$Link) or die ("Erro na query de inser��o dos dados do Controle de Produ��o no banco de dados! $sql3 ".mysql_error());		
if (empty($erro2)){$erro2="";}
Header("Location:con_cp.php?cp=$cp&msg=$msg&erro=$erro2");
?>