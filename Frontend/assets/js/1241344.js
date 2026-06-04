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

// Documentos
function adicionarDocumento() {
    let tipo = document.getElementById('docTipo').value;
    let nome = document.getElementById('docNome').value;
    let data = document.getElementById('docData').value;
    let validade = document.getElementById('docValidade').value;
    let ficheiro = document.getElementById('docFicheiro').files[0];

    if (!nome || !data || !ficheiro) {
        alert("Preencha todos os campos obrigatórios.");
        return;
    }

    let tabela = document.getElementById('tabelaDocumentos');

    let linha = `
        <tr>
            <td>${tipo}</td>
            <td>${nome}</td>
            <td>${data}</td>
            <td>Manual</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" onclick="verDocumento()">Ver</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Remover</button>
            </td>
        </tr>
    `;

    tabela.innerHTML += linha;

    var modal = bootstrap.Modal.getInstance(document.getElementById('modalDocumento'));
    modal.hide();

    document.getElementById('docNome').value = "";
    document.getElementById('docData').value = "";
    document.getElementById('docValidade').value = "";
    document.getElementById('docFicheiro').value = "";
}

function verDocumento() {
    alert("Aqui vais abrir o PDF");
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
