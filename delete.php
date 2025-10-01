<?php
session_start();
require_once __DIR__ . '/model/ArquivosModel.php';

$ArquivosModel = new ArquivosModel();

//Validar o tipo de requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    $_SESSION['mensagem'] = 'Metodo de validação inválido!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('location: index.php');
    exit;
}

//Validar se o id foi enviado
if (!isset(($_POST['id'])) || empty($_POST['id'])){
    $_SESSION['mensagem']= 'ID da imagem não informado!';
    $_SESSION['tipo_mensagem']= 'erro';
    header('location: index.php');
    exit;
}

$id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

if ($id  === false){
    $_SESSION['mensagem']= 'ID inválido';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

//buscar a imagem no banco
$imagem = $ArquivosModel->buscarPorId($id);

if(!$imagem){
    $_SESSION['mensagem']= 'Imagem não encontrada';
    $_SESSION['tipo_mensagem']= 'erro';
    header('Location: index.php');
    exit;
}

// Deletar o arquivo
$caminhoArquivo = $imagem['caminho'];
if(file_exists($caminhoArquivo)){
    if(!unlink($caminhoArquivo)){
        $_SESSION['mensagem']= 'erro  ao deletar o arquivo fisico';
        $_SESSION['tipo_mensagem']= 'erro';
        header('Location: index.php');
        exit;
    }
}

// Deletar do banco 
try {
    $confirmacao = $ArquivosModel->deletar($id);
    
    if ($confirmacao) {
        $_SESSION['mensagem'] = 'Imagem deletada com sucesso!';
        $_SESSION['tipo_mensagem'] = 'sucesso';
    } else {
        $_SESSION['mensagem'] = 'Erro ao deletar imagem do banco de dados!';
        $_SESSION['tipo_mensagem'] = 'erro';
    }
} catch (Exception $e) {
    $_SESSION['mensagem'] = 'Erro ao processar a exclusão: ' . $e->getMessage();
    $_SESSION['tipo_mensagem'] = 'erro';
}

header('Location: index.php');
exit;

?>