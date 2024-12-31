<div class="row">
    <div class="col-md-12">
        <label for="" class="form-label">Header Scripts</label>
        <textarea id="header_scripts" name="header_scripts" class="form-control" rows="5">{{ old('header_scripts', isset($form) ? $form?->header_scripts : '') }}</textarea>
        @error('header_scripts')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="" class="form-label">Footer Scripts</label>
        <textarea id="footer_scripts" name="footer_scripts" class="form-control" rows="5">{{ old('footer_scripts', isset($form) ? $form?->footer_scripts : '') }}</textarea>

        @error('footer_scripts')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
