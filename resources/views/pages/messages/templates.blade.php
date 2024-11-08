@extends('layouts.design')
@section('title')
    {{ ucfirst($channel) }} Templates
@endsection

@section('extra_css')
    <style>
        /* select2 arrow */
        select {
            -webkit-appearance: listbox !important;
        }

        /* custom-select border & inline edit */
        .btn-light {
            background-color: #fff !important;
            color: #000 !important;
        }

        div.filter-option-inner-inner {
            color: #000 !important;
        }

        /* select2 height proper */
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

        .mce-content-body span.editor-hide {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
@endsection

@section('content')

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>{{ ucfirst($channel) }} Templates: </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Template Templates</li>
                    <li class="breadcrumb-item active">{{ $channel }}</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="users-list-wrapper">
            <div class="users-list-filter px-1">
            </div>
        </section>

        @if (Session::has('success'))
            <div class="alert alert-success mb-3 text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <section>
            <!-- The element triggering the popover -->
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-info" id="loadPlaceholders" data-bs-toggle="popover"
                    data-bs-html="true" data-bs-content="">
                    View Template Placeholders
                </button>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <div class="table table-responsive">
                                <table id="products-table" class="table custom-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            {{-- <th>Channel</th> --}}
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($templates) > 0)
                                            @foreach ($templates as $template)
                                                @php
                                                    $message = $template->message;
                                                    $maxLength = 30;
                                                    $truncatedText =
                                                        strlen($message) > $maxLength
                                                            ? substr($message, 0, $maxLength) . '...'
                                                            : $message;
                                                @endphp
                                                <tr>
                                                    <td class="categoryname">{{ $template->name }}</td>
                                                    {{-- <td>{{ $template->channel }}</td> --}}
                                                    <td data-bs-toggle="popoverrrr" data-trigger="hover"
                                                        data-content="{{ $message }}" title="Message">
                                                        {!! $truncatedText !!} </td>
                                                    <td>
                                                        @if ($template->is_active)
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'deactivate']) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                                data-bs-title="Click to Deactivate Template"
                                                                data-bs-title="Click to Deactivate Template"> <span
                                                                    class="badge badge-success">Active</span></a>
                                                        @else
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'activate']) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="auto"
                                                                data-bs-title="Click to Activate Template"> <span
                                                                    class="badge badge-dark">Inactive</span></a>
                                                        @endif

                                                        {{-- {!! $template->is_active
                                                            ? '<span class="badge badge-success">Active</span>'
                                                            : '<span class="badge badge-dark">Inactive</span>' !!} --}}
                                                    </td>
                                                    <td>
                                                        <!-- Edit button to open modal -->
                                                        <button class="btn btn-sm btn-primary me-2"
                                                            onclick="editTemplateModal({{ $template->id }}, '{{ $template->subject }}', `{!! $template->message !!}`, '{{ $template->channel }}')">Edit</button>
                                                        {{-- @if ($template->is_active)
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'deactivate']) }}"
                                                                class="btn btn-success btn-sm"
                                                                data-bs-title="Click to Deactivate Template"> Deactivate</a>
                                                        @else
                                                            <a href="{{ route('updateTemplateStatus', ['template' => $template->id, 'status' => 'activate']) }}"
                                                                class="btn btn-primary btn-sm"
                                                                data-bs-title="Click to Activate Template"> Activate</a>
                                                        @endif --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Edit Template Modal -->
    <div class="modal fade" id="editTemplateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editTemplateForm" action="" method="POST">@csrf
                    <div class="modal-body">
                        <input type="hidden" name="template_id" id="template_id">

                        <div class="mb-3">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="">
                        </div>

                        <div class="mb-2">
                            <label for="template">Message Template</label>
                            <div class="editor-container">
                                <input type="hidden" name="template" id="template">
                                <textarea id="template-editor" class="form-control" cols="30" rows="10"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary update-template-btn">Update Template</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal HTML Structure -->
    <div class="modal fade" id="placeholderModal" tabindex="-1" aria-labelledby="placeholderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="placeholderModalLabel">Template Placeholders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <!-- Content will be dynamically loaded here -->
                    @include('pages.messages.placeholders.order-details')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra_js')
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        $('#loadPlaceholders').on('click', function() {
            $('#placeholderModal').modal('show');
        })



        function editTemplateModal(id, subject, template, type = 'whatsapp') {
            // Clear previous modal content before setting new data
            $('#template-editor').val(''); // Clear text area
            tinymce.remove('#template-editor'); // Clear editor content

            // Show the modal and set initial values
            $('#editTemplateModal').modal('show');
            $('#template_id').val(id);
            $('#subject').val(subject);
            $('#template-editor').val(template); // Set template content

            // Initialize the editor based on the type (whatsapp, sms, email)
            switch (type) {
                case 'email':
                    initializeHTMLEditor('#template-editor'); // Rich text editor for email
                    break;
                case 'sms':
                    initializePlainTextEditor('#template-editor'); // Plain text editor for SMS
                    break;
                case 'whatsapp':
                    initializeWhatsAppEditor('#template-editor'); // Custom editor for WhatsApp
                    break;
            }

            // Update form action dynamically with the correct template ID
            $('#editTemplateForm').attr('action', '/update-message-template/' + id);

            // Unbind any previously bound click events to avoid multiple handlers
            $('.update-template-btn').off('click').on('click', function(e) {
                e.preventDefault();

                let templateContent;
                if (type === 'email') {
                    templateContent = tinymce.get('template-editor').getContent(); // Get HTML content for email
                } else {
                    templateContent = tinymce.get('template-editor').getContent({
                        format: 'text'
                    }); // Get plain text for SMS/WhatsApp
                }


                $('#template').val(templateContent); // Set the hidden field with content
                $('#editTemplateForm').submit(); // Submit the form
            });
        }

        // When the modal is hidden, clear the fields and editor
        $('#editTemplateModal').on('hidden.bs.modal', function() {
            $('#template-editor').val(''); // Clear textarea content
            tinymce.remove('#template-editor'); // Remove the TinyMCE editor instance
        });

        // Initialize the rich HTML editor (for email templates)
        function initializeHTMLEditor(selector) {
            tinymce.remove(selector);
            tinymce.init({
                selector: selector,
                menubar: false,
                plugins: "lists link image table code",
                toolbar: "undo redo | styleselect | fontsize | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code",
                fontsize_formats: "8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt", // Define font sizes
            });
        }

        // Initialize a simple text editor (for SMS and WhatsApp templates)
        function initializePlainTextEditor(selector) {
            tinymce.remove(selector);
            tinymce.init({
                selector: selector,
                toolbar: 'undo redo', // Minimal toolbar for text-only editors
                menubar: false,
                // forced_root_block: false, // Prevent <p> tags
                // entity_encoding: 'raw', // Output plain text (no HTML)
                // valid_elements: 'none', // Restrict allowed elements (no HTML)
                content_style: "body { font-family: Arial; font-size: 14px; }" // Basic styling
            });
        }

        // WhatsApp-specific custom behavior (bold, italic, strikethrough)
        // WhatsApp-specific custom behavior (bold, italic, strikethrough, monospace, lists)
        function preserveLineBreaks(content) {
            return content.replace(/\n/g, '<br>');
        }

        function initializeWhatsAppEditor(selector) {
            tinymce.remove(selector);
            tinymce.init({
                selector: selector,
                menubar: false,
                icons: 'default',
                toolbar: 'cbold citalic cstrikethrough cmonospace cquote | undo redo', // Added more buttons
                setup: function(editor) {
                    editor.on('init', function() {
                        editor.contentStyles.push(`
                            .editor-hide {
                                display: none;
                            }
                        `);
                    });
                    // Bold button for WhatsApp (*text*)
                    editor.ui.registry.addButton('cbold', {
                        icon: 'bold',
                        tooltip: 'Bold (WhatsApp-style)',
                        shortcut: 'meta+b',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {
                                let formattedText = '<b><span class="editor-hide">*</span>' +
                                    selectedText + '<span class="editor-hide">*</span></b>';
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });
                    // Custom shortcut for bold (Ctrl+B or Cmd+B)
                    editor.shortcuts.add('meta+b', 'Bold (WhatsApp-style)', function() {
                        let selectedText = editor.selection.getContent({
                            format: 'text'
                        });
                        if (selectedText) {
                            let formattedText = '<b><span class="editor-hide">*</span>' +
                                selectedText + '<span class="editor-hide">*</span></b>';
                            editor.selection.setContent(formattedText);
                        }
                    });

                    // Italic button for WhatsApp (_text_)
                    editor.ui.registry.addButton('citalic', {
                        icon: 'italic',
                        tooltip: 'Italic (WhatsApp-style)',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {

                                let formattedText = '<em><span class="editor-hide">_</span>' +
                                    selectedText + '<span class="editor-hide">_</span></em>';
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });

                    // Strikethrough button for WhatsApp (~text~)
                    editor.ui.registry.addButton('cstrikethrough', {
                        icon: 'strike-through',
                        tooltip: 'Strikethrough (WhatsApp-style)',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {

                                let formattedText = '<strike><span class="editor-hide">~</span>' +
                                    selectedText + '<span class="editor-hide">~</span></strike>';
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });

                    // Monospace button for WhatsApp (`text`)
                    editor.ui.registry.addButton('cmonospace', {
                        icon: 'sourcecode',
                        tooltip: 'Monospace (WhatsApp-style)',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {
                                // let formattedText = '```' + selectedText + '```';
                                let formattedText = '<code><span class="editor-hide">```</span>' +
                                selectedText + '<span class="editor-hide">```</span></code>';
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });

                    // Unordered list (bullet points)
                    editor.ui.registry.addButton('cbulletedlist', {
                        icon: 'unordered-list',
                        tooltip: 'Bulleted list',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {
                                // Split text by newlines and add a bullet to each line
                                let formattedText = selectedText.split('\n').map(line => '* ' +
                                    line + '\n').join('\n');

                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });


                    // Ordered list (numbered points)
                    editor.ui.registry.addButton('cnumberedlist', {
                        icon: 'ordered-list',
                        tooltip: 'Numbered list',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {
                                // Split text by newlines and add a number to each line
                                let lines = selectedText.split('\n');
                                let formattedText = lines.map((line, index) => (index + 1) + '. ' +
                                    line).join('\n');
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });


                    editor.ui.registry.addButton('cquote', {
                        icon: 'quote',
                        tooltip: 'Quote',
                        onAction: function() {
                            let selectedText = editor.selection.getContent({
                                format: 'text'
                            });
                            if (selectedText) {
                                // let formattedText = '> ' + selectedText;
                                let formattedText =
                                    '<blockquote><span class="editor-hide">> </span>' +
                                    selectedText + '</blockquote>';
                                editor.selection.setContent(
                                    formattedText); // Replace with formatted text
                            }
                        }
                    });



                },
                // forced_root_block: false, // Prevent wrapping content in <p> tags
                // entity_encoding: 'named', // Raw text encoding
                // content_style: "body { font-family: Arial; font-size: 14px; }",
                plugins: 'paste',
                // paste_as_text: true, // Paste as plain text
                // formats: {
                //     removeformat: [{
                //             selector: 'b,strong,em,i,strike',
                //             remove: 'all',
                //             split: true,
                //             expand: false,
                //             deep: true
                //         },
                //         {
                //             selector: 'span',
                //             attributes: ['style'],
                //             remove: 'empty'
                //         }
                //     ]
                // },
                init_instance_callback: function(editor) {
                    let initialContent = preserveLineBreaks(editor.getContent()); // Convert newlines to <br>
                    editor.setContent(initialContent);
                }
            });
        }
    </script>
@endsection
