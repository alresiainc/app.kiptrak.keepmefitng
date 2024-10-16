<!-- Switch for Downsell On/Off -->

<div class="mt-3 d-flex align-items-center" style="gap: 20px;">
    <div class="category switch_downsell">
        <input type="radio" name="switch_downsell" value="on" id="on"
            {{ old('switch_downsell', isset($form) && $form?->downsell_id ? 'on' : 'off') == 'on' ? 'checked' : '' }}>
        <label for="on" class="ml-1">On</label>
    </div>

    <div class="category switch_downsell">
        <input type="radio" name="switch_downsell" value="off" id="off"
            {{ old('switch_downsell', isset($form) && $form?->downsell_id ? 'on' : 'off') == 'off' ? 'checked' : '' }}>
        <label for="off">Off</label>
    </div>
</div>

<div id="downsellOptions"
    class="{{ old('switch_downsell', isset($form) && $form?->downsell_id ? 'on' : 'off') == 'on' ? '' : 'd-none' }}">
    <!-- Heading Input (Hidden) -->
    <div class="mt-3 d-none">
        <label for="editDownsell_heading" class="form-label">Heading</label>
        <input type="text" name="downsell_heading" id="editDownsell_heading" class="form-control" value="">
    </div>

    <!-- Sub Heading Textarea (Hidden) -->
    <div class="mt-3 d-none">
        <label for="editDownsell_subheading" class="form-label">Sub Heading</label>
        <textarea name="downsell_subheading" id="editDownsell_subheading" cols="30" rows="5"
            class="mytextarea form-control"></textarea>
    </div>

    <!-- Template Selection -->
    <div class="mt-3">
        <label for="downsell_setting_id" class="form-label">Select Template</label>
        <select name="downsell_setting_id" id="downsell_setting_id" data-live-search="true"
            class="form-control form-control-sm border @error('downsell_product') is-invalid @enderror">
            @if (isset($form?->downsell_id) && isset($form?->downsell->template->id))
                <option value="{{ $form?->downsell->template->id }}">{{ $form?->downsell->template->template_code }}
                </option>
            @endif
            @if (isset($downsellTemplates) && count($downsellTemplates) > 0)
                @foreach ($downsellTemplates as $template)
                    <option value="{{ $template->id }}">{{ $template->template_code }}</option>
                @endforeach
            @endif
        </select>
        @error('downsell_product')
            <span class="invalid-feedback mb-3" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Product Package Selection -->
    <div class="mt-3">
        <label for="downsellProductSelect" class="form-label">Select Product Package</label>
        <select name="downsell_product" id="downsellProductSelect" data-live-search="true"
            class="form-control form-control-sm border @error('downsell_product') is-invalid @enderror">
            @if (isset($form?->downsell_id) && isset($form?->downsell->product->id))
                <option value="{{ $form?->downsell->product->id }}">{{ $form?->downsell->product->name }}</option>
            @endif
            @if (count($products) > 0)
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            @endif
        </select>
        @error('downsell_product')
            <span class="invalid-feedback mb-3" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
