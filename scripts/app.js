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

  var index

  function request (url, data) {
    return $.get(url, data)
  }

  function randomNumber (min, max) {
    index = Math.floor(Math.random() * (max - min)) + min
    return index
  }

  function getRandomicIndex (arr) {
    return arr[randomNumber(0, arr.length)]
  }

  function makeTemplate (data) {
    var tpl = ''
    +  '<div class="row">'
    +    '<a href="#" class="col-sm-8 col-sm-offset-2">'
    +      '<img class="img-thumbnail img-circle img-responsive" src="' + data.profile_picture + '">'
    +    '</a>'
    +    '<h3 class="text-center text-uppercase"><a href="#"><em>' + data.name + '</em></a></h3>'
    +  '</div><!-- /.row -->'

    return tpl
  }

  function makeWinnerTemplate (data) {
    var tpl = ''
    +  '<div class="col-sm-4">'
    +    '<a href="#" class="col-sm-8 col-sm-offset-2">'
    +      '<img class="img-thumbnail img-circle img-responsive" src="' + data.profile_picture + '">'
    +    '</a>'
    +    '<h3 class="text-center text-uppercase"><a href="#"><em>' + data.name + '</em></a></h3>'
    +  '</div><!-- /.col-sm-4 -->'

    return tpl
  }

  function lottery (data) {
    var interval = setInterval(function () {
      $('.random-person').html(makeTemplate(getRandomicIndex(data)))
    }, 100)

    setTimeout(function () {
      clearInterval(interval)

      var person = getRandomicIndex(data)

      $('.random-person').html(makeTemplate(person))
      $('.winners .container').append(makeWinnerTemplate(person))
    }, 3000)
  }

  function handleMultiple (iterator, data) {
    lottery(data)
  }

  function init (winners, url) {
    request(url)
      .then(function (response) {
        var interval = setInterval(function () {

          handleMultiple(winners, response.data)

          winners--

          if (winners <= 0) {
            clearInterval(interval)
          }
        }, 1000)
      })
      .fail(function (error) {
        console.error(error)
      })
  }

  window.lottery = init
})(window, document, window.jQuery)
