
jQuery(function($) {
    $("#story").append('<span class="load-more"></span>');
    var button = $('#story .load-more');
    var page = 1;
    var loading = false;

    loadPosts();
    
    var scrollHandling = {
        allow: true,
        reallow: function() {
            scrollHandling.allow = true;
        },
        delay: 400
    };

    $(window).scroll(function() {
        if(!loading && scrollHandling.allow) {
            scrollHandling.allow = false;
            setTimeout(scrollHandling.reallow, scrollHandling.delay);
            var offset = $(button).offset().top - $(window).scrollTop();
            if(1000 > offset) {
                loadPosts();
            }
        }
    });

    function loadPosts() {
        button.text("Cargando...");
        loading = true;
        var data = {
            action: 'la_linea_ajax_load_more', page: page
        };
        $.post(vars.url, data, function(res) {
            if(res.success) {
                res.data.forEach(function(post) {
                    $('#story').append('<p>' + post.post_title + ' ' + post.post_date + '</p>');
                });
                $('#story').append(button);
                page = page + 1;
                loading = false;
                button.text("");
            }
            else {
                button.text("");
            }
        });
    }
});