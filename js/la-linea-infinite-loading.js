jQuery(function($) {
    $("#story").append('<span class="load-more">Cargar más</span>');
    var button = $('#story .load-more');
    var page = 2;
    var loading = false;
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
                    }
                    else {

                    }
                });
            }
        }
    });
});