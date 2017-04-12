/**
 * SorteioJS
 *
 * Requisitos
 *
 * Randomizar pessoas por 3 segundos
 * Adicionar a pessoa selecionada ao array
 */
(function (window, document, $) {
  'use strict'

  function fetchRandomUser(users) {
    return users[Math.floor(Math.random() * users.length)]
  }

  function makeUserTemplate(user) {
    return `
      <div class="row">
        <a href="#" class="col-sm-8 col-sm-offset-2">
          <img class="img-thumbnail img-circle img-responsive" src="${user.profile_picture}">
        </a>
        <h3 class="text-center text-uppercase"><a href="${user.profile}"><em>${user.name}</em></a></h3>
      </div>
    `
  }

  function makeWinnerTemplate(user) {
    return `
      <div class="col-sm-4">
        <a href="#" class="col-sm-8 col-sm-offset-2">
          <img class="img-thumbnail img-circle img-responsive" src="${user.profile_picture}">
        </a>
        <h3 class="text-center text-uppercase"><a href="${user.profile}"><em>${user.name}</em></a></h3>
      </div>
    `
  }

  $('form').on('submit', function (event) {
    let quantity = $(this).find('.quantity').val()
    let link = $(this).find('.link').val()

    $.get('/data.json').then(response => {
      let interval = setInterval(() => {
        $('.random-person').html(makeUserTemplate(fetchRandomUser(response.data)))
      }, 100)

      setTimeout(() => {
        clearInterval(interval)

        let winner = fetchRandomUser(response.data)

        $('.random-person').html(makeUserTemplate(winner))
        $('.winners .container').append(makeWinnerTemplate(winner))
      }, 3000)
    })

    event.preventDefault()
  })
})(window, document, window.jQuery)
