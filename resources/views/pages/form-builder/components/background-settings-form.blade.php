<label>Background Color:</label>
<input type="color" id="form-bg-color" name="form_bg_color" class="form-control form-control-sm mb-2"
    value="{{ old('form_bg_color', $form?->form_bg_color ?? '#ffffff') }}">
<label>Background Image URL:</label>
<input type="url" id="form-bg-url" name="form_bg_url" class="form-control form-control-sm mb-2"
    value="{{ old('form_bg_url', $form?->form_bg_url ?? '') }}">
<label>Background Text Color:</label>
<input type="color" id="form-bg-text-color" name="form_bg_text_color" class="form-control form-control-sm mb-2"
    value="{{ old('form_bg_text_color', $form?->form_bg_text_color ?? '') }}">
