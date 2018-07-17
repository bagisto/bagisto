$(function() {
    $(document).click(function(e) {
        var target = e.target;
        if(!$(target).parents('.dropdown-open').length || $(target).is('li') || $(target).is('a')) {
            $('.dropdown-list').hide();
            $('.dropdown-toggle').removeClass('active');
        }
    });

    $('body').delegate('.dropdown-toggle', 'click', function(e) {
        toggleDropdown(e);
    });

    function toggleDropdown(e) {
        var currentElement = $(e.currentTarget);
        if(currentElement.attr('disabled') == "disabled")
            return;
        
        $('.dropdown-list').hide();
        if(currentElement.hasClass('active')) {
            currentElement.removeClass('active');
        } else {
            currentElement.addClass('active');
            currentElement.parent().find('.dropdown-list').fadeIn(100);
            currentElement.parent().addClass('dropdown-open');
            autoDropupDropdown();
        }
    }

    $('.dropdown-list .search-box .control').on('input', function() {
        var currentElement = $(this);
        currentElement.parents(".dropdown-list").find('li').each(function() {
            var text = $(this).text().trim().toLowerCase();
            var value = $(this).attr('data-id');
            if(value) {
                var isTextContained = text.search(currentElement.val().toLowerCase());
                var isValueContained = value.search(currentElement.val());
                if(isTextContained < 0 && isValueContained < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                    flag = 1;
                }
            } else {
                var isTextContained = text.search(currentElement.val().toLowerCase());
                if(isTextContained < 0) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }
        });
    });

    function autoDropupDropdown() {
        dropdown = $(".dropdown-open");
        if(!dropdown.find('.dropdown-list').hasClass('top-left') && !dropdown.find('.dropdown-list').hasClass('top-right') && dropdown.length) {
            dropdown = dropdown.find('.dropdown-list');
            height = dropdown.height() + 50;
            var topOffset = dropdown.offset().top - 70;
            var bottomOffset = $(window).height() - topOffset - dropdown.height();
            
            if(bottomOffset > topOffset || height < bottomOffset) {
                dropdown.removeClass("bottom");
                if(dropdown.hasClass('top-right')) {
                    dropdown.removeClass('top-right')
                    dropdown.addClass('bottom-right')
                } else if(dropdown.hasClass('top-left')) {
                    dropdown.removeClass('top-left')
                    dropdown.addClass('bottom-left')
                }
            } else {
                if(dropdown.hasClass('bottom-right')) {
                    dropdown.removeClass('bottom-right')
                    dropdown.addClass('top-right')
                } else if(dropdown.hasClass('bottom-left')) {
                    dropdown.removeClass('bottom-left')
                    dropdown.addClass('top-left')
                }
            }
        }
    }

    $('div').scroll(function() {
        autoDropupDropdown()
    });

});