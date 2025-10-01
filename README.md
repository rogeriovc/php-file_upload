#  Sistema de Armazenamento de Imagens

Sistema web para upload, visualização e gerenciamento de imagens com PHP e MySQL.

##  Funcionalidades

-  Upload de imagens (JPEG, PNG, GIF, WEBP)
-  Limite de 16MB por arquivo
-  Validação de tipo e extensão
-  Visualização em grid responsivo
-  Download de imagens
-  Exclusão de imagens
-  Armazenamento seguro (nomes únicos)
-  Registro de nome original e data de envio

##  Requisitos

- XAMPP (ou WAMP/LAMP)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior

##  Instalação

1. **Extraia os arquivos na pasta htdocs do XAMPP**
   ```
   C:\xampp\htdocs\nome-da-pasta
   ```

2. **Inicie Apache e MySQL no XAMPP Control Panel**

3. **Configure o banco de dados**
   - Edite `database/Database.php` com suas credenciais MySQL
   - Acesse http://localhost/phpmyadmin
   - Crie o banco de dados executando o script `database/ddl.sql`

4. **Acesse no navegador**
   ```
   http://localhost/nome-da-pasta
   ```
   *Exemplo: `http://localhost/php-file_upload`*

##  Estrutura

```
├── assets/
│   └── style.css          # Estilos da aplicação
├── database/
│   ├── Database.php       # Conexão com banco
│   └── ddl.sql           # Script de criação do banco
├── model/
│   └── ArquivosModel.php # Model para operações
├── upload/               # Diretório de imagens (criado automaticamente)
├── index.php            # Página principal
├── upload.php           # Processa upload
└── delete.php           # Processa exclusão
```

##  Como Usar

1. **Enviar imagem**: Clique em "Escolha uma imagem", selecione o arquivo e clique em "Enviar Imagem"
2. **Visualizar**: As imagens aparecem automaticamente na galeria
3. **Baixar**: Clique no botão "Baixar" no card da imagem
4. **Deletar**: Clique no botão "Deletar" e confirme a exclusão

##  Segurança

- Validação de tipo MIME
- Verificação de extensão
- Validação com `getimagesize()`
- Nomes únicos para evitar sobrescrita
- Limite de tamanho de 16MB
- Proteção contra XSS com `htmlspecialchars()`

##  Tecnologias

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Frontend**: HTML5, CSS3
- **Design**: Responsivo e moderno

##  Observações

- O diretório `upload/` é criado automaticamente se não existir
- Imagens são armazenadas com nomes únicos (timestamp + uniqid)
- O nome original é preservado no banco de dados
- Ao deletar, tanto o arquivo quanto o registro são removidos

---

**Desenvolvido com carinho em PHP**