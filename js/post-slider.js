if(typeof vars!=="undefined" && vars.max > 0) {
    jQuery( "#post-slider" ).slider({
        value:vars.max,
        min: 0,
        max: vars.max,
        step: 1,
        slide: function( event, ui ) {
            const index = ui.value;
            jQuery("#post-content").html(vars.revisions[index].post_content);
            jQuery(".revision-date").text(index==vars.max ? "" : "(Revisión: " + vars.revisions[index].post_formatted_date + ")");
        }
    });
}