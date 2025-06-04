const menuLista = document.getElementById('menuLista');
const menuNavegacao = document.getElementById('menuNavegacao');

menuLista.addEventListener('click', () => {
    const estaAberto = menuNavegacao.classList.toggle('menu-aberto');
    menuLista.setAttribute('aria-expanded', estaAberto);
    // Opcional: Mudar o ícone quando o menu está aberto
    if (estaAberto) {
        menuLista.innerHTML = '<i class="fas fa-times"></i>'; // Ícone de fechar (X)
    } else {
        menuLista.innerHTML = '<i class="fas fa-bars"></i>'; // Ícone de hambúrguer
    }
});