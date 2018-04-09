$(document).ready(function () {
    initialize_select2('select.search_select');
    $('.simple_datatable').dataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        bSort: false,
        pageLength: per_page_show,
    });
    initialize_datetimepicker('.datetimepicker');
    $('select[name=company_main]').change(function() {
        var company_id = $(this).val();
        $.ajax({
            method: 'GET',
            url: base_url+'/company/change-company/'+company_id
        }).done(function() {
            location.reload();
        });
    });
});


function initialize_select2(div) {
    $(div).select2();
}
function initialize_datetimepicker(div) {
    $(div).datetimepicker({
        format: 'dd/mm/yyyy HH:ii p'
    });
}
function initializeSummerNote() {
    $('.summernote').summernote({
        dialogsInBody: true,
        onCreateLink : function(originalLink) {
            return originalLink; // return original link
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', [ 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['insert', ['picture','link', 'video']],
            ['para', ['ul', 'ol', 'paragraph', 'style']],
            ['misc', ['codeview']],
        ],
        callbacks: {
            onImageUpload: function(files) {
                sendFileSummerNote(files[0],this);
            },
            onPaste: function(e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                setTimeout(function () {
                    document.execCommand('insertText', false, bufferText);
                }, 10);
            }
        }
    });
}
function toastrShow(title,text) {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            "progressBar": false,
            showMethod: 'slideDown',
            "positionClass": "toast-top-center",
            "showEasing": "swing",
            timeOut: 3000
        };
        toastr.success(title, text);

    }, 1300);
}
function showAjaxSpinner(){
    $('.loader_ajax').height(400);
    $('.loader_ajax').append('<div class="spinner">&nbsp;</div>');
    $('.loader_ajax .spinner').fadeIn();
}
function hideAjaxSpinner(){
    $('.loader_ajax .spinner').fadeOut();
    $('.loader_ajax').height('auto');
    $('.loader_ajax .spinner').remove();
}

function loadingSpinnerHtml() {
    return '<div class="sk-spinner sk-spinner-three-bounce">'+
        '<div class="sk-bounce1"></div>'+
        '<div class="sk-bounce2"></div>'+
        '<div class="sk-bounce3"></div>'+
        '</div>';
}

function processBtnDisable(btn) {
    var spinner = '<div class="sk-spinner sk-spinner-wave">'+
        '<div class="sk-rect1"></div>'+
        '<div class="sk-rect2"></div>'+
        '<div class="sk-rect3"></div>'+
        '<div class="sk-rect4"></div>'+
        '<div class="sk-rect5"></div>'+
        '</div>';
    btn.html(spinner);
    btn.attr('disabled','disabled');
}
function revertProcessBtnDisable(btn,text) {
    btn.removeAttr('disabled');
    btn.html(text);
}

function showSpiner() {
    var spiner = $('.sk-spinner.sk-spinner-three-bounce');
    if(spiner.hasClass('hide')) {
        spiner.removeClass('hide');
    }
}
function hideSpiner() {
    var spiner = $('.sk-spinner.sk-spinner-three-bounce');
    if(!spiner.hasClass('hide')) {
        spiner.addClass('hide');
    }
}

function reloadAjaxSubmit(parent_id,url,field_name,btn_text) {
    var name = $('#'+parent_id+' input[name='+field_name+']');
    if(name.val() == '') {
        name.focus();
        return false;
    }
    var data = $('#'+parent_id+' form').serialize() ;
    var btn  = $('#'+parent_id+' button[type=submit]');
    var formData = new FormData($('#'+parent_id+' form')[0]);
    formData.append('_token', csrf_token);
    processBtnDisable(btn);
    $.ajax({
        method: 'POST',
        url: url,
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
    }).done(function(data) {
        if(data.success) {
            revertProcessBtnDisable(btn,btn_text);
            $('#'+parent_id+' form')[0].reset();
            location.reload();
        } else {
            revertProcessBtnDisable(btn,btn_text);
            $('#'+parent_id+' .modal-body').prepend('<div class="alert alert-danger">'+ data.errors +'</div>');
        }
    }).fail(function(response) {
        response = response.responseJSON;
        $.each(response.errors, function(i, item) {
            $('#'+parent_id+' #'+i).css('border-color','red');
        });
        revertProcessBtnDisable(btn,btn_text);
    });
    return false;
}
function dateGroupDiv(divFor){
    return $(divFor).datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: false,
        autoclose: true,
        format: "mm/dd/yyyy",
        todayHighlight: true
    })
}

