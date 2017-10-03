<?
// Este script começou a ser contruid em 30 de Julho de 2006
//inicialmente vai realizra uma cópia binária das tabelas do banco através do commando backup para o diretório especificado no formulário
require_once("sis_valida.php");
require_once("sis_conn.php");
require("includes/zip.lib.php");
$pastaDe1=$_POST["txtPasta"];
$pastaDe=str_replace("\\","/",$pastaDe1);
if (!file_exists($pastaDe)) {
	die("<h1><font color=red>A pasta de destino $pastaDe não existe!!! informe o caminho correto ou crie esta pasta antes de iniciar o Back-Up!");
}
$zip= new zipfile;
?>
<html>
<head></head> 
<body>
Back-Up Gerado na pasta: <? print($pastaDe);?>
<br>RESULTADO
<table width="800" border="1" align="center">
  <tr>
    <td width="248">Table</td>
    <td width="123">Op</td>
    <td width="121">Msg_type</td>
    <td width="280">Msg_text</td>
    <td width="280">A1</td>
    <td width="280">A2</td>
  </tr>
<?
$erro=0;
$sql="show tables";
$res=mysql_db_query ("$bd",$sql,$Link) or die (mysql_error());
while ($linha = mysql_fetch_array($res)){
	// EXCLUINDO ARQUIVOS DA PASTA DESTINO
	$arquivo1="$pastaDe$linha[Tables_in_ptvccbd].MYD";
	$arquivo2="$pastaDe$linha[Tables_in_ptvccbd].FRM";
	if (file_exists($arquivo1)) {
		$a1="$arquivo1 apagado e atualizado";
		unlink($arquivo1);
	}else{
		$a1="$arquivo1 não existi ATUALIZADO";
	}
	if (file_exists($arquivo2)) {
		$a2="$arquivo2 apagado e atualizado";
		unlink($arquivo2);
	}else{
		$a2="$arquivo2 não existi ATUALIZADO";
	}
	// FIM EXCLUINDO
	
	$sql2="backup table $linha[Tables_in_ptvccbd] to '$pastaDe'";
	$res2=mysql_db_query ("$bd",$sql2,$Link) or die (mysql_error());
	
	// ZIPANDO OS ARQUIVOS COPIADOS parte 1 loopng
	$arquivo1s="bkp/$linha[Tables_in_ptvccbd].MYD";
	$arquivo2s="bkp/$linha[Tables_in_ptvccbd].FRM";
	
	copy($arquivo1, $arquivo1s);
	copy($arquivo2, $arquivo2s); //Como não consegui gerar zip de outras pastas etnão copiei p/ uma pasta do servidor
	
	$abre = fopen($arquivo2s, "rb");
	$compactado = fread($abre, filesize($arquivo2s)); //string contendo o arquivo a ser compactado
	fclose($abre);
	$zip->addFile($compactado,"$arquivo2s"); //adiciona um arquivo ao zip

	$abre = fopen($arquivo1s, "rb");
	$compactado = fread($abre, filesize($arquivo1s)); //string contendo o arquivo a ser compactado
	fclose($abre);
	$zip->addFile($compactado,"$arquivo1s"); //adiciona um arquivo ao zip


	//FIM DO LOOPING ZIPANDO ARQUIVOS COPIADOS O FINAL DO CÓDIGO ZIPANDO ESTA NO FINAL DESTE SCRIPT parte 1 looping
	while ($linha2 = mysql_fetch_array($res2)){		
		?>
		  <tr>
			<td><? print($linha2["Table"]);?></td>
			<td><? print($linha2["Op"]);?></td>
			<td><? print($linha2["Msg_type"]);?></td>
			<td><? print($linha2["Msg_text"]);
			if($linha2["Msg_text"]<>"OK"){$erro++;}?></td>
		  <TD><? print($a1);?></TD>
		  <TD><? print($a2);?></TD>
		  </tr>
		<?
	}
}
if($erro==0){
	mysql_query("update base set pasta_backup = '$pastaDe',ultimo_backup = now()");
	print("<h3><font color=blue>Back-Up realizado com sucesso!!!!</font></h3>");
}else{
	print("<h3><font color=red>$erro Erro(s) encontrado(s) ao realizar o Backup consulte o analista do sistema</font></h3>");
}
// ZIPANDO OS ARQUIVOS COPIADOS parte 1 loopng
//$arquivo2="t/teste.txt";
//$abre = fopen($arquivo2,"rb");
//$compactado = fread($abre, filesize($arquivo2)); //string contendo o arquivo a ser compactado
//fclose($abre);
//$zip->addFile($compactado,"teste/$arquivo2"); //adiciona um arquivo ao zip
//FIM DO LOOPING ZIPANDO ARQUIVOS COPIADOS O FINAL DO CÓDIGO ZIPANDO ESTA NO FINAL DESTE SCRIPT parte 1 looping

// 2 parte gerando ZIP
$strzip=$zip->file(); //string contendo o arquivo zip
$arq="bkp/backup teste.zip";
$abre = fopen($arq, "wb");
$salva = fwrite($abre, $strzip);
fclose($abre);
// fim 2 parte gerando ZIP
?>
<a href="<? print($arq);?>">Baixar cópia do Back-Up</a>
</table>
</body> 
</html> 