<?php 
require_once("..\sis_conn.php");	
define("IncluirDB",true); //Responsavel para fazer com que seja inserido o Banco de dados na sua instru��o SQL

/* ----------------------------------------------------------------------------- 

+++++++++++++++++++++++++++++++++++++++++++ 
+::         LEIA COM ATEN��O!!!         ::+ 
+++++++++++++++++++++++++++++++++++++++++++ 

Eu alterei algumas coisas da v. 1.0 para torn�-lo mais port�vel e para que funcionasse 
da maneira que eu precisava em qualquer situa��o, ele agora efetua o backup e na 
restaura��o n�od� erro se alguns dados j� existirem, n�o exclui os dados existentes, 
n�o inclui registrosonde alguma chave seria duplicada, o que causaria erro no script 
SQL e pararia todo o processo, n�o d� mais erro se algum campo do BD contiver o caracter 
" ' ", o script gerado � capaz de gerar tamb�m o banco de dados, embora n�o d� erro se 
ele j� existir, n�o perde a sele��o do banco de dados quando executado por linha de comando 
ou em um frontend como o mysqlfront (para windows) ou o myAdmin (PHP). 

O que ele faz: Ele cria um arquivo de script SQL a partir de um banco de dados 
que � capaz de recriar o banco com toda a sua estrutura e dados, �timo para backup 
de bancos de dados de tamanho m�dio. 

Para restaurar o backup vc pode executar o arquivo em uma mysql_query(), linha de comando 
ou a partir de um frontend para mysql. 

Funcionamento: basta incluir esse arquivo no seu controle de backups e chamar 
a fun��o backupmysql de acordo com a sintaxe: 

backupmysql ( nome_do_bd , local_relativo_destino_arquivo [, endereco_de_email] ); 
onde: 
- nome_do_bd =                nome do banco de dados que vc quer criar o backup 

- local_relativo_destino =    local onde vc quer criar o arquivo de backup no 
                            servidor deve ser um endere�o relativo, "." para 
                            o mesmo diret�rio do script, se voc� n�o quiser 
                            criar um arquivo no servidor, informe esse campo 
                            com um 0 (zero) apenas. 

- endereco_de_email =        endere�o de e-mail para onde vc quer enviar uma c�pia 
                            do arquivo se n�o for informado, n�o ser� enviado 
                            e-mail e o arquivo ficar� no servidor apenas. 
retorno: 
A fun��o retornar� "sucesso" se for executada com sucesso ou uma mensagem de erro string 
sem formata��o. 

Exemplo de chamada desta fun��o: 
--------------------------------------- 
// Chamada da fun��o armazenando o retorno em uma vari�vel: 
$bkpbd = backupmysql("clientes",".","j.fast@tutopia.com.br"); 

// Verifica��o do retorno: 
if($bkpbd != "sucesso") 
    { 
    // Impress�o da mensagem de erro na tela: 
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
+::    Implementa��o do GZIP            ::+  
+::    Rodrigo Lopes (30/04/06)         ::+
+::    rvl@ufrj.br                      ::+
+::                                     ::+ 
+::  Implementa��o de Banco de Dados    ::+  
+::    Leandro Vieira (04/05/05)        ::+
+::    levisants@yahoo.com.br           ::+
+++++++++++++++++++++++++++++++++++++++++++


Efetue a configura��o para a sua conex�o com o servidor mySQl abaixo 
nas linhas 76 a 79: 
----------------------------------------------------------------------------- */ 

function backupMysql($dbname,$local,$email) 
    { 
     
	// Coloque aqui os seus par�metros para o servidor mySQL:
	$host = "localhost"; // host do servidor mySQL
	$usuario = "$u"; // usu�rio
	$senha = "$a"; // senha
     
     
    /* ----------------------------------------------------------------------------- 
    A partir daqui o script usa os dados acima e os passados na chamada da fun��o 
    para gerar o backup, n�o � necess�rio qualquer altera��o. 
    ----------------------------------------------------------------------------- */ 
    $signerro = ";\n<br>Leia as instru��es de uso desse script no arquivo bkpmysql.php."; 
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
        return "Erro ao conectar o servidor MySQL, � necess�rio configurar os dados de conex�o no arquivo bkpmysql.php, linhas 76 a 79<br>;\nVerifique abaixo o erro reportado pelo servidor:<br>;\n".mysql_error(); 
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
        return "N�o foi poss�vel obter a lista de tabelas no banco de dados, verifique suas permiss�es no servidor MySQL.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
        } 
    while($row = mysql_fetch_row($res)) 
        { 
        $table = $row[0]; // cada uma das tabelas 
        @$res2 = mysql_query("SHOW CREATE TABLE $table"); 
        if(!$res2) 
            { 
            return "N�o foi poss�vel obter a estrutura das tabelas no banco de dados, verifique suas permiss�es no servidor MySQL.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
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
                return "N�o foi poss�vel selecionar os dados da tabela $table.;\n<br>Verifique abaixo o erro gerado pelo servidor:;\n<br>".mysql_error().$signerro; 
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
		
	// Cria��o do arquivo no servidor (se informado um endere�o na chamada
	// da fun��o no 2� par�metro diferente de 0
	if($local != "0")
	{
	if ($gzipado)
		{
			$arquivo = $local."/$dbname.sql.gz";
			@$back = gzopen($arquivo, "w"); // Abre com compress�o m�xima
			if(!$back)
			{
				return "Ocorreu um erro ao criar o arquivo de backup dos dados no servidor, verifique o local informado e as permiss�es para esse diret�rio.".$signerro;
			}
			@$escreve = gzwrite($back,$fcont);
			if(!$escreve)
			{
				return "N�o foi poss�vel escrever no arquivo de backup no servidor.;\n<br>Poss�veis causas para isso incluem problemas com o servidor ou com o script.;\n<br>Tente novamente e se o problema persistir, contate o administrador.";
			}
			gzclose($back);
			
			// Calibrar $fcont com o conte�do zipado
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
				return "Ocorreu um erro ao criar o arquivo de backup dos dados no servidor, verifique o local informado e as permiss�es para esse diret�rio.".$signerro;
			}
			@$escreve = fwrite($back,$fcont);
			if(!$escreve)
			{
				return "N�o foi poss�vel escrever no arquivo de backup no servidor.;\n<br>Poss�veis causas para isso incluem problemas com o servidor ou com o script.;\n<br>Tente novamente e se o problema persistir, contate o administrador.";
			}
			fclose($back);
		}
		
		$nome_do_arquivo = ($gzipado) ? "$dbname.sql.gz" : "$dbname.sql";
	}
     
    // Verifica o endere�o de e-mail 
    if($email) 
        { 
        if(!(ereg("^([0-9,a-z,A-Z]+)([.,_]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$",$email))) 
            { 
            return "O endere�o de e-mail informado � inv�lido, o arquivo de backup foi gerado e est� localizado no servidor.".$signerro; 
            } 
         
        $boundary = "XYZ-" . date("dmyhms") . "-ZYX"; 
         
        $message = "--".$boundary."\n"; 
        $message .= "Content-Transfer-Encoding: 8bits\n"; 

        $message .= "Content-Type: text/html; charset=iso-8859-1\n\n"; 
        $message .= "<font face='verdana' size=2 color=#000000>"; 
        $message .= "Backup do banco de dados $dbname em arquivo anexo.<br><br>"; 
        $message .= "Para restaurar o backup vc pode executar o arquivo em uma query SQL, linha de comando ou a partir de um frontend para mysql."; 
		$message .= "<br><br><br><br><font size=1>BKP MySQL by Juarez Fiuza Junior<br><a href=mailto:j.fast@tutopia.com.br>j.fast@tutopia.com.br</a><br><br></font></font>";
		$message .= "<br><font size=1>Implementa��o do Gzip by Rodrigo Lopes<br><a href=mailto:rod.001@ig.com.br>rod.001@ig.com.br</a><br><br></font></font>";
		$message .= "<br><font size=1>Implementa��o do Banco Leandro Vieira<br><a href=mailto:levisants@yahoo.com.br>levisants@yahoo.com.br</a><br><br></font></font>";

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
            return "Ocorreu um erro ao tentar enviar o e-mail, se um local foi informado o arquivo de backup foi gerado e est� localizado no servidor.".$signerro; 
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
+::    Implementa��o do GZIP            ::+  
+::    Rodrigo Lopes (30/04/06)         ::+
+::    rvl@ufrj.br                      ::+
+::                                     ::+ 
+::  Implementa��o de Banco de Dados    ::+  
+::    Leandro Vieira (04/05/05)        ::+
+::    levisants@yahoo.com.br           ::+
+++++++++++++++++++++++++++++++++++++++++++
----------------------------------------------------------------------------- */
$bkpbd = backupMysql($bd,"/bkp","penhatv@ig.com.br");

// Verifica��o do retorno: 
if($bkpbd != "sucesso") 
    { 
    // Impress�o da mensagem de erro na tela: 
    echo $bkpbd; 
    } 
else 
    { 
    echo "Backup criado com sucesso!"; 
    } 
?> 
