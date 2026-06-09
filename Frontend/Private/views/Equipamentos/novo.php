<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>


<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>


        <main class="col-md-9 col-lg-10 p-4">

            <div class="card-form">

                <h2 class="mb-4" style="color: #1a826d;">
                    <i class="fa-solid fa-plus me-2"></i> Novo Equipamento
                </h2>

                <!-- SEPARADORES -->
                <ul class="nav nav-tabs mb-4 flex-nowrap" id="equipTabs" role="tablist">


                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#dados" type="button">
                            Equipamento
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#componentes"
                            type="button">
                            Componentes <br> e Consumíveis
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#aquisicao"
                            type="button">
                            Aquisição
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#fornecedor"
                            type="button">
                            Fornecedor <br> Associado
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#localizacao"
                            type="button">
                            Localização <br> Associada
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#garantia"
                            type="button">
                            Garantia
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#contrato"
                            type="button">
                            Contrato de <br> Manutenção
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link disabled" data-bs-toggle="tab" data-bs-target="#documentos"
                            type="button">
                            Documentação <br> Associada
                        </button>
                    </li>

                </ul>

                <div class="alert alert-danger">
                    <strong>Foram encontrados erros:</strong>
                    <ul class="mt-2 mb-0">
                        <li>Código interno é obrigatório</li>
                        <li>Categoria é obrigatória</li>
                    </ul>
                </div>

                <!-- CONTEÚDO DOS SEPARADORES -->
                <form id="formEquipamentoCompleto">

                    <div class="tab-content" id="equipTabsContent">


                        <!-- SEPARADOR 1 — EQUIPAMENTO -->
                        <div class="tab-pane fade show active" id="dados" role="tabpanel">

                            <!-- Código + Designação -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Código Interno De Inventário *</label>
                                    <input type="text" class="form-control" placeholder="Ex: EQ-2025-001">
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Designação Do Equipamento *</label>
                                    <input type="text" class="form-control"
                                        placeholder="Ex: Monitor multiparamétrico">
                                </div>
                            </div>

                            <!-- Categoria -->
                            <div class="mb-3">
                                <label class="form-label">
                                    Categoria *
                                    <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                        data-bs-trigger="hover focus" data-bs-html="true" title="Categorias"
                                        data-bs-content="
        Diagnóstico - Obtém informação clínica para diagnóstico.<br>
        Terapia - Utilizado no tratamento do paciente.<br>
        Monitorização - Acompanha sinais vitais e parâmetros clínicos.<br>
        Acessório - Complementa outro equipamento.<br>
        Laboratório - Utilizado em análises e testes.<br>
        Esterilização - Limpa, desinfeta ou esteriliza materiais.<br>
        Reabilitação — Apoia a recuperação funcional do paciente.
   ">
                                    </i>


                                </label>

                                <select class="form-select">
                                    <option>Monitorização</option>
                                    <option>Suporte de vida</option>
                                    <option>Terapia</option>
                                    <option>Diagnóstico</option>
                                    <option>Laboratório</option>
                                    <option>Esterilização</option>
                                    <option>Reabilitação</option>
                                </select>
                            </div>

                            <!-- Marca + Modelo -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Marca *</label>
                                    <input type="text" class="form-control" placeholder="Ex: Philips">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Modelo *</label>
                                    <input type="text" class="form-control" placeholder="Ex: IntelliVue MP5">
                                </div>
                            </div>

                            <!-- Nº Série + Fabricante -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número de Série *</label>
                                    <input type="text" class="form-control" placeholder="Ex: MP5-2022-45873">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fabricante *</label>
                                    <input type="text" class="form-control" placeholder="Ex: Philips Healthcare">
                                </div>
                            </div>

                            <!-- Ano Fabrico + Estado + Criticidade -->
                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ano de Fabrico *</label>
                                    <input type="number" class="form-control" placeholder="Ex: 2022" min="1900"
                                        max="2100">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Estado *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true" title="Estados"
                                            data-bs-content="
        Ativo - Disponível para utilização.<br>
        Em manutenção - Em intervenção técnica.<br>
        Inativo - Temporariamente fora de uso.<br>
        Em calibração - Em ajuste ou validação técnica.<br>
        Em quarentena - Isolado para avaliação<br>
        Abatido - Removido definitivamente do inventário.
   ">
                                        </i>


                                    </label>

                                    <select class="form-select">
                                        <option>Ativo</option>
                                        <option>Em manutenção</option>
                                        <option>Inativo</option>
                                        <option>Em calibração</option>
                                        <option>Em quarentena</option>
                                        <option>Abatido</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">
                                        Criticidade *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true" title="Criticidade"
                                            data-bs-content="
        Baixa - Equipamentos cuja falha não tem impacto direto na
segurança do doente.<br>
        Média - Equipamentos utilizados em procedimentos clínicos,
mas cuja falha não compromete imediatamente a vida
do doente.
<br>
        Alta - Equipamentos essenciais para diagnóstico ou
tratamento clínico.<br>
        Suporte de vida - Equipamentos cuja falha pode colocar em risco
imediato a vida do doente.
   ">
                                        </i>


                                    </label>

                                    <select class="form-select">
                                        <option>Baixa</option>
                                        <option>Média</option>
                                        <option>Alta</option>
                                        <option>Suporte de vida</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="lista.php" class="btn btn-secondary">← Voltar</a>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('dados', 'componentes')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 2 — COMPONENTES ASSOCIADOS -->
                        <div class="tab-pane fade" id="componentes" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Componentes Associados</h4>

                            <p class="text-muted mb-3">
                                Adicione componentes ou consumíveis que fazem parte do equipamento principal.
                            </p>

                            <div id="componentesContainer">

                                <!-- BLOCO BASE DO COMPONENTE -->
                                <div class="componente-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row g-3">

                                        <!-- Tipo -->
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">
                                                Tipo *
                                                <i class="fa-solid fa-circle-info ms-1 text-muted"
                                                    data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                    data-bs-html="true" title="Tipo de Item" data-bs-content="
Componente - Parte técnica do equipamento (sensores, cabos, baterias, módulos).<br>
Consumível - Item usado e substituído regularmente (gel, filtros, papel térmico).
           ">
                                                </i>
                                            </label>

                                            <select class="form-select">
                                                <option value="componente">Componente</option>
                                                <option value="consumivel">Consumível</option>
                                            </select>
                                        </div>


                                        <!-- Nome -->
                                        <div class="col-md-4">
                                            <label class="form-label">Nome *</label>
                                            <input type="text" class="form-control"
                                                placeholder="Ex: Sensor SpO2, Gel, Cabo ECG">
                                        </div>

                                        <!-- Referência -->
                                        <div class="col-md-4">
                                            <label class="form-label">Referência</label>
                                            <input type="text" class="form-control" placeholder="Ex: DS-100A">
                                        </div>

                                        <!-- Quantidade -->
                                        <div class="col-md-4">
                                            <label class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" placeholder="Ex: 3">
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-4">
                                            <label class="form-label">Estado</label>
                                            <select class="form-select">
                                                <option value="">—</option>
                                                <option>Ativo</option>
                                                <option>Em manutenção</option>
                                                <option>Inativo</option>
                                                <option>Em calibração</option>
                                                <option>Abatido</option>
                                            </select>
                                        </div>

                                        <!-- Observações -->
                                        <div class="col-md-12">
                                            <label class="form-label">Observações</label>
                                            <textarea class="form-control" rows="1"
                                                placeholder="Notas adicionais"></textarea>
                                        </div>

                                        <!-- Botão remover -->
                                        <div class="col-12 text-end mt-1">
                                            <button type="button" class="btn btn-danger btn-sm remover-componente">
                                                Remover
                                            </button>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Botão adicionar -->
                            <button type="button" class="btn btn-success mb-4" id="adicionarComponente">
                                + Adicionar
                            </button>

                            <!-- Botões navegação -->
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary" onclick="mostrarSeparador('dados')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('componentes', 'aquisicao')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 3 — AQUISIÇÃO -->
                        <div class="tab-pane fade" id="aquisicao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Aquisição</h4>

                            <div class="row">

                                <!-- Data de aquisição -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Aquisição *</label>
                                    <input type="date" class="form-control">
                                </div>

                                <!-- Custo -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Custo de Aquisição (€) *</label>
                                    <input type="number" class="form-control" placeholder="Ex: 3500" min="0"
                                        step="0.01">
                                </div>

                                <!-- Tipo de entrada -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Tipo de Entrada *
                                        <i class="fa-solid fa-circle-info ms-1 text-muted" data-bs-toggle="popover"
                                            data-bs-trigger="hover focus" data-bs-html="true"
                                            title="Tipo de Entrada" data-bs-content="
            Compra - Adquirido pela instituição.<br>
            Doação - Recebido sem contrapartida financeira.<br>
            Empréstimo - Cedido temporariamente e devolvido após uso.<br>
            Aluguer - Obtido através de contrato de aluguer.
       ">
                                        </i>
                                    </label>

                                    <select class="form-select" id="tipoEntrada">
                                        <option value="compra">Compra</option>
                                        <option value="doacao">Doação</option>
                                        <option value="aluguer">Aluguer</option>
                                        <option value="emprestimo">Empréstimo</option>
                                    </select>

                                </div>

                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionAquisicao">

                                <!-- ITEM 1 — Documentos da Aquisição -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingDocAquisicao">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseDocAquisicao" aria-expanded="true"
                                            aria-controls="collapseDocAquisicao">
                                            Documentos da Aquisição
                                        </button>
                                    </h2>

                                    <div id="collapseDocAquisicao" class="accordion-collapse collapse show"
                                        aria-labelledby="headingDocAquisicao" data-bs-parent="#accordionAquisicao">

                                        <div class="accordion-body">

                                            <div class="row">

                                                <!-- BLOCO 1 — Contrato de Aquisição -->
                                                <div class="col-md-6">
                                                    <div class="border rounded p-3 mb-3">

                                                        <h5 class="mb-3" style="color:#1a826d;">Contrato de
                                                            Aquisição</h5>

                                                        <div class="row">
                                                            <!-- Tipo de Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento
                                                                    *</label>
                                                                <select class="form-select">
                                                                    <option>Contrato de Aquisição</option>
                                                                </select>
                                                            </div>

                                                            <!-- Nome do Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento
                                                                    *</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Ex: Contrato de Compra">
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data do Documento
                                                                    *</label>
                                                                <input type="date" class="form-control">
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Validade</label>
                                                                <input type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control"
                                                                accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover
                                                            Documento</button>

                                                    </div>
                                                </div>

                                                <!-- BLOCO 2 — Fatura da Aquisição -->
                                                <div class="col-md-6" id="blocoFatura">

                                                    <div class="border rounded p-3 mb-3">

                                                        <h5 class="mb-3" style="color:#1a826d;">Fatura da Aquisição
                                                        </h5>

                                                        <div class="row">
                                                            <!-- Tipo de Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Tipo de Documento
                                                                    *</label>
                                                                <select class="form-select">
                                                                    <option>Fatura de Aquisição</option>
                                                                </select>
                                                            </div>

                                                            <!-- Nome do Documento -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Nome do Documento
                                                                    *</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Ex: Contrato de Compra">
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data da Fatura *</label>
                                                                <input type="date" class="form-control">
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Data de Pagamento</label>
                                                                <input type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Ficheiro (PDF) *</label>
                                                            <input type="file" class="form-control"
                                                                accept="application/pdf">
                                                        </div>

                                                        <button type="button" class="btn btn-danger">Remover
                                                            Documento</button>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('componentes')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('aquisicao', 'fornecedor')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 4 — FORNECEDOR ASSOCIADO -->
                        <div class="tab-pane fade" id="fornecedor" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Fornecedor Associado</h4>

                            <div id="fornecedores-container">

                                <!-- BLOCO DE FORNECEDOR  -->
                                <div class="fornecedor-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row g-3">

                                        <!-- Fornecedor -->
                                        <div class="col-md-4">
                                            <label class="form-label">Fornecedor *</label>
                                            <select class="form-select">
                                                <option value="">Selecione...</option>
                                                <option value="1">F001 – MedTech Solutions</option>
                                                <option value="2">F002 – Dräger Portugal</option>
                                                <option value="3">F003 – TecAssist</option>
                                            </select>
                                        </div>

                                        <!-- Tipo de Fornecedor-->
                                        <div class="col-md-4">
                                            <label class="form-label">Tipo *</label>
                                            <select class="form-select">
                                                <option>Fabricante</option>
                                                <option>Distribuidor / Comercial</option>
                                                <option>Assistência Técnica</option>
                                                <option>Consumíveis / Acessórios</option>
                                            </select>
                                        </div>

                                        <!-- Morada Associada-->
                                        <div class="col-md-4">
                                            <label class="form-label">Morada Associada *</label>
                                            <input type="text" class="form-control"
                                                placeholder="Ex: Armazém Norte – Braga">
                                        </div>

                                        <!-- Pessoa contacto -->
                                        <div class="col-md-4">
                                            <label class="form-label">Pessoa de Contacto</label>
                                            <input type="text" class="form-control" placeholder="Nome da pessoa">
                                        </div>

                                        <!-- Telefone da Pessoa de Contacto-->
                                        <div class="col-md-4">
                                            <label class="form-label">Telefone da Pessoa de Contacto</label>
                                            <input type="number" class="form-control" placeholder="912345678">
                                        </div>

                                        <!-- Observações -->
                                        <div class="col-md-4">
                                            <label class="form-label">Observações</label>
                                            <textarea class="form-control" rows="1"></textarea>
                                        </div>

                                        <!-- Botão remover -->
                                        <div class="col-12 text-end mt-1">
                                            <button type="button" class="btn btn-danger btn-sm remover-fornecedor">
                                                Remover Fornecedor
                                            </button>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Botão adicionar -->
                            <button type="button" class="btn btn-success mb-4" id="adicionarFornecedor">
                                + Adicionar Fornecedor
                            </button>

                            <!-- Botões navegação -->
                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('aquisicao')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('fornecedor', 'localizacao')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>

                        <!-- SEPARADOR 5 — LOCALIZAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade" id="localizacao" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Localização Associada</h4>

                            <form>

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Localização *</label>
                                        <select class="form-select">
                                            <option value="">Selecione...</option>
                                            <option value="LOC001">LOC001 – Edifício A / Piso 1 / Sala 3</option>
                                            <option value="LOC002">LOC002 – Edifício B / Piso 0 / Urgência</option>
                                            <option value="LOC003">LOC003 – Edifício C / Piso 2 / Sala 12</option>
                                        </select>
                                    </div>

                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="mostrarSeparador('fornecedor')">
                                        ← Anterior
                                    </button>

                                    <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                        onclick="validarEAvancar('localizacao', 'garantia')">
                                        Seguinte →
                                    </button>
                                </div>

                            </form>

                        </div>

                        <!-- SEPARADOR 6 — GARANTIA -->
                        <div class="tab-pane fade" id="garantia" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Garantia</h4>

                            <!-- Datas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Início da Garantia *</label>
                                    <input type="date" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data de Fim da Garantia *</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                            <!-- Observações + Entidade Responsável -->
                            <div class="row">

                                <!-- Entidade Responsável -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Entidade Responsável *</label>
                                    <select class="form-select">
                                        <option value="">Selecione...</option>
                                        <option>Fabricante</option>
                                        <option>Fornecedor Comercial</option>
                                        <option>Distribuidor Autorizado</option>
                                        <option>Outro</option>
                                    </select>
                                </div>

                                <!-- Observações -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Observações</label>
                                    <textarea class="form-control" rows="3"></textarea>
                                </div>


                            </div>

                            <hr class="my-4">

                            <div class="accordion" id="accordionGarantia">

                                <!-- ITEM 1 — Certificado de Garantia -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCertGarantia">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseCertGarantia" aria-expanded="true"
                                            aria-controls="collapseCertGarantia">
                                            Certificado de Garantia
                                        </button>
                                    </h2>

                                    <div id="collapseCertGarantia" class="accordion-collapse collapse show"
                                        aria-labelledby="headingCertGarantia" data-bs-parent="#accordionGarantia">

                                        <div class="accordion-body">

                                            <div class="border rounded p-3 mb-3">

                                                <h5 class="mb-3" style="color:#1a826d;">Certificado de Garantia</h5>

                                                <div class="row">
                                                    <!-- Tipo de Documento -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tipo de Documento *</label>
                                                        <select class="form-select">
                                                            <option>Certificado de Garantia</option>
                                                        </select>
                                                    </div>

                                                    <!-- Nome do Documento -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nome do Documento *</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Ex: Certificado de Garantia">
                                                    </div>
                                                </div>

                                                <!-- Datas -->
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data do Documento *</label>
                                                        <input type="date" class="form-control">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data de Validade</label>
                                                        <input type="date" class="form-control">
                                                    </div>
                                                </div>

                                                <!-- PDF -->
                                                <div class="mb-3">
                                                    <label class="form-label">Ficheiro (PDF) *</label>
                                                    <input type="file" class="form-control"
                                                        accept="application/pdf">
                                                </div>

                                                <button type="button" class="btn btn-danger">Remover
                                                    Documento</button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('localizacao')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('garantia', 'contrato')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 7 — CONTRATO DE MANUTENÇÃO -->
                        <div class="tab-pane fade" id="contrato" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Contrato de Manutenção</h4>

                            <!-- Existe contrato? -->
                            <div class="mb-3">
                                <label class="form-label">Existe Contrato de Manutenção? *</label>
                                <select class="form-select" onchange="toggleContrato(this.value)">
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                </select>
                            </div>

                            <!-- Campos do contrato (escondidos por padrão) -->
                            <div id="camposContrato" style="display:none;">

                                <!-- Tipo + Periodicidade -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tipo de Contrato *</label>
                                        <select class="form-select">
                                            <option>Manutenção Preventiva</option>
                                            <option>Manutenção Corretiva</option>
                                            <option>Full-Service</option>
                                            <option>Outsourcing</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Periodicidade *</label>
                                        <select class="form-select">
                                            <option>Mensal</option>
                                            <option>Trimestral</option>
                                            <option>Semestral</option>
                                            <option>Anual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Datas -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Início *</label>
                                        <input type="date" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data de Fim *</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>

                                <!-- Observações + Entidade Responsável -->
                                <div class="row">

                                    <!-- Entidade Responsável -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Entidade Responsável *</label>
                                        <select class="form-select">
                                            <option value="">Selecione...</option>
                                            <option>Empresa de assistência técnica</option>
                                            <option>Fabricante</option>
                                            <option>Distribuidor Autorizado</option>
                                            <option>Outro</option>
                                        </select>
                                    </div>

                                    <!-- Observações -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Observações</label>
                                        <textarea class="form-control" rows="3"></textarea>
                                    </div>

                                </div>

                                <hr class="my-4">

                                <div class="accordion" id="accordionContratoManutencao">

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingContratoManutencaoDoc">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseContratoManutencaoDoc" aria-expanded="true"
                                                aria-controls="collapseContratoManutencaoDoc">
                                                Documento do Contrato de Manutenção
                                            </button>
                                        </h2>

                                        <div id="collapseContratoManutencaoDoc"
                                            class="accordion-collapse collapse show"
                                            aria-labelledby="headingContratoManutencaoDoc"
                                            data-bs-parent="#accordionContratoManutencao">

                                            <div class="accordion-body">

                                                <div class="border rounded p-3 mb-3">

                                                    <h5 class="mb-3" style="color:#1a826d;">Contrato de Manutenção
                                                    </h5>

                                                    <div class="row">
                                                        <!-- Tipo de Documento -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipo de Documento *</label>
                                                            <select class="form-select">
                                                                <option>Contrato de Manutenção</option>
                                                            </select>
                                                        </div>

                                                        <!-- Nome do Documento -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nome do Documento *</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Ex: Contrato de Manutenção 2024-2025">
                                                        </div>
                                                    </div>


                                                    <!-- Datas -->
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data do Documento *</label>
                                                            <input type="date" class="form-control">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Data de Validade</label>
                                                            <input type="date" class="form-control">
                                                        </div>
                                                    </div>

                                                    <!-- PDF -->
                                                    <div class="mb-3">
                                                        <label class="form-label">Ficheiro (PDF) *</label>
                                                        <input type="file" class="form-control"
                                                            accept="application/pdf">
                                                    </div>

                                                    <button type="button" class="btn btn-danger">Remover
                                                        Documento</button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('garantia')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="validarEAvancar('contrato', 'documentos')">
                                    Seguinte →
                                </button>
                            </div>

                        </div>


                        <!-- SEPARADOR 8 — DOCUMENTAÇÃO ASSOCIADA -->
                        <div class="tab-pane fade" id="documentos" role="tabpanel">

                            <h4 class="mb-3" style="color:#1a826d;">Documentação Associada</h4>

                            <!-- CONTAINER DOS DOCUMENTOS -->
                            <div id="documentosContainer">

                                <!-- BLOCO BASE DE DOCUMENTO -->
                                <div class="documento-bloco border rounded p-3 mb-3" style="border-color:#86b0aa;">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Documento *</label>
                                            <select class="form-select">
                                                <option>Manual</option>
                                                <option>Ficha</option>
                                                <option>Certificado</option>
                                                <option>Relatório</option>
                                                <option>Declaração</option>
                                                <option>Outro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nome do Documento *</label>
                                            <input type="text" class="form-control"
                                                placeholder="Ex: Manual do Utilizador">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data do Documento *</label>
                                            <input type="date" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Data de Validade</label>
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ficheiro (PDF) *</label>
                                        <input type="file" class="form-control" accept="application/pdf">
                                    </div>

                                    <button type="button" class="btn btn-danger btn-sm remover-documento">
                                        Remover Documento
                                    </button>

                                </div>

                            </div>

                            <!-- BOTÃO ADICIONAR -->
                            <button type="button" class="btn btn-success mb-3" id="adicionarDocumento">
                                + Adicionar Documento
                            </button>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="mostrarSeparador('contrato')">
                                    ← Anterior
                                </button>

                                <button type="button" class="btn" style="background-color:#1a826d; color:white;"
                                    onclick="window.location.href='lista.php'">
                                    Guardar Equipamento ✔
                                </button>
                            </div>

                        </div>



                    </div>
                </form>


        </main>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>