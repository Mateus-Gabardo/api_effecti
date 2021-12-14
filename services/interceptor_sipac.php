<?php
require_once __DIR__.'\..\model\model_item_licitacao.php';
require_once __DIR__.'\..\model\model_licitacao.php';

class InterceptorSipac {
    private const ENDERECO_SIPAC    = 'https://sig.ifsc.edu.br/public/listaEditais.do?tipo=2&aba=p-editais-atas&buscaTodas=true&acao=544';
    private const HOST_SIPAC        = 'https://sig.ifsc.edu.br';
    private const HOST_ITENS_EDITAL = 'https://sig.ifsc.edu.br/public/visualizaAta.do?tipoBusca=5&somenteAta=true&processoCompra.id=';
    
  
  public function getAllLicitacoesToJson(){
    $dados = $this->getDadosLicitacaoModel();
    echo json_encode($dados);
  }
  
  public function getAllItensLicitacaoToJson($id) {
      $dados = $this->getDadosItensLicitacaoModel($id);
      echo json_encode($dados);
  }
  
  /**
   * Retorna um conjunto de modelo de Licitação extraidos da pagina web.
   * @return array \ModelLicitacao
   */
  private function getDadosLicitacaoModel() {
      $aLicitacoes = [];
      $aDados = $this->getLicitacoesByHtml();
      
      foreach ($aDados as $dado) {
        $oModelLicitacao = new ModelLicitacao();
        
        $oModelLicitacao->setId(utf8_encode($dado[0]));
        $oModelLicitacao->setPregaoIdentificador(utf8_encode($dado[1]));
        $oModelLicitacao->setStatus(utf8_encode($dado[2]));
        $oModelLicitacao->setLocalizacao(utf8_encode($dado[3]));
        $oModelLicitacao->setVigencia(utf8_encode($dado[4]));
        $oModelLicitacao->setDescricao(utf8_encode($dado[5]));
        $oModelLicitacao->setLinkForItens(utf8_encode($dado[6]));
        
        $aLicitacoes[] = $oModelLicitacao->toJson();
        
      }
      return $aLicitacoes;
  }
  
  /**
   * Retorna um conjunto de modelos de itens de licitação extraidos da pagia web a partir de um id específico
   * @param int $id - identificador do edital
   * @return boolean|array\ ModelItemLicitacao
   */
  private function getDadosItensLicitacaoModel($id) {
      $aItensLicitacao = [];
      if(!isset($id)){
          return false;
      } else {
          $aDados = $this->getItensLicitacaoByHtml($id);
          foreach ($aDados as $dado) {
              $oItemLicitacao = new ModelItemLicitacao();
              
              $oItemLicitacao->setId(utf8_encode($dado[0]));
              $oItemLicitacao->setMaterial(utf8_encode($dado[1]));
              $oItemLicitacao->setUnidade(utf8_encode($dado[2]));
              $oItemLicitacao->setMarca(utf8_encode($dado[3]));
              $oItemLicitacao->setDescricao(utf8_encode($dado[4]));
              $oItemLicitacao->setValor(utf8_encode($dado[5]));
              
              $aItensLicitacao[] = $oItemLicitacao->toJson();
          }
      }
      return $aItensLicitacao;
  }
  
  private function getLicitacoesByHtml() {
      $tagLicitacoes = 'tbody';
      // Pega-se todo o conteudo html
      $oHtml = $this->getHtml(self::ENDERECO_SIPAC);
      // Encontra somente a área de licitações
      $oBody = $this->getDadosEntreTags($oHtml, $tagLicitacoes);
      // Cria um array com licitação
      $aLicitacoes  = explode('</tr>', $oBody);
      array_pop($aLicitacoes);
      // remove as tags desnecessárias
      $aLicitacoes = $this->removeAllTagsByArray($aLicitacoes, ['td','a']);
      $aNewLicitacoes = [];
      
      foreach ($aLicitacoes as $slicitacao) {
          $aItem = explode('</td>', $slicitacao);
          $aItem = $this->removeAllTagsByArray($aItem, ['a']);
          array_pop($aItem);
          $keyLast = array_key_last($aItem);
          // Extrai o link dos itens do licitação corretamente
          $newLink = $this->extraiLinkAta($aItem[$keyLast]);
          $aItem[$keyLast] = $newLink;
          
          // Extrai o id com a quanl é possível buscar os itens da licitação
          $idByLink = $this->extraiIdByLink($newLink);
          array_unshift($aItem, $idByLink);
          
          $aNewLicitacoes[] = $aItem;
      }
      
      //$aNewLicitacoes[] = $this->getItensLicitacaoByHtml(1549);
     return $aNewLicitacoes;
  }
  
  private function getItensLicitacaoByHtml($idEdital) {
      $sLink = InterceptorSipac::HOST_ITENS_EDITAL.$idEdital;
      $oHtml = $this->getHtml($sLink);
      $oTeste = $this->getDadosEntreTags($oHtml, 'tbody');
      $aHtml = explode('tbody>', $oHtml);
      $aHtml = explode('</tbody>', $aHtml[1]);
      $oBody = $aHtml[0];
      $aItens  = explode('</tr>', $oBody);
      array_pop($aItens);
      $aItens = $this->removeAllTagsByArray($aItens, ['td']);
      
      $newItens = [];
      for($i = 0; $i < count($aItens); $i++) {
          $newItem = explode('<td>', $aItens[$i]);
          $newItens[] = $newItem;
          //retira-se o primeiro elemento do array impar que é desnecessário;
          if($i % 2 != 0) {
            array_shift($newItens[$i]);
          }
      }
      $aMerge = [];
      for($i = 0; $i < count($newItens); $i = $i+2) {
          if(isset($newItens[$i+1])){
            $aMerge[$i] = array_merge($newItens[$i], $newItens[$i+1]);
          }
      }
      $aAta = [];
      foreach ($aMerge as $aRequisitos) {
          $artigos = [];
          $aMarcaCusto = explode('nowrap>', $aRequisitos[3]);
          $aDescricaoEmpresa = explode('</td>', $aRequisitos[4]);
          forEach($aRequisitos as $requisito) {
              $artigos[] = $requisito;
          }
          $artigos[3] = $aMarcaCusto[0];
          $artigos[] = $aMarcaCusto[1];
          
          $artigos[4] = $aDescricaoEmpresa[1];
          $artigos = $this->removeAllTagsByArray($artigos);
          $aAta[] = $artigos;
      }      
      return $aAta;
  }
  
  private function removeAllTagsByArray(array $aDados, $tag_excessoes = null){
      $newDados = [];
      foreach ($aDados as $item) {
          $dadoTratado = strip_tags($item, $tag_excessoes);
          $newDados[] = trim($dadoTratado);
      }
      // retira os espaços em branco
      $newDados = preg_replace('/\s+/', ' ', $newDados);
      return $newDados;
  }
  
  /**
   * Extrai o link dos itens do edital
   * @param type $string - String a qual será retirado o link
   * @return object - URL
   */
  private function extraiLinkAta($string) {
      $aLinks = explode('</a>', $string);
      preg_match('/<a href="(.+)">/', trim($aLinks[1]), $match);
      $url = self::HOST_SIPAC.$match[1];
      return $url;
  }
  
  private function extraiIdByLink($link) {
      $aLinks = explode('id=', $link);
      if(isset($aLinks[1])){
          return $aLinks[1];
      } else {
          return false;
      }
  }
  
  /**
   * Realiza o tratamento de um conteúdo HTML passado trazendo tudo o que esta entre a tag passada como parâmetro.
   * 
   * A expressão regular filtra o conteúdo pelos seguintes parametros:
   * <ul><b>' \b ' </b> garante que um erro de digitação (como <codeS>) não seja capturado</ul>
   * <ul><b>' [^>]* ' </b> captura o conteúdo de uma tag com atributos como por exemplo uma classe</ul>
   * <ul><b>' s '</b> captura conteúdos com novas linhas</ul>
   * 
   * @param String $html - conteúdo a ser filtrado
   * @param String $nomeTag - tag que será utilizada como parametro para o filtro
   * @return String - conteúdo que se encontra entre a tag corresponte. Retorna false quando nada form encontrado.
   */
  private function getDadosEntreTags($html, $nomeTag) {
      $aMatchs;
      $expressao = "#<\s*?$nomeTag\b[^>]*>(.*?)</$nomeTag\b[^>]*>#s";
      preg_match($expressao, $html, $aMatchs);
      if(isset($aMatchs[1])){
          return $aMatchs[1];
      }
      return false;
  }
  
  /**
   * Retorna todo o conteúdo de uma pagina html
   * @param String $Html
   * @return String - dados da página.
   */
  private function getHtml($Html) {
      try{
          return file_get_contents($Html);
      } catch (Exception $e){
          echo e;
      }
  }
}



