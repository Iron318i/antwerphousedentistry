+function ($) {
    var $grid = $('.grid').masonry({
        columnWidth: '.grid-sizer',
        itemSelector: '.grid-item',
        gutter: '.gutter-sizer',
        percentPosition: true
    });

    $('#treatmentType').on('change', function () {
        $cat = this.value;
        if ($cat == 'all') {
            $('.ahd-case-studies .case').show();
            $grid.masonry('layout');
        } else {
            $('.ahd-case-studies .case').hide();
            $('.ahd-case-studies .' + $cat).show();
            $grid.masonry('layout');
        }
    });

}(jQuery);

