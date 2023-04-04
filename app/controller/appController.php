<?php 

//Definir namespace
namespace app\controller;
//Executar a classe do arquivo

use mf\controller\controller;
use mf\model\modelConnect ;

/*----------------------------------
    Extenção da classe Controller
-------------------------------------*/
class appController extends controller{
   
   /*Metodo Post ROTA Home
    ------------------------*/
    public function home(){ 
        $this->validaAutenticacao();
        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');
        
        session_start();

       //Chamar a pagina Home
        $this->reqLayout('home','layout2');

        return null;
    }

    public function getAllUser(){

        $this->validaAutenticacao();
        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');
        
        session_start();

        // Atribui os valores ao objeto Usuario
        $oUsr->__set('nId'   ,  $_SESSION['nId']   );
        $oUsr->__set('cNome' ,  $_SESSION['cNome'] );
        $oUsr->__set('cSquad',  $_SESSION['cSquad']);
      
        //Encaminhar os dados para a View é necessario utilizar o echo na pagina para imprimir o codigo JS
        $aResp= $oUsr->dbGetAll();
        $aResp[count($aResp)] = $_SESSION;
        //Encaminhar os dados para a View é necessario utilizar o echo na pagina para imprimir o codigo JS
        echo json_encode($aResp);
        
        return null;
    }

    /*Metodo Post insertVoto
    ------------------------*/
    public function insertVoto(){ 
        $this->validaAutenticacao();
        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');

        session_start();
        
        // Atribui os valores ao objeto Usuario
        $oUsr->__set('nId'        ,  $_REQUEST['nIdUser']   );
        $oUsr->__set('cSquad'     ,  $_REQUEST['cSquad']);
        $oUsr->__set('nVoto'      ,  intval($_REQUEST["nIdVoto"])); //Capturar o voto da requisição

        //Transformar array em json para encaminhar para o JS
        $oUsr->dbInsertPonto();
        
        return null;
        
    }

    /*Metodo Post resetarVoto
    ------------------------*/
    public function resetVotos(){ 
        $this->validaAutenticacao();
        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');

        session_start();
        
        $oUsr->__set('nVoto' ,  '0');
        $oUsr->__set('cSquad',  $_SESSION['cSquad']);

        //Transformar array em json para encaminhar para o JS
        $oUsr->dbResetPonto();
        
        //header('Location:  /Poker/home');   

        return null;
    }

    /*Metodo Post resetarVoto
    ------------------------*/
    public function showVotos(){ 
        $this->validaAutenticacao();

        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');

        session_start();
        $oUsr->__set('cSquad', $_SESSION['cSquad']);
        
        //Transformar array em json para encaminhar para o JS
        $oUsr->dbMostrarVoto();
        
        //header('Location:  /Poker/home');   

        return null;
    }

    /*Metodo Post resetarVoto
    ------------------------*/
    public function getUsrVencedor(){ 
        $this->validaAutenticacao();

        // Realiza conexão com o banco e instacia o modelo de dados Usuario
        $oUsr = modelConnect::getModel('dbUsuario');

        session_start();
        $oUsr->__set('cSquad', $_SESSION['cSquad']);
        
        //Transformar array em json para encaminhar para o JS
        $aResp=  $oUsr->dbVenc();
       // $aResp[count($aResp)] = $_SESSION;
        //Encaminhar os dados para a View é necessario utilizar o echo na pagina para imprimir o codigo JS
        echo json_encode($aResp);    
        //header('Location:  /Poker/home');   

        return null;
    }

    /*Função Validar autenticação
    -------------------------------------*/
    public function validaAutenticacao(){     
        //Iniciar seção PHP
        session_start();

        if( $_SESSION['participante'] != 'espectador' && ( $_SESSION['nId'] == '' )){
            //Define o caminho ?login=erro
            header('Location: /Poker/?login=erro');
        }else{
            return true;
        }
    }

    
}

?>    

    