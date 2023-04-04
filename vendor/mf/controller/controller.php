<?php

// Definir o caminho para instanciar a clase
namespace mf\controller;

/* Classe controler que defini as ações POST
-------------------------------------------*/
abstract class controller{
    // Definição de variaveis
    protected $oView;

    // Metodo Construtor
    public function __construct(){
        $this->oView = new \stdClass();
    }

    // Metodo POST  Reder View
    protected function reqLayout($cView, $cLayout = 'layout'){

        $this->oView->cPage = $cView;

        //Verifica o arquivo no caminho Views
        if(file_exists("app/view/" . $cLayout . ".phtml")){
            require_once "app/view/" . $cLayout . ".phtml";
        }else{
            $this->reqView();
        }
    }
    
    //Chama a view dentro do layout
	protected function reqView() {
		$oClassAtual = get_class($this);

		$oClassAtual = str_replace('app\\controller\\', '', $oClassAtual);

		$oClassAtual = strtolower(str_replace('Controller', '', $oClassAtual));

		require_once "app/view/".$oClassAtual."/".$this->oView->cPage.".phtml";
	}
}