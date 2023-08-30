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