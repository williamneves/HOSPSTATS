var ifila=0
var fila=new Array();var iframe=false;var callbackReturn=null;var callbackErrorFunction=typeof(toastr)!=='object'?false:true;BASE=typeof BASE!=="undefined"?(BASE=="/")?"":BASE:"";var img_loader_ajax_pequena=BASE+"/visao/img/loader.gif";var img_loader_ajax_grande=BASE+"/visao/img/loader.gif";try
{xmlhttp=new XMLHttpRequest();try
{if(xmlhttp.overrideMimeType)
{xmlhttp.overrideMimeType('text/xml');}}catch(e1){xmlhttp=false;}}
catch(e2)
{try
{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e3)
{try
{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
catch(e4)
{xmlhttp=false;}}}
if(!xmlhttp)
{console.log("Erro AJAX. Seu navegador nao suporta objeto XMLHttpRequest, mudando metodo de envio para iframe.");AjaxIFrame();}
verificarDivCarregando();if(typeof JSON!=='object')
{var objScript=document.createElement("script");objScript.src=BASE+"/visao/js/plugins/ajax/json2.min.js";document.body.appendChild(objScript);}
function AjaxLink(id_target,url,callback,carregando)
{callback=typeof callback!=='undefined'?callback:null;carregando=typeof carregando!=="undefined"?carregando:null;ajaxMensagemCarregando(carregando,"exibir");fila[fila.length]=[id_target,url,carregando,"GET",null,callback,null];if(fila.length==1){ajaxRun();}
return;}
function AjaxForm(id_target,id_form,url,callback,carregando)
{callback=typeof callback!=='undefined'?callback:null;carregando=typeof carregando!=="undefined"?carregando:null;if(document.getElementById(id_form)==null)
{alert("O formulário enviado não existe ("+id_form+")");return;}
var metodoEnvio=document.getElementById(id_form).method.toUpperCase();if(xmlhttp)
{var elementos_form=BuscaElementosForm(id_form);}
ajaxMensagemCarregando(carregando,"exibir");fila[fila.length]=[id_target,url,carregando,metodoEnvio,elementos_form,callback,id_form];if(fila.length==1){ajaxRun();}
return;}
function ajaxRun()
{var url=fila[ifila][1];var metodoEnvio;nocache=Math.random().toString().substr(2);if(fila[ifila][3]==null||fila[ifila][3]==""){metodoEnvio="POST";}
else{metodoEnvio=fila[ifila][3];if(metodoEnvio=="GET"&&fila[ifila][4]!=null)
{url=url+"?"+fila[ifila][4]+"&nocache="+nocache+"&ajax=TRUE";}
else
{separador=(url.indexOf("?")>=0)?"&":"?";url=url+separador+"nocache="+nocache+"&ajax=TRUE";}}
if(!xmlhttp)
{if(metodoEnvio=="GET")
{iframe.setAttribute("src",url);iframe.setAttribute("onload","monitorarStatusIFrame()");}
else
{iframe.setAttribute("onload","monitorarStatusIFrame()");acao_atual=document.getElementById(fila[ifila][6]).action;document.getElementById(fila[ifila][6]).setAttribute("target","ajax_iframe");document.getElementById(fila[ifila][6]).action=url;document.getElementById(fila[ifila][6]).submit();if(url!=acao_atual)
{document.getElementById(fila[ifila][6]).action=acao_atual;}}
return;}
xmlhttp.open(metodoEnvio,url,true);xmlhttp.onreadystatechange=monitorarStatusXMLHTTP;if(metodoEnvio=="POST")
{if(typeof fila[ifila][4]!="object")
{xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}
xmlhttp.send(fila[ifila][4]);}
else{xmlhttp.send(null);}
return;}
function monitorarStatusXMLHTTP()
{if(xmlhttp.readyState==1)
{ajaxMensagemCarregando(fila[ifila][2],"exibir");}
else
{if(xmlhttp.readyState==4){StatusXMLHTTPCompleto(xmlhttp,fila[ifila][0],fila[ifila][2],fila[ifila][5]);}}}
function StatusXMLHTTPCompleto(xmlhttp,id_retorno,carregando,callback)
{var retorno;if(xmlhttp.status==200||xmlhttp.status==0)
{retorno=unescape(xmlhttp.responseText);}
else
{retorno=ajaxPaginaErro(xmlhttp);}
ajaxMensagemCarregando(carregando,"esconder");if(callback==null)
{document.getElementById(id_retorno).innerHTML=retorno;ExtraiScript(retorno);}
else
{if(typeof(callback)=="function")
{try{callbackReturn=JSON.parse(retorno);}
catch(e){callbackReturn={status:false,msg:"Retorno JSON inválido: "+retorno};}
verificarRetornoCallback(callback);}
else
{alert("A função informada de retorno não existe ou ainda não foi declarada ("+callback+")");return;}}
ifila++;if(ifila<fila.length){setTimeout("ajaxRun()",20);}
else
{fila=null;fila=new Array();ifila=0;}
return;}
function StatusIFrameCompleto(iframeDoc,id_retorno,carregando,callback)
{retorno=iframeDoc.body.innerHTML;iframeDoc.body.innerHTML="";iframe.removeAttribute("src");iframe.removeAttribute("onload");ajaxMensagemCarregando(carregando,"esconder");if(callback==null)
{document.getElementById(id_retorno).innerHTML=retorno;}
else
{if(typeof(callback)=="function")
{try{callbackReturn=JSON.parse(retorno);}
catch(e){callbackReturn={status:false,msg:"Retorno JSON inválido: "+retorno};}
verificarRetornoCallback(callback);}
else
{alert("A função informada de retorno não existe ou ainda não foi declarada ("+callback+")");return;}}
ifila++;if(ifila<fila.length)
{setTimeout("ajaxRun()",20);}
else
{fila=null;fila=new Array();ifila=0;}
return;}
function verificarRetornoCallback(callback)
{if(callbackReturn==null||callbackReturn.status==undefined||callbackReturn.status==false)
{msg=(callbackReturn==null||callbackReturn.msg==undefined)?callbackReturn:callbackReturn.msg;if(callbackErrorFunction==true){toastr["error"](msg,'Erro');}
else{alert("Erro: "+msg);}}
callback.call(this,callbackReturn);}
function monitorarStatusIFrame()
{iframe=document.getElementById('ajax_iframe');var iframeDoc=iframe.contentDocument||iframe.contentWindow.document;if(iframeDoc.readyState=='complete'&&iframeDoc.body.innerHTML!="")
{StatusIFrameCompleto(iframeDoc,fila[ifila][0],fila[ifila][2],fila[ifila][5]);return;}
window.setTimeout('monitorarStatusIFrame()',1000);}
function AjaxIFrame()
{try{iframe=document.createElement('<iframe name="ajax_iframe">');}
catch(ex){iframe=document.createElement('iframe');}
iframe.id='ajax_iframe';iframe.name='ajax_iframe';iframe.width=0;iframe.height=0;iframe.border=0;iframe.style.display="none";document.body.appendChild(iframe);xmlhttp=false;}
function ajaxPaginaErro(xmlhttp)
{var retorno;switch(xmlhttp.status)
{case 404:return"Página não encontrada!!!";break;case 500:console.log(xmlhttp.responseText);return"Erro interno do servidor!!!";break;default:console.log(xmlhttp.responseText);return"Erro desconhecido!!!<br>"+xmlhttp.status+" - "+xmlhttp.statusText.replace(/\+/g," ");break;}}
function ajaxMensagemCarregando(carregando,tarefa)
{if(tarefa=="exibir")
{if(carregando==null){document.getElementById("div_loader_ajax").style.display="block";}
else{document.getElementById(carregando).innerHTML="<img src='"+img_loader_ajax_pequena+"' alt='carregando' />";}}
else
{if(carregando==null){document.getElementById("div_loader_ajax").style.display="none";}
else{document.getElementById(carregando).innerHTML="";}}}
function ExtraiScript(texto)
{var ini,pos_src,fim,codigo,texto_pesquisa;var objScript=null;texto_pesquisa=texto.toLowerCase();ini=texto_pesquisa.indexOf('<script',0);while(ini!=-1)
{var objScript=document.createElement("script");pos_src=texto_pesquisa.indexOf(' src',ini);ini=texto_pesquisa.indexOf('>',ini)+1;if(pos_src<ini&&pos_src>=0)
{ini=pos_src+4;fim=texto_pesquisa.indexOf('.',ini)+4;codigo=texto.substring(ini,fim);codigo=codigo.replace("=","").replace(" ","").replace("\"","").replace("\"","").replace("\'","").replace("\'","").replace(">","");objScript.src=codigo;}
else
{fim=texto_pesquisa.indexOf('</script>',ini);codigo=texto.substring(ini,fim);objScript.text=codigo;}
document.body.appendChild(objScript);ini=texto.indexOf('<script',fim);objScript=null;}}
function BuscaElementosForm(idForm)
{var elementosFormulario=document.getElementById(idForm).elements;var qtdElementos=elementosFormulario.length;var queryString="";var elemento;var arquivo=(typeof window.FormData=="function")?true:false;var elementos=[];for(var i=0;i<qtdElementos;i++)
{elemento=elementosFormulario[i];if(!elemento.disabled)
{switch(elemento.type)
{case'select-one':if(elemento.selectedIndex>=0)
{elementos[i]={nome:elemento.name,valor:elemento.options[elemento.selectedIndex].value};}
break;case'select-multiple':for(var j=0;j<elemento.options.length;j++)
{if(elemento.options[j].selected)
{elementos[i+j+"multiple"]={nome:elemento.name,valor:elemento.options[j].value};}}
break;case'checkbox':case'radio':if(elemento.checked)
{elementos[i]={nome:elemento.name,valor:elemento.value};}
break;case'file':if(arquivo==false)
{console.log("Envio de arquivo detectado e sem suporte a API FormData alterando envio de ajax para iframe");AjaxIFrame();return true;}
len=elemento.files.length;if(len>0)
{arquivo=true;for(var j=0;j<len;j++)
{elementos[i+j+"file"]={nome:elemento.name,valor:elemento.files[j]};}}
break;default:elementos[i]={nome:elemento.name,valor:elemento.value};break;}}}
if(arquivo==true){var queryString=new FormData();}
for(var index in elementos)
{if(arquivo==false)
{if(queryString.length>0){queryString+="&";}
queryString+=encodeURI(elementos[index].nome)+"="+encodeURI(elementos[index].valor).replace("&","%26");}
else
{queryString.append(elementos[index].nome,elementos[index].valor);}}
return queryString;}
function verificarDivCarregando()
{if(document.getElementById("div_loader_ajax")==null)
{try{var div_ajax=document.createElement("<div id='div_loader_ajax'>");}
catch(ex){var div_ajax=document.createElement("div");}
div_ajax.id="div_loader_ajax";div_ajax.style.cssText="display: none;position: fixed;z-index: 999999;top: 0;left: 0;height: 100%;width: 100%;background: rgba( 255, 255, 255, .8 ) url('"+img_loader_ajax_grande+"') 50% 45% no-repeat;cursor: wait;text-align: center;";document.body.appendChild(div_ajax);}}