<?php

class ModelLicitacao {
    
    private $id;
    private $pregaoIdentificador;
    private $status;
    private $localizacao;
    private $vigencia;
    private $descricao;
    private $linkForItens;
    private $lido;
    
    public function __construct() {
        $this->lido = false;
    }

        
    public function getId() {
        return $this->id;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getLocalizacao() {
        return $this->localizacao;
    }

    public function getVigencia() {
        return $this->vigencia;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    
    public function getPregaoIdentificador() {
        return $this->pregaoIdentificador;
    }

    public function getLinkForItens() {
        return $this->linkForItens;
    }

    public function setPregaoIdentificador($pregaoIdentificador): void {
        $this->pregaoIdentificador = $pregaoIdentificador;
    }

    public function setLinkForItens($linkForItens): void {
        $this->linkForItens = $linkForItens;
    }

    
    public function setId($id): void {
        $this->id = $id;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

    public function setLocalizacao($localizacao): void {
        $this->localizacao = $localizacao;
    }

    public function setVigencia($vigencia): void {
        $this->vigencia = $vigencia;
    }

    public function setDescricao($descricao): void {
        $this->descricao = $descricao;
    }
    
    public function getLido() {
        return $this->lido;
    }

    public function setLido($lido): void {
        $this->lido = $lido;
    }
    
    public function toJson() {
        return [
            'id'         => (int)$this->getId(),
            'pregao'     => $this->getPregaoIdentificador(),
            'status'     => $this->getStatus(),
            'localizacao'=> $this->getLocalizacao(),
            'vigencia'   => $this->getVigencia(),
            'descricao'  => $this->getDescricao(),
            'link_itens' => $this->getLinkForItens(),
            'lido'       => $this->getLido(),
        ];
    }

}


