<?
// Erros que podem ocorrer nesta p�gina
// um chamado ser registrado duas vezes
// cp ser de um fornecedor diferente de nova data
require_once("sis_valida.php");
require_once("sis_conn.php");
if ($_COOKIE["adm"]<>1){$erro=("N�o h� privil�gios de Administrador para sua conta!");}
if (!$_POST["txtOs"]==""){$os=$_POST["txtOs"];}else{$erro="N�mero do chamado n�o preenchido";$os="";}
if (!$_POST["txtSerie"]==""){$serie=$_POST["txtSerie"];}else{$serie="";}
if (!$_POST["txtCp"]==""){$cp=$_POST["txtCp"];}else{$cp="";}
if ($cp=="" && $serie==""){$erro="ERRO: Controle de Produ��o e S�rie vazios! <br> Imposs�vel realizar a busca no banco!";}
if ($cp<>"" && $serie<>""){$erro="ERRO: Controle de Produ��o e S�rie preenchidos concomitantemente! <br> Imposs�vel realizar a busca no banco! <br> Preencha apenas um campo por vez!";}
if (isset($erro)){Header("Location:frm_os_novadata.php?erro=$erro&cp=$cp&serie=$serie&os=$os");exit;}


//  // verificando se a ordem de servi�os informada j� foi inserida no banco pela coluna OS_FORNECEDOR
$sql=mysql_query("select cod from cp where os_fornecedor='$os'")or die(mysql_error()."erro 1");
$row=mysql_num_rows($sql);
if ($row>=1){
	$cod=mysql_result($sql,0,"cod");
	$erro="Este chamado j� est� cadastrado para o controle de produ��o $cod !";
} 
//  // verificando se o CP � Nova Data e buscado a chave prim�ria ao mesmo tempo
if ($serie==""){
	$sql2="select cp.cod as cp, cod_fornecedor from cp inner join modelo on modelo.cod = cp.cod_modelo
	where cp.cod='$cp'";
}else{
	$sql2="select max(cp.cod) as cp, cod_fornecedor from cp inner join modelo on modelo.cod = cp.cod_modelo
	where cp.serie='$serie'
	group by cod_fornecedor";
}
$res2=mysql_query($sql2)or die(mysql_error());
$rows=mysql_num_rows($res2);
if ($rows>=1){
	$forne=mysql_result($res2,0,"cod_fornecedor");
	$cp2=mysql_result($res2,0,"cp");
	if ($forne<>3){
		$erro="O controle de produ��o $cp n�o pertence a um produto Nova Data";
	}
}else{
	$erro="Nenhum regisistro encontrado p/ o N. de s�rie <font color='blue'>$serie</font> ou cp <font color='blue'>$cp</font> informados";

}
//erro2
if (isset($erro)){Header("Location:frm_os_novadata.php?erro=$erro&cp=$cp&serie=$serie&os=$os");exit;}
// se n�o houver nenhum erro ent�o...
$sql=mysql_query("update cp set os_fornecedor='$os' where cod='$cp2'")or die(mysql_error());
Header("Location:frm_os_novadata.php?erro=Dados atuualizados com �xito!")
?>
