<?php
session_start();
require_once __DIR__ . '/model/ArquivosModel.php';

$ArquivosModel = new ArquivosModel();

// Validar o tipo de requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = 'Método de requisição inválido!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// Validar o conteúdo do formulário
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] === UPLOAD_ERR_NO_FILE) {
    $_SESSION['mensagem'] = 'Nenhuma imagem foi enviada!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

$imagem = $_FILES["imagem"];

// Verificar erros no upload
if ($imagem['error'] !== UPLOAD_ERR_OK) {
    $mensagensErro = [
        UPLOAD_ERR_INI_SIZE => 'O arquivo excede o tamanho máximo permitido pelo servidor.',
        UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o tamanho máximo permitido pelo formulário.',
        UPLOAD_ERR_PARTIAL => 'O arquivo foi enviado apenas parcialmente.',
        UPLOAD_ERR_NO_TMP_DIR => 'Pasta temporária não encontrada.',
        UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo no disco.',
        UPLOAD_ERR_EXTENSION => 'Uma extensão PHP interrompeu o upload.'
    ];
    
    $_SESSION['mensagem'] = $mensagensErro[$imagem['error']] ?? 'Erro desconhecido no upload.';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// VALIDAÇÃO 1: Tamanho máximo de 16MB
$tamanhoMaximo = 16 * 1024 * 1024; // 16MB em bytes
if ($imagem['size'] > $tamanhoMaximo) {
    $_SESSION['mensagem'] = 'A imagem excede o tamanho máximo de 16MB!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// Validar o tipo e extensão de arquivo
$tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$extensoesPermitidas = ['jpeg', 'jpg', 'png', 'webp', 'gif'];

// Validar MIME type
if (!in_array($imagem['type'], $tiposPermitidos)) {
    $_SESSION['mensagem'] = 'Tipo de arquivo inválido! Apenas imagens são permitidas.';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// Validar extensão
$arquivoExtensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
if (!in_array($arquivoExtensao, $extensoesPermitidas)) {
    $_SESSION['mensagem'] = 'Extensão do arquivo inválida!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// Validação adicional: verificar se é realmente uma imagem
$infoImagem = getimagesize($imagem['tmp_name']);
if ($infoImagem === false) {
    $_SESSION['mensagem'] = 'O arquivo enviado não é uma imagem válida!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// VALIDAÇÃO 2: Criar diretório upload se não existir
$diretorioDestino = "upload/";
if (!file_exists($diretorioDestino)) {
    if (!mkdir($diretorioDestino, 0755, true)) {
        $_SESSION['mensagem'] = 'Erro ao criar o diretório de upload!';
        $_SESSION['tipo_mensagem'] = 'erro';
        header('Location: index.php');
        exit;
    }
}

$caminhoTemporario = $imagem["tmp_name"];

// Tratamento para nome de arquivo único (evita sobrescrita)
$nomeOriginal = basename($imagem["name"]);
$nomeUnico = uniqid() . '_' . time() . '.' . $arquivoExtensao;
$caminhoDestino = $diretorioDestino . $nomeUnico;

// Mover arquivo para o destino
$salvou = move_uploaded_file($caminhoTemporario, $caminhoDestino);

if (!$salvou) {
    $_SESSION['mensagem'] = 'Erro ao salvar o arquivo no servidor!';
    $_SESSION['tipo_mensagem'] = 'erro';
    header('Location: index.php');
    exit;
}

// Preparar dados para salvar no banco
$dados = [
    "nome" => $nomeOriginal,
    "nome_original" => $nomeUnico,
    "caminho" => $caminhoDestino,
    "tamanho" => $imagem['size']
];

// Salvar no banco de dados
try {
    $confirmacao = $ArquivosModel->criar($dados);
    
    if ($confirmacao) {
        $_SESSION['mensagem'] = 'Imagem enviada com sucesso!';
        $_SESSION['tipo_mensagem'] = 'sucesso';
    } else {
        // Se falhar ao salvar no banco, remover o arquivo
        unlink($caminhoDestino);
        $_SESSION['mensagem'] = 'Erro ao salvar informações no banco de dados!';
        $_SESSION['tipo_mensagem'] = 'erro';
    }
} catch (Exception $e) {
    // Se houver erro, remover o arquivo
    if (file_exists($caminhoDestino)) {
        unlink($caminhoDestino);
    }
    $_SESSION['mensagem'] = 'Erro ao processar a imagem: ' . $e->getMessage();
    $_SESSION['tipo_mensagem'] = 'erro';
}

header('Location: index.php');
exit;