<?
require_once("sis_valida.php");
require_once("sis_conn.php");
$res=mysql_query("SELECT rh_cargo.adm as adm from rh_user inner join rh_cargo on rh_user.cargo = rh_cargo.cod where rh_user.cod=$id")or die(mysql_error());
$adm=mysql_result($res,0,"adm");
if ($adm<>1){print("OPERAÇÃO PERMITIDA SOMENTE PARA ADMINISTRAÇÃO");exit;}
if ($_POST["txtDescricao"]<>""){$descricao=$_POST["txtDescricao"];}else{$erro="Descrição não Preenchida";}
if (isset($_POST["txtRegistro"])){$registro=$_POST["txtRegistro"];}
if (isset($_POST["cmbTipo"])){$tipo=$_POST["cmbTipo"];}
if ($tipo=="Z"){$erro="Tipo de Registro de Saída não preenchido";}
if (isset($_POST["txtQtOs"])){$qtOs=$_POST["txtQtOs"];}
if (isset($_POST["txtObs"])){$obs=$_POST["txtObs"];}

if (isset($_POST["txtDiaReg"])){$dia=$_POST["txtDiaReg"];}else{$dia="";}
if (isset($_POST["txtMesReg"])){$mes=$_POST["txtMesReg"];}else{$mes="";}
if (isset($_POST["txtAnoReg"])){$ano=$_POST["txtAnoReg"];}else{$ano="";}

if (isset($_POST["txtValor"])){$valor=$_POST["txtValor"];}else{$valor="";}

if (isset($_POST["cod"])){$cod=$_POST["cod"];}else{$cod="";}
$dataReg="$ano-$mes-$dia";
$acao=$_POST["Envia"];
if (isset($erro)){
	header("Location:frm_fechamento.php?cod=$cod&msg=<h1><center><font color='red'>$erro</font></center></h1>");	
	exit;
}
		if ($acao=="Alterar"){
			$sql="update fechamento_reg set descricao='$descricao' , tipo='$tipo',valor='$valor',
			registro='$registro',data_registro='$dataReg',obs='$obs',qt_os='$qtOs'
			where fechamento_reg.cod = $cod";
			$msg="A Atualização dos dados do $descricao foi realizada com sucesso!";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
		}
		if ($acao=="Cadastrar"){
			$sql="insert into fechamento_reg (valor,descricao,tipo,registro,data_registro,obs,qt_os,data_abre,cod_colab_abre)
			values ('$valor','$descricao','$tipo','$registro','$dataReg','$obs','$qtOs',now(),$id)";
			mysql_db_query ("$bd",$sql,$Link) or die ("Erro na Inserção $sql".mysql_error());
			$sql1=mysql_query("select max(cod) as cod from fechamento_reg");
			$codigo = mysql_result($sql1,0,"cod") or die ("Erro na consulta de código".mysql_error());
			$msg="O Cadastro do $descricao foi realizado sob código $codigo com sucesso!";
			header("Location:frm_reg.php?codf=$codigo");
			exit;

		}
		if ($acao=="Encerrar"){
			//Fazer as consistencia antes de fechar
			//Inclusive as cconsistencias de destino
			$res=mysql_query("select count(cod) as tot from cp where cod_fechamento_reg=$cod");
			$tot=mysql_result($res,0,"tot");
			if ("$tot"<>"$qtOs"){
				$erro="ERRO! A Quantidade de OS Informada no cadastro de registro é $qtOs e o numero de produtos cadastrados neste Fechamento é de $tot. Confira com cautela antes de Encerrar o Fechamento.";
			}
			if ($registro==""){$erro="Registro não Preenchido Impossivel fechar";}
			if ($dataReg==""){$erro="Data do Registro não Preenchido Impossivel fechar";}
			if ($valor==""){$erro="Valor R$ total do registro não Preenchido Impossivel fechar";}

			if (isset($erro)){
				header("Location:frm_fechamento.php?cod=$cod&msg=<h1><center><font color='red'>$erro</font></center></h1>");	
				exit;
			}
				$sql="update fechamento_reg set descricao='$descricao' , tipo='$tipo',valor='$valor',
				registro='$registro',data_registro='$dataReg',obs='$obs',qt_os='$qtOs',cod_colab_fecha='$id',data_fecha=now()
				where fechamento_reg.cod = $cod";
				$msg="<font color='blue'>O encerramento do fechamento $descricao foi realizado com sucesso!</font>";
				mysql_db_query ("$bd",$sql,$Link) or die ("Erro na ATUALIZAÇÃO $sql <BR> ".mysql_error());	
		}
header("Location:frm_fechamento.php?msg=$msg");
?>