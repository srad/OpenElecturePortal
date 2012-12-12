$(function () {

    $('#accordion').accordion({
        autoHeight:false,
        clearStyle:true,
        header:'span.accordion-header',
        collapsible:true,
        active: 'span.selected'
    });

    $('.sidebar .accordion-header a').click(function (event) {
        var $this = $(this),
            href = $this.attr('href');

        if ($this.hasClass('disabled')) {
            event.stopPropagation();
            return false;
        }

        if (href !== '') {
            event.stopPropagation();

            window.location = href;

            return false;
        }
    });

    UFM.ep.highlightCurrentMenuItem = function () {
        var $buttons = $('.navbar .navbar-inner ul.nav li'),
            controller = UFM.ep.controller,
            action = UFM.ep.action,
            currentId = UFM.ep.currentId;

        $buttons.each(function () {
            var $this = $(this),
                activeOnControllers = String($this.data('controller')).split('||'),
                isButtonsController = activeOnControllers.indexOf(controller) !== -1,
                isButtonsAction = $this.data('action') === action || $this.data('action') === '*',
                isButtonsId = $this.data('id') === currentId || $this.data('id') === '*';

            if (isButtonsController && isButtonsAction && isButtonsId) {
                $this.addClass('active');
            }
        });
    };

    UFM.ep.highlightCurrentMenuItem();

    UFM.ep.$autocomplete = $('#formSearch .search-query').autocomplete({
        source: function( request, response ) {
            $.ajax({
                type: 'POST',
                url: UFM.ep.baseUrl + "/videos/search",
                dataType: 'json',
                data: { term: request.term },
                success: function(data) {
                    response( $.map( data, function( item ) {
                        return {
                            label: (item.Listing.name + ' - ' + item.Video.title).substring(0, 55),
                            value: { id: item.Listing.id, termId: item.Listing.term_id, categoryId: item.Listing.category_id }
                        }
                    }));
                }
            });
        },
        minLength: 3,
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        },
        select: function( event, ui ) {
            window.location = UFM.ep.baseUrl + '/listings/view/' + ui.item.value.id + '/' + ui.item.value.categoryId + '/' + ui.item.value.termId;
        }
    });

    UFM.ep.$autocomplete.data('autocomplete').menu.element.css('min-width', '600px');

});