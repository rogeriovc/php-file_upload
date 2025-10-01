<?php
require_once __DIR__ . '/model/ArquivosModel.php';

$ArquivosModel = new ArquivosModel();
$imagensLista = $ArquivosModel->listar();

// Verificar se há mensagem de sucesso ou erro na sessão
session_start();
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null;
$tipoMensagem = isset($_SESSION['tipo_mensagem']) ? $_SESSION['tipo_mensagem'] : null;
unset($_SESSION['mensagem']);
unset($_SESSION['tipo_mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Upload de Arquivos</title>
</head>
<body>
    <div class="container">
        <h1>Sistema de Armazenamento de Imagens</h1>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo $tipoMensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <div class="form-group">
                <label for="imagem">Escolha uma imagem (máx. 16MB):</label>
                <input type="file" id="imagem" name="imagem" accept="image/*" required>
            </div>
            <button type="submit" class="btn-enviar">
                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                    <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/>
                </svg>
                Enviar Imagem
            </button>
        </form>

        <h2>Galeria de Imagens</h2>
        
        <?php if (empty($imagensLista)): ?>
            <p class="sem-imagens">Nenhuma imagem enviada ainda. Faça o upload da primeira!</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach($imagensLista as $imagem): ?>
                    <div class="card">
                        <div class="image-container">
                            <img alt="<?php echo htmlspecialchars($imagem['nome']); ?>" 
                                 class="imgPrincipal" 
                                 src="<?php echo htmlspecialchars($imagem['caminho']); ?>" 
                                 loading="lazy" />
                        </div>
                        <div class="card-info">
                            <p class="nome-arquivo" title="<?php echo htmlspecialchars($imagem['nome']); ?>">
                                <strong>Arquivo:</strong> <?php echo htmlspecialchars($imagem['nome']); ?>
                            </p>
                            <p class="nome-sistema">
                                <strong>ID Sistema:</strong> <?php echo htmlspecialchars($imagem['nome_original']); ?>
                            </p>
                            <p class="data">
                                <strong>Enviado em:</strong> <?php echo date('d/m/Y H:i', strtotime($imagem['data_envio'])); ?>
                            </p>
                            <p class="tamanho">
                                <strong>Tamanho:</strong> <?php echo number_format($imagem['tamanho'] / 1024, 2); ?> KB
                            </p>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <a href="<?php echo htmlspecialchars($imagem['caminho']); ?>" download="<?php echo htmlspecialchars($imagem['nome']); ?>" class="btn-download" style="flex: 1;">
                                <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                    <path d="M160-80v-80h640v80H160Zm320-160L200-600h160v-280h240v280h160L480-240Zm0-130 116-150h-76v-280h-80v280h-76l116 150Zm0-150Z"/>
                                </svg>
                                Baixar
                            </a>
                            <form method="POST" action="delete.php" onsubmit="return confirm('Tem certeza que deseja deletar esta imagem?');" style="flex: 1;">
                                <input type="hidden" name="id" value="<?php echo $imagem['id']; ?>">
                                <button type="submit" class="btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                    </svg>
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>