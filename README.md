# Corte Supremo 🥩🔥

---
## 🎯 Objetivo do Projeto

O objetivo deste projeto é desenvolver uma plataforma web para o restaurante "Corte Supremo" que beneficie tanto os clientes quanto o estabelecimento, otimizando a experiência gastronômica e a eficiência operacional.

**Para os Clientes:**

* **Conveniência e Planejamento:** Proporcionar a comodidade de explorar o cardápio completo e selecionar seus pedidos antecipadamente, no conforto de suas casas.
* **Previsibilidade Financeira:** Permitir que tenham uma estimativa clara dos gastos antes mesmo de chegarem ao restaurante.
* **Redução do Tempo de Espera:** Reduzir significativamente o tempo de espera no local, uma vez que os pedidos já estarão pré-definidos e poderão ser preparados com maior agilidade assim que chegarem.

**Para o Restaurante:**

* **Eficiência Operacional Aprimorada:** Aumentar a eficiência da cozinha e do serviço ao ter conhecimento prévio dos pedidos agendados para o dia.
* **Minimização de Desperdícios:** Reduzir o desperdício de ingredientes, permitindo um planejamento mais preciso e o preparo focado nos pratos efetivamente reservados.
* **Agilidade no Atendimento:** Agilizar a entrega dos pedidos às mesas, otimizando o fluxo de serviço.
* **Satisfação Elevada do Cliente:** Melhorar a experiência geral do cliente, oferecendo pratos de qualidade com um tempo de espera consideravelmente reduzido, resultando em maior contentamento e fidelização.

---

## Descrição
**Corte Supremo** é um projeto web para um restaurante fictício especializado em carnes. O site permite que os usuários visualizem a página inicial com destaques, conheçam a história do restaurante na página "Sobre", explorem o cardápio completo e, o mais importante, façam reservas online. O sistema de reservas inclui a opção de pré-selecionar itens do cardápio e envia um e-mail de confirmação para o cliente após a conclusão da reserva.

O projeto demonstra funcionalidades de frontend para interação com o usuário e um backend em PHP para processamento de dados, interação com banco de dados MySQL e envio de e-mails.

---

## 🛠️ Linguagens e Tecnologias Usadas

* **Frontend:**
    * ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
    * ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
    * ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
* **Backend:**
    * ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
* **Banco de Dados:**
    * ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
* **Servidor Local:**
    * ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
* **Envio de E-mail:**
    * PHPMailer (Pacote PHP)

*(**Nota:** Para usar os shields acima, você precisa garantir que os URLs das imagens sejam válidos e apontem para os badges corretos. Você pode gerá-los em [Shields.io](https://shields.io/) ou sites similares.)*

---

## ⚙️ Como Instalar o XAMPP e Configurar o Projeto Localmente

Para testar este projeto localmente, você precisará do XAMPP, que é um ambiente de desenvolvimento PHP popular.

### 1. Instalar o XAMPP
   a.  **Baixe o XAMPP:** Vá para a página oficial de downloads do [XAMPP](https://www.apachefriends.org/index.html) e baixe a versão apropriada para o seu sistema operacional.
   b.  **Execute o Instalador:** Siga as instruções do instalador. Recomenda-se manter as opções padrão, garantindo que **Apache** e **MySQL** sejam instalados.
   c.  **Inicie os Módulos:** Após a instalação, abra o Painel de Controle do XAMPP e inicie os módulos **Apache** e **MySQL**.

### 2. Configurar os Arquivos do Projeto
   a.  **Clone ou Baixe o Repositório:** Obtenha os arquivos do projeto do GitHub.
   b.  **Mova os Arquivos:** Copie a pasta do projeto para o diretório `htdocs` dentro da sua pasta de instalação do XAMPP (geralmente `C:\xampp\htdocs\` no Windows ou `/Applications/XAMPP/htdocs/` no macOS).

### 3. Criar o Banco de Dados 🗄️
   a.  **Acesse o phpMyAdmin:** Abra seu navegador e vá para `http://localhost/phpmyadmin`.
   b.  **Crie um Novo Banco de Dados:**
       * Clique em "**Novo**" (New) no menu lateral esquerdo.
       * No campo "Nome do banco de dados" (Database name), digite `restaurante_reservas`.
       * Selecione uma codificação como `utf8mb4_general_ci` para melhor compatibilidade de caracteres (opcional, mas recomendado).
       * Clique em "**Criar**" (Create).
   c.  **Importe as Tabelas:**
       * Selecione o banco de dados `restaurante_reservas` que você acabou de criar na lista à esquerda.
       * Clique na aba "**SQL**" no topo da página.
       * Copie e cole o código SQL fornecido abaixo na caixa de texto.
       * Clique no botão "**Executar**" (Go) no canto inferior direito.

### 4. Código SQL para o Banco de Dados

```sql
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    unidade VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reserva_menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    item VARCHAR(255) NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
);
```

### 5. Verificar Configurações de Conexão com o Banco de Dados
Os arquivos `reserva2.php` e `test_db.php` contêm as credenciais de conexão com o banco de dados. Estas são as configurações padrão do XAMPP. Se o seu ambiente XAMPP tiver um usuário ou senha diferentes para o MySQL, ajuste esses valores nos arquivos PHP.
No `reserva2.php` e `test_db.php`, você encontrará:

```php

$servername = "localhost"; // Servidor MySQL, geralmente localhost
$username = "root";    // Usuário padrão do XAMPP para MySQL
$password = "";        // Senha padrão do XAMPP para MySQL (vazia)
$dbname = "restaurante_reservas"; // Nome do banco de dados criado

```

### 6. Configurar o Envio de E-mail (PHPMailer)

O arquivo `reserva2.php` está configurado para enviar e-mails de confirmação de reserva usando PHPMailer via SMTP do Gmail. Para que isso funcione, você precisará configurar suas próprias credenciais do Gmail.

Localize esta seção em `reserva2.php`:

```php

$mail->isSMTP(); // Define o Mailer para usar SMTP
$mail->Host = 'smtp.gmail.com';   // Servidor SMTP do Gmail
$mail->SMTPAuth = true; // Habilita autenticação SMTP
$mail->Username = 'seu.emai1@gmail.com';   // SEU EMAIL GMAIL - SUBSTITUA
$mail->Password = 'vyzfqwfxgsckokbs'; // SUA SENHA DE APP GMAIL - SUBSTITUA
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilita criptografia TLS
$mail->Port = 587;     // Porta TCP para TLS
$mail->CharSet = 'UTF-8'; // Define o charset

$mail->setFrom('seu.emai1@gmail.com', 'Corte Supremo'); // E-mail e nome do remetente - SUBSTITUA
$mail->addAddress($email); // Adiciona o e-mail do destinatário (obtido do formulário)

```


### 7. Testar o Projeto 🧪

1.   Após configurar o XAMPP, os arquivos do projeto, o banco de dados e (opcionalmente) o envio de e-mail, abra seu navegador.
2.   Acesse o projeto. Se você colocou a pasta do projeto diretamente em `htdocs` e a nomeou, por exemplo, `corte-supremo-website`, o URL será `http://localhost/corte-supremo-website/`. A página principal é `index.html`.
3.   Navegue pelas diferentes páginas:
    * Início (`index.html`)
    * Cardápio (`html/cardapio.html`)
    * Sobre (`html/sobre.html`)
    * Reservas (`html/reservas.html`)
4.   Teste o formulário de reserva na página `reservas.html`. Preencha os dados e, se desejar, selecione itens do menu.
5.   Após submeter o formulário, verifique:
    * Se uma mensagem de sucesso ou erro é exibida na página.
    * Se os dados da reserva foram salvos corretamente nas tabelas `reservas` e `reserva_menu` no banco de dados `restaurante_reservas` (você pode verificar isso usando o phpMyAdmin).
    * Se um e-mail de confirmação foi enviado para o endereço de e-mail fornecido (caso tenha configurado corretamente as credenciais do PHPMailer).
6.   Você também pode usar o script `test_db.php` para uma verificação rápida da conexão com o banco de dados. Acesse-o via `http://localhost/nome-da-pasta-do-projeto/test_db.php`. Ele tentará conectar ao servidor MySQL e selecionar o banco de dados `restaurante_reservas`.
