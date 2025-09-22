<div class="row">
    <div class="col-md-12">
        @php
            $errorMessage = '';
            $accounts = [];
            try {
                $apiKey = \App\Models\GeneralSetting::first()?->serlzo_api_key;
                $response = Http::withHeaders(['x-serlzo-api-key' => $apiKey])->get(
                    'https://whatsapp-reseller.serlzo.com/whatsapp/get-all-whatsapp-accounts',
                );

                if ($response->status() === 200) {
                    // $accounts = collect($response->json()['data'] ?? [])->filter(function (
                    //     $account,
                    // ) {
                    //     return $account['status'] == 'active';
                    // });
                    $accounts = $response->json()['data'] ?? [];
                    if (count($accounts) == 0) {
                        $errorMessage = 'No active account found';
                    }
                } else {
                    $errorMessage = 'No account found';
                }
            } catch (\Throwable $e) {
                $errorMessage = $e->getMessage();
            }

        @endphp
        <label for="" class="form-label">WhatsApp (serlzo) Account Token @if ($errorMessage)
                <span class="text-warning text-sm">
                    <strong>{{ $errorMessage }}</strong>
                </span>
            @endif
        </label>
        <label for="" class="form-label">Header Scripts</label>
        <select name="serlzo_account_token" data-live-search="true"
            class="custom-select form-control border tags @error('country') is-invalid @enderror">



            <option value="1">Select WhatsApp Account</option>

            @foreach ($accounts as $account)
                <option value="{{ $account['token'] }}" @if ($staff->serlzo_account_token == $account['token']) selected @endif>
                    {{ $account['publicName'] != '' ? $account['publicName'] : $account['username'] }}
                </option>
            @endforeach
            <option value="">None</option>

        </select>
        @error('serlzo_account_token')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>


</div>
