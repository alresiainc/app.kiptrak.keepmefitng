<div class="form-group">
    <label class="form-label d-block">Text:</label>
    <input type="text" id="form-button-text" name="form_button_text" class="form-control form-control-sm mb-2"
        value="{{ old('form_button_text', $form?->form_button_text ?? 'Submit Order') }}">
</div>

<div class="form-group">
    <label class="form-label d-block">Background:</label>
    <input type="color" id="form-button-bg" name="form_button_bg" class="form-control form-control-sm mb-2"
        value="{{ old('form_button_bg', $form?->form_button_bg ?? '#04512d') }}">
</div>

<div class="form-group">
    <label class="form-label d-block">Color:</label>
    <input type="color" id="form-button-color" name="form_button_color" class="form-control form-control-sm mb-2"
        value="{{ old('form_button_color', $form?->form_button_color ?? '#ffffff') }}">
</div>

<div class="form-group">
    <label class="form-label d-block">Alignment:</label>
    <select class="form-control form-control-sm mb-2" id="form-button-alignment" name="form_button_alignment">
        <option value="Left"
            {{ old('form_button_alignment', $form?->form_button_alignment ?? 'Center') == 'Left' ? 'selected' : '' }}>
            Left</option>
        <option value="Center"
            {{ old('form_button_alignment', $form?->form_button_alignment ?? 'Center') == 'Center' ? 'selected' : '' }}>
            Center</option>
        <option value="Right"
            {{ old('form_button_alignment', $form?->form_button_alignment ?? 'Center') == 'Right' ? 'selected' : '' }}>
            Right</option>
    </select>
</div>

<div class="form-group">
    <label class="form-label">Button Type:</label>
    <select class="form-control form-control-sm mb-2" id="form-button-type" name="form_button_type">
        <option value="Regular"
            {{ old('form_button_type', $form?->form_button_type ?? 'Rounded') == 'Regular' ? 'selected' : '' }}>Regular
        </option>
        <option value="Rounded"
            {{ old('form_button_type', $form?->form_button_type ?? 'Rounded') == 'Rounded' ? 'selected' : '' }}>Rounded
        </option>
    </select>
</div>
