$(function (UFM) {
    'use strict';

    UFM.ep.accordion = {};

    /**
     * Expands a certain accordion.
     * @param id The lecture id.
     */
    UFM.ep.accordion.expandByLectureId = function (id) {
        $('#listings h3.depth-1,#listings h3.depth-2').each(function(index) {
            if (parseInt($(this).data('id'), 10) === parseInt(id, 10)) {
                $('#listings .container-videolist').accordion('option', 'active', index);
                return;
            }
        });
    };

    UFM.ep.accordion.init = function () {
        $('#accordion').accordion({
            autoHeight:false,
            clearStyle:true,
            header:'span.accordion-header',
            collapsible:true,
            active: 'span.selected',
            beforeActivate: function(event, ui) {
                if (ui.newPanel.children().size() === 0 || ui.newHeader.hasClass('disabled')) {
                    event.stopPropagation();
                    return false;
                }
            },
            create : function (event, ui) {
                var content = ui.header.next();

                if (content.children().size() === 0) {
                    content.hide();
                }
            }
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

        // Expand selected index for accordion
        $('#accordion .dynamic').next().find('a').click(function (event) {
            event.stopPropagation();
            UFM.ep.accordion.expandByLectureId($(this).data('id'));
            return false;
        });
    };

    /**
     * This could be done server side.
     */
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

    UFM.ep.$autocomplete = $('#formSearch .search-query').autocomplete({
        source: function( request, response ) {
            $.ajax({
                type: 'POST',
                url: UFM.ep.baseUrl + "/lectures/search",
                dataType: 'json',
                data: { term: request.term },
                success: function(data) {
                    response( $.map( data, function( item ) {
                        return {
                            label: UFM.ep.getLabel(item),
                            value: item.Lecture.name,
                            data: { id: item.Lecture.id, termId: item.Lecture.term_id, categoryId: item.Lecture.category_id }
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
            window.location = UFM.ep.baseUrl + '/lectures/view/' + ui.item.data.id + '/' + ui.item.data.categoryId + '/' + ui.item.data.termId;
        }
    });

    /**
     * Label Callback. It got a little long, separated to a function
     * @param data
     * @return {String}
     */
    UFM.ep.getLabel = function (data) {
        var label = String(data.Lecture.name).substring(0, 40);
        if (data.Term.name !== null) {
            label += (' - ' + data.Term.name);
        }
        if (data.Video.speaker !== null) {
            label += (' - ' + data.Video.speaker);
        }
        return label.substring(0, 65)
    }

    return function () {
        UFM.ep.highlightCurrentMenuItem();
        UFM.ep.$autocomplete.data('autocomplete').menu.element.css('min-width', '500px');
        UFM.ep.accordion.init();
    };

}(UFM));