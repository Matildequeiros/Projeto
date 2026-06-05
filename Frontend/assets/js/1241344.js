
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

    if (validarSeparador(separadorAtual)) {
        desbloquearSeparador(separadorSeguinte);
    } else {
        alert("Preencha todos os campos antes de avançar.");
    }
}


// DOCUMENTO ASSOCIADO
function adicionarBlocoDocumento() {

    const container = document.getElementById("documentosContainer");

    const bloco = document.createElement("div");
    bloco.classList.add("p-3", "border", "rounded", "mb-3");
    bloco.style.borderColor = "#86b0aa";

    bloco.innerHTML = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tipo de Documento *</label>
                <select class="form-select">
                    <option>Manual</option>
                    <option>Ficha Técnica</option>
                    <option>Certificado</option>
                    <option>Relatório</option>
                    <option>Outro</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nome do Documento *</label>
                <input type="text" class="form-control" placeholder="Ex: Manual do Utilizador">
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

        <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">
            Remover Documento
        </button>
    `;

    container.appendChild(bloco);
}
