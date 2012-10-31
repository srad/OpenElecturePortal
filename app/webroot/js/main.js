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

    $('#formSearch .search-query').autocomplete({
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
                            value: item.Listing.id
                        }
                    }));
                }
            });
        },
        minLength: 2,
        select: function( event, ui ) {
            log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    });

});