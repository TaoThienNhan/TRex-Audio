jQuery(document).ready(function($) {
    var menu = new Mmenu("#mmenu", {
        "extensions": [
            "theme-dark",
            "effect-slide"
        ],
        "navbar": {
            "title": "DANH MỤC SẢN PHẨM"
        },
        "navbars": [
            {
                "position": "top",
            }
        ]
    });

    var mmenuAPI = menu.API;
    $("#mmenu-btn").click(function() {
        mmenuAPI.open();
    });
});

jQuery(document).ready(function($) {
    $('.img-gallery a').on('click', function(e) {
        e.preventDefault();
        var image_url = $(this).attr('href');
        $('.main-image img').attr('src', image_url).removeAttr('srcset');
    });
});