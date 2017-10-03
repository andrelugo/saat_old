if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saat.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=85);";if(IE5) tr="";
document.write(".saat_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1c437b;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saat_plain:link, a.saat_plain:visited{text-align:left;background-color:#1c437b;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saat_plain:hover, a.saat_plain:active{background-color:#8cc5d5;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x8cc5d5;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saat_b1",".gif",29,133,loc+"mnu_entrada.php","mainFrame","Menu de Entrada de Produtos",2,2,"saat_plain");
mainMenuItem("img/botoes/saat_b2",".gif",29,133,loc+"mnu_cp.php","mainFrame","Menu de Acesso a Planilha Técnica",2,2,"saat_plain");
mainMenuItem("img/botoes/saat_b3",".gif",29,133,loc+"mnu_cq.php","mainFrame","Menu de Acesso a planilha de Controle de Qualidade",2,2,"saat_plain");
mainMenuItem("img/botoes/saat_b4",".gif",29,133,loc+"frm_conprod.php","mainFrame","Consultar um Controle de Proução",2,2,"saat_plain");
mainMenuItem("img/botoes/saat_b5",".gif",29,133,loc+"frameadm.php","mainFrame","Menu de Acesso restrito a dados cadastrais",2,2,"saat_plain");
mainMenuItem("img/botoes/saat_b6",".gif",29,133,loc+"javascript:window.close();","_top","Sair do SAAT II",2,2,"saat_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saat_b4","saat_menu",133);
submenuItem("Controle de Produção",loc+"frm_conprod.php","mainFrame","saat_plain");
endSubmenu("img/botoes/saat_b4");

startSubmenu("img/botoes/saat_b3","saat_menu",144);
submenuItem("Produtos Prontos",loc+"frm_sairg.php","mainFrame","saat_plain");
submenuItem("Produtos Reprovados",loc+"frm_reprg.php","mainFrame","saat_plain");
submenuItem("Consultar Produtos Prontos",loc+"con_prontoscq.php","mainFrame","saat_plain");
submenuItem("Gerar Planilha de Saída",loc+"scr_fecha_cq.php","mainFrame","saat_plain");
endSubmenu("img/botoes/saat_b3");

startSubmenu("img/botoes/saat_b2","saat_menu",133);
submenuItem("Minhas Pendências",loc+"con_pendenciatec.php","mainFrame","saat_plain");
endSubmenu("img/botoes/saat_b2");

//startSubmenu("img/botoes/saat_b1","saat_menu",155);
//submenuItem("Entrada por Código de Barras",loc+"frm_entrarg.php","mainFrame","saat_plain");
//endSubmenu("img/botoes/saat_b1");

loc="";
