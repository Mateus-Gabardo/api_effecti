<?php

class ModelItemLicitacao {
    
   
    private $id;
    private $material;
    private $unidade;
    private $marca;
    private $valor;
    private $descricao;

    
    public function getId() {
        return $this->id;
    }

    public function getMaterial() {
        return $this->material;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getValor() {
        return $this->valor;
    }
    
    public function getDescricao(){
        return $this->descricao;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function setMaterial($material): void {
        $this->material = $material;
    }

    public function setUnidade($unidade): void {
        $this->unidade = $unidade;
    }

    public function setMarca($marca): void {
        $this->marca = $marca;
    }

    public function setValor($valor): void {
        $this->valor = $valor;
    }
    
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
            
    public function toJson() {
        return [
            'id'        => (int) $this->getId(),
            'material'  => $this->getMaterial(),
            'unidade'   => $this->getUnidade(),
            'marca'     => $this->getMarca(),
            'valor'     => $this->getValor(),
            'descricao' => $this->getDescricao(),
        ];
    }


}

