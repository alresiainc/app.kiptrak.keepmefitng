<div class="mt-3 d-flex align-items-center" style="gap: 20px;">
    <div class="category switch_orderbump">
        <input type="radio" name="switch_orderbump" value="on" id="on"
            {{ old('switch_orderbump', isset($form) && $form?->orderbump?->id ? 'on' : 'off') == 'on' ? 'checked' : '' }}>
        <label for="on" class="ml-1">On</label>
    </div>

    <div class="category switch_orderbump">
        <input type="radio" name="switch_orderbump" value="off" id="off"
            {{ old('switch_orderbump', isset($form) && $form?->orderbump?->id ? 'on' : 'off') == 'off' ? 'checked' : '' }}>
        <label for="off">Off</label>
    </div>
</div>

<div id="orderbumpOptions"
    class="{{ old('switch_orderbump', isset($form) && $form?->orderbump?->id ? 'on' : 'off') == 'on' ? '' : 'd-none' }}">
    <div class="mt-3">
        <label for="editOrderbump_heading" class="form-label">Heading | Optional</label>
        <input type="text" name="orderbump_heading" id="editOrderbump_heading" class="form-control form-control-sm"
            value="{{ old('orderbump_heading', $form?->orderbump?->orderbump_heading ?? '') }}">
    </div>

    <div class="mt-3">
        <div class="product-clone-section clone-item">
            <div class="col-md-12 mt-1 wrapper">
                <label for="" class="form-label">Sub Headings | Optional</label>

                @forelse (old('orderbump_subheading', $form?->orderbump?->orderbump_subheading ?? []) as $orderbump_subheading)
                    <div class="d-flex align-items-center product-container mb-2 w-100 element">
                        <input type="text" name="orderbump_subheading[]" class="form-control form-control-sm"
                            placeholder="" value="{{ $orderbump_subheading }}">
                        <button class="btn btn-sm btn-default ms-2 remove" type="button"><span
                                class="bi bi-x-lg"></span></button>
                    </div>
                @empty
                    <div class="d-flex align-items-center product-container mb-2 w-100 element">
                        <input type="text" name="orderbump_subheading[]" class="form-control form-control-sm"
                            placeholder="">
                        <button class="btn btn-sm btn-default ms-2 remove" type="button"><span
                                class="bi bi-x-lg"></span></button>
                    </div>
                @endforelse
            </div>

            <button type="button" class="clone btn btn-success btn-sm">
                <i class="bi bi-plus"></i> Add more
            </button>
        </div>
    </div>

    <div class="mt-3">
        <label for="orderbumpProductSelect" class="form-label">Select Product Package</label>
        <select id="orderbumpProductSelect" name="orderbump_product" data-live-search="true"
            class="form-control form-control-sm border @error('orderbump_product') is-invalid @enderror">
            <option value="">Nothing Selected</option>
            @if (isset($products) && count($products) > 0)
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        {{ old('orderbump_product', $form?->orderbump?->orderbump_product ?? '') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} @<span class="product_sale_price">{{ $product->sale_price }}</span>
                    </option>
                @endforeach
            @endif
        </select>

        @error('orderbump_product')
            <span class="invalid-feedback mb-3" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mt-3">
        <label for="" class="form-label">Discount Amount</label>
        <input type="text" name="orderbump_discount" class="form-control form-control-sm"
            value="{{ old('orderbump_discount', $form?->orderbump?->orderbump_discount ?? '') }}">
    </div>
</div>
