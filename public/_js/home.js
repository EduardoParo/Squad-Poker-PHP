
/*--------------------------------------------------------------------
| AO INICIALIZAR A PAGINA CARREGAR A FUNÇÕES PRINCIPAIS
 ----------------------------------------------------------------------*/
 $(document).ready(function () {
    IniciarPontos();
    showUsers();
})

/*--------------------------------------------------------------------
| INICIALIZAR OS PONTOS
 ----------------------------------------------------------------------*/
function IniciarPontos(){
    let oPontos = document.getElementById("pontos");
    let cHtml ='';
    let aFibo = [1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144];
 
    for(let nI = 0; nI< aFibo.length; nI++){   
        cHtml+=`<button onclick = "pontuar(${aFibo[nI]})" class="btn btn-outline-primary btn-lg mr-3 mt-2 " align="center"  >${aFibo[nI]}</button>`;    
    }    
  
    oPontos.innerHTML=cHtml;
}
/*--------------------------------------------------------------------
| REQUISIÇÃO GET A API
 ----------------------------------------------------------------------*/
function getAllUser(){
    var xhttp = new XMLHttpRequest();

    xhttp.open("GET", "/Poker/getAllUser", false);
    xhttp.send();//A execução do script pára aqui até a requisição retornar do servidor
    
    jLista = xhttp.responseText;
    jLista = JSON.parse(jLista)
    //console.log(jLista);
    return jLista
}

/*--------------------------------------------------------------------
| Deletar um Usuario
 ----------------------------------------------------------------------*/
 function delUsr(nId,lOk=false){
    let formData = new FormData(document.forms.person);
    let aForm = [];

    if(!lOk){
        lOk = pergunte(`delUsr(${nId},true)`,`Deletar Usuário`,`Tem certeza que deseja deletar esse usuaario Id ${nId} ?`)
    }

    formData.append("nId", nId);

    aForm = {
        method: 'POST',
        body: formData
    }

    if (lOk){
        fetch( `/Poker/sair`, aForm)
            .then( (response)=>{
        });
    }
}
/*--------------------------------------------------------------------
| GRAVAR PONTO SELECIONADO
 ----------------------------------------------------------------------*/
function pontuar(nId){
    let nIdVoto = nId;
    //var data = new Date();
    //let cTimeEvent = data.getHours() +':'+ data.getMinutes() +':'+ data.getSeconds();

    jLista = getAllUser();

    jSESSION = jLista[jLista.length-1];

    if(jLista[0]["usr_mostrar"] != "1"){
        let formData = new FormData(document.forms.person);
        let aForm = [];
 
        formData.append('nIdUser'   , jSESSION["nId"])
        formData.append('nIdVoto'   , nIdVoto)
        formData.append('cSquad'    , jSESSION["cSquad"])

        aForm = {
            method: 'POST',
            body: formData
        }

        fetch(`/Poker/insertVoto`,aForm)
        .then((response)=>{
            showUsers();
        })
    }
}

/*--------------------------------------------------------------------
| MOSTRAR TODOS USUARIOS
 ----------------------------------------------------------------------*/
function showVotos(lOk = false){
    if(!lOk){
        lOk = pergunte('showVotos(true)','Revelar os votos','Tem certeza que deseja revelar os votos ?')
    }

    if (lOk){
         fetch(`/Poker/showVotos`,{method:'POST'})
        .then((response)=>{
             lJaApVenc = false;
             showVencedor();
        });
    }
   
}

/*--------------------------------------------------------------------
| REINICIAR VOTOS
 ----------------------------------------------------------------------*/
function resetVotos(lOk = false){
    if(!lOk){
        pergunte('resetVotos(true)','Reiniciar os votos','Tem certeza que deseja reiniciar os votos ?')
    } 

    if (lOk){
        fetch(`/Poker/resetVotos`,{method : 'POST'})
            .then( (response) =>{
                showUsers();
            });
    }
}

/*--------------------------------------------------------------------
| EVENTO DE CLICK
 ----------------------------------------------------------------------*/
document.onclick = function(){
    showUsers();
 }

 /*--------------------------------------------------------------------
| MOSTRAR USUARIOS ONLINES
 ----------------------------------------------------------------------*/
function showUsers(jLista){
    let oCardUsuario = document.getElementById("cardUsuario");
    let cHtml='';
    let lOnline = false;
    let jSESSION =[];
    
    jSESSION['participante']='';

    if(jLista == undefined){
        jLista = getAllUser();
    }

    jSESSION = jLista[jLista.length-1];

    //Aprensenta os votos na tela
    for(let nX=0; nX<jLista.length -1 ; nX++){
        cHtml+= ' <div  class="col-md-2">';
        cHtml+= ' <div  class="card mt-3">';
        cHtml+= ' <div class="card-ponto text-center">';

        if( jLista[nX]['usr_id'] == jSESSION["nId"] || jLista[nX]['usr_mostrar'] =="1" ){
            cHtml+=jLista[nX]['usr_voto'];
            lOnline = true;
        }else{
            if(jLista[nX]['usr_voto'] == '0'){
                cHtml+='?';
            }else{
                cHtml+='OK';
            }
        }
        cHtml+= '</div> </div> </div>';
    }

    if (lOnline == false && jSESSION['participante'] !="espectador"){
        delUsr(jSESSION["nId"]);
        window.location.href = "/Poker/";
    }else{
        oCardUsuario.innerHTML=cHtml;   
        showTabela(jLista,jSESSION) ;
    }

}
/*--------------------------------------------------------------------
| CRIAR TABELA DE USUARIOS ONLINES
 ----------------------------------------------------------------------*/
function showTabela(jLista,jSESSION) {
    let nCont = 0;
    let nTam = 0

    if (jLista == undefined){
        jLista = getAllUser();
    };

    jLista.pop(jLista.length);

    nTam = jLista.length -1;
    lMostrar = jLista[nTam]['usr_mostrar'] == "1"

    for(let nX=0; nX<jLista.length; nX++){
        if( !lMostrar){
            if( jLista[nX]['usr_voto'] == '0'  ){
                jLista[nX]['usr_voto'] = '?';
            }else{
                jLista[nX]['usr_voto'] = 'OK';
                nCont++;
            }
        }
        if(jSESSION['participante'] !="espectador"){
            jLista[nX]['usr_nome'] = '**********';
        }   
    } 
    
    if(nCont == jLista.length && jSESSION['participante'] == "espectador"){
        let oBtns =  document.getElementById('btnRevReini');
        oBtns.innerHTML=' <button id="btnRevelar" class="btn btn-secondary text-white  btn-lg  mr-3 mb-2" onclick="showVotos()"> Revelar Votos </button>  <button class="btn btn-warning text-white btn-lg  mb-2" onclick="resetVotos()" > Reiniciar Votos</button>';   

    }else {
        let oBtnRevelar =  $('#btnRevelar');
        oBtnRevelar.hide();
    }

    //Caso todos já votaram mostrar o ganhador
    //if(nCont == jLista.length){
    //    showVotos(true);
    //}

    if(jLista[nTam]['usr_showVenc'] == '1'){
        showVencedor();
    }

    let aColunas = [
                  { "sTitle": "Id"                , "data": "usr_id"          , "bSortable": true                     },
                  { "sTitle": "Nome"              , "data": "usr_nome"        , "bSortable": true                     },
                  { "sTitle": "Squad"             , "data": "usr_squad"       , "bSortable": true                     },
                  { "sTitle": "Voto"              , "data": "usr_voto"        , "bSortable": true,"sWidth": "15%"     }];
                  
    
    if(jSESSION['participante'] =="espectador"){
            
        let aBtnAcoes = { "sTitle": "Ações", "data": null, 
            render: function (data, type, row){

                let botoes = '<div class="col-md-12">'
                botoes += '<button class="btn btn-danger  btn-md" onclick="delUsr('+ row.usr_id +')" >'
                botoes += '<span class="icon">X</span></button>'

                botoes += '</div>'

                return botoes

            }, "sWidth": "10%"}

        aColunas.push(aBtnAcoes);

    };

    $('#tabelaDados').DataTable({
        "data":  jLista,
        "aoColumns": aColunas,
        "responsive": true,
        "bLengthChange": true,
        "bFilter": false,
        "bSort": true,
        "bInfo": true,
        "sDom": "tIipr",
        "bPaginate": true,


    });

    $('#tabelaDados').DataTable().destroy();
}
/*--------------------------------------------------------------------
| METODO PARA ATUALIZAR OS DADOS APRENSENTADOS
 ----------------------------------------------------------------------*/
setInterval(()=> {
    fetch('/Poker/getAllUserUser')
    .then((jdata)=>{
        showUsers(jdata);
    });
    
    //window.location.reload(1);
}, 3000); //tempo em milisegundos. Neste caso, o refresh vai acontecer de 5 em 5 segundos.

 /*---------------------------------------------------------------------
  Mostrar Modal
 ----------------------------------------------------------------------*/
 function showModal(){
    //Inicialisa o modal
    $('#telaModal').modal('show');
    // Limpa os inputs
    $('input, textarea').val(''); 

 }
/*---------------------------------------------------------------------
  MOSTRAR VENCEDOR
 ----------------------------------------------------------------------*/
 var lJaApVenc = false;

 function showVencedor(jLista){
    fetch('/Poker/getUsrVencedor')
    .then((response)=> response.json())
        .then((jdata)=>{
            //console.log(jdata);
            let jMaior = jdata[0];
            let jEmpat = jdata[1];
            let cRet = `${jMaior['Ponto']} Ganhou !!! <br> ${jMaior['qtdPes']} pessoas votaram ${jMaior['Ponto']}`    

            for(let nX=1; nX < jdata.length; nX++ ){
                if(jdata[nX]['qtdPes'] > jMaior['qtdPes']){
                    if(typeof(jMaior) !='undefined'){
                        jMaior =jdata[nX]; 
                        cRet = `${jMaior['Ponto']}<b> <span class='text-primary'> Ganhou !!! </span><b><br> ${jMaior['qtdPes']} pessoas votaram ${jMaior['Ponto']}`    
                    }    
                }else if(jdata[nX]['qtdPes'] == jMaior['qtdPes'] ){
                    jEmpat = jdata[nX];
                }
            }

            if(typeof(jEmpat) !='undefined' && typeof(jMaior) !='undefined' ){
                if(jMaior['qtdPes'] == jEmpat['qtdPes']){

                    if(jMaior['qtdPes'] ==1 && jEmpat['qtdPes'] ==1){
                        cRet=`Não identificamos um ganhador`;
                    }else{
                        cRet = `<b> <span class='text-primary'> Empatou !!!<span></b> <br> ${jMaior['qtdPes']} pessoas votaram ${jMaior['Ponto']} e ${jEmpat['qtdPes']} de pessoas votaram ${jEmpat['Ponto']}`    
                   }
                }
            }

            //console.log(cRet);
            if(lJaApVenc == false){
                showModal();
                $('#btn_sim_modal').hide();
                $('#titulo_modal_div').addClass('modal-header text-warning');
                $('#titulo_modal').html('Resultado');
                $('#conteudo_modal').html(cRet);
                $('#btn_voltar_modal').html('Voltar');
                $('#btn_voltar_modal').addClass('btn btn-secondary');
                lJaApVenc = true;  
            }
        });        
 }

 /*---------------------------------------------------------------------
  MOSTRAR PERGUNTE
 ----------------------------------------------------------------------*/
function pergunte(cFunc,cHeader,cBody){
    showModal();
    
    $('#titulo_modal').html(cHeader);
    $('#titulo_modal_div').addClass('modal-header text-warning');
    
    $('#conteudo_modal').html(cBody);
     
    //Botao confirmar 
    $('#btn_sim_modal').show();
    $('#btn_sim_modal').html('Sim');
    $('#btn_sim_modal').addClass('btn btn-secondary bg-warning');
    $('#btn_sim_modal').click(function(){
        eval(cFunc)
        cFunc='';
        return true;
    });

    $('#btn_voltar_modal').html('Não');
    $('#btn_voltar_modal').addClass('btn btn-secondary');
    $('#btn_voltar_modal').click(function() {
        $('#telaModal').modal('hide');
        cFunc='';
        return false;
    });
 
    
   
}