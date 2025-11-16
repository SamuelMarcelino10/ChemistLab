# ⚗️ ChemistLab

Sistema de gerenciamento e controle de estoque para laboratórios químicos.

## 📋 Pré-requisitos

Antes de começar, certifique-se de ter o seguinte ambiente configurado:

* **PHP 7.4+**
* **PostgreSQL** (Banco de dados)
* **Driver PDO_PGSQL** habilitado no `php.ini`
* Servidor web (Apache, Nginx ou PHP Built-in Server)

---

## 🚀 Instalação e Configuração Inicial

### 1. Banco de Dados
Certifique-se de que o banco de dados `chemistlab` foi criado no seu PostgreSQL.

### 2. Criar Usuário Administrativo (Obrigatório)

Para realizar o primeiro login, é necessário executar um script manual que insere o usuário "Regente" no banco de dados com a senha criptografada corretamente.

**Passo A:** Crie um arquivo chamado `setup_regente.php` na raiz do projeto.

**Passo B:** Cole o seguinte código dentro dele:

```php
<?php

echo "Iniciando script de cadastro do Regente...<br>";

$host = 'localhost';
$dbname = 'chemistlab'; 
$user = 'postgres';
$password = '1234'; // Ajuste a senha do seu banco aqui

$nome_regente = "Admin Regente";
$cpf_regente = "000.000.000-00";
$email_regente = "admin@chemistlab.com";
$senha_plana = "admin123"; 

try {
    $dsn = "pgsql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificação: O usuário já existe?
    $stmt_check = $pdo->prepare("SELECT id FROM usuarios WHERE cpf = :cpf");
    $stmt_check->bindParam(':cpf', $cpf_regente);
    $stmt_check->execute();

    if ($stmt_check->fetch()) {
        echo "<strong style='color:orange;'>AVISO:</strong> O usuário com CPF $cpf_regente já existe no banco. Nenhuma ação foi tomada.";
    } else {
        $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome_completo, cpf, email, senha, tipo_conta) 
                VALUES (:nome, :cpf, :email, :senha_hash, 'Regente')";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':nome', $nome_regente);
        $stmt->bindParam(':cpf', $cpf_regente);
        $stmt->bindParam(':email', $email_regente);
        $stmt->bindParam(':senha_hash', $senha_hash);

        $stmt->execute();

        echo "<strong style='color:green;'>SUCESSO!</strong> Usuário Regente criado.<br>";
        echo "<b>CPF para login:</b> $cpf_regente<br>";
        echo "<b>Senha para login:</b> $senha_plana<br>";
    }

} catch (PDOException $e) {
    echo "<strong style='color:red;'>ERRO:</strong> Falha ao conectar ou inserir no banco.<br>";
    echo "Detalhes: " . $e->getMessage();
}
?>
```
## 🔑 Acesso ao Sistema

Após executar o script com sucesso, utilize as credenciais abaixo na tela de login:

| Campo | Valor |
| :--- | :--- |
| **CPF** | `000.000.000-00` |
| **Senha** | `admin123` |
| **Perfil** | Regente |
