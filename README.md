# LarPerto

Portal imobiliário para compra e aluguel de imóveis em Posse-GO e região.

![Prévia do portal LarPerto](image.png)

## Tecnologias

- PHP 8.1+
- MySQL ou MariaDB
- Apache com `mod_rewrite`
- HTML5, CSS3 e JavaScript
- PDO com prepared statements
- XAMPP ou hospedagem compartilhada com cPanel

## Requisitos

- XAMPP instalado
- Apache ativo
- MySQL ativo
- PHP 8.1 ou superior
- Extensão PDO MySQL habilitada

## Instalação no XAMPP

1. Coloque o projeto em:

```text
C:\xampp\htdocs\larperto
```

2. Inicie **Apache** e **MySQL** no painel do XAMPP.

3. Crie o banco de dados importando o arquivo:

```text
sql/schema.sql
```

Pode usar o phpMyAdmin em:

```text
http://localhost/phpmyadmin
```

4. Confira os dados de conexão em:

```text
app/config/database.php
```

Configuração padrão do XAMPP:

```php
'host' => 'localhost',
'dbname' => 'larperto',
'username' => 'root',
'password' => '',
```

5. Acesse o portal:

```text
http://localhost/larperto/public/
```

## Acesso pela rede local

Para acessar pelo celular ou por outro computador conectado à mesma rede, descubra o IPv4 do computador com:

```powershell
ipconfig
```

Depois use o endereço:

```text
http://SEU-IP/larperto/public/
```

Exemplo:

```text
http://192.168.137.1/larperto/public/
```

Os caminhos de CSS e JavaScript são relativos, portanto funcionam tanto com `localhost` quanto pelo IP da rede.

## Acesso administrativo

Página de login:

```text
http://localhost/larperto/public/entrar
```

Painel:

```text
http://localhost/larperto/public/admin
```

Credenciais iniciais:

```text
E-mail: admin@larperto.com.br
Senha: admin123
```

Altere a senha antes de colocar o sistema em produção.

## Funcionalidades atuais

- Página inicial responsiva
- Busca de imóveis
- Filtros por finalidade, categoria, cidade, bairro e preço
- Cadastro de usuários
- Login e logout
- Cadastro de imóveis
- Upload de até 15 imagens por imóvel
- Validação de tipo e tamanho das imagens
- Status de moderação: pendente, publicado, recusado e inativo
- Página individual do imóvel
- Botão de contato pelo WhatsApp
- Painel do anunciante
- Painel administrativo
- Aprovação de anúncios
- Espaço reservado para publicidade
- SEO básico com meta description e Open Graph
- URLs amigáveis com `.htaccess`

## Estrutura do projeto

```text
larperto/
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── robots.txt
│   ├── sitemap.php
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── img/
│       └── uploads/imoveis/
├── app/
│   ├── config/
│   ├── core/
│   ├── models/
│   ├── controllers/
│   └── views/
│       ├── layouts/
│       ├── home/
│       ├── auth/
│       ├── imovel/
│       ├── usuario/
│       └── admin/
└── sql/
    └── schema.sql
```

## Principais rotas

```text
/                         Página inicial
/buscar                   Busca de imóveis
/imovel/{slug}            Página individual do imóvel
/entrar                   Login
/cadastrar                Cadastro de usuário
/anunciar                 Novo anúncio
/minha-conta              Painel do anunciante
/admin                    Painel administrativo
/sair                     Logout
```

## Segurança

- Senhas armazenadas com `password_hash()`.
- Login validado com `password_verify()`.
- Consultas ao banco com PDO e prepared statements.
- Saída HTML escapada para reduzir risco de XSS.
- Upload validado por MIME type, extensão e tamanho.
- Nomes de arquivos enviados gerados aleatoriamente.
- Área administrativa protegida por sessão e papel de usuário.
- Arquivos PHP bloqueados dentro da pasta de uploads pelo `.htaccess`.

## Validação do código

Para verificar a sintaxe PHP usando o XAMPP no PowerShell:

```powershell
Get-ChildItem -Path . -Recurse -Filter *.php | ForEach-Object {
    & 'C:\xampp\php\php.exe' -l $_.FullName
}
```

## Próximas melhorias

- Editar e excluir anúncios pelo painel do anunciante
- Recusar anúncios com motivo
- Recuperação de senha por e-mail
- Favoritos
- Galeria visual completa das fotos
- Denúncia de anúncios
- Imóveis em destaque
- Estatísticas de visualizações
- Login com Google
- Planos para imobiliárias e corretores
- Sitemap dinâmico

## Status

MVP em desenvolvimento para Posse-GO e região.
