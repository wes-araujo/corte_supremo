// reservas2.js

// Função para coletar dados do menu para o backend (incluindo preços)
// Deixando-a global, pois é uma função utilitária limpa.
function getMenuData() {
    const menuItems = document.querySelectorAll('.menu-item');
    const itens_menu = [];
    const quantidades_menu = [];
    const precos_menu = []; // Array para armazenar os preços dos itens selecionados

    menuItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"][name="item_menu[]"]');
        if (checkbox && checkbox.checked) {
            const quantityInput = item.querySelector('input.quantidade[name="quantidade_menu[]"]');
            
            // Usar data-nome para consistência, fallback para value se data-nome não estiver presente
            itens_menu.push(checkbox.dataset.nome || checkbox.value);
            quantidades_menu.push(quantityInput ? parseInt(quantityInput.value, 10) : 1);
            // Captura o preço do atributo data-preco do checkbox
            precos_menu.push(checkbox.dataset.preco ? parseFloat(checkbox.dataset.preco) : 0.00);
        }
    });
    return { itens_menu, quantidades_menu, precos_menu };
}

// Função para mostrar/esconder o menu e a área de resumo
// Definida globalmente para ser acessível pelo onclick no HTML
window.mostrarMenu = function() {
    const menuDiv = document.getElementById('menu');
    const informativoDiv = document.getElementById('informativo');

    if (menuDiv && informativoDiv) {
        const isMenuHidden = menuDiv.style.display === 'none' || menuDiv.style.display === '';
        
        menuDiv.style.display = isMenuHidden ? 'block' : 'none';
        informativoDiv.style.display = isMenuHidden ? 'block' : 'none';

        if (isMenuHidden) { // Se o menu foi exibido
            window.atualizarResumo(); // Atualiza o resumo
        }
    }
}

// Função para atualizar o conteúdo do resumo da reserva
// Definida globalmente para ser acessível pelos onchange no HTML e por outras funções
window.atualizarResumo = function() {
    const resumoTextoEl = document.getElementById('resumoTexto');
    if (!resumoTextoEl) {
        return;
    }

    let resumoString = "";
    let totalGeralItens = 0;

    const email = document.getElementById('email')?.value;
    const data = document.getElementById('data')?.value;
    const hora = document.getElementById('hora')?.value;
    const grupo = document.getElementById('grupo')?.value;
    const unidade = document.getElementById('unidade')?.value;

    resumoString += `E-mail: ${email || '(não informado)'}\n`;
    resumoString += `Data: ${data || '(não informada)'}\n`;
    resumoString += `Hora: ${hora || '(não informada)'}\n`;
    resumoString += `Grupo: ${grupo || '(não informado)'}\n`;
    resumoString += `Unidade: ${unidade || '(não informada)'}\n\n`;
    resumoString += "Itens do Cardápio Selecionados:\n";

    const checkboxes = document.querySelectorAll('.menu-item input[type="checkbox"][name="item_menu[]"]');
    let algumItemSelecionado = false;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            algumItemSelecionado = true;
            const menuItemDiv = checkbox.closest('.menu-item');
            const nome = checkbox.dataset.nome;
            const preco = parseFloat(checkbox.dataset.preco);
            
            const quantidadeInput = menuItemDiv.querySelector('input.quantidade[name="quantidade_menu[]"]');
            let quantidade = 1;
            if (quantidadeInput) {
                quantidade = parseInt(quantidadeInput.value, 10);
            }

            if (nome && !isNaN(preco) && !isNaN(quantidade) && quantidade > 0) {
                const subtotal = preco * quantidade;
                totalGeralItens += subtotal;
                const precoFormatado = preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                const subtotalFormatado = subtotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                resumoString += `- ${nome} (x${quantidade}) - Preço Unit.: ${precoFormatado} = Subtotal: ${subtotalFormatado}\n`;
            }
        }
    });

    if (!algumItemSelecionado) {
        resumoString += "Nenhum item selecionado.\n";
    }

    const totalGeralFormatado = totalGeralItens.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    resumoString += `\nTotal Geral dos Itens: ${totalGeralFormatado}\n`;

    resumoTextoEl.textContent = resumoString;
}

// Lógica principal que executa após o carregamento completo do DOM
document.addEventListener('DOMContentLoaded', function() {
    const reservaForm = document.getElementById('reservaForm');
    const menuDiv = document.getElementById('menu');
    const informativoDiv = document.getElementById('informativo');

    if (menuDiv) menuDiv.style.display = 'none';
    if (informativoDiv) informativoDiv.style.display = 'none';

    if (reservaForm) {
        reservaForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(reservaForm);
            const menuData = getMenuData(); 

            // Adiciona os dados do menu (incluindo preços) ao FormData
            // com as chaves que o PHP irá esperar na Opção 1
            formData.append('itens_menu', JSON.stringify(menuData.itens_menu));
            formData.append('quantidades_menu', JSON.stringify(menuData.quantidades_menu));
            formData.append('precos_menu', JSON.stringify(menuData.precos_menu)); // Enviando os preços dos itens selecionados

            fetch('../reserva2.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Reserva realizada com sucesso! Verifique seu e-mail.');
                    reservaForm.reset();
                    if (menuDiv) menuDiv.style.display = 'none';
                    if (informativoDiv) informativoDiv.style.display = 'none';
                    window.atualizarResumo(); 
                } else {
                    alert('Erro ao realizar a reserva: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro na submissão do formulário:', error);
                alert('Ocorreu um erro ao processar a reserva. Verifique o console para mais detalhes.');
            });
        });
    }
    window.atualizarResumo();
});