<div class="mt-3">
    <label for="upsell_product" class="form-label">Select Template</label>
    <select name="thankyou_template_id" data-live-search="true"
        class="form-control form-control-sm border @error('thankyou_template_id') is-invalid @enderror"
        id="thankyou_template_selected">
        <option value="">Nothing Selected</option>
        @if (count($thankYouTemplates) > 0)

            @foreach ($thankYouTemplates as $template)
                <option value="{{ $template->id }}"
                    {{ old('thankyou_template_id', isset($form) ? $form?->thankyou_id : '') == $template->id ? 'selected' : '' }}>
                    {{ $template->template_name }}</option>
            @endforeach

        @endif

    </select>

    @error('product')
        <span class="invalid-feedback mb-3" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="mt-3">
    <label for="template_external_url" class="form-label">External Url (Redirection)</label>
    <input type="text" name="template_external_url" id="template_external_url" class="form-control form-control-sm"
        placeholder="https://..."
        value="{{ old('template_external_url', isset($form) ? $form?->thankYou?->template_external_url : '') }}">

    @error('template_external_url')
        <span class="invalid-feedback mb-3" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
