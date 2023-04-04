<?php

namespace app\model;

use mf\model\model;

/*-----------------------------------
Extensão da classe model
------------------------------------*/
class dbUsuario extends model{
       //Definir Propriedades do Objeto
    private $nId;
    private $cNome;
    private $cSquad;
    private $nVoto;

    //Metodo GET Retornar os atributos do OBJ
    public function __get($xAtributo){
        return $this->$xAtributo;
    }

    //Metodo PUT seta os valores dos atributos
    public function __set($xAtributo,$xValor){
        $this->$xAtributo = $xValor;
    }

    //Metodo POST salvar os dados 
    public function dbInsertUsr(){
       //Montagem da query
        $cQuery =" INSERT INTO sys_usr ";
        $cQuery.="     (usr_nome, usr_voto, usr_squad, usr_mostrar, usr_showVenc)  " ;
        $cQuery.="  VALUES ";
        $cQuery.="  (:cNome, :nVoto, :cSquad, false, false) ";

        $oQuery = $this->oDb->prepare($cQuery);
        
        $oQuery->bindValue(':cNome'         , $this->__get('cNome'));
        $oQuery->bindValue(':nVoto'         , '0');
        $oQuery->bindValue(':cSquad'        , $this->__get('cSquad'));
      
        $lOK = $oQuery->execute();

        $this->dbAutentic();

        return $this;

    }

    //Metodo autenticar
    public function dbAutentic(){
        //Montagem da Query
        $cQuery = "SELECT ";
        $cQuery.= "     usr_id, usr_nome, usr_squad ";
        $cQuery.=" FROM ";
        $cQuery.="      sys_usr ";
        $cQuery.=" WHERE ";
        $cQuery.="      usr_nome = :cNome AND usr_squad = :cSquad ";

        $oQuery = $this->oDb->prepare($cQuery);
        
        $oQuery->bindValue('cNome' , $this->__get('cNome'));
        $oQuery->bindValue('cSquad', $this->__get('cSquad'));

        $oQuery->execute();

        $xRet = $oQuery->fetch(\PDO::FETCH_ASSOC);

        if(gettype($xRet) == "array"){
            $aUsr = $xRet;

            if($aUsr['usr_id'] !='' && $aUsr['usr_nome'] != ''){
                $this->__set('nId'  , $aUsr['usr_id']);
                $this->__set('cNome', $aUsr['usr_nome']);
                $this->__set('cSquad', $aUsr['usr_squad']);
            }

        }else{
            $cQuery = null;
            $oQuery = null;
            $this->dbInsertUsr();
        }
        //Retornar Obj
        return $this;
    }

    /*Metodo GET retornar todos dados 
    --------------------------------------------*/
    public function dbGetAll(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery = " SELECT ";
        $cQuery.= " * FROM sys_usr WHERE usr_squad = :squad ";

        $oQuery = $this->oDb->prepare($cQuery);
        $oQuery->bindValue(':squad', $this->__get('cSquad') );
        
        $lOK = $oQuery->execute();
        
        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet; 
    }

    /*Metodo POST retornar todos dados 
    --------------------------------------------*/
    public function dbInsertPonto(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery =" UPDATE sys_usr ";
        $cQuery.="  SET ";
        $cQuery.="       usr_voto  = :nVoto  ";
        $cQuery.="  WHERE ";
        $cQuery.="     usr_id = :nId ";
     
        $oQuery = $this->oDb->prepare($cQuery);
        
        $oQuery->bindValue(':nId'         , intval($this->__get('nId')));
        $oQuery->bindValue(':nVoto'       , intval( $this->__get('nVoto')));

        $lOK = $oQuery->execute();
        
        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet; 
     
    }

    /*Metodo POST Mostrar todos dados 
    --------------------------------------------*/
    public function dbMostrarVoto(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery =" UPDATE sys_usr ";
        $cQuery.="  SET ";
        $cQuery.="       usr_mostrar  = true, usr_showVenc = true ";
        $cQuery.="  WHERE ";
        $cQuery.="     usr_squad = :cSquad ";
     
        $oQuery = $this->oDb->prepare($cQuery);
        
        $oQuery->bindValue(':cSquad'          ,$this->__get('cSquad'));

        $lOK = $oQuery->execute();
        
        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet; 
     
    }

    /*Metodo POST resetar todos dados 
    --------------------------------------------*/
    public function dbResetPonto(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery =" UPDATE sys_usr ";
        $cQuery.="  SET ";
        $cQuery.="       usr_voto  = :nVoto ,";
        $cQuery.="       usr_mostrar  = '', usr_showVenc = '' ";
        $cQuery.="  WHERE ";
        $cQuery.="     usr_squad = :cSquad ";
     
        $oQuery = $this->oDb->prepare($cQuery);
        
        $oQuery->bindValue(':cSquad'          ,$this->__get('cSquad'));
        $oQuery->bindValue(':nVoto'       ,intval( $this->__get('nVoto')));

        $lOK = $oQuery->execute();
        
        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet; 
     
    }

    /*Metodo Reiniciar retornar todos dados 
    --------------------------------------------*/
    public function dbDelet(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery ="DELETE FROM sys_usr ";
        $cQuery.="  WHERE ";
        $cQuery.="     usr_id = :nId ";
     
        $oQuery = $this->oDb->prepare($cQuery);

        $oQuery->bindValue(':nId'       ,intval( $this->__get('nId')));

        $lOK = $oQuery->execute();

        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet; 
          
    }

    /*Metodo Reiniciar retornar todos dados 
    --------------------------------------------*/
    public function dbVenc(){
        $lOK     = true;
        $cRet    = '';
        $cQuery  = '';
        $$oQuery = null;

        //Montagem da query
        $cQuery =" SELECT usr_voto as 'Ponto' ,";
        $cQuery.= " COUNT(usr_nome) as 'qtdPes' FROM sys_usr "; 
        $cQuery.=" GROUP BY usr_voto ";
        $cQuery.=" ORDER BY COUNT(usr_nome) DESC";

        $oQuery = $this->oDb->prepare($cQuery);

        $lOK = $oQuery->execute();

        if($lOK){
            $cRet = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            $cRet= 'falha na query';     
        }
        return  $cRet;      
    }

}