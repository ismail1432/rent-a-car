$(document).ready(function(){
    $('select').formSelect();

    // recupere prototype html créer par symfony
    var $container = $('#car_keywords');

    // recuprere le nombre d'input keyword
    var index = $container.find(':input').length;

    $container.find('label.required').remove();

    // si 0 input Keyword ajoute 1
    if(index == 0) {
        addKeyword($container);
    }

    // Event click pout ajouter un input keyword
    $('.addKeyword').click(function(e) {
        e.preventDefault();

        addKeyword($container);

    });

    $('.delete-image').click(function (e) {
        $('.responsive-img').remove();
    });

    $('.delete-keyword').click(function (e) {
        var path = $(this).attr('data-delete-path');
        var keywordId = $(this).attr('data-keyword-id');
        var $keywordArea = $(this).closest('.keywordArea');


        $.ajax({
            method: "POST",
            url: path,
            data: {id:keywordId },
            success: function () {
                $keywordArea.remove();
            },
            error: function () {
                $('.error-delete-keyword').css('display', 'block');
            }
        })
    });

    // creer l'input keyword pour l'index courant et l'ajoute dans la div id="car_keywords" avec la méthode append
    function addKeyword($container) {

        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Mot clé n°' + (index + 1))
            .replace(/__name__/g, index)
        ;

        var $prototype = $(template);

        deleteButton($prototype);

        $container.append($prototype);

        index ++;
    }

    function deleteButton($prototype) {
        var $deleteLink = $('<a href="#" class="btn waves-effect waves-light red">Annuler</a>');

        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();

            e.preventDefault(); // évite qu'un # apparaisse dans l'URL
            return false;
        });
    }

});
