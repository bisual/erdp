jQuery(function($) {
    const urlParams = new URLSearchParams(window.location.search);
    
    var $activeButton = $("#activas");
    var $finalizadasButton = $("#finalizadas");
    var $sortSelector = $("#sortSelector");
    var $proposalsDiv = $("#proposals");

    var showActive = urlParams.get('finalized')!='1';
    var sortByDate = urlParams.get('sort_by') == 'date';

    if(showActive) $activeButton.addClass('active');
    else $finalizadasButton.addClass('active');

    /*$("#sortSelector option").filter(function() {
        return this.value == vars.sort_by;
    }).prop('selected', true);*/
    $sortSelector.val(sortByDate ? 'date' : 'votes');

    initListeners();
    loadProposals();
    
    function loadProposals() {
        var data = {
            action: 'el_cajon_ajax',
            active: showActive ? 1 : 0,
            sort_by_date: sortByDate ? 1 : 0
        };

        $.post(vars.url, data, function(res) {
            if(res.success) {
                if(res.data.length > 0) {
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
                        if(post.done !== 'done') $actions.append($button);
                        $div.append($actions);
                        $proposalsDiv.append($div);
                    });
                }
                else {

                }
            }
            else {

            }
        });
    }

    function vote($votes, $button, post_id) {
        $button.unbind('click'); //eliminem el click
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

    function setGetParameter(paramName, paramValue) {
        var url = window.location.href;
        var hash = location.hash;
        url = url.replace(hash, '');
        if (url.indexOf(paramName + "=") >= 0) {
            var prefix = url.substring(0, url.indexOf(paramName + "=")); 
            var suffix = url.substring(url.indexOf(paramName + "="));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        }
        else {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
        window.location.href = url + hash;
    }

    function initListeners() {
        if(showActive) {
            $finalizadasButton.click(function() {
                setGetParameter('finalized', "1");
                document.reload();
            });
        }
        else {
            $activeButton.click(function() {
                setGetParameter('finalized', "0");
                document.reload();
            });
        }

        $sortSelector.change(function(val) {
            setGetParameter('sort_by', $sortSelector.val())
            document.reload();
        });
    }
});