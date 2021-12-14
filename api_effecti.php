<?php
header('Content-Type: application/json');
require './services/interceptor_sipac.php';


/*

 * 
 * se for get busca se for post atualiza. 
 * Criar configurar banco de dados para salvar
 * realizar documentacao corerta com m script de criacao de tabela;
 * disponibilizar o link no git
 * 
 * busca os dados verifica se existe no banco; se existe ignora se não atualiza.
*/
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $oInterceptor = new InterceptorSipac();
    $oInterceptor->getAllLicitacoesToJson();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['operacao'])){
        $oInterceptor = new InterceptorSipac();
        $operacao = $_POST['operacao'];
        switch ($operacao) {
            case 'busca_licitacoes':
                $oInterceptor->getAllLicitacoesToJson();
                break;
            case 'busca_itens':
                if(isset($_POST['chave'])){
                    $chave = $_POST['chave'];
                    $oInterceptor->getAllItensLicitacaoToJson($chave);
                } else {
                    echo 'parâmetro "chave" não encontrado';
                }
                break;

            default:
                echo 'Nenhuma ação foi encontrada!';
                break;
        }
    } else {
        echo 'Parâmetro "operacao" não encontrado.';
    }
} 




