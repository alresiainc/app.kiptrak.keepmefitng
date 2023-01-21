/* 
This Simple Form Builder was Developed by 
Ugo Sunday Raphael
First Published in: www.kiptak.com
*/
$(function() {

    $('#add_q-item').click(function(e) {
        var el = $('#q-item-clone').clone() //clone a d-none div
        var f_arr = []
        $('#question-field .question-item').each(function() { //question-item, div similar to 'el(cloned)' above
            f_arr.push(parseInt($(this).attr('data-item')))
        })
        console.log(f_arr)
        var i = f_arr.length
            // console.log(i)
        el.find('.question-item').attr('data-item', i)
        el.find('textarea').attr('name', 'q[' + i + ']')
        $('#question-field').append(el.html())
        $('body,html').animate({ scrollTop: $(this).offset().top }, 'fast')
        _initilize()
    })
    $('#question-field').sortable({
            handle: '.item-move',
            classes: {
                "ui-sortable": "highlight"
            }
        })
        // $("#question-field").disableSelection();

    function _initilize() {
        $('[contenteditable="true"]').each(function() {
            $(this).on("blur focusout", function() {
                if ($(this).text() == "") {
                    $(this).text($(this).attr("title"))
                }
            })

        })
        $('.question-item .form-check').find('label').on('keypress keyup paste cut', function() {
            $(this).siblings('input').val($(this).text())
        })
        $('.question-item .req-chk').click(function() {
            if ($(this).siblings('input[type="checkbox"]').is(":checked") == true) {
                $(this).siblings('input[type="checkbox"]').prop("checked", false).trigger("change")
            } else {
                $(this).siblings('input[type="checkbox"]').prop("checked", true).trigger("change")
            }
        })
        $('.rem-q-item').click(function() {
            $(this).closest('.question-item').remove()
            // $('.remove').closest('.wrapper').find('.element').not(':first').last().remove();
            //$("a:not(:first):not(:last)").css('z-index', '90')
        })
        $('.req-item').change(function() {
            var _parent = $(this).closest('.question-item')
            if ($(this).is(":checked") == true) {
                _parent.find("input").first().attr('required', true)
                _parent.find("textarea").first().attr('required', true)
                $(this).attr('checked', true)
            } else {
                _parent.find("input").first().attr('required', false)
                _parent.find("textarea").first().attr('required', false)
                $(this).attr('checked', false)
            }
        })

        //logic for changing fields
        $('.choice-option').change(function() {
            var choice = $(this).val()
            var _field = $(this).closest('.question-item').attr('data-item') //gives 0
            if (choice == "p") {
                paragraph($(this), _field)
            
            //code added
            } else if (choice == "text_field") {
                text_field($(this), _field)
            
            //code added, similar to radio option
            } else if (choice == "number_field") {
                number_field($(this), _field)
            
            //code added, similar to radio option
            } else if (choice == "checkbox") {
                //find closest and replace the html
                $(this).closest('.question-item').find('.choice-field').html('<button type="button" class="add_chk btn btn-sm btn-success border"><i class="bi bi-plus"></i> Add option</button>')
                //for populating already shown checkboxes
                add_checkbox()
                for (var i = 0; i < 3; i++) {
                    checkbox_field($(this), _field, "Enter Option")
                }
            
            } else if (choice == "package_single") {
                //adds add-btn
                $(this).closest('.question-item').find('.choice-field').html('<button type="button" class="add_package btn btn-sm btn-success border"><i class="bi bi-plus"></i> Add option</button>')
                add_package()
                for (var i = 0; i < 3; i++) {
                    package_field($(this), _field, "Enter Option")
                }

            //multi choice single option
            } else if (choice == "package_multi") {
                //adds add-btn
                $(this).closest('.question-item').find('.choice-field').html('<button type="button" class="add_package btn btn-sm btn-success border"><i class="bi bi-plus"></i> Add option</button>')
                add_package()
                for (var i = 0; i < 3; i++) {
                    package_field($(this), _field, "Enter Option")
                }

            //multi choice single option, not used
            } else if (choice == "radio") {
                $(this).closest('.question-item').find('.choice-field').html('<button type="button" class="add_radio btn btn-sm btn-success border"><i class="bi bi-plus"></i> Add option</button>')
                add_radio()
                for (var i = 0; i < 3; i++) {
                    radio_field($(this), _field, "Enter Option")
                }

            } else if (choice == "file") {
                file_field($(this), _field)
            }
            $(this).closest('.question-item').find('.req-item').trigger('change')
        })

        //added logic for changing labels
        $('.form_name').change(function() {
            var name = $(this).val()
            var form_name_selected = $(this).closest('.question-item').find('.form_name_selected')

            //check repeated form_names
            // var values = $('input[name="form_name_selected[]"]').map(function() {
            //     return this.value;
            //   }).toArray();

            // var hasDups = !values.every(function(v,i) {
            // return values.indexOf(v) == i;
            // });
            // if(hasDups){
            //     // having duplicate values
            //     alert("Please Do Not Repeat the Input Names. Check Duplicates!")
                
            // }

            form_name_selected.val(name);
            $(this).closest('.question-item').find('.form_label').val(name)
            
        });
    }

    function add_checkbox() {
        $('.add_chk').click(function() {
            var _this = $(this)
            var _field = _this.closest('.question-item').attr('data-item')
            checkbox_field(_this, _field, "Enter Option")
        })
    }

    function add_radio() {
        $('.add_radio').click(function() {
            var _this = $(this)
            var _field = _this.closest('.question-item').attr('data-item')
            radio_field(_this, _field, "Enter Option")
        })
    }

    //select-field
    function add_package() {
        $('.add_package').click(function() {
            var _this = $(this)
            var _field = _this.closest('.question-item').attr('data-item')
            package_field(_this, _field, "Enter Option")
        })
    }

    function paragraph(_this, _field) {
        var el = $('<textarea>')
        el.attr({
            "cols": 30,
            "rows": 5,
            "placeholder": "Write your answer here",
            "name": "q[" + _field + "]",
            "class": "form-control col-sm-12"
        })
        _this.closest('.question-item').find('.choice-field').html(el)
    }

    function text_field(_this, _field) {
        var el = $('<input>')
        el.attr({
            "type": "text",
            "placeholder": "Type here",
            "name": "q[" + _field + "]",
            "class": "form-control col-sm-12"
        })
        _this.closest('.question-item').find('.choice-field').html(el)
    }

    function number_field(_this, _field) {
        var el = $('<input>')
        el.attr({
            "type": "number",
            "placeholder": "Type here",
            "name": "q[" + _field + "]",
            "class": "form-control col-sm-12"
        })
        _this.closest('.question-item').find('.choice-field').html(el)
    }

    function file_field(_this, _field) {
        var el = $('<input>')
        el.attr({
            "type": "file",
            "name": "q[" + _field + "]",
            "class": "form-control-file"
        })
        _this.closest('.question-item').find('.choice-field').html(el)
    }

    function checkbox_field(_this, _field, _text = "option") {
        var chk = $("<div>")
        var rem = $("<div>")
        chk.attr({
            "class": "col-sm-11 d-flex align-items-center",
        })
        rem.attr({
            "class": "col-sm-1 rem-on-display",
        })
        rem.append("<button class='btn btn-sm btn-default' type='button'><span class='bi bi-x-lg'></span></button>")
        rem.attr('onclick', "$(this).closest('.row').remove()")
        var item = create_checkbox_field(_field, _text)
        chk.append(item)
        el = $("<div class='row w-100'>")
        el.append(rem)
        el.append(chk)
        _this.closest('.question-item').find('.choice-field .add_chk').before(el)
    }

    function radio_field(_this, _field, _text = "option") {
        var chk = $("<div>")
        var rem = $("<div>")
        chk.attr({
            "class": "col-sm-11 d-flex align-items-center", // where check-radio options live
        })
        rem.attr({
            "class": "col-sm-1 rem-on-display", // where remove icons live
        })
        rem.append("<button class='btn btn-sm btn-default' type='button'><span class='bi bi-x-lg'></span></button>")
        rem.attr('onclick', "$(this).closest('.row').remove()")
        var item = create_radio_field(_field, _text)
        chk.append(item)
        el = $("<div class='row w-100'>")
        el.append(rem)
        el.append(chk)
        _this.closest('.question-item').find('.choice-field .add_radio').before(el)
    }

    function package_field(_this, _field, _text = "option") {
        var chk = $("<div>")
        var rem = $("<div>")
        chk.attr({
            "class": "col-sm-11 d-flex align-items-center", // where check-radio options live
        })
        rem.attr({
            "class": "col-sm-1 rem-on-display", // where remove icons live
        })
        rem.append("<button class='btn btn-sm btn-default' type='button'><span class='bi bi-x-lg'></span></button>")
        rem.attr('onclick', "$(this).closest('.row').remove()")
        var item = create_select_field(_field, _text)
        chk.append(item)
        el = $("<div class='row w-100'>")
        el.append(rem)
        el.append(chk)
        _this.closest('.question-item').find('.choice-field .add_package').before(el)
    }



    function create_checkbox_field(_field, _text) {

        var el = $('<div>')
        el.attr({
            "class": "form-check q-fc"
        })
        var inp = $('<input>')
        inp.attr({
            "class": "form-check-input",
            "name": "q[" + _field + "][]",
            "type": "checkbox",
            "value": _text
        })
        var label = $('<label>')
        label.attr({
            "class": "form-check-label",
            "contenteditable": true,
            "title": "Enter option here"
        })
        label.text(_text)
        el.append(inp)
        el.append(label)
        return el
    }

    function create_radio_field(_field, _text) {

        var el = $('<div>')
        el.attr({
            "class": "form-check q-fc"
        })
        var inp = $('<input>')
        inp.attr({
            "class": "form-check-input",
            "name": "q[" + _field + "]",
            "type": "radio",
            "value": _text
        })
        var label = $('<label>')
        label.attr({
            "class": "form-check-label",
            "contenteditable": true,
            "title": "Enter option here"
        })
        label.text(_text)
        el.append(inp)
        el.append(label)
        return el
    }

    function create_select_field(_field, _text) {

        var el = $('<div>')
        var products = $('.package_select').val()
        // console.log(products)
        el.attr({
            "class": "mb-3 q-fc w-100"
        })
        var select = $('<select>')
        select.attr({
            "class": "form-control",
            "name": "q[" + _field + "]",
            //"type": "radio",
            //"value": _text
        })
        var option = $('<option>')
        option.attr({
            //"class": "form-check-label",
            //"contenteditable": true,
            //"title": "Enter option here"
            "value": "1"
        })
        
        option.text(_text) //nott
        select.append(option) //not used
        el.append(products) //products contains the raw html with product-data, from controller e.g@newFormbuilder()
        //el.append(option)
        return el
    }
    _initilize()

    function save_form() {
        var new_el = $('<div>')
        var form_el = $('#form-field').clone()
        var form_code = $("[name='form_code']").length > 0 ? $("[name='form_code']").val() : "";
        var title = $('#form-title').text()
        var description = $('#form-description').text()
        form_el.find("[name='form_code']").remove()
        new_el.append(form_el)
        start_loader()
        $.ajax({
            url: "classes/Forms.php?a=save_form",
            method: 'POST',
            data: { form_data: new_el.html(), description: description, title: title, form_code: form_code },
            dataType: 'json',
            error: err => {
                console.log(err)
                alert("an error occured")
            },
            success: function(resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    alert("Form successfully saved")
                    location.href = "./"
                } else {
                    console.log(resp)
                    alert("an error occured")
                }
                end_loader()
            }
        })
    }
    $('#save_form').click(function() {
        save_form()
    })
})