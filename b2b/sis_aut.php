<?
require_once("sis_conn.php");
if (empty($_POST["txtUser"])){die("Nome não preenchido!");}else{$usuario=$_POST["txtUser"];}
$senha=$_POST["txtSenha"];
//$senha=md5($senha);
$sqlF=mysql_query("SELECT fornecedor.NOME AS NOME,fornecedor.COD AS ID 
from fornecedor
where fornecedor.LOGIN='$usuario'")or die("Erro no Camando 1 SQL pág sis_aut.php<br>".mysql_error());
$rowF=mysql_num_rows($sqlF);
if($rowF==0){
	echo "Usuário Inválido";
	exit;
}else{
		$sql1=mysql_query("SELECT fornecedor.NOME AS NOME,fornecedor.COD AS ID 
		from fornecedor
		where fornecedor.LOGIN='$usuario' and fornecedor.senha='$senha'")or die("Erro no Camando 2 SQL pág sis_aut.php<br>".mysql_error());
		$row1=mysql_num_rows($sql1);
		if($row1==0){
			$senha=md5($senha);
			$sqlU=mysql_query("SELECT NOME,adm from rh_user inner join rh_cargo on rh_cargo.cod=rh_user.cargo where senha='$senha'")or die("Erro no Camando 3SQL pág sis_aut.php<br>".mysql_error());
			$rowU=mysql_num_rows($sqlU);
			if ($rowU==0){
				echo "Senha Inválida";
				exit;
			}else{
				$adm=mysql_result($sqlU,0,"adm");
				if ($adm==0){
					$nome=mysql_result($sqlU,0,"nome");
					echo "$nome não é Administrador - ACESSO NEGADO";
					exit;
				}else{
					//É administrador e portanto tem acesso aos dados do fornecedor escolhido
					$user = mysql_result($sqlU,0,"nome");
					$id = mysql_result($sqlF,0,"id");
					$nome = mysql_result($sqlF,0,"nome");
					setcookie("idf",$id);
					setcookie("adm",$adm);
					setcookie("nome","$nome - ADM $user");
					Header("Location:frame.php");
				}
			}
		}else{
			//É o fornecedor se conectando com seu login e senha corretos
			$id = mysql_result($sql1,0,"id");
			$nome = mysql_result($sql1,0,"nome");
			setcookie("idf",$id);	
			setcookie("nome",$nome);
			setcookie("adm",0);
			Header("Location:frame.php");
		}
}
?>