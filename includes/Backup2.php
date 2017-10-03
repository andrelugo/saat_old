<?php 
require_once("..\sis_conn.php");	
define("IncluirDB",true); //Responsavel para fazer com que seja inserido o Banco de dados na sua instrução SQL

/* ----------------------------------------------------------------------------- 

+++++++++++++++++++++++++++++++++++++++++++ 
+::         LEIA COM ATENÇÃO!!!         ::+ 
+++++++++++++++++++++++++++++++++++++++++++ 

Eu alterei algumas coisas da v. 1.0 para torná-lo mais portável e para que funcionasse 
da maneira que eu precisava em qualquer situação, ele agora efetua o backup e na 
restauração nãodá erro se alguns dados já existirem, não exclui os dados existentes, 
não inclui registrosonde alguma chave seria duplicada, o que causaria erro no script 
SQL e pararia todo o processo, não dá mais erro se algum campo do BD contiver o caracter 
" ' ", o script gerado é capaz de gerar também o banco de dados, embora não dê erro se 
ele já existir, não perde a seleção do banco de dados quando executado por linha de comando 
ou em um frontend como o mysqlfront (para windows) ou o myAdmin (PHP). 

O que ele faz: Ele cria um arquivo de script SQL a partir de um banco de dados 
que é capaz de recriar o banco com toda a sua estrutura e dados, ótimo para backup 
de bancos de dados de tamanho médio. 

Para restaurar o backup vc pode executar o arquivo em uma mysql_query(), linha de comando 
ou a partir de um frontend para mysql. 

Funcionamento: basta incluir esse arquivo no seu controle de backups e chamar 
a função backupmysql de acordo com a sintaxe: 

backupmysql ( nome_do_bd , local_relativo_destino_arquivo [, endereco_de_email] ); 
onde: 
- nome_do_bd =                nome do banco de dados que vc quer criar o backup 

- local_relativo_destino =    local onde vc quer criar o arquivo de backup no 
                            servidor deve ser um endereço relativo, "." para 
                            o mesmo diretório do script, se você não quiser 
                            criar um arquivo no servidor, informe esse campo 
                            com um 0 (zero) apenas. 

- endereco_de_email =        endereço de e-mail para onde vc quer enviar uma cópia 
                            do arquivo se não for informado, não será enviado 
                            e-mail e o arquivo ficará no servidor apenas. 
retorno: 
A função retornará "sucesso" se for executada com sucesso ou uma mensagem de erro string 
sem formatação. 

Exemplo de chamada desta função: 
--------------------------------------- 
// Chamada da função armazenando o retorno em uma variável: 
$bkpbd = backupmysql("clientes",".","j.fast@tutopia.com.br"); 

// Verificação do retorno: 
if($bkpbd != "sucesso") 
    { 
    // Impressão da mensagem de erro na tela: 
    echo $bkpbd; 
    } 
else 
    { 
    echo "Backup criado com sucesso!"; 
    } 
--------------------------------------- 
Para incluir o Banco de Dados na hora de fazer o Backup basta setar a variavel para True ou False

---------------------------------------
+++++++++++++++++++++++++++++++++++++++++++
+::   Script desenvolvido por:          ::+
+::   Juarez Fiuza Junior (12/11/03)    ::+
+::   j.fast@tutopia.com.br             ::+
+::                                     ::+ 
+::    Implementação do GZIP            ::+  
+::    Rodrigo Lopes (30/04/06)         ::+
+::    rvl@ufrj.br                      ::+
+::                                     ::+ 
+::  Implementação de Banco de Dados    ::+  
+::    Leandro Vieira (04/05/05)        ::+
+::    levisants@yahoo.com.br           ::+
+++++++++++++++++++++++++++++++++++++++++++


Efetue a configuração para a sua conexão com o servidor mySQl abaixo 
nas linhas 76 a 79: 
----------------------------------------------------------------------------- */ 

function backupMysql($dbname,$local,$email) 
    { 
     
	// Coloque aqui os seus parâmetros para o servidor mySQL:
	$host = "localhost"; // host do servidor mySQL
	$usuario = "$u"; // usuário
	$senha = "$a"; // senha
     
     
    /* ----------------------------------------------------------------------------- 
    A partir daqui o script usa os dados acima e os passados na chamada da função 
    para gerar o backup, não é necessário qualquer alteração. 
    ----------------------------------------------------------------------------- */ 
    $signerro = ";\n<br>Leia as instruções de uso desse script no arquivo bkpmysql.php."; 
    if(!$dbname) 
        { 
        return "O nome do banco de dados precisa ser informado.".$signerro; 
        } 
    if(!$local and $local != 0) 
        { 
        return "O local onde o arquivo deve ser salvo precisa ser informado.".$signerro; 
        } 
    @$con = mysql_connect($host,$usuario,$senha); 
    if(!$con) 
        { 
        return "Erro ao conectar o servidor MySQL, é necessário configurar os dados de conexão no arquivo bkpmysql.php, linhas 76 a 79<br>;\nVerifique abaixo o erro reportado pelo servidor:<br>;\n".mysql_error(); 
        } 
     
    @$sel = mysql_select_db($dbname); 
    if(!$sel) 
        { 
        return "Erro ao selecionar o banco de dados: \"$dbname\"<br>;\nVerifique abaixo o erro reportado pelo servidor:<br>;\n".mysql_error(); 
        } 
     
    $fcont = "# Criando banco de dados : $dbname;\n\n"; 
    $fcont .= "CREATE DATABASE IF NOT EXISTS $dbname;\n"; 

    @$res = mysql_list_tables($dbname); // Pega a lista de todas as tabelas 
    if(!$res) 
        { 
        return "Não foi possível obter a lista de tabelas no banco de dados, verifique suas permissões no servidor MySQL.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
        } 
    while($row = mysql_fetch_row($res)) 
        { 
        $table = $row[0]; // cada uma das tabelas 
        @$res2 = mysql_query("SHOW CREATE TABLE $table"); 
        if(!$res2) 
            { 
            return "Não foi possível obter a estrutura das tabelas no banco de dados, verifique suas permissões no servidor MySQL.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
            } 
        while($lin = mysql_fetch_row($res2)) 
            { // Para cada tabela 
            $fcont .= "# Criando tabela: $table;\n"; 
            $create_table = str_replace("`","",$lin[1]); 
            $comando = substr($create_table,0,13); 
			
			if (IncluirDB==true):
    	        $comando .= "IF NOT EXISTS ".$dbname.".".substr($create_table,13,strlen($create_table)); 
			else:
 	           $comando .= "IF NOT EXISTS ".substr($create_table,13,strlen($create_table)); 			
			endif;
			
            $fcont .= "$comando;\n# Dump de Dados;\n"; 
            @$res3 = mysql_query("SELECT * FROM $table"); 
            if(!$res3) 
                { 
                return "Não foi possível selecionar os dados da tabela $table.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
                } 
            while($r=mysql_fetch_row($res3)) 
                { // Dump de todos os dados das tabelas 
                $ct = count($r); 
                for($i = 0;$i < $ct;$i ++) 
                    { 
                    $r[$i] = addslashes($r[$i]); 
                    } 
					if (IncluirDB==true):					
						$sql="INSERT IGNORE INTO $dbname.$table VALUES ('"; 
					else:
						$sql="INSERT IGNORE INTO $table VALUES ('"; 					
					endif;	
                $sql .= implode("','",$r); 
                $sql .= "');\n"; 
                $fcont .= $sql;
                } 
            } //FIM DO WHILE DOS REGISTROS 
			
        } 
		
	// Criação do arquivo no servidor (se informado um endereço na chamada
	// da função no 2º parâmetro diferente de 0
	if($local != "0")
	{
	if ($gzipado)
		{
			$arquivo = $local."/$dbname.sql.gz";
			@$back = gzopen($arquivo, "w"); // Abre com compressão máxima
			if(!$back)
			{
				return "Ocorreu um erro ao criar o arquivo de backup dos dados no servidor, verifique o local informado e as permissões para esse diretório.".$signerro;
			}
			@$escreve = gzwrite($back,$fcont);
			if(!$escreve)
			{
				return "Não foi possível escrever no arquivo de backup no servidor.;\n<br>Possíveis causas para isso incluem problemas com o servidor ou com o script.;\n<br>Tente novamente e se o problema persistir, contate o administrador.";
			}
			gzclose($back);
			
			// Calibrar $fcont com o conteúdo zipado
			$fp = fopen($arquivo,"r");
			$fcont = fread($fp, filesize($arquivo));
			fclose($fp);
			$fcont = imap_binary($fcont);
			
		}
		
		else 
		{
		
			$arquivo = $local."/$dbname.sql";
			@$back = fopen($arquivo,"w");
			if(!$back)
			{
				return "Ocorreu um erro ao criar o arquivo de backup dos dados no servidor, verifique o local informado e as permissões para esse diretório.".$signerro;
			}
			@$escreve = fwrite($back,$fcont);
			if(!$escreve)
			{
				return "Não foi possível escrever no arquivo de backup no servidor.;\n<br>Possíveis causas para isso incluem problemas com o servidor ou com o script.;\n<br>Tente novamente e se o problema persistir, contate o administrador.";
			}
			fclose($back);
		}
		
		$nome_do_arquivo = ($gzipado) ? "$dbname.sql.gz" : "$dbname.sql";
	}
     
    // Verifica o endereço de e-mail 
    if($email) 
        { 
        if(!(ereg("^([0-9,a-z,A-Z]+)([.,_]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$",$email))) 
            { 
            return "O endereço de e-mail informado é inválido, o arquivo de backup foi gerado e está localizado no servidor.".$signerro; 
            } 
         
        $boundary = "XYZ-" . date("dmyhms") . "-ZYX"; 
         
        $message = "--".$boundary."\n"; 
        $message .= "Content-Transfer-Encoding: 8bits\n"; 

        $message .= "Content-Type: text/html; charset=iso-8859-1\n\n"; 
        $message .= "<font face='verdana' size=2 color=#000000>"; 
        $message .= "Backup do banco de dados $dbname em arquivo anexo.<br><br>"; 
        $message .= "Para restaurar o backup vc pode executar o arquivo em uma query SQL, linha de comando ou a partir de um frontend para mysql."; 
		$message .= "<br><br><br><br><font size=1>BKP MySQL by Juarez Fiuza Junior<br><a href=mailto:j.fast@tutopia.com.br>j.fast@tutopia.com.br</a><br><br></font></font>";
		$message .= "<br><font size=1>Implementação do Gzip by Rodrigo Lopes<br><a href=mailto:rod.001@ig.com.br>rod.001@ig.com.br</a><br><br></font></font>";
		$message .= "<br><font size=1>Implementação do Banco Leandro Vieira<br><a href=mailto:levisants@yahoo.com.br>levisants@yahoo.com.br</a><br><br></font></font>";

        $message .= "\r\n\r\n"; 

        $subject = "Backup do banco de dados $dbname"; 

        $message .= "--".$boundary."\n"; 
        $message .= "Content-Type: text/plain\n"; 
        $message .= "Content-Disposition: attachment; filename=\"$dbname.sql\" \n"; 
        $message .= $fcont."\n"; 
        $message .= "--".$boundary."--\r\n"; 

        $to = $email; 
        $nomefrom = "MySQL"; 
        $emailfrom = "seu-email@seu-provedor.com.br"; 
         
        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "From: $nomefrom <$emailfrom>\r\n"; 
        $headers .= "Reply-to: <$emailfrom>\r\n"; 
        $headers .= "Return-path: <$emailfrom>\r\n"; 
        $headers .= "X-Sender: <$emailfrom>\r\n"; 
        $headers .= "X-Mailer: Proj/PHP\r\n"; 
        $headers .= "X-Priority: 3\r\n"; 
        $headers .= "Content-type: multipart/mixed; boundary=\"".$boundary."\"\r\n"; 

        if(!(mail($to, $subject, $message, $headers))) 
            { 
            return "Ocorreu um erro ao tentar enviar o e-mail, se um local foi informado o arquivo de backup foi gerado e está localizado no servidor.".$signerro; 
            } 
        } 
    return "sucesso"; 
    } 
/* -----------------------------------------------------------------------------
Fim do Script
+++++++++++++++++++++++++++++++++++++++++++
+::   Script desenvolvido por:          ::+
+::   Juarez Fiuza Junior (12/11/03)    ::+
+::   j.fast@tutopia.com.br             ::+
+::                                     ::+ 
+::    Implementação do GZIP            ::+  
+::    Rodrigo Lopes (30/04/06)         ::+
+::    rvl@ufrj.br                      ::+
+::                                     ::+ 
+::  Implementação de Banco de Dados    ::+  
+::    Leandro Vieira (04/05/05)        ::+
+::    levisants@yahoo.com.br           ::+
+++++++++++++++++++++++++++++++++++++++++++
----------------------------------------------------------------------------- */
$bkpbd = backupMysql($bd,"/bkp","penhatv@ig.com.br");

// Verificação do retorno: 
if($bkpbd != "sucesso") 
    { 
    // Impressão da mensagem de erro na tela: 
    echo $bkpbd; 
    } 
else 
    { 
    echo "Backup criado com sucesso!"; 
    } 
?> 
