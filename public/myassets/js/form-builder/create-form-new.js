// remove commented codes and logs

$(document).ready(function () {
    // Sample default items (replace this with your actual data if needed)
    const form_data_json = $("#form_data_json").val();
    const defaultItems = JSON.parse(form_data_json);

    // Function to add default items to the canvas
    function addDefaultItems() {
        defaultItems.forEach((item) => {
            const { type, config } = item;
            const newItem = $("<div>")
                .addClass("canvas-element")
                .data("type", type);
            $(".drop-container").append(newItem);
            addElementToCanvas(type, newItem, item);
        });

        // Hide placeholder if items are added
        if ($(".drop-container").children().length > 0) {
            $(".sortable-placeholder").hide();
        }
    }

    // Call the function to add default items
    addDefaultItems();
    $(".draggable").draggable({
        helper: "clone",
        revert: "invalid",
        connectToSortable: ".drop-container",
    });

    // Initialize the sortable functionality
    $(".drop-container").sortable({
        placeholder: "sortable-placeholder",
        start: function (event, ui) {
            // Hide the placeholder when dragging starts
            $(".sortable-placeholder").hide();
        },
        stop: function (event, ui) {
            // Show the placeholder if there are no items left
            if (
                $(".drop-container").children().not(".sortable-placeholder")
                    .length === 0
            ) {
                $(".sortable-placeholder").show();
            }

            // Check if the item being sorted is not already part of the canvas
            const type = ui.item.data("type");
            // Check if the type is product and if there's already a product in the drop-container
            if (
                type === "product" &&
                $(".drop-container").find(".product-element").length > 0
            ) {
                // If a product already exists, show the properties form for the existing product and remove the new one
                const existingProduct =
                    $(".drop-container").find(".product-element");
                showPropertiesForm("product", existingProduct);
                // Add the tooltip to the existing product
                existingProduct.attr(
                    "title",
                    "You Already have a product element in the form. select products from the properties section"
                );
                existingProduct.attr("data-bs-toggle", "tooltip");

                // Initialize the tooltip
                const tooltip = new bootstrap.Tooltip(existingProduct[0]);
                tooltip.show(); // Show the tooltip

                // Set a timeout to dispose of the tooltip after it shows
                setTimeout(() => {
                    tooltip.dispose(); // Remove the tooltip instance
                    existingProduct.removeAttr("title"); // Remove the title attribute
                    existingProduct.removeAttr("data-bs-toggle"); // Remove the tooltip attribute
                }, 5000);
                ui.item.remove(); // Remove the dragged item as we won't add another product
            } else {
                // If no product exists, add a new one
                addElementToCanvas(type, ui.item);
            }
        },
        receive: function (event, ui) {
            // Remove the placeholder when an item is received
            $(".sortable-placeholder").hide();
        },
    });

    $(".draggable").on("click", function () {
        const type = $(this).data("type");
        // Check if the type is product and if there's already a product in the drop-container
        if (
            type === "product" &&
            $(".drop-container").find(".product-element").length > 0
        ) {
            // If a product already exists, show the properties form for the existing product
            const existingProduct =
                $(".drop-container").find(".product-element");
            showPropertiesForm("product", existingProduct);

            // Add the tooltip to the existing product
            existingProduct.attr(
                "title",
                "You Already have a product element in the form. select products from the properties section"
            );
            existingProduct.attr("data-bs-toggle", "tooltip");

            // Initialize the tooltip
            const tooltip = new bootstrap.Tooltip(existingProduct[0]);
            tooltip.show(); // Show the tooltip
            // Set a timeout to dispose of the tooltip after it shows
            setTimeout(() => {
                tooltip.dispose(); // Remove the tooltip instance
                existingProduct.removeAttr("title"); // Remove the title attribute
                existingProduct.removeAttr("data-bs-toggle"); // Remove the tooltip attribute
            }, 5000);
        } else {
            // If no product exists, add a new one
            const item = $(this).clone();
            $(".drop-container").append(item);
            $(".sortable-placeholder").hide();
            addElementToCanvas(type, item);
        }
    });

    function addElementToCanvas(type, item, data) {
        let element, label;

        switch (type) {
            case "text":
                element = $(
                    '<div class="canvas-element col-sm-12"><span class="item-remove text-center"><i class="bi bi-x-lg" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="canvas-content text-field-content" contenteditable="true">Editable Text</div> <span class="item-move text-center"><i class="bi bi-grip-vertical" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                );
                break;
            case "form":
                label = data?.config?.label ?? getNextLabelName("Field");
                element = $(
                    '<div class="canvas-element col-sm-12"><span class="item-remove text-center"><i class="bi bi-x-lg" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="form-group w-100"><div class="form-label text-field-content" contenteditable="true">' +
                        label +
                        '</div><input class="canvas-input canvas-content form-control" type="text" placeholder="Enter value"></div> <span class="item-move text-center"><i class="bi bi-grip-vertical" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                );
                break;
            case "image":
                element = $(
                    '<div class="canvas-element col-sm-12"><span class="item-remove text-center"><i class="bi bi-x-lg" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><img class="canvas-img canvas-content img-fluid" src="https://via.placeholder.com/150" alt="Image" style="width:100px;height:100px;"> <span class="item-move text-center"><i class="bi bi-grip-vertical" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                );
                break;
            case "product":
                label = data?.config?.label ?? getNextLabelName("Product");
                // Add a class "product-element" to identify product elements
                element = $(
                    '<div class="canvas-element col-sm-12 product-element"><span class="item-remove text-center"><i class="bi bi-x-lg" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><div class="product-label text-field-content" contenteditable="true">' +
                        label +
                        '</div><div class="product-container canvas-content row"><label for="package0" class="product_field form-label  me-3 product-item p-3 rounded shadow-sm"><span class="me-1 product-title">Add products to display here</span></label></div> <span class="item-move text-center"><i class="bi bi-grip-vertical" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                );
                break;
            case "seperator":
                element = $(
                    '<div class="canvas-element col-sm-12"><span class="item-remove text-center"><i class="bi bi-x-lg" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Remove item from form"></i></span><hr class="canvas-content canvas-element-seperator"> <span class="item-move text-center"><i class="bi bi-grip-vertical" data-bs-toggle="tooltip" data-bs-placement="auto" data-bs-title="Drag item to another position"></i></span></div>'
                );
                break;

            default:
                return;
        }

        element.attr("data-config", JSON.stringify(data));

        item.replaceWith(element);
        showPropertiesForm(type, element);

        element.on("click", function () {
            showPropertiesForm(type, element);
        });
    }

    function getBootstrapColumnClass(classString) {
        if (classString) {
            // Use a regular expression to match 'col-sm-*'
            const bootstrapClasses = classString.match(/col-sm-\d+/g);

            // Return the first match if found
            if (bootstrapClasses) {
                return bootstrapClasses[0]?.trim(); // Return the first matching class
            }
        }

        return null; // Return null if no class is found
    }

    function showPropertiesForm(type, canvasElement) {
        let element = canvasElement.find(".canvas-content");

        var data = canvasElement?.data("config");
        let column_width = getBootstrapColumnClass(
            data?.config?.column_width ??
                element?.find(".product-wrapper").first().attr("class")
        );

        $("#form-properties-tab").click();
        const form = $("#form-properties");
        form.empty();
        $(".properties-placeholder").hide();

        // Common style fields for all elements
        form.append(`<h6>Attributes</h6>`);

        $("#element-class").on("input", function () {
            element.attr("class", $(this).val());
        });
        $("#element-margin").on("input", function () {
            element.css("margin", $(this).val());
        });
        $("#element-padding").on("input", function () {
            element.css("padding", $(this).val());
        });

        // Initialize first to set the default value

        var defaultFields = `
            <label class="propertiy-label">Column width:</label>
            <select class="form-control form-control-sm" id="column-size">
                <option value="col-sm-12" ${
                    column_width == "col-sm-12" ? "selected" : ""
                }>Full width</option>
                <option value="col-sm-6" ${
                    column_width == "col-sm-6" ? "selected" : ""
                }>Width 1/2</option>
                <option value="col-sm-4" ${
                    column_width == "col-sm-4" ? "selected" : ""
                }>Width 1/3</option>
                <option value="col-sm-3" ${
                    column_width == "col-sm-3" ? "selected" : ""
                }>Width 1/4</option>
                <option value="col-sm-2" ${
                    column_width == "col-sm-2" ? "selected" : ""
                }>Width 1/6</option>
                <option value="col-sm-8" ${
                    column_width == "col-sm-8" ? "selected" : ""
                }>Width 2/3</option>
                <option value="col-sm-9" ${
                    column_width == "col-sm-9" ? "selected" : ""
                }>Width 3/4</option>
                <option value="col-sm-10" ${
                    column_width == "col-sm-10" ? "selected" : ""
                }>Width 5/6</option>
            </select>
            <label class="propertiy-label">Margin:</label>
            <div class="input-group input-group-sm mb-2">
                <span>Top</span>
                <input class="form-control form-control-sm" type="number" id="margin-top" placeholder="Top" value="${element
                    .parents(".canvas-element")
                    ?.css("margin-top")
                    ?.replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Right</span>
                <input class="form-control form-control-sm" type="number" id="margin-right" placeholder="Right" value="${element
                    .parents(".canvas-element")
                    ?.css("margin-right")
                    ?.replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Bottom</span>
                <input class="form-control form-control-sm" type="number" id="margin-bottom" placeholder="Bottom" value="${element
                    .parents(".canvas-element")
                    ?.css("margin-bottom")
                    ?.replace("px", "")}">
                <span>px</span>
            </div>
            <div class="input-group input-group-sm mb-2">
                <span>Left</span>
                <input class="form-control form-control-sm" type="number" id="margin-left" placeholder="Left" value="${element
                    .parents(".canvas-element")
                    ?.css("margin-left")
                    ?.replace("px", "")}">
                <span>px</span>
            </div>
        `;

        // Append the #column-size field last to maintain its visual order
        form.append(defaultFields);

        // $("#column-size").trigger("change");
        // $("#margin-top").trigger("input");
        // $("#margin-right").trigger("input");
        // $("#margin-bottom").trigger("input");
        // $("#margin-left").trigger("input");

        $("#column-size").on("change", function () {
            if (type == "product") {
                element
                    .find(".product-wrapper")
                    ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
                    .addClass($(this).val());
            } else if (type == "separator") {
                element
                    .parents(".canvas-element")
                    ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3");
            } else {
                element
                    .parents(".canvas-element")
                    ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
                    .addClass($(this).val());
            }

            updateConfig(element, type, {
                column_width: $(this).val(),
            });
        });

        $("#margin-top").on("input", function () {
            element
                .parents(".canvas-element")
                ?.css("margin-top", $(this).val() + "px");
            updateConfig(element, type, {
                marginTop: $(this).val() + "px",
            });
        });
        $("#margin-right").on("input", function () {
            element
                .parents(".canvas-element")
                ?.css("margin-right", $(this).val() + "px");
            updateConfig(element, type, {
                marginRight: $(this).val() + "px",
            });
        });
        $("#margin-bottom").on("input", function () {
            element
                .parents(".canvas-element")
                ?.css("margin-bottom", $(this).val() + "px");
            updateConfig(element, type, {
                marginBottom: $(this).val() + "px",
            });
        });
        $("#margin-left").on("input", function () {
            element
                .parents(".canvas-element")
                ?.css("margin-left", $(this).val() + "px");
            updateConfig(element, type, {
                marginLeft: $(this).val() + "px",
            });
        });

        // Specific fields based on the type
        if (type === "text" && element.attr("contenteditable")) {
            text_field(form, element);
        }

        if (type === "product") {
            product_field(form, element);
        }

        if (type === "form") {
            form_field(form, element);
        }

        if (type === "seperator") {
            seperator_field(form, element);
        }

        if (type === "image") {
            image_field(form, element);
        }
    }
    // Function to get the next available label name
    function getNextLabelName(baseLabel) {
        // Retrieve the JSON data from the input field
        const formDataJson = document.getElementById("form_data_json").value;
        const formData = JSON.parse(formDataJson);
        // Get all existing labels from the form data
        const existingLabels = formData.map((item) => item.config.label);

        // Check if the base label already exists
        if (!existingLabels.includes(baseLabel)) {
            return baseLabel; // If not, return the base label itself
        }

        // If it exists, increment the number until we find an available label name
        let counter = 2;
        while (existingLabels.includes(`${baseLabel} ${counter}`)) {
            counter++;
        }

        return `${baseLabel} ${counter}`;
    }

    $(document).on("click", ".item-remove i", function () {
        $(this).parents(".canvas-element").remove();
        const form = $("#form-properties");
        form.empty();
        $(".properties-placeholder").show();
        if (
            $(".drop-container").children().not(".sortable-placeholder")
                .length === 0
        ) {
            $(".sortable-placeholder").show();
        }
        collectFormConfig();
    });

    $(document).on("click", "#preview-code", function () {
        alert("modal open");
        //Open the paste modal #pasteModal
        $("#pasteModal").modal("show");

        var inputValue = $("#form_data_json").val();
        $("#jsonInput").val(inputValue);
    });
    function placeCaretOnClick(e) {
        // Explicitly set focus on the clicked element
        $(this).focus();

        // Get the clicked element (contenteditable div)
        const contentEditableElement = this;

        // Create a range and selection
        const range = document.createRange();
        const selection = window.getSelection();

        // Get the caret position based on the click location
        const x = e.clientX;
        const y = e.clientY;

        // Use only `caretRangeFromPoint` for better browser compatibility
        const caretPosition = document.caretRangeFromPoint
            ? document.caretRangeFromPoint(x, y)
            : null;

        if (caretPosition) {
            // Set the start of the range at the position clicked
            range.setStart(
                caretPosition.startContainer,
                caretPosition.startOffset
            );
            range.collapse(true);

            // Clear previous selection
            selection.removeAllRanges();
            // Add the new range
            selection.addRange(range);
        } else {
            console.warn("Caret position could not be determined.");
        }
    }

    // Function to update the data-config attribute
    function updateConfig(element, type, current) {
        var canvasElement = element.parents(".canvas-element");
        let config = {};

        // For different types, set the config properties accordingly
        switch (type) {
            case "form":
                let size;
                if (element.hasClass("form-control-sm")) {
                    size = "sm";
                } else if (element.hasClass("form-control-lg")) {
                    size = "lg";
                } else {
                    size = "md";
                }

                let options;
                if (element.is("select")) {
                    options =
                        element
                            .find("option")
                            .map(function () {
                                return $(this).text();
                            })
                            .get() ?? [];
                } else if (
                    element.find('input[type="radio"], input[type="checkbox"]')
                        .length > 0
                ) {
                    options =
                        element
                            // .closest("form") // Search within the closest form to find all radio/checkbox options with the same name
                            .find(`input[name="${element.attr("name")}"]`)
                            .map(function () {
                                return (
                                    $(this).next("label").text() ||
                                    $(this).val()
                                ); // Try to find the associated label or use the value as a fallback
                            })
                            .get() ?? [];
                }

                config = {
                    label: element.prev(".form-label").text() || "",
                    size: size,
                    type: element.attr("type") || "text",
                    placeholder: element.attr("placeholder") || "",
                    defaultValue: element.val() || "",
                    required: element.attr("isRequired") || false,
                    options: options,
                    name:
                        element
                            .prev(".form-label")
                            .text()
                            .replace(/\s+/g, "_")
                            .toLowerCase() || "",
                };

                break;

            case "text":
                config = {
                    // mode: "simple",
                    mode: element.attr("data-mode") || "simple",
                    color: element.css("color") || "",
                    fontWeight: element.css("font-weight") || "",
                    fontFamily: element.css("font-family") || "",
                    fontSize: element.css("font-size") || "",
                    fontStyle: element.css("font-style") || "",
                    textAlign: element.css("text-align") || "",
                    textTransform: element.css("text-transform") || "",
                    textDecoration: element.css("text-decoration") || "",
                    content: element.text() || "",
                };
                break;

            case "image":
                config = {
                    width: element.css("width") || "",
                    height: element.css("height") || "",
                    textAlign:
                        element.parents(".canvas-element")?.css("text-align") ||
                        "",
                    src: element.attr("src") || "",
                };
                break;

            case "seperator":
                config = {
                    width: element.css("width") || "",
                    height: element.css("height") || "",
                };
                break;

            case "product":
                config = {
                    package_choice: element.css("width") || "",
                    label: element.prev(".product-label").text() || "",
                    selected_package: element.data("selected-package") || [],
                };

                break;

            default:
                type != "*" ? console.warn("Unknown type:", type) : "";
        }

        // Add properties common for all types
        if (type == "product") {
            config.column_width = getBootstrapColumnClass(
                element?.find(".product-wrapper").first()?.attr("class")
            );
        } else {
            config.column_width = getBootstrapColumnClass(
                element.parents(".canvas-element")?.attr("class")
            );
        }

        config.marginBottom =
            element.parents(".canvas-element")?.css("margin-bottom") || "";
        config.marginTop =
            element.parents(".canvas-element")?.css("margin-top") || "";
        config.marginLeft =
            element.parents(".canvas-element")?.css("margin-left") || "";
        config.marginRight =
            element.parents(".canvas-element")?.css("margin-right") || "";

        // Merge the current object into the config object
        Object.assign(config, current);
        if (type == "text") {
            if (config.mode == "simple") {
                config.content = element.text();
            } else {
                config.content = element.html();
            }
        }

        let data = {
            type: type,
            config: config,
        };

        // Update the attribute with the new configuration
        canvasElement.attr("data-config", JSON.stringify(data));
        collectFormConfig();
    }

    function collectFormConfig() {
        // Find all elements with the class 'canvas-element' inside the container with class 'drop-container'
        const elements = document.querySelectorAll(
            ".drop-container .canvas-element"
        );
        const formConfig = [];

        elements.forEach((element) => {
            // Retrieve the config data from the element's dataset (if available) or use its tag name
            const config =
                element.dataset.config || element.tagName.toLowerCase();

            formConfig.push(JSON.parse(config));
        });

        // Ensure the element with ID 'form_data_json' exists before attempting to set its value
        const formDataElement = document.querySelector("#form_data_json");
        if (formDataElement) {
            formDataElement.value = JSON.stringify(formConfig);
        } else {
            console.warn("Element with ID 'form_data_json' not found.");
        }
    }

    function text_field(form, element) {
        element.attr("contenteditable", "true");
        var canvasElement = element.parents(".canvas-element");
        var data = canvasElement?.data("config");

        let config;
        if (data?.config) {
            config = data?.config;
        } else {
            config = {
                mode: element.data("mode") || "simple",
                color: element.data("color") || "",
                fontWeight: element.css("font-weight") || "",
                fontFamily: "default",
                fontSize: element.css("font-size") || "",
                fontStyle: element.css("font-style") || "normal",
                textAlign: "start",
                textTransform: element.css("text-transform") || "",
                textDecoration: element.css("text-decoration") || "",
                content: element.text() || "",
                column_width: getBootstrapColumnClass(
                    element.parents(".canvas-element")?.attr("class")
                ),
                marginBottom:
                    element.parents(".canvas-element")?.css("margin-bottom") ||
                    "",
                marginTop:
                    element.parents(".canvas-element")?.css("margin-top") || "",
                marginLeft:
                    element.parents(".canvas-element")?.css("margin-left") ||
                    "",
                marginRight:
                    element.parents(".canvas-element")?.css("margin-right") ||
                    "",
            };

            if (config.mode == "simple") {
                config.content = element.text();
            } else {
                config.content = element.html();
            }
        }

        console.log("Loaded Mode:", config.mode);
        console.log("Loaded content:", config.content);

        //Load default if available

        element
            .parents(".canvas-element")
            ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
            ?.addClass(config.column_width);
        if (config.mode == "simple") {
            element.text(config.content);
        } else {
            element.html(config.content);
        }
        element.attr("data-mode", config.mode);
        element
            .css({
                color: config.color,
                "font-weight": config.fontWeight,
                "font-family": config.fontFamily,
                "font-size": config.fontSize,
                "font-style": config.fontStyle,
                "text-align": config.textAlign,
                "text-transform": config.textTransform,
                "text-decoration": config.textDecoration,
            })
            .parents(".canvas-element")
            .css({
                "margin-bottom": config.marginBottom,
                "margin-top": config.marginTop,
                "margin-left": config.marginLeft,
                "margin-right": config.marginRight,
            });

        // Initial HTML structure with a toggle for Simple/Advanced
        form.prepend(`
    <div class="toggle-editor">
        <label class="propertiy-label">Editor Mode:</label>
        <select class="form-control form-control-sm" id="editor-mode">
            <option value="simple" ${
                config.mode == "simple" ? "selected" : ""
            }>Simple Text</option>
            <option value="advanced" ${
                config.mode == "advanced" ? "selected" : ""
            }>Advanced Text</option>
        </select>
    </div>

    <div id="simple-editor" class="editor-content">
        <label class="propertiy-label">Content:</label>
        <textarea class="form-control form-control-sm" id="text-content">${element.text()}</textarea>
        <h6>Attributes</h6>
        <label class="propertiy-label">Text Color:</label>
        <input class="form-control form-control-sm" type="color" id="text-color" value="${element.css(
            "color"
        )}">
        <label class="propertiy-label">Text Size:</label>
        <div class="input-group input-group-sm mb-2">
            <input class="form-control form-control-sm" type="number" id="font-size" value="${element
                .css("font-size")
                .replace("px", "")}">
            <span>px</span>
        </div>
<label class="propertiy-label">Font Family:</label>
    <select class="form-control form-control-sm" id="text-font">
    <option value="Default" ${
        config.fontFamily == "Default" ? "selected" : ""
    }>Default</option>
        <option value="Arial" ${
            config.fontFamily == "Arial" ? "selected" : ""
        }>Arial</option>
        <option value="Times New Roman" ${
            config.fontFamily == "Times New Roman" ? "selected" : ""
        }>Times New Roman</option>
        <option value="Georgia" ${
            config.fontFamily == "Georgia" ? "selected" : ""
        }>Georgia</option>
        <option value="Courier New" ${
            config.fontFamily == "Courier New" ? "selected" : ""
        }>Courier New</option>
        <option value="Verdana" ${
            config.fontFamily == "Verdana" ? "selected" : ""
        }>Verdana</option>
    </select>
    
    <label class="propertiy-label">Text Weight:</label>
    <select class="form-control form-control-sm" id="text-weight">
        <option value="normal" ${
            config.fontWeight == "normal" ? "selected" : ""
        }>Normal</option>
        <option value="bold" ${
            config.fontWeight == "bold" ? "selected" : ""
        }>Bold</option>
        <option value="bolder" ${
            config.fontWeight == "bolder" ? "selected" : ""
        }>Bolder</option>
        <option value="lighter" ${
            config.fontWeight == "lighter" ? "selected" : ""
        }>Lighter</option>
        <option value="100" ${
            config.fontWeight == "100" ? "selected" : ""
        }>100 - Thin</option>
        <option value="200" ${
            config.fontWeight == "200" ? "selected" : ""
        }>200 - Extra Light</option>
        <option value="300" ${
            config.fontWeight == "300" ? "selected" : ""
        }>300 - Light</option>
        <option value="400" ${
            config.fontWeight == "400" ? "selected" : ""
        }>400 - Normal</option>
        <option value="500" ${
            config.fontWeight == "500" ? "selected" : ""
        }>500 - Medium</option>
        <option value="600" ${
            config.fontWeight == "600" ? "selected" : ""
        }>600 - Semi Bold</option>
        <option value="700" ${
            config.fontWeight == "700" ? "selected" : ""
        }>700 - Bold</option>
        <option value="800" ${
            config.fontWeight == "800" ? "selected" : ""
        }>800 - Extra Bold</option>
        <option value="900" ${
            config.fontWeight == "900" ? "selected" : ""
        }>900 - Black</option>
    </select>
    
    <label class="propertiy-label">Text Style:</label>
    <select class="form-control form-control-sm" id="text-style">
        <option value="normal" ${
            config.fontStyle == "normal" ? "selected" : ""
        }>Normal</option>
        <option value="italic" ${
            config.fontStyle == "italic" ? "selected" : ""
        }>Italic</option>
        <option value="oblique" ${
            config.fontStyle == "oblique" ? "selected" : ""
        }>Oblique</option>
        <option value="underline" ${
            config.fontStyle == "underline" ? "selected" : ""
        }>Underline</option>
        <option value="line-through" ${
            config.fontStyle == "line-through" ? "selected" : ""
        }>Line-through</option>
        <option value="overline" ${
            config.fontStyle == "overline" ? "selected" : ""
        }>Overline</option>
        <option value="none" ${
            config.fontStyle == "none" ? "selected" : ""
        }>None</option>
        <option value="uppercase" ${
            config.fontStyle == "uppercase" ? "selected" : ""
        }>Uppercase</option>
        <option value="lowercase" ${
            config.fontStyle == "lowercase" ? "selected" : ""
        }>Lowercase</option>
        <option value="capitalize" ${
            config.fontStyle == "capitalize" ? "selected" : ""
        }>Capitalize</option>
    </select>
    
    <label class="propertiy-label">Alignment:</label>
    <select class="form-control form-control-sm" id="text-alignment">
        <option value="left" ${
            config.textAlign == "left" ? "selected" : ""
        }>Left</option>
         <option value="Start" ${
             config.textAlign == "Start" ? "selected" : ""
         }>Start</option>
        <option value="center" ${
            config.textAlign == "center" ? "selected" : ""
        }>Center</option>
        <option value="right" ${
            config.textAlign == "right" ? "selected" : ""
        }>Right</option>
        <option value="End" ${
            config.textAlign == "End" ? "selected" : ""
        }>End</option>
        <option value="justify" ${
            config.textAlign == "justify" ? "selected" : ""
        }>Justify</option>
    </select>
    </div>

    <div id="advanced-editor" class="editor-content" style="display: none;">
        <label class="propertiy-label">Content:</label>
        <textarea id="tinymce-editor">${element.html()}</textarea>
    </div>
    


`);

        // Toggle between simple and advanced editors
        $("#editor-mode").on("change", function () {
            const mode = $(this).val();
            if (mode === "simple") {
                $("#simple-editor").show();
                $("#advanced-editor").hide();
            } else {
                $("#simple-editor").hide();
                $("#advanced-editor").show();

                // Make sure the content matches when switching to TinyMCE mode
                if (tinymce.get("tinymce-editor")) {
                    tinymce.get("tinymce-editor").setContent(element.html());
                } else {
                    reinitializeTinyMCE(); // Reinitialize if TinyMCE instance is not found
                }
            }
            element.attr("data-mode", mode);

            config.mode = mode;
            updateConfig(element, "text", {
                mode: mode,
            });
        });

        if (config.mode == "simple") {
            $("#simple-editor").show();
            $("#advanced-editor").hide();
        } else {
            $("#simple-editor").hide();
            $("#advanced-editor").show();

            // Make sure the content matches when switching to TinyMCE mode
            if (tinymce.get("tinymce-editor")) {
                tinymce.get("tinymce-editor").setContent(element.html());
            } else {
                reinitializeTinyMCE(); // Reinitialize if TinyMCE instance is not found
            }
        }

        // Simple Editor Event Listeners

        element.on("blur", function () {
            if (tinymce.get("tinymce-editor")) {
                // Update the content through TinyMCE
                tinymce.get("tinymce-editor").setContent(element.html());
                updateConfig(element, "text", {
                    content: element.html(),
                });
            }
        });

        $("#text-content").on("input", function () {
            element.text($(this).val());
            config.content = $(this).val();
            updateConfig(element, "text", {
                content: config.content,
            });
        });

        // element.on("input", function (e) {
        //     e.stopPropagation();

        //     if ($("#editor-mode").val() == "simple") {
        //         $("#text-content").val($(this).text());
        //         updateConfig(element, "text", { content: $(this).text() });
        //     } else {
        //         tinymce.get("tinymce-editor").setContent($(this).html());
        //         updateConfig(element, "text", { content: $(this).html() });
        //     }
        //     $(this).trigger("change");
        // });

        element.off("input").on("input", function (e) {
            e.stopPropagation();
            // Code specific to this element
            if ($("#editor-mode").val() == "simple") {
                $("#text-content").val($(this).text());
                updateConfig(element, "text", { content: $(this).text() });
            } else {
                tinymce.get("tinymce-editor").setContent($(this).html());
                updateConfig(element, "text", { content: $(this).html() });
            }
            $(this).trigger("change");
        });

        element.attr("tabindex", "0");
        // Now, this should work as expected
        element.focus();
        // element.on("focus", function (e) {
        //     placeCaretOnClick(e);
        //     console.log("hhh");
        // });

        // element.on("focus", placeCaretOnClick));

        $("#text-color").on("input", function () {
            element.css("color", $(this).val());
            config.color = $(this).val();
            updateConfig(element, "text", {
                color: config.color,
            });
        });

        $("#font-size").on("input", function () {
            element.css("font-size", $(this).val() + "px");
            config.fontSize = $(this).val() + "px";
            updateConfig(element, "text", {
                fontSize: config.fontSize,
            });
        });

        $("#text-font").val(element.css("font-family"));
        $("#text-font").on("change", function () {
            element.css("font-family", $(this).val());
            config.fontFamily = $(this).val();
            updateConfig(element, "text", {
                fontFamily: config.fontFamily,
            });
        });

        $("#text-alignment").val(element.css("text-align"));
        $("#text-alignment").on("change", function () {
            element.css("text-align", $(this).val());
            config.textAlign = $(this).val();
            updateConfig(element, "text", {
                textAlign: config.textAlign,
            });
        });

        $("#text-weight").val(element.css("font-weight"));
        $("#text-weight").on("change", function () {
            element.css("font-weight", $(this).val());
            config.fontWeight = $(this).val();
            updateConfig(element, "text", {
                fontWeight: config.fontWeight,
            });
        });

        $("#text-style").val(getInitialTextStyle(element));
        $("#text-style").on("change", function () {
            const selectedStyle = $(this).val();
            element.css({
                "font-style": "normal",
                "text-decoration": "none",
                "text-transform": "none",
            });
            switch (selectedStyle) {
                case "italic":
                case "oblique":
                    element.css("font-style", selectedStyle);
                    config.fontStyle = selectedStyle;
                    updateConfig(element, "text", {
                        fontStyle: selectedStyle,
                    });
                    break;
                case "underline":
                case "line-through":
                case "overline":
                    element.css("text-decoration", selectedStyle);
                    config.textDecoration = selectedStyle;
                    updateConfig(element, "text", {
                        textDecoration: selectedStyle,
                    });
                    break;
                case "uppercase":
                case "lowercase":
                case "capitalize":
                    element.css("text-transform", selectedStyle);
                    config.textTransform = selectedStyle;
                    updateConfig(element, "text", {
                        textTransform: selectedStyle,
                    });
                    break;
                case "normal":
                default:
                    break;
            }
        });

        function reinitializeTinyMCE() {
            // Destroy the existing editor instance
            tinymce.remove("#tinymce-editor");

            // Reinitialize TinyMCE
            tinymce.init({
                selector: "#tinymce-editor",
                menubar: false,
                plugins: "lists link image table code",
                toolbar:
                    "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code",
                setup: function (editor) {
                    editor.on("init", function () {
                        // Set the content to match the element again after reinitialization
                        editor.setContent(element.html());
                    });

                    editor.on("keyup change", function () {
                        element.html(editor.getContent());
                        config.content = editor.getContent();
                        updateConfig(element, "text", {
                            content: editor.getContent(),
                        });
                    });
                },
            });
        }

        // Call this function whenever you directly edit the element's HTML
        reinitializeTinyMCE();

        // Helper function to get initial text style
        function getInitialTextStyle(element) {
            const fontStyle = element.css("font-style");
            const textDecoration = element.css("text-decoration");
            const textTransform = element.css("text-transform");

            if (fontStyle === "italic" || fontStyle === "oblique") {
                return fontStyle;
            } else if (textDecoration.includes("underline")) {
                return "underline";
            } else if (textDecoration.includes("line-through")) {
                return "line-through";
            } else if (textDecoration.includes("overline")) {
                return "overline";
            } else if (textTransform === "uppercase") {
                return "uppercase";
            } else if (textTransform === "lowercase") {
                return "lowercase";
            } else if (textTransform === "capitalize") {
                return "capitalize";
            }

            return "normal";
        }

        updateConfig(element, "text", config);
    }

    function product_field(form, element) {
        var canvasElement = element.parents(".canvas-element");
        var data = canvasElement?.data("config");
        let config = data?.config
            ? data?.config
            : {
                  label: element.prev(".product-label").text() || "",
                  package_choice: "package_single",
                  selected_package: [],
                  column_width: getBootstrapColumnClass(
                      element?.find(".product-wrapper").first()?.attr("class")
                  ),
                  marginBottom:
                      element
                          .parents(".canvas-element")
                          ?.css("margin-bottom") || "",
                  marginTop:
                      element.parents(".canvas-element")?.css("margin-top") ||
                      "",
                  marginLeft:
                      element.parents(".canvas-element")?.css("margin-left") ||
                      "",
                  marginRight:
                      element.parents(".canvas-element")?.css("margin-right") ||
                      "",
              };

        //Load default if available
        element.prev(".product-label").text(config.label);
        element
            .parents(".canvas-element")
            ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
            ?.addClass("col-sm-12");
        element.parents(".canvas-element").css({
            "margin-bottom": config.marginBottom,
            "margin-top": config.marginTop,
            "margin-left": config.marginLeft,
            "margin-right": config.marginRight,
        });
        var container = $("<div>");
        container.attr({
            class: "mb-3 w-100",
        });
        // Ensure config.selected_package is an array; if not, initialize it to an empty array
        const selectedPackages = Array.isArray(config.selected_package)
            ? config.selected_package
            : [];

        // Add the initial three select fields by default
        const numToCreate = Math.max(3 - selectedPackages.length, 0);

        // Add select fields based on items in selectedPackages
        selectedPackages.forEach((item) => {
            create_package_field(container, item);
        });

        // Add any remaining select fields needed to reach a total of 3
        for (let i = 0; i < numToCreate; i++) {
            create_package_field(container);
        }
        let ell = $("<div>");
        ell.append(`<div><label class="propertiy-label">Label:</label>
            <input type="text" class="form-control form-control-sm" id="product-label" value="${
                config?.label ?? "Product Label"
            }">
            </div>
        `);

        ell.append(`<label>Select Products</label>`);
        ell.append(container);
        ell.append(
            '<button type="button" class="add_package btn btn-sm btn-success border mt-2"><i class="bi bi-plus"></i> Add option</button><br>'
        );

        ell.append(`
            <label class="propertiy-choice">Package Choice:</label>
                <select class="form-control form-control-sm" id="package-choice">
                    <option value="package_single" ${
                        config.package_choice == "package_single"
                            ? "selected"
                            : ""
                    }>Single Package (single option)</option>
                    <option value="package_multi" ${
                        config.package_choice == "package_multi"
                            ? "selected"
                            : ""
                    }>Multi-Choice Package (multiple option)</option>
                </select>
        `);

        form.prepend(ell);

        $(".add_package").click(function () {
            create_package_field(container);
        });

        $("#product-label").on("input", function () {
            config.label = $(this).val();
            if (element.prev(".product-label").length) {
                element.prev(".product-label").text(config.label);
            } else {
                $(
                    "<label class='product-label'>" + config.label + "</label>"
                ).insertBefore(element);
            }
            updateConfig(element, "product", {
                label: config.label,
            });
        });

        element.prev(".product-label").on("input", function (e) {
            e.stopPropagation();
            const updatedText = $(this).text();
            config.label = updatedText;
            updateConfig(element, "product", { label: updatedText });
            $("#product-label").val(updatedText);
            $(this).trigger("change");
        });

        // Make the label focusable
        element.prev(".product-label").attr("tabindex", "0");
        // Now, this should work as expected
        element.prev(".product-label").focus();
        element.prev(".product-label").on("click", placeCaretOnClick);

        $("#package-choice").on("change", function () {
            const choice = $(this).val();
            if (choice === "package_single") {
                displaySelectedValues(element, "radio");
            } else {
                displaySelectedValues(element, "checkbox");
            }
            updateConfig(element, "product", {
                package_choice: choice,
            });
        });
        $;

        displaySelectedValues(
            element,
            config.package_choice === "package_single" ? "radio" : "checkbox"
        );

        // Define a function to gather and display all selected values from all select fields
        function displaySelectedValues(element, type = null) {
            let package_choice = $(form).find("#package-choice").val();
            let column_size = $(form).find("#column-size").val();

            if (!type) {
                if (package_choice === "package_single") {
                    type = "radio";
                } else {
                    type = "checkbox";
                }
            }

            // Clear previous content before appending the updated HTML
            element.html("");
            // Get all product-selector elements
            var selected = [];
            $(form)
                .find(".product-selector")
                .each(function () {
                    // Find the currently selected option within this select field
                    var selectedOption = $(this).find("option:selected");
                    var productId = selectedOption.data("id");
                    let productHtml;
                    let name = selectedOption.text();

                    // Retrieve data attributes from the selected option and set default values if they are undefined or null
                    var is_combo = selectedOption.data("combo-product-ids")
                        ? true
                        : false;
                    var combo_product_ids =
                        selectedOption.data("combo-product-ids") || [];
                    var short_description =
                        selectedOption.data("short-description") || "";

                    var availableColors =
                        selectedOption.data("available-colors") || []; // Default to an empty array if null or undefined
                    var amount = selectedOption.data("amount") || "0"; // Default to '0' if not available
                    var currency_symbol =
                        selectedOption.data("currency-symbol") || "NGN"; // Default to 'NGN' if not available
                    var imageUrl =
                        selectedOption.data("image-url") ||
                        "https://via.placeholder.com/150"; // Default image placeholder
                    var availableSizes = selectedOption.data(
                        "available-sizes"
                    ) || ["1"]; // Default to an empty array if null or undefined
                    var totalQuantity =
                        selectedOption.data("total-quantity") || 1; // Default to 1 if not available

                    // Generate HTML for the product based on selected values
                    if (productId) {
                        selected.push(productId);
                        productHtml = `
                     <div class="${
                         column_size ?? "col-sm-12"
                     } product-wrapper mt-3">
                     <input type="${type}" name="product_packages[]" class="me-3 product-package product-checker" value="${productId}" id="product-${productId}">
                        <label for="product-${productId}" class="product_field me-3 product-item p-3 rounded shadow-sm" style="min-width: 100%; width: 100%;">
                            <div>
                        <div class="product-title me-1 fw-bold mb-2">${name} ${
                            is_combo
                                ? '<span class="badge badge-success"><span>Combo</span></span>'
                                : ""
                        } </div>
                            <div class="d-flex align-items-start align-items-center">
                                
                                <div class="d-flex flex-column flex-md-row w-100 align-items-start flex-wrap gap-2">
                                    <div class="product-img-container">
                                        <img class="product-img img-fluid rounded" src="/uploads/products/${imageUrl}" alt="Image">
                                        
                                    </div>
                                    <div class="product-info d-flex flex-column">
                                        <div class="text-sm  fw-bold" style="font-size: 15px;">${currency_symbol}${amount} </div>
                                        <div
                                            class="d-flex align-items-center mt-1 gap-1 ${
                                                is_combo || totalQuantity < 2
                                                    ? "d-none"
                                                    : ""
                                            }">
                                            <span
                                                style="font-size: 14px; font-weight: 600; opacity: 0.5;">
                                                Qty:
                                            </span>
                                            <div
                                                class="input-group product-qty">
                                                <button
                                                    class="btn btn-sm btn-icon btn-light border minusQty"
                                                    type="button">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input
                                                    class="form-control border text-center select_product_qtys"
                                                    placeholder="" value="1"
                                                    min="1"
                                                    name="select_product_qty[${productId}]"
                                                    max="${totalQuantity}">
                                                <button
                                                    class="btn btn-sm btn-icon btn-light border plusQty"
                                                    type="button">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                            <div class="size-options d-flex align-items-center mt-1  ${
                                                availableSizes &&
                                                availableSizes.length > 0
                                                    ? ""
                                                    : "d-none"
                                            }">
                                                <span style="font-size: 14px; font-weight: 600; opacity: 0.5;">Sizes: </span>${availableSizes
                                                    .map(
                                                        (size) => `
                                                        <input type="radio" id="size-${size}" name="product_size" value="${size}" class="size-radio d-none">
                                                        <label for="size-${size}" class="size-box">${size}</label>
                                                        `
                                                    )
                                                    .join("")}
                                            </div>
                                            <div class="color-options d-flex align-items-center mt-1 ${
                                                availableColors &&
                                                availableColors.length > 0
                                                    ? ""
                                                    : "d-none"
                                            }">
                                                <span style="font-size: 14px; font-weight: 600; opacity: 0.5;"Colors: </span>${availableColors
                                                    .map(
                                                        (color) => `
                                                        <input type="radio" id="color-${color}" name="product_color" value="${color}" class="color-radio d-none">
                                                        <label for="color-${color}" class="color-circle">${color}</label>
                                                        `
                                                    )
                                                    .join("")}
                                            </div>
                                            <div class=" ${
                                                is_combo ? "" : "d-none"
                                            }"><span class="">${short_description}</span>
                                            </div>

                                    </div>
                                </div>
                            </div>
                            <div>
                        </label>
                        </div>   
                    `;

                        // Update the canvas-content element with the generated HTML
                        element.append(productHtml);

                        // Prevent interaction when the label is clicked
                        element.on(
                            "click",
                            ".product-wrapper",
                            function (event) {
                                event.stopPropagation();
                            }
                        );
                        element.on("click", ".plusQty", function () {
                            var product_quantity = parseInt(
                                $(this)
                                    .closest(".product-qty")
                                    .find("input")
                                    .val()
                            );

                            product_quantity++;
                            $(this)
                                .closest(".product-qty")
                                .find("input")
                                .val(product_quantity);
                        });
                        element.on("click", ".minusQty", function () {
                            var product_quantity = parseInt(
                                $(this)
                                    .closest(".product-qty")
                                    .find("input")
                                    .val()
                            );
                            if (product_quantity > 1) {
                                product_quantity--;
                                $(this)
                                    .closest(".product-qty")
                                    .find("input")
                                    .val(product_quantity);
                            }
                        });
                    }
                });

            if (selected?.length == 0) {
                element.html(
                    '<div class="no-product"><i class="bi bi-bag-plus"></i> Select products to preview them here</div>'
                );
            }

            config.selected_package = selected;

            element.attr("data-selected-package", JSON.stringify(selected)),
                updateConfig(element, "product", {
                    selected_package: selected,
                });
        }

        // Function to create a package field (row with select and remove button)
        function create_package_field(_this, selected = null) {
            var select = $(".package_select").val(); // Get the value of the select box (assuming it's HTML)
            var originalSelect = $(select);

            if (!originalSelect.length) {
                console.error(
                    "No select element found with the class 'package_select'."
                );
                return;
            }

            // Clone the select element to create a new one
            // var element = originalSelect.clone();

            // Set the classes for the cloned select element
            originalSelect.attr({
                class: "form-control form-control-sm product-selector",
            });

            // If a selected value is provided, find and select the option with the matching data-id
            if (selected) {
                originalSelect
                    .find(`option[data-id="${selected}"]`)
                    .prop("selected", true);
            }

            // Remove button setup
            var rem = $(
                "<button class='btn btn-sm btn-default ms-2' type='button'><span class='bi bi-x-lg'></span></button>"
            );

            // Add click event to remove button

            // Create the row containing the select field and the remove button
            var el = $(
                "<div class='d-flex align-items-center product-container mb-2 w-100'>"
            );

            // Append the cloned select field and remove button to the row
            el.append(originalSelect);
            el.append(rem);

            // Append the complete row to the parent element
            _this.append(el);

            // Initialize Select2 for the cloned select element if needed

            rem.click(function () {
                $(this).closest(".product-container").remove();
                displaySelectedValues(element);
            });
        }

        $(form).on("change", ".product-selector", function () {
            // Call the function to display all selected values
            displaySelectedValues(element);
        });

        updateConfig(element, "product", config);
    }

    function image_field(form, element) {
        var canvasElement = element.parents(".canvas-element");
        var data = canvasElement?.data("config");
        let config = data?.config
            ? data?.config
            : {
                  width: element.css("width") || "100px",
                  height: element.css("height") || "100px",
                  textAlign:
                      element.parents(".canvas-element")?.css("text-align") ||
                      "",
                  src: element.attr("src") || "",
                  column_width: getBootstrapColumnClass(
                      element.parents(".canvas-element")?.attr("class")
                  ),
                  marginBottom:
                      element
                          .parents(".canvas-element")
                          ?.css("margin-bottom") || "",
                  marginTop:
                      element.parents(".canvas-element")?.css("margin-top") ||
                      "",
                  marginLeft:
                      element.parents(".canvas-element")?.css("margin-left") ||
                      "",
                  marginRight:
                      element.parents(".canvas-element")?.css("margin-right") ||
                      "",
              };

        element.attr("src", config.src);
        element.css({
            width: config.width,
            height: config.height,
        });
        element.prev(".product-label").text(config.label);
        element
            .parents(".canvas-element")
            ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
            ?.addClass(config.column_width);
        element.parents(".canvas-element").css({
            "margin-bottom": config.marginBottom,
            "margin-top": config.marginTop,
            "margin-left": config.marginLeft,
            "margin-right": config.marginRight,
            "text-align": config.textAlign,
        });

        // Image Element Properties
        form.prepend(`
    <label>Image Source:</label>
    <input class="form-control form-control-sm" type="text" id="image-src" value="${element.attr(
        "src"
    )}">
    <label>Width:</label>
    <input class="form-control form-control-sm" type="number" id="image-width" value="${element
        .css("width")
        .replace("px", "")}">
    <label>Height:</label>
    <input class="form-control form-control-sm" type="number" id="image-height" value="${element
        .css("height")
        .replace("px", "")}">
    <label class="propertiy-label">Alignment:</label>
    <select class="form-control form-control-sm" id="image-alignment">
        <option value="left">Left</option>
        <option value="center">Center</option>
        <option value="right">Right</option>
    </select>

`);

        // Event listeners for image properties
        $("#image-src").focus();

        $("#image-src").on("input", function () {
            element.attr("src", $(this).val());
            config.src = $(this).val();
            updateConfig(element, "image", {
                src: $(this).val(),
            });
        });

        $("#image-width").on("input", function () {
            config.width = $(this).val() + "px";
            element.css("width", $(this).val() + "px");
            updateConfig(element, "image", {
                width: $(this).val() + "px",
            });
        });

        $("#image-height").on("input", function () {
            element.css("height", $(this).val() + "px");
            config.height = $(this).val() + "px";
            updateConfig(element, "image", {
                height: $(this).val() + "px",
            });
        });
        $("#image-alignment").on("input", function () {
            element.parent().css("text-align", $(this).val());
            config.textAlign = $(this).val();
            updateConfig(element, "image", {
                textAlign: $(this).val(),
            });
        });

        updateConfig(element, "image", config);
    }

    function seperator_field(form, element) {
        var canvasElement = element.parents(".canvas-element");
        var data = canvasElement?.data("config");
        let config = data?.config
            ? data?.config
            : {
                  width: element.css("width") || "",
                  height: element.css("height") || "",
                  column_width: getBootstrapColumnClass(
                      element.parents(".canvas-element")?.attr("class")
                  ),
                  marginBottom:
                      element
                          .parents(".canvas-element")
                          ?.css("margin-bottom") || "",
                  marginTop:
                      element.parents(".canvas-element")?.css("margin-top") ||
                      "",
                  marginLeft:
                      element.parents(".canvas-element")?.css("margin-left") ||
                      "",
                  marginRight:
                      element.parents(".canvas-element")?.css("margin-right") ||
                      "",
              };

        element.css({
            width: config.width,
            height: config.height,
        });

        element
            .parents(".canvas-element")
            ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
            ?.addClass(config.column_width);
        element.parents(".canvas-element").css({
            "margin-bottom": config.marginBottom,
            "margin-top": config.marginTop,
            "margin-left": config.marginLeft,
            "margin-right": config.marginRight,
        });

        // Image Element Properties
        form.prepend(`
    <label>Width:</label>
    <input class="form-control form-control-sm" type="number" id="seperator-width" value="${element
        .css("width")
        .replace("px", "")}">
    <label>Height:</label>
    <input class="form-control form-control-sm" type="number" id="seperator-height" value="${element
        .css("height")
        .replace("px", "")}">
`);

        $("#seperator-width").on("input", function () {
            element.css("width", $(this).val() + "px");
            updateConfig(element, "seperator", {
                width: $(this).val() + "px",
            });
        });

        $("#seperator-height").on("input", function () {
            element.css("height", $(this).val() + "px");
            updateConfig(element, "seperator", {
                height: $(this).val() + "px",
            });
        });

        updateConfig(element, "seperator", config);
    }

    function form_field(form, element) {
        // Retrieve existing config from the element or initialize it
        var canvasElement = element.parents(".canvas-element");
        var data = canvasElement?.data("config");

        let config;
        if (data?.config) {
            config = data?.config;
        } else {
            config = {
                label: element.prev(".form-label").text() || "",
                size: "md",
                type: element.attr("type") || "text",
                placeholder: element.attr("placeholder") || "",
                defaultValue: element.val() || "",
                required: false,
                options: [],
                column_width: getBootstrapColumnClass(
                    element.parents(".canvas-element")?.attr("class")
                ),
                marginBottom:
                    element.parents(".canvas-element")?.css("margin-bottom") ||
                    "",
                marginTop:
                    element.parents(".canvas-element")?.css("margin-top") || "",
                marginLeft:
                    element.parents(".canvas-element")?.css("margin-left") ||
                    "",
                marginRight:
                    element.parents(".canvas-element")?.css("margin-right") ||
                    "",
            };

            if (element.is("select")) {
                config.options =
                    element
                        .find("option")
                        .map(function () {
                            return $(this).text();
                        })
                        .get() ?? [];
            } else if (
                element.find('input[type="radio"], input[type="checkbox"]')
                    .length > 0
            ) {
                config.type =
                    element.find('input[type="radio"]').length > 0
                        ? "radio"
                        : "checkbox";

                config.options =
                    element
                        // .closest("form") // Search within the closest form to find all radio/checkbox options with the same name
                        .find(`input[name="${element.attr("name")}"]`)
                        .map(function () {
                            return (
                                $(this).next("label").text() || $(this).val()
                            ); // Try to find the associated label or use the value as a fallback
                        })
                        .get() ?? [];
            }
        }

        // Append input fields to the form
        form.prepend(`
    <label class="propertiy-label">Label:</label>
    <input type="text" class="form-control form-control-sm" id="input-label" value="${
        config.label
    }">
    <label class="propertiy-label">Size:</label>
    <select class="form-control form-control-sm" id="input-size">
        <option value="sm" ${
            config.size == "sm" ? "selected" : ""
        }>Small (sm)</option>
        <option value="md" ${
            config.size == "md" ? "selected" : ""
        }>Medium (md)</option>
        <option value="lg" ${
            config.size == "lg" ? "selected" : ""
        }>Large (lg)</option>
    </select>
    <label class="propertiy-label">Type:</label>
    <select class="form-control form-control-sm" id="input-type">
        <option value="text" ${
            config.type == "text" ? "selected" : ""
        }>Text</option>
        <option value="password" ${
            config.type == "password" ? "selected" : ""
        }>Password</option>
        <option value="email" ${
            config.type == "email" ? "selected" : ""
        }>Email</option>
        <option value="number" ${
            config.type == "number" ? "selected" : ""
        }>Number</option>
         <option value="tel" ${
             config.type == "tel" ? "selected" : ""
         }>Phone number</option>
        <option value="file" ${
            config.type == "file" ? "selected" : ""
        }>File</option>
         <option value="radio" ${
             config.type == "radio" ? "selected" : ""
         }>Radio</option>
        <option value="checkbox" ${
            config.type == "checkbox" ? "selected" : ""
        }>Checkbox</option>
        <option value="select" ${
            config.type == "select" ? "selected" : ""
        }>Dropdown</option>
        <option value="textarea" ${
            config.type == "textarea" ? "selected" : ""
        }>Textarea</option>
    </select>
    <div id="field-options-container" style="display: none;">
        <label class="propertiy-label">Select Options (Comma-separated):</label>
        <input type="text" class="form-control form-control-sm" id="field-options" value="${config.options?.join(
            ", "
        )}">
    </div>
    <label class="propertiy-label">Placeholder:</label>
    <input type="text" class="form-control form-control-sm" id="input-placeholder" value="${
        config.placeholder
    }">
    <label class="propertiy-label">Default Value (optional):</label>
    <input type="text" class="form-control form-control-sm" id="input-default-value" value="${
        config.defaultValue
    }">
    <div class="form-check">
        <input class="form-check-input req-item" id="input-required" type="checkbox" ${
            config.required ? "checked" : ""
        }>
        <label class="propertiy-label form-check-label req-chk" for="input-required">
            * Required
        </label>
    </div>
`);

        // Set the selected size in the dropdown
        $("#input-size").val(config.size);
        $("#input-type").val(config.type);

        $("#input-label").on("input", function () {
            config.label = $(this).val();
            if (element.prev(".form-label").length) {
                element.prev(".form-label").text(config.label);
            } else {
                $(
                    "<label class='propertiy-label'>" +
                        config.label +
                        "</label>"
                ).insertBefore(element);
            }
            updateConfig(element, "form", {
                label: config.label,
            });
        });
        // Make the label focusable
        element.prev(".form-label").attr("tabindex", "0");

        element.prev(".form-label").on("click", placeCaretOnClick);
        element.prev(".form-label").on("input", function (e) {
            e.stopPropagation();
            const updatedText = $(this).text();
            updateConfig(element, "form", { content: updatedText });
            $("#input-label").val(updatedText);
            $(this).trigger("change");
        });

        $("#input-size").on("change", function () {
            config.size = $(this).val();
            if (config.size == "sm") {
                element.removeClass("form-control-lg");
                element.addClass("form-control-sm");
            } else if (config.size == "lg") {
                element.removeClass("form-control-sm");
                element.addClass("form-control-lg");
            } else {
                element.removeClass("form-control-sm form-control-lg");
            }

            updateConfig(element, "form", {
                size: config.size,
            });
        });

        $("#input-placeholder").on("input", function () {
            config.placeholder = $(this).val();
            element.attr("placeholder", config.placeholder);
            updateConfig(element, "form", {
                placeholder: $(this).val(),
            });
        });

        $("#input-default-value").on("input", function () {
            config.defaultValue = $(this).val();
            element.val(config.defaultValue);
            updateConfig(element, "form", {
                defaultValue: $(this).val(),
            });
        });

        $("#input-required").on("change", function () {
            config.required = $(this).is(":checked");
            element.attr("isRequired", config.required);
            updateConfig(element, "form", {
                required: config.required,
            });
        });

        $("#input-type").on("change", function () {
            const selectedType = $(this).val();
            config.type = selectedType;

            let newElement;

            // Create the appropriate element based on the selected type
            if (selectedType === "select") {
                newElement = $(
                    "<select class='" +
                        config.size +
                        " canvas-content form-control select2'></select>"
                );
                $("#field-options-container").show();
                updateFieldOptions("select"); // Initialize with existing options
            } else if (selectedType === "textarea") {
                newElement = $(
                    "<textarea class='" +
                        config.size +
                        " canvas-content form-control'></textarea>"
                );
                $("#field-options-container").hide();
            } else if (["radio", "checkbox"].includes(selectedType)) {
                newElement = $(
                    "<div class='canvas-content d-flex gap-1'><small>Add options to display</small></div>"
                );
                $("#field-options-container").show();
                updateFieldOptions(selectedType);
            } else {
                newElement = $(
                    "<input type='" +
                        selectedType +
                        "' class='" +
                        config.size +
                        " canvas-content form-control'>"
                );
                $("#field-options-container").hide();
            }

            // Replace the existing element with the new element
            element.replaceWith(newElement);

            // Reassign 'element' to the new jQuery object
            element = newElement;

            // Preserve existing options if switching to a type that uses options
            if (["select", "radio", "checkbox"].includes(selectedType)) {
                element.data("config", config);
                updateFieldOptions(selectedType);
            }

            // Update config with the new type
            updateConfig(element, "form", {
                type: config.type,
            });

            // Trigger change event on the newly created element
            element.trigger("change");
        });

        $("#field-options").on("input", function () {
            updateFieldOptions($("#input-type").val());
        });

        function updateFieldOptions(type = null) {
            const optionsString = $("#field-options").val();

            const options = optionsString
                .split(",")
                .map((option) => option.trim());
            config.options = options;

            element.empty(); // Clear existing options
            config.options.forEach((option, sn) => {
                if (option) {
                    var name = $("#input-label")
                        .val()
                        .replace(/\s+/g, "_")
                        .toLowerCase();
                    if (type == "radio") {
                        element.append(
                            `<div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-${sn}" value="${option}">
                                <label class="form-check-label" for="${name}-${sn}" style="text-transform: none;">
                                    ${option}
                                </label>
                            </div>`
                        );
                    } else if (type == "checkbox") {
                        element.append(
                            `<div class="form-check">
                                <input class="form-check-input" type="checkbox" name="${name}" id="${name}-${sn}" value="${option}">
                                <label class="form-check-label" for="${name}${sn}" style="text-transform: none;">
                                    ${option}
                                </label>
                            </div>`
                        );
                    } else {
                        element.append(
                            `<option value="${option}">${option}</option>`
                        );
                    }
                }
            });

            updateConfig(element, "form", {
                options,
            });
        }

        if (["select", "textarea", "radio", "checkbox"].includes(config.type)) {
            $("#input-type").trigger("change");
        }

        // $("#input-label").trigger("change").trigger("input");
        element.val(config.defaultValue);
        element.attr({
            placeholder: config.placeholder,
            required: config.required,
            type: config.type,
        });
        // element.prev(".form-label").text(config.label);
        element
            .parents(".canvas-element")
            ?.removeClass("col-sm-12 col-sm-6 col-sm-4 col-sm-3")
            ?.addClass(config.column_width);
        element.parents(".canvas-element").css({
            "margin-bottom": config.marginBottom,
            "margin-top": config.marginTop,
            "margin-left": config.marginLeft,
            "margin-right": config.marginRight,
        });

        // Initialize the select options if the element is a select
        if (element.is("select")) {
            $("#input-type").val("select");
            $("#field-options-container").show();
        } else if (element.is("textarea")) {
            $("#input-type").val("textarea");
            $("#field-options-container").hide();
        } else if (
            element.find('input[type="radio"], input[type="checkbox"]').length >
            0
        ) {
            $("#input-type").val(element.attr("type"));
            $("#field-options-container").show();
        }

        // Initial config update
        updateConfig(element, "form", config);
    }
});
