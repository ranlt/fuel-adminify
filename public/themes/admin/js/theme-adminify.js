

	$(document).ready(function(){

		$('ol.sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			isTree: true,
			expandOnHover: 700,
			startCollapsed: true
		});

		$('.disclose').on('click', function() {
			$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
		})

		$('#serialize').click(function(){
			serialized = $('ol.sortable').nestedSortable('serialize');
			$('#serializeOutput').text(serialized+'\n\n');
		})

		$('#toHierarchy').click(function(e){
			hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
			hiered = dump(hiered);
			(typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
			$('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
		})

		$('#toArray').click(function(e){
			arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
			arraied = dump(arraied);
			(typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
			$('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;
		})

	});

	function dump(arr,level) {
		var dumped_text = "";
		if(!level) level = 0;

		//The padding given at the beginning of the line.
		var level_padding = "";
		for(var j=0;j<level+1;j++) level_padding += "    ";

		if(typeof(arr) == 'object') { //Array/Hashes/Objects
			for(var item in arr) {
				var value = arr[item];

				if(typeof(value) == 'object') { //If it is an array,
					dumped_text += level_padding + "'" + item + "' ...\n";
					dumped_text += dump(value,level+1);
				} else {
					dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
				}
			}
		} else { //Strings/Chars/Numbers etc.
			dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
		}
		return dumped_text;
	}


    // Sortable row
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };
    // Sortable
    $(function() {
	    $('#table-menu.sortable tbody').sortable({
	        helper: fixHelper,
	        update: function(event, ui) {
	            updatePosition(event, ui);
	        }
	    });
    });

    function updatePosition(event, ui)
    {
        var objectMove = $('#' + ui.item.context.id);
        var objectNext = objectMove.next();
        var objectPrevious = objectMove.prev();

        if (objectNext.attr('id') !== undefined)
        {
            var dataForRequest = {idCurrent: objectMove.attr('data-id'), idNext: objectNext.attr('data-id')};
        }
        else if (objectPrevious.attr('id') !== undefined)
        {
            var dataForRequest = {idCurrent: objectMove.attr('data-id'), idPrev: objectPrevious.attr('data-id')};
        }
        else
        {
            return false;
        }

        var request = $.ajax({
            url: '/menu/backend/index/api/move_menu.json',
            type: 'GET',
            data: dataForRequest,
            dataType: "json"
        });

        request.done(function(data) {
            addAlert("<?= __('menu.message.position'); ?>");
        });
    }

    function addAlert(message) {
    	if ($('#alerts:empty').length == 0)
    	{
    		$('#alerts').fadeOut(50).html('');
    	}
    	
        $('#alerts').append(
                '<div class="alert alert-success">' +
                '<button type="button" class="close" data-dismiss="alert">' +
                '&times;</button>' + message + '</div>').fadeIn(500);
    }



