$(document).ready(function () {
    $('a.img-preview').fancybox({
        padding: 3
    });
    window.token = $('input[name=_token]').val();

    // Seen all link
    bindSeenAll();

    // Getting new messages
    // setInterval(function () {
    //     $.post('/admin/get-new-messages',{
    //         '_token': window.token
    //     }, function (data) {
    //         if (data.success) {
    //             let countContainer = $('#message-counter');
    //             if (!countContainer.length) {
    //                 $('#message-counter-container').append(
    //                     $('<span></span>').attr('id','message-counter').addClass('badge bg-warning-400')
    //                 );
    //                 newMessages(data.counter, data.messages);
    //             } else if (parseInt(countContainer.html()) != data.counter) {
    //                 newMessages(data.counter, data.messages);
    //             }
    //         }
    //     });
    // }, 30000);

    // Forming CSV
    $('button.form-csv').click(function () {
        $(this).attr('disabled','disabled');
        var parent = $(this).parents('.customer,.sub-tasks'),
            table = parent.find('table.table-items'),
            downloadLink = parent.find('a.download-csv'),
            content = '',
            columns = 5;

        for (var i=0;i<columns;i++) {
            // content += translit($(table.find('th')[i]).html(), false) + (i == columns-1 ? "\n" : ';');
            content += $(table.find('th')[i]).html() + (i == columns-1 ? "\n" : ';');
        }

        $.each(table.find('tr.tasks-row'), function (k,row) {
            for (var i=0;i<columns;i++) {
                var cell = $($(row).find('td')[i]);

                if (!i) cell = cell.find('a');
                else if (i == columns-1) cell = cell.find('span');

                var cellContent = $.trim(cell.html().replace(/(<([^>]+)>)/gi, "")).replace(',','.');
                if (i == 1) cellContent = cellContent.replace(/(\s+(\d){1,3}%)/gi, "");
                content += cellContent + (i == columns-1 ? "\n" : ';');
            }
        });

        $(this).fadeOut(function () {
            downloadLink.attr({
                'download':'tasks.csv',
                'href':makeCSV(content)
            }).fadeIn();
        });
    });

    // Phone mask
    $('input[name=phone]').mask("+7(999)999-99-99");

    // Preview upload image
    $('input[type=file]').change(function () {
        var input = $(this)[0],
            parent = $(this).parents('.edit-image-preview'),
            imagePreview = parent.find('img');

        if (input.files[0].type.match('image.*')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.attr('src', e.target.result);
                if (!imagePreview.is(':visible')) imagePreview.fadeIn();
            };
            reader.readAsDataURL(input.files[0]);
        } else if (parent.hasClass('file-advanced')) {
            imagePreview.attr('src', '');
            imagePreview.fadeOut();
        } else {
            imagePreview.attr('src', '/images/placeholder.jpg');
        }
    });

    // Click YES on delete modal
    $('.delete-yes').click(function () {
        $('#'+localStorage.getItem('delete_modal')).modal('hide');

        $.post('/admin/'+localStorage.getItem('delete_function'), {
            '_token': window.token,
            'id': localStorage.getItem('delete_id'),
        }, function (data) {
            if (data.success) {
                var row = localStorage.getItem('delete_row');
                $('#'+row).remove();
            }
        });
    });

    // Change customer in bill's page
    $('form.bill-form select[name=task_id]').change(function () {
        var id = $(this).val();
        $.post('/admin/get-convention-number', {
            '_token': window.token,
            'id': id
        }, function (data) {
            $('input[name=convention_number]').val(data.number);
        });

        $.post('/admin/get-bill-value', {
            '_token': window.token,
            'id': id
        }, function (data) {
            $('input[name=value]').val(data.value);
        });
    });

    // Change customer in task's page
    $('form.task-form select[name=customer_id]').change(function () {
        let ltd = $(this).find(':selected').attr('ltd'),
            changingWidthBlocks = $('.col-3-to-4'),
            hiding = $('.hiding');

        if (ltd > 2) {
            changingWidthBlocks.removeClass('col-md-3').addClass('col-md-4');
            hiding.addClass('hidden');
        } else {
            changingWidthBlocks.removeClass('col-md-4').addClass('col-md-3');
            hiding.removeClass('hidden');
        }
    });

    // Change task status
    $('.task-status input').on('switchChange.bootstrapSwitch', function() {
        let hidden = $('.hiding1'),
            showing = $('.showing1');

        if ($(this).val() >= 6) {
            $('.col-3-to-4.col-md-3').removeClass('col-md-3').addClass('col-md-4');
            hidden.addClass('hidden');
            showing.removeClass('hidden');
        } else {
            // if ($('form.task-form input[name=use_duty]').is(':checked'))
            //     $('.col-3-to-4.col-md-4').removeClass('col-md-4').addClass('col-md-3');
            hidden.removeClass('hidden');
            showing.addClass('hidden');
        }
    });

    // Change customer type
    $('.customer-type input').on('switchChange.bootstrapSwitch', function() {
        var hidden = $('.hiding');

        if ($(this).val() == 2) {
            $('.col-4-to-12.col-md-4').removeClass('col-md-4').addClass('col-md-12');
            hidden.addClass('hidden');
        } else {
            $('.col-4-to-12.col-md-12').removeClass('col-md-12').addClass('col-md-4');
            hidden.removeClass('hidden');
        }
    });

    // Change use duty
    $('form.task-form input[name=use_duty]').change(function () {
        let hiddenBlocks = $('.hiding');

        if ($(this).is(":checked")) {
            $('.col-md-4.col-3-to-4').removeClass('col-md-4').addClass('col-md-3');
            hiddenBlocks.removeClass('hidden');
        } else {
            $('.col-md-3.col-3-to-4').removeClass('col-md-3').addClass('col-md-4');
            hiddenBlocks.addClass('hidden');
        }
    });

    // Change my status in settings
    $('form input[name=my_status]').on('switchChange.bootstrapSwitch', function() {
        let ieBlock = $('.ie-block'),
            seBlock = $('.se-block');

        if ($(this).val() == 1) {
            ieBlock.removeClass('hidden');
            seBlock.addClass('hidden');
        } else {
            ieBlock.addClass('hidden');
            seBlock.removeClass('hidden');
        }
    });

    // Click to page button
    $('.datatable-basic').on('draw.dt', function () {
        clickToDeleteIcon();
    });

    // Click to delete items
    clickToDeleteIcon();
});

function clickToDeleteIcon() {
    $('.glyphicon-remove-circle').click(function () {
        deleteItem($(this));
    });
}

function deleteItem(obj) {
    var deleteModal = $('#'+obj.attr('modal-data'));

    localStorage.clear();
    localStorage.setItem('delete_id',obj.attr('del-data'));
    localStorage.setItem('delete_function',deleteModal.find('.modal-body').attr('del-function'));
    localStorage.setItem('delete_row', (obj.parents('tr').length ? obj.parents('tr').attr('id') : obj.parents('.col-lg-2').attr('id')));
    localStorage.setItem('delete_modal',obj.attr('modal-data'));

    deleteModal.modal('show');
}

function makeCSV(text) {
    var data = new Blob([text], {type: 'text/plain;charset=UTF-8'});

    // If we are replacing a previously generated file we need to
    // manually revoke the object URL to avoid memory leaks.
    if (window.csvFile !== null) {
        window.URL.revokeObjectURL(window.csvFile);
    }

    window.csvFile = window.URL.createObjectURL(data);
    return window.csvFile;
}

function translit(text, engToRus) {
    var rus = "щ ш ч ц ю я ё ж ъ ы э а б в г д е з и й к л м н о п р с т у ф х ь".split(/ +/g),
        eng = "shh sh ch cz yu ya yo zh `` y' e` a b v g d e z i j k l m n o p r s t u f x `".split(/ +/g);

    var x;
    for(x=0;x<rus.length; x++) {
        text = text.split(engToRus ? eng[x] : rus[x]).join(engToRus ? rus[x] : eng[x]);
        text = text.split(engToRus ? eng[x].toUpperCase() : rus[x].toUpperCase()).join(engToRus ? rus[x].toUpperCase() : eng[x].toUpperCase());
    }
    return text;
}

function bindSeenAll() {
    $('#seen-all').click(function (e) {
        e.preventDefault();
        $.post('/admin/seen-all',{
            '_token': window.token,
        }, function (data) {
            if (data.success) {
                var counter = $('#message-counter'),
                    messageCount = parseInt(counter.html()),
                    title = $('title'),
                    titleText = title.html();

                if (data.messages.length == messageCount) {
                    counter.remove();
                    $('#seen-all').remove();
                    var newTitleCounter = '';
                } else {
                    counter.html(data.messages.length);
                    newTitleCounter = '('+data.messages.length+') ';
                }

                counter.html(data.messages.length);
                $.each(data.messages, function (k,id) {
                    $('#message'+id).remove();
                });

                title.html(titleText.replace(/^(\(\d+\)\s)/g,newTitleCounter));
            }
        });
    });
}

function playWarning() {
    if (window.userInteract) {
        var audio = new Audio();
        audio.preload = 'auto';
        audio.src = '/sound/new_message.wav';
        audio.play();
    }
}

function newMessages(newCounter, messages) {
    $('#message-counter').html(newCounter);
    playWarning();

    $('#messages').html(newCounter);
    bindSeenAll();
}

function cloneArrayData(data) {
    var newData = [];
    $.each(data, function (k,item) {
        newData[k] = item;
    });
    return newData;
}

var PowerSlider = function (target, values, value, step, tips, range, callback) {
    var slider = $(target);
    if (slider.length) {
        var width = slider.parents('.p-slider').width();
        return new rSlider({
            width: width,
            target: target,
            values: values,
            set: value,
            labels: true,
            range: range,
            step: step,
            tooltip: tips,
            onChange: function (vals) {
                if (callback) callback(vals);
            }
        });
    }
};
