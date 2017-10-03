if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML.toLowerCase();var last=tt.indexOf("saatetc.js\"");if(last>0){var first=tt.lastIndexOf("\"",last);if(first>0&&first<last)loc=document.body.innerHTML.substr(first+1,last-first-1);}}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
var tr="filter:alpha(opacity=90);";if(IE5) tr="";
document.write(".saatetc_menu {"+tr+"border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#3399ff;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write("a.saatetc_plain:link, a.saatetc_plain:visited{text-align:left;background-color:#3399ff;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.saatetc_plain:hover, a.saatetc_plain:active{background-color:#c5b899;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:0px 0px 0px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0xc5b899;
if(typeof(frames)=="undefined"){var frames=4;if(frames>0)animate();}

startMainMenu("",0,0,2,0,0)
mainMenuItem("img/botoes/saatetc_b1",".gif",25,100,"javascript:;","","Gerenciamento do SAAT",2,2,"saatetc_plain");
endMainMenu("",0,0);

startSubmenu("img/botoes/saatetc_b1","saatetc_menu",103);
submenuItem("Entrada simples",loc+"frm_entraqt.php","adm_mainFrame","saatetc_plain");
submenuItem("Mover produtos",loc+"frm_poscp.php","adm_mainFrame","saatsai_plain");
submenuItem("Excluir produtos",loc+"frm_excluircp.php","adm_mainFrame","saatsai_plain");
submenuItem("OS Carga",loc+"frm_carga.php","adm_mainFrame","saatetc_plain");
submenuItem("OS Manual",loc+"mnu_os.php","adm_mainFrame","saatetc_plain");
submenuItem("N.F. Peca",loc+"frm_nf_peca.php","adm_mainFrame","saatetc_plain");
submenuItem("Back Up",loc+"frm_backup.php","adm_mainFrame","saatetc_plain");
submenuItem("Imprimir",loc+"frm_pdf.php","adm_mainFrame","saatsai_plain");
endSubmenu("img/botoes/saatetc_b1");

loc="";
