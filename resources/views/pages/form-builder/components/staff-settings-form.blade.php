<div class="mt-3">
    <div class="product-clone-section clone-item">
        <div class="col-md-12 mt-1 wrapper">
            <label for="" class="form-label">Select Staffs</label>

            @forelse (old('staff_assigned_ids', $form?->staff_assigned_ids ?? []) as $staff_assigned_ids)
                <div class="d-flex align-items-center product-container mb-2 w-100 element">

                    <select name="staff_assigned_ids[]" id="" data-live-search="true"
                        class="form-control form-control-sm border">
                        <option value="">Nothing Selected</option>

                        @if (count($staffs) > 0)
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}"
                                    {{ $staff->id == $staff_assigned_ids ? 'selected' : '' }}>
                                    {{ $staff->name }} | {{ $staff->id }}</option>
                            @endforeach
                        @endif
                    </select>
                    <button class="btn btn-sm btn-default ms-2 remove" type="button"><span
                            class="bi bi-x-lg"></span></button>
                </div>

            @empty
                <div class="d-flex align-items-center product-container mb-2 w-100 element">
                    <select name="staff_assigned_ids[]" id="" data-live-search="true"
                        class="form-control form-control-sm border">
                        <option value="">Nothing Selected</option>

                        @if (count($staffs) > 0)
                            @foreach ($staffs as $staff)
                                <option value="{{ $staff->id }}">
                                    {{ $staff->name }} | {{ $staff->id }}
                                </option>
                            @endforeach
                        @endif
                    </select>
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
    <div class="form-check">
        <input class="form-check-input req-item" id="input-required" type="checkbox" name="auto_orders_distribution"
            {{ old('auto_orders_distribution', isset($form) && $form?->auto_orders_distribution ? 'on' : 'off') == 'on' ? 'checked' : '' }}>
        <label class="propertiy-label form-check-label req-chk" for="input-required">
            Distribute Orders Automatically
        </label>
    </div>
    <div style="font-size: 12px;" class="text-muted">
        Distribute orders automatically among available staff members in a rotating manner. If unchecked, orders will
        wait for the first staff member to accept them
    </div>
</div>
