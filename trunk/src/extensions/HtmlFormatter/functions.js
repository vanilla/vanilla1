(function($){
    $.HtmlFormatter = {
        margin: 280,

        getDesiredWidth: function() {
            var innerWidth = window.innerWidth ||
                document.documentElement.clientWidth;
            return innerWidth - $.HtmlFormatter.margin;
        },

        changeDimensions: function() {
            var $img = $('img.InlineImage'),
                desiredWidth = $.HtmlFormatter.getDesiredWidth();

            $img.width('auto');
            if ($img.width() > desiredWidth) {
                $img.width(desiredWidth);
            }
        }
    };

    $(window).bind('load resize', $.HtmlFormatter.changeDimensions);


})(jQuery.noConflict());