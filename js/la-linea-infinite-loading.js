
jQuery(function($) {
    $("#story").append('<span class="load-more"></span>');
    var button = $('#story .load-more');
    var page = 1;
    const page_size = 10;
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
        loading = true;
        var data = { action: 'la_linea_ajax_load_more', page: page, page_size: page_size };
        $.post(vars.url, data, function(res) {
            if(res.success) {
                res.data.forEach(function(post) {
                    $('#story').append(`
                        <div class="story-item">
                            <p class="story-title">${post.title}</p>
                            <h2>${post.post_title}</h2>
                            <p>${post.custom_date_format} · <a href="${post.url}">${post.link_text}</a></p>
                        </div>
                    `);
                });
                $('#story').append(button);
                page = page + 1;
                loading = false;
            }
            else {

            }
        });
    }
});