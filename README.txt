========================================
HOSPITALGEST - Sistema de Gestão de Inventário Hospitalar de Equipamentos Médicos
========================================

PROJETO: SIBDAS - Sistemas de Informação e Base de Dados Aplicados à Saúde (LEBIOM)
NOME DO ESTUDANTE: Matilde Queirós
NÚMERO DO ESTUDANTE: 1241344
ANO LETIVO: 2025/2026


----------------------------------------
1. DESCRIÇÃO DA APLICAÇÃO
----------------------------------------

O HospitalGest é uma aplicação web que simula um sistema de gestão de
inventário hospitalar de equipamentos médicos, desenvolvida individualmente
no âmbito da unidade curricular SIBDAS.

A aplicação inclui:
- Área pública (Front Office): website institucional da empresa de software,
  com informação sobre serviços, contactos e formulário de mensagens.
- Área privada (Back Office): sistema de gestão utilizado pelo hospital,
  com autenticação, gestão de equipamentos, fornecedores, localizações,
  documentação, garantias e contratos, e dashboard com indicadores.


----------------------------------------
2. INSTRUÇÕES DE INSTALAÇÃO E EXECUÇÃO
----------------------------------------

1. Copiar a pasta "hospitalgest" para o servidor local (XAMPP), dentro de:
   htdocs/sibdas/1241344/hospitalgest

2. Base de dados:
   - Criar a base de dados no servidor MySQL.
   - Importar o ficheiro "Base de Dados/hospitalgest_ddl.sql" (estrutura).
   - Importar o ficheiro "Base de Dados/hospitalgest_inserts.sql" (dados).

3. Configuração:
   - Verificar/ajustar as credenciais de acesso à base de dados no ficheiro
     "config/config.php" (MYSQL_HOST, MYSQL_PORT, MYSQL_DATABASE,
     MYSQL_USERNAME, MYSQL_PASSWORD).

4. Aceder à aplicação através do browser, no seguinte endereço:
   http://127.0.0.1/sibdas/1241344/hospitalgest


----------------------------------------
3. INSTRUÇÕES PARA TESTES PRINCIPAIS
----------------------------------------

- Área pública: aceder ao endereço acima sem fazer login. Testar a navegação
  pelas secções (Sobre Nós, Serviços, Contactos) e o envio de uma mensagem
  através do formulário de contacto.

- Login: aceder a "/public/login.php" e entrar com qualquer uma das
  credenciais indicadas no ponto 4.

- Após login, testar:
  - Dashboard: indicadores e gráficos (por serviço e por categoria).
  - Equipamentos / Fornecedores / Localizações: listar, pesquisar/filtrar,
    consultar detalhes, criar, editar, eliminar (soft delete) e reativar.
  - Exportação de dados: nas listagens de Equipamentos, Fornecedores e
    Localizações, usar os botões "Exportar CSV" e "Exportar JSON".
  - Gestão da Área Pública e Mensagens do Público: visível apenas com o
    perfil Administrador.
  - Perfis de acesso: testar o mesmo percurso com os 3 perfis (ver ponto 4)
    para confirmar as diferenças de permissões.
  - Logout: confirmar que a sessão é destruída e que páginas privadas
    deixam de estar acessíveis sem novo login.


----------------------------------------
4. CREDENCIAIS DE ACESSO (TODOS OS PERFIS)
----------------------------------------

ADMINISTRADOR (acesso total)
  Email: admin@hospitalgest.pt
  Password: admin123

TÉCNICO (sem acesso a Gestão da Área Pública e Mensagens; pode criar,
editar e eliminar equipamentos, fornecedores e localizações)
  Email: tecnico@hospitalgest.pt
  Password: tecnico1

PROFISSIONAL DE SAÚDE (sem acesso a Gestão da Área Pública e Mensagens;
apenas consulta - sem criar, editar ou eliminar registos)
  Email: enfermeiro@hospitalgest.pt
  Password: saude123


----------------------------------------
5. INFORMAÇÃO ADICIONAL
----------------------------------------

- Todas as bibliotecas externas (Bootstrap, jQuery, Font Awesome,
  DataTables, Flatpickr e Chart.js) estão incluídas localmente na pasta
  "assets/", pelo que a aplicação funciona sem ligação à internet.

- A aplicação regista eventos de autenticação (logins bem-sucedidos e
  falhados) no ficheiro "logs/eventos.log", criado automaticamente.

- A eliminação de equipamentos, fornecedores e localizações é feita por
  soft delete (o registo é marcado como inativo, podendo ser reativado).