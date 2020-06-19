jQuery(function($) {
    var $activeButton = $("#activas");
    var $finalizadasButton = $("#finalizadas");
    var $sortSelector = $("#sortSelector");
    var $proposalsDiv = $("#proposals");

    var showActive = vars.finalized==false;
    var sortByDate = vars.sort_by === 'date';

    var page = vars.page;
    const page_size = 10;

    if(showActive) $activeButton.addClass('active');
    else $finalizadasButton.addClass('active');

    loadProposals();
    
    function loadProposals() {
        var data = {
            action: 'el_cajon_ajax',
            active: showActive,
            sort_by_date: sortByDate,
            page: page,
            page_size: page_size
        };

        $.post(vars.url, data, function(res) {
            if(res.success) {
                res.data.forEach(function(post) {
                    var $div = $(`
                        <div class="proposal-item">
                            <h2>${post.post_title}</h2>
                            <p class="the-date">${post.custom_date_format}</p>
                            <p class="the-excerpt">${post.post_excerpt}</p>
                        </div>
                    `);
                    var $actions = $(`<div class="actions"></div>`);
                    var $votes = $(`<span class="votes">${post.votes ? post.votes : 0} votos</span>`);
                    var $button = $('<button class="btn icon">Votar</button>');

                    $button.click(function() {
                        vote($votes, $button, post.ID);
                    });

                    $actions.append($votes);
                    $actions.append($button);
                    $div.append($actions);
                    $proposalsDiv.append($div);
                });
            }
            else {

            }
        });
    }

    function vote($votes, $button, post_id) {
        $button.click(null); //eliminem el click
        $button.text("Votando...");

        var data = {
            action: 'el_cajon_vote_ajax',
            post_id: post_id
        };

        $.post(vars.url, data, function(res) {
            if(res.success) {
                $votes.text(res.data.votes + ' votos');
                $button.text("Has votado.");
                $button.delete();
            }
        });
    }
});