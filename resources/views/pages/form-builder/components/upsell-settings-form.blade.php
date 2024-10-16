<!-- Switch for Upsell On/Off -->

<div class="mt-3 d-flex align-items-center" style="gap: 20px;">
    <div class="category switch_upsell">
        <input type="radio" name="switch_upsell" value="on" id="on"
            {{ old('switch_upsell', isset($form) && $form?->upsell_id ? 'on' : 'off') == 'on' ? 'checked' : '' }}>
        <label for="on" class="ml-1">On</label>
    </div>

    <div class="category switch_upsell">
        <input type="radio" name="switch_upsell" value="off" id="off"
            {{ old('switch_upsell', isset($form) && $form?->upsell_id ? 'on' : 'off') == 'off' ? 'checked' : '' }}>
        <label for="off">Off</label>
    </div>
</div>

<div id="upsellOptions"
    class="{{ old('switch_upsell', isset($form) && $form?->upsell_id ? 'on' : 'off') == 'on' ? '' : 'd-none' }}">
    <!-- Heading Input (Hidden) -->
    <div class="mt-3 d-none">
        <label for="editUpsell_heading" class="form-label">Heading</label>
        <input type="text" name="upsell_heading" id="editUpsell_heading" class="form-control" value="">
    </div>

    <!-- Sub Heading Textarea (Hidden) -->
    <div class="mt-3 d-none">
        <label for="editUpsell_subheading" class="form-label">Sub Heading</label>
        <textarea name="upsell_subheading" id="editUpsell_subheading" cols="30" rows="5"
            class="mytextarea form-control"></textarea>
    </div>

    <!-- Template Selection -->
    <div class="mt-3">
        <label for="upsell_setting_id" class="form-label">Select Template</label>
        <select name="upsell_setting_id" id="upsell_setting_id" data-live-search="true"
            class="form-control form-control-sm border @error('upsell_product') is-invalid @enderror">
            @if (isset($form?->upsell_id) && isset($form?->upsell->template->id))
                <option value="{{ $form?->upsell->template->id }}">{{ $form?->upsell->template->template_code }}
                </option>
            @endif
            @if (count($upsellTemplates) > 0)
                @foreach ($upsellTemplates as $template)
                    <option value="{{ $template->id }}">{{ $template->template_code }}</option>
                @endforeach
            @endif
        </select>
        @error('upsell_product')
            <span class="invalid-feedback mb-3" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Product Package Selection -->
    <div class="mt-3">
        <label for="upsellProductSelect" class="form-label">Select Product Package</label>
        <select name="upsell_product" id="upsellProductSelect" data-live-search="true"
            class="form-control form-control-sm border @error('upsell_product') is-invalid @enderror">
            @if (isset($form?->upsell_id) && isset($form?->upsell->product->id))
                <option value="{{ $form?->upsell->product->id }}">{{ $form?->upsell->product->name }}</option>
            @endif
            @if (count($products) > 0)
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            @endif
        </select>
        @error('upsell_product')
            <span class="invalid-feedback mb-3" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
