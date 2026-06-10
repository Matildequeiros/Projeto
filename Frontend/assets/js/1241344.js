
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
document.getElementById("adicionarDocumento").addEventListener("click", function () {
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
});

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
document.getElementById("adicionarFornecedor").addEventListener("click", function () {
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

// Popover do novo equipamento
document.addEventListener("DOMContentLoaded", function () {
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(p => new bootstrap.Popover(p));
});



// Bloquear a fatura de aquisição ao selecionar um tipo de entrada específico
document.getElementById('tipoEntrada').addEventListener('change', function () {
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

// ADICIONAR COMPONENTE
document.getElementById("adicionarComponente").addEventListener("click", function () {
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
 function abrirModalApagar(link, codigo, designacao) {

            // Preenche os dados dentro do modal
            document.getElementById("dadosApagar").innerHTML = `
        <p><strong>Código Interno:</strong> ${codigo}</p>
        <p><strong>Designação:</strong> ${designacao}</p>
    `;

            // Define o link do botão "Remover"
            document.getElementById("btnConfirmarApagar").href = link;

            // Abre o modal
            const modal = new bootstrap.Modal(document.getElementById("modalApagar"));
            modal.show();
        }



// MODAL APAGAR LISTA FORNECEDORES 
 function abrirModalApagarFornecedor(codigo, nome, tipo) {

            document.getElementById("dadosFornecedor").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Nome:</strong> ${nome}</p>
        <p><strong>Tipo:</strong> ${tipo}</p>
    `;

            const modal = new bootstrap.Modal(document.getElementById("modalApagarFornecedor"));
            modal.show();
        }


// MODAL APAGAR LISTA LOCALIZAÇÕES
 function abrirModalApagarLocalizacao(codigo, edificio, piso, servico, sala) {

            document.getElementById("dadosLocalizacao").innerHTML = `
        <p><strong>Código:</strong> ${codigo}</p>
        <p><strong>Edifício:</strong> ${edificio}</p>
        <p><strong>Piso:</strong> ${piso}</p>
        <p><strong>Serviço / Departamento:</strong> ${servico}</p>
        <p><strong>Sala / Gabinete:</strong> ${sala}</p>
    `;

            const modal = new bootstrap.Modal(document.getElementById("modalApagarLocalizacao"));
            modal.show();
        }



