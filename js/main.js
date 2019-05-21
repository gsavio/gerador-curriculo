$(function () {
    let options = {
        onKeyPress: function (tel, e, field, options) {
            console.log(tel.length)
            if (tel.length > 14) {
                $('#telefone').mask('(00) 00000-0000', options)
            } else {
                $('#telefone').mask('(00) 0000-00000', options)
            }
        }
    }

    $('#telefone').mask('(00) 0000-00000', options)

    $('.data-exp').mask('00/0000')
})

// Adiciona um campo idêntico ao selecionado
var addCampo = function (tipo) {
    let inputs = document.querySelector('.' + tipo)
    let novosInputs = inputs.cloneNode(true)

    novosInputs.classList.add('campo-extra')

    // Limpa os campos do elemento clonado
    novosInputs.querySelectorAll('input, textarea').forEach(function (input) {
        input.value = ''
        input.checked = false
    })

    document.querySelector('.' + tipo + 's').appendChild(novosInputs)

    let div = document.createElement('div')
    div.classList.add('excluir-campo')
    div.setAttribute('onclick', 'excluirCampo(this)')
    div.innerHTML = '<i class="far fa-trash-alt"></i>'

    botaoExcluir = novosInputs.appendChild(div)
    novosInputs.appendChild(botaoExcluir)
}

var excluirCampo = function (el) {
    el.parentElement.remove()
}

// Eventos que são capturados mesmo havendo alterações no DOM
document.addEventListener('change', function (e) {

    // Limpar os checkbox de Experiência se outro for selecionado
    if (e.target.type === 'checkbox' && e.target.checked === true) {
        document.querySelectorAll('.trabalho-atual').forEach(function (el) {
            el.checked = false
        })

        // Verifica se o checkbox clicado já está ativo ou não para alterar o estado
        e.target.checked = true
    }

    $('.data-exp').mask('00/0000')

})