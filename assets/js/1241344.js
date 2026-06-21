
// Navegação entre separadores
function mostrarSeparador(id) {
    const trigger = document.querySelector(`[data-bs-target="#${id}"]`);
    const tab = new bootstrap.Tab(trigger);
    tab.show();
}

function desbloquearSeparador(id) {
    const tab = document.querySelector(`[data-bs-target="#${id}"]`);
    tab.classList.remove("disabled");
    const trigger = new bootstrap.Tab(tab);
    trigger.show();
}

// Contrato
function toggleContrato(valor) {
    document.getElementById('camposContrato').style.display =
        valor === 'sim' ? 'block' : 'none';
}

// Executar ao carregar a página
document.addEventListener("DOMContentLoaded", function () {
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(p => new bootstrap.Popover(p));

    // Mostrar campos do contrato se já estiver selecionado "Sim"
    const temContrato = document.getElementById('temContrato');
    if (temContrato && temContrato.value === 'sim') {
        document.getElementById('camposContrato').style.display = 'block';
    }
});

function validarSeparador(id) {

    let valido = true;

    // Seleciona todos os inputs, selects e textareas dentro do separador
    const campos = document.querySelectorAll(`#${id} input, #${id} select, #${id} textarea`);

    campos.forEach(campo => {

        // Verifica se o campo está vazio
        if (campo.value.trim() === "") {
            campo.classList.add("is-invalid");
            valido = false;
        } else {
            campo.classList.remove("is-invalid");
        }
    });

    return valido;
}

function validarEAvancar(separadorAtual, separadorSeguinte) {

    desbloquearSeparador(separadorSeguinte);

}


// ADICIONAR DOCUMENTO
const btnAdicionarDocumento = document.getElementById("adicionarDocumento");
if (btnAdicionarDocumento) {
    btnAdicionarDocumento.addEventListener("click", function () {
        const container = document.getElementById("documentosContainer");
        const blocoOriginal = container.querySelector(".documento-bloco");

        // Clonar bloco
        const novoBloco = blocoOriginal.cloneNode(true);

        // Limpar inputs
        novoBloco.querySelectorAll("input, textarea").forEach(input => input.value = "");
        novoBloco.querySelectorAll("select").forEach(sel => sel.selectedIndex = 0);

        // Ativar botão remover
        novoBloco.querySelector(".remover-documento").addEventListener("click", function () {
            removerBlocoDocumento(novoBloco);
        });

        container.appendChild(novoBloco);

        // Ativar Flatpickr nos novos campos de data
        novoBloco.querySelectorAll('.doc-data, .doc-validade').forEach(function (input) {
            flatpickr(input, { dateFormat: "Y-m-d" });
        });
    });
}

// REMOVER DOCUMENTO
function removerBlocoDocumento(bloco) {
    const container = document.getElementById("documentosContainer");
    const total = container.querySelectorAll(".documento-bloco").length;

    if (total > 1) {
        bloco.remove();
    }
}

// Ativar remover no primeiro bloco
document.querySelectorAll(".remover-documento").forEach(btn => {
    btn.addEventListener("click", function () {
        removerBlocoDocumento(btn.closest(".documento-bloco"));
    });
});

// ADICIONAR FORNECEDOR
const btnAdicionarFornecedor = document.getElementById("adicionarFornecedor");
if (btnAdicionarFornecedor) {
    btnAdicionarFornecedor.addEventListener("click", function () {
        const container = document.getElementById("fornecedores-container");
        const blocoOriginal = container.querySelector(".fornecedor-bloco");

        // Clonar bloco
        const novoBloco = blocoOriginal.cloneNode(true);

        // Limpar inputs
        novoBloco.querySelectorAll("input, textarea").forEach(input => input.value = "");

        // Ativar botão remover no novo bloco
        novoBloco.querySelector(".remover-fornecedor").addEventListener("click", function () {
            removerBlocoFornecedor(novoBloco);
        });

        container.appendChild(novoBloco);
    });
}

// REMOVER FORNECEDOR
function removerBlocoFornecedor(bloco) {
    const container = document.getElementById("fornecedores-container");
    const total = container.querySelectorAll(".fornecedor-bloco").length;

    if (total > 1) {
        bloco.remove();
    }
}

// Ativar remover no primeiro bloco
document.querySelectorAll(".remover-fornecedor").forEach(btn => {
    btn.addEventListener("click", function () {
        removerBlocoFornecedor(btn.closest(".fornecedor-bloco"));
    });
});


// Bloquear a fatura de aquisição ao selecionar um tipo de entrada específico
const selectTipoEntrada = document.getElementById('tipoEntrada');
if (selectTipoEntrada) {
    selectTipoEntrada.addEventListener('change', function () {
        const tipo = this.value;
        const blocoFatura = document.getElementById('blocoFatura');

        // Seleciona todos os inputs e selects dentro do bloco da fatura
        const campos = blocoFatura.querySelectorAll('input, select');

        if (tipo === 'compra') {
            // Ativar campos
            campos.forEach(c => c.disabled = false);
            blocoFatura.style.opacity = "1";
        } else {
            // Desativar campos
            campos.forEach(c => c.disabled = true);
            blocoFatura.style.opacity = "0.5";
        }
    });
}

// ADICIONAR COMPONENTE
const btnAdicionarComponente = document.getElementById("adicionarComponente");
if (btnAdicionarComponente) {
    btnAdicionarComponente.addEventListener("click", function () {
        const container = document.getElementById("componentesContainer");
        const blocoOriginal = container.querySelector(".componente-bloco");

        // Clonar bloco
        const novoBloco = blocoOriginal.cloneNode(true);

        // Limpar inputs
        novoBloco.querySelectorAll("input, textarea").forEach(input => input.value = "");
        novoBloco.querySelectorAll("select").forEach(sel => sel.selectedIndex = 0);

        // Ativar botão remover
        novoBloco.querySelector(".remover-componente").addEventListener("click", function () {
            removerBlocoComponente(novoBloco);
        });

        container.appendChild(novoBloco);
    });
}

// REMOVER COMPONENTE
function removerBlocoComponente(bloco) {
    const container = document.getElementById("componentesContainer");
    const total = container.querySelectorAll(".componente-bloco").length;

    if (total > 1) {
        bloco.remove();
    }
}

// Ativar remover no primeiro bloco
document.querySelectorAll(".remover-componente").forEach(btn => {
    btn.addEventListener("click", function () {
        removerBlocoComponente(btn.closest(".componente-bloco"));
    });
});

// MODAL APAGAR NA LSITA 
function abrirModalApagar(idEncriptado, codigo, designacao) {

    // Preenche os dados dentro do modal
    document.getElementById("dadosApagar").innerHTML = `
        <p><strong>Código Interno:</strong> ${codigo}</p>
        <p><strong>Designação:</strong> ${designacao}</p>
    `;

    // Define o link do botão "Remover" para a página que desativa o equipamento
    document.getElementById("btnConfirmarApagar").href = "confirmar_apagar.php?id_equipamento=" + idEncriptado;

    // Abre o modal
    const modal = new bootstrap.Modal(document.getElementById("modalApagar"));
    modal.show();
}

// MODAL REATIVAR NA LISTA
function abrirModalReativar(idEncriptado, codigo, designacao) {

    // Preenche os dados dentro do modal
    document.getElementById("dadosReativar").innerHTML = `
        <p><strong>Código Interno:</strong> ${codigo}</p>
        <p><strong>Designação:</strong> ${designacao}</p>
    `;

    // Define o link do botão "Reativar" para a página que reativa o equipamento
    document.getElementById("btnConfirmarReativar").href = "reativar.php?id_equipamento=" + idEncriptado;

    // Abre o modal
    const modal = new bootstrap.Modal(document.getElementById("modalReativar"));
    modal.show();
}


// MODAL APAGAR LISTA FORNECEDORES 
function abrirModalApagarFornecedor(idEncriptado, codigo, nome, tipo) {

    document.getElementById("dadosFornecedor").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Nome:</strong> ${nome}</p>
        <p><strong>Tipo:</strong> ${tipo}</p>
    `;

    document.getElementById("btnConfirmarApagarFornecedor").href = "confirmar_apagar_fornecedores.php?id_fornecedor=" + idEncriptado;

    const modal = new bootstrap.Modal(document.getElementById("modalApagarFornecedor"));
    modal.show();
}

function abrirModalReativarFornecedor(idEncriptado, codigo, nome) {

    document.getElementById("dadosReativarFornecedor").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Nome:</strong> ${nome}</p>
    `;

    document.getElementById("btnConfirmarReativarFornecedor").href = "reativar_fornecedores.php?id_fornecedor=" + idEncriptado;

    const modal = new bootstrap.Modal(document.getElementById("modalReativarFornecedor"));
    modal.show();
}

// MODAL APAGAR LISTA LOCALIZAÇÕES
function abrirModalApagarLocalizacao(idEncriptado, codigo, edificio, piso, servico, sala) {

    document.getElementById("dadosLocalizacao").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Edifício:</strong> ${edificio}</p>
        <p><strong>Piso:</strong> ${piso}</p>
        <p><strong>Serviço / Departamento:</strong> ${servico}</p>
        <p><strong>Sala / Gabinete:</strong> ${sala}</p>
    `;

    document.getElementById("btnConfirmarApagarLocalizacao").href = "confirmar_apagar_localizacoes.php?id_localizacao=" + idEncriptado;

    const modal = new bootstrap.Modal(document.getElementById("modalApagarLocalizacao"));
    modal.show();
}

function abrirModalReativarLocalizacao(idEncriptado, codigo, edificio, piso, servico, sala) {

    document.getElementById("dadosReativarLocalizacao").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Edifício:</strong> ${edificio}</p>
        <p><strong>Piso:</strong> ${piso}</p>
        <p><strong>Serviço / Departamento:</strong> ${servico}</p>
        <p><strong>Sala / Gabinete:</strong> ${sala}</p>
    `;

    document.getElementById("btnConfirmarReativarLocalizacao").href = "reativar_localizacoes.php?id_localizacao=" + idEncriptado;

    const modal = new bootstrap.Modal(document.getElementById("modalReativarLocalizacao"));
    modal.show();
}

let idLocalizacaoParaRemover = null;

function abrirModalApagarComSubstituicao(idEncriptado, codigo, totalEquipamentos) {

    idLocalizacaoParaRemover = idEncriptado;

    document.getElementById("textoAvisoSubstituicao").innerHTML =
        `A localização <strong>${codigo}</strong> tem <strong>${totalEquipamentos}</strong> equipamento(s) associado(s). ` +
        `Escolha uma localização para onde mover esses equipamentos antes de remover.`;

    document.getElementById("selectLocalizacaoSubstituta").value = "";

    const modal = new bootstrap.Modal(document.getElementById("modalApagarComSubstituicao"));
    modal.show();
}

const btnConfirmarSubstituicao = document.getElementById("btnConfirmarSubstituicao");
if (btnConfirmarSubstituicao) {
    btnConfirmarSubstituicao.addEventListener("click", function () {
        const idSubstituta = document.getElementById("selectLocalizacaoSubstituta").value;

        if (!idSubstituta) {
            alert("Por favor, selecione uma localização para mover os equipamentos.");
            return;
        }

        window.location.href = "remover_localizacao_com_substituicao.php?id_localizacao=" + idLocalizacaoParaRemover + "&id_substituta=" + idSubstituta;
    });
}

let idFornecedorParaRemover = null;

function abrirModalApagarFornecedorComSubstituicao(idEncriptado, codigo, totalEquipamentos) {

    idFornecedorParaRemover = idEncriptado;

    document.getElementById("textoAvisoSubstituicaoFornecedor").innerHTML =
        `O fornecedor <strong>${codigo}</strong> está associado a <strong>${totalEquipamentos}</strong> equipamento(s). ` +
        `Escolha um fornecedor substituto antes de remover.`;

    document.getElementById("selectFornecedorSubstituto").value = "";

    const modal = new bootstrap.Modal(document.getElementById("modalApagarFornecedorComSubstituicao"));
    modal.show();
}

const btnConfirmarSubstituicaoFornecedor = document.getElementById("btnConfirmarSubstituicaoFornecedor");
if (btnConfirmarSubstituicaoFornecedor) {
    btnConfirmarSubstituicaoFornecedor.addEventListener("click", function () {
        const idSubstituto = document.getElementById("selectFornecedorSubstituto").value;

        if (!idSubstituto) {
            alert("Por favor, selecione um fornecedor substituto.");
            return;
        }

        window.location.href = "remover_fornecedor_com_substituicao.php?id_fornecedor=" + idFornecedorParaRemover + "&id_substituto=" + idSubstituto;
    });
}

function verPDF(url) {
    window.open(url, '_blank');
}

//ÁREA PÚBLICA- POPUP
function abrirFormulario() {
    document.getElementById("popup-form").classList.remove("escondido");
}

function fecharFormulario() {
    document.getElementById("popup-form").classList.add("escondido");
}