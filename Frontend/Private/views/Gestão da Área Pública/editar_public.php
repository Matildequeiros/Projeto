<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <?php include '../../includes/sidebar.php'; ?>

        
            <!-- CONTEÚDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-4">

                <div class="card shadow p-4" style="border-radius: 12px; max-width: 1000px; margin: 0 auto;">

                    <h2 class="mb-4" style="color: #1a826d;">
                        <i class="fa-solid fa-globe me-2"></i> Gestão da Área Pública
                    </h2>

                    <form>

                        <div class="accordion" id="accordionPublico">

                            <!-- SECÇÃO HERO -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heroHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#hero" aria-expanded="true" aria-controls="hero">
                                        Secção Hero
                                    </button>
                                </h2>

                                <div id="hero" class="accordion-collapse collapse show" aria-labelledby="heroHeading"
                                    data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="mb-3">
                                            <label class="form-label">Título</label>
                                            <input type="text" class="form-control"
                                                value="Soluções Digitais para Gestão Hospitalar">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Subtítulo</label>
                                            <textarea class="form-control"
                                                rows="2">Desenvolvemos software para inventário hospitalar...</textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Texto do Botão</label>
                                            <input type="text" class="form-control" value="Fale Connosco">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- SOBRE NÓS -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="sobreHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#sobre" aria-expanded="false" aria-controls="sobre">
                                        Sobre Nós
                                    </button>
                                </h2>

                                <div id="sobre" class="accordion-collapse collapse" aria-labelledby="sobreHeading"
                                    data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="mb-3">
                                            <label class="form-label">Texto Principal</label>
                                            <textarea class="form-control"
                                                rows="3">A HospitalGest desenvolve soluções digitais...</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">O Problema</label>
                                                <textarea class="form-control"
                                                    rows="2">Hospitais usam Excel...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">A Nossa Solução</label>
                                                <textarea class="form-control"
                                                    rows="2">Aplicação web para organizar...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">O Que Oferecemos</label>
                                                <textarea class="form-control"
                                                    rows="3">Inventário estruturado...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Objetivo</label>
                                                <textarea class="form-control"
                                                    rows="2">Criar uma plataforma que ajude...</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- SERVIÇOS -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="servicosHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#servicos" aria-expanded="false" aria-controls="servicos">
                                        Serviços
                                    </button>
                                </h2>

                                <div id="servicos" class="accordion-collapse collapse" aria-labelledby="servicosHeading"
                                    data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Gestão de Equipamentos</label>
                                                <textarea class="form-control"
                                                    rows="2">Registo, edição e consulta...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Localizações</label>
                                                <textarea class="form-control"
                                                    rows="2">Organização dos equipamentos...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Fornecedores</label>
                                                <textarea class="form-control"
                                                    rows="2">Associação de fabricantes...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Documentação</label>
                                                <textarea class="form-control" rows="2">Gestão de manuais...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Garantias e Contratos</label>
                                                <textarea class="form-control"
                                                    rows="2">Consulta rápida de datas...</textarea>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Dashboard</label>
                                                <textarea class="form-control"
                                                    rows="2">Pesquisa avançada e indicadores...</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- SECÇÃO CONTACTO -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="contactoHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#contacto" aria-expanded="false" aria-controls="contacto">
                                        Secção Contacto
                                    </button>
                                </h2>

                                <div id="contacto" class="accordion-collapse collapse" aria-labelledby="contactoHeading"
                                    data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="mb-3">
                                            <label class="form-label">Título da Barra</label>
                                            <input type="text" class="form-control" value="Como Podemos Ajudar?">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Subtítulo da Barra</label>
                                            <textarea class="form-control"
                                                rows="2">A nossa equipa está disponível para esclarecer dúvidas e fornecer toda a informação necessária.</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Texto do Botão "Fale Connosco"</label>
                                            <input type="text" class="form-control" value="Fale Connosco">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- POPUP -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="popupHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#popup" aria-expanded="false" aria-controls="popup">
                                        Popup "Fale Connosco"
                                    </button>
                                </h2>

                                <div id="popup" class="accordion-collapse collapse" aria-labelledby="popupHeading"
                                    data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="mb-3">
                                            <label class="form-label">Título do Popup</label>
                                            <input type="text" class="form-control" value="Como Podemos Ajudar?">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Label - Nome</label>
                                                <input type="text" class="form-control" value="Nome:">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Label - Email</label>
                                                <input type="text" class="form-control" value="Email:">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Label - Assunto</label>
                                                <input type="text" class="form-control" value="Assunto:">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Label - Mensagem</label>
                                                <input type="text" class="form-control" value="Mensagem:">
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Texto do Botão "Enviar"</label>
                                            <input type="text" class="form-control" value="Enviar">
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- CONTACTOS -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="contactosHeading">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#contactos" aria-expanded="false" aria-controls="contactos">
                                        Contactos
                                    </button>
                                </h2>

                                <div id="contactos" class="accordion-collapse collapse"
                                    aria-labelledby="contactosHeading" data-bs-parent="#accordionPublico">
                                    <div class="accordion-body">

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Telefone Fixo</label>
                                                <input type="text" class="form-control" value="254 344 253">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Telemóvel</label>
                                                <input type="text" class="form-control" value="912 745 234">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" value="geral@hospitalgest.pt">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Morada</label>
                                                <input type="text" class="form-control" value="Rua da Boa Saúde nº10">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Localidade</label>
                                                <input type="text" class="form-control"
                                                    value="4523-089 Viana do Castelo">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Texto do Horário</label>
                                                <input type="text" class="form-control" value="Todos os dias">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Horário</label>
                                                <input type="text" class="form-control" value="07:00h - 22:00h">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- BOTÕES -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="../../index.php" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left me-2"></i> Voltar
                            </a>

                            <button type="submit" class="btn" style="background-color: #1a826d; color: white;">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Alterações
                            </button>
                        </div>

                    </form>

                </div>

            </main>





        </div>
    </div>

<?php include '../../includes/footer.php'; ?>
