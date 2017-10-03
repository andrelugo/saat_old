<p>
<?
//$previous_name = session_name("WebsiteID");
print("Cod usuário logado = $id <br>");
print("Session_module_name =".session_module_name()."<br>");
echo "Session_name =".session_name()."<br>";

session_start();

print("<br>SID =".SID."<br>");
print("Session_id =".session_id()."<br>");

$_SESSION['favcolor'] = 'green';
$_SESSION['animal']   = 'cat';
$_SESSION['time']     = time();

// Funciona se o cookie de seção foi aceito
echo '<br /><a href="page2.php">page 2</a>';

// Ou talves passando o ID da seção se necessário
echo '<br /><a href="page2.php?' . SID . '">page 2</a>';


?>&nbsp;</p>

