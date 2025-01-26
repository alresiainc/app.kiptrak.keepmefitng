<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UpsellSetting;

class UpsellSettingController extends Controller
{
    //allUpsellTemplates
    public function allUpsellTemplates()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $upsellTemplates = UpsellSetting::where('type', 'upsell')->get();
        return view('pages.settings.upsell.allUpsellTemplates', \compact('authUser', 'user_role', 'upsellTemplates'));
    }

    public function singleUpsellTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $upsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if (!$upsellTemplate->exists()) {
            abort(404);
        }
        $upsellTemplate = $upsellTemplate->first();

        return view('pages.settings.upsell.singleUpsellTemplate', \compact('authUser', 'user_role', 'upsellTemplate'));
    }

    //allUpsellTemplates
    public function addUpsellTemplate()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $string = 'kpups-' . date("Ymd") . '-' . date("his");
        $randomStrings = UpsellSetting::where('template_code', 'like', $string . '%')->pluck('template_code');

        do {
            $randomString = 'kpups-' . date("Ymd") . '-' . date("his");
        } while ($randomStrings->contains($randomString));

        $template_code = $randomString;
        return view('pages.settings.upsell.addUpsellTemplate', \compact('authUser', 'user_role', 'template_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUpsellTemplatePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $data = $request->all();
        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|array',
            'description_text' => 'nullable|string',
        ]);

        $data = $request->all();

        $subheading_text = array_filter($data['subheading_text'], fn($value) => !is_null($value) && $value !== '');

        $upsellTemplate = new UpsellSetting();

        $upsellTemplate->type = 'upsell';
        $upsellTemplate->template_code = $data['template_code'];

        $upsellTemplate->body_bg_color = $data['body_bg_color'];
        $upsellTemplate->body_border_style = $data['body_border_style']; //solid, dotted, dashed
        $upsellTemplate->body_border_color = $data['body_border_color'];
        $upsellTemplate->body_border_thickness = $data['body_border_thickness']; //1px, 2px etc
        $upsellTemplate->body_border_radius = $data['body_border_radius']; //normal, rounded, rounded-pill

        $upsellTemplate->heading_text = $data['heading_text'];
        $upsellTemplate->heading_text_style = $data['heading_text_style']; //normal, italic
        $upsellTemplate->heading_text_align = $data['heading_text_align']; //left, center, right
        $upsellTemplate->heading_text_color = $data['heading_text_color'];
        $upsellTemplate->heading_text_weight = $data['heading_text_weight'];
        $upsellTemplate->heading_text_size = $data['heading_text_size'];

        $upsellTemplate->subheading_text = serialize($subheading_text);
        $upsellTemplate->subheading_text_style = $data['subheading_text_style']; //normal, italic
        $upsellTemplate->subheading_text_align = $data['subheading_text_align']; //left, center, right
        $upsellTemplate->subheading_text_color = $data['subheading_text_color'];
        $upsellTemplate->subheading_text_weight = $data['subheading_text_weight'];
        $upsellTemplate->subheading_text_size = $data['subheading_text_size'];

        if (!empty($data['description_text'])) {
            $upsellTemplate->description_text = $data['description_text'];
            $upsellTemplate->description_text_style = $data['description_text_style']; //normal, italic
            $upsellTemplate->description_text_align = $data['description_text_align']; //left, center, right
            $upsellTemplate->description_text_color = $data['description_text_color'];
            $upsellTemplate->description_text_weight = $data['description_text_weight'];
            $upsellTemplate->description_text_weight = $data['description_text_weight'];
        }

        $upsellTemplate->package_text_style = $data['package_text_style']; //normal, italic
        $upsellTemplate->package_text_align = $data['package_text_align']; //left, center, right
        $upsellTemplate->package_text_color = $data['package_text_color'];
        $upsellTemplate->package_text_weight = $data['package_text_weight'];
        $upsellTemplate->package_text_weight = $data['package_text_weight'];

        $upsellTemplate->before_button_text = $data['before_button_text'];
        $upsellTemplate->before_button_text_style = $data['before_button_text_style']; //normal, itallic
        $upsellTemplate->before_button_text_align = $data['before_button_text_align']; //left, center, right
        $upsellTemplate->before_button_text_color = $data['before_button_text_color'];
        $upsellTemplate->before_button_text_weight = $data['before_button_text_weight'];
        $upsellTemplate->before_button_text_weight = $data['before_button_text_weight'];

        $upsellTemplate->button_bg_color = $data['button_bg_color'];
        $upsellTemplate->button_text = $data['button_text'];
        $upsellTemplate->button_text_style = $data['button_text_style']; //normal, itallic
        $upsellTemplate->button_text_align = $data['button_text_align']; //left, center, right
        $upsellTemplate->button_text_color = $data['button_text_color'];
        $upsellTemplate->button_text_weight = $data['button_text_weight'];
        $upsellTemplate->header_scripts = $data['header_scripts'];
        $upsellTemplate->footer_scripts = $data['footer_scripts'];

        $upsellTemplate->created_by = 1;
        $upsellTemplate->status = 'true';

        $upsellTemplate->save();

        return back()->with('success', 'Template Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUpsellTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $upsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if (!$upsellTemplate->exists()) {
            abort(404);
        }
        $upsellTemplate = $upsellTemplate->first();


        return view('pages.settings.upsell.editUpsellTemplate', \compact('authUser', 'user_role', 'upsellTemplate'));
    }

    public function duplicateUpsellTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $upsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if (!$upsellTemplate->exists()) {
            abort(404);
        }
        $upsellTemplate = $upsellTemplate->first();

        $string = 'kpups-' . date("Ymd") . '-' . date("his");
        $randomStrings = UpsellSetting::where('template_code', 'like', $string . '%')->pluck('template_code');

        do {
            $randomString = 'kpups-' . date("Ymd") . '-' . date("his");
        } while ($randomStrings->contains($randomString));

        $template_code = $randomString;
        // dd($template_code);
        return view('pages.settings.upsell.duplicateUpsellTemplate', \compact('authUser', 'user_role', 'upsellTemplate', 'template_code'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUpsellTemplatePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $upsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        if (!$upsellTemplate->exists()) {
            abort(404);
        }
        $upsellTemplate = $upsellTemplate->first();

        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|array',
            'description_text' => 'nullable|string',
        ]);

        $data = $request->all();

        //remove empty or null values
        $subheading_text = array_filter($data['subheading_text'], fn($value) => !is_null($value) && $value !== '');

        $upsellTemplate->template_code = $data['template_code'];

        $upsellTemplate->body_bg_color = $data['body_bg_color'];
        $upsellTemplate->body_border_style = $data['body_border_style']; //solid, dotted, dashed
        $upsellTemplate->body_border_color = $data['body_border_color'];
        $upsellTemplate->body_border_thickness = $data['body_border_thickness']; //1px, 2px etc
        $upsellTemplate->body_border_radius = $data['body_border_radius']; //normal, rounded, rounded-pill

        $upsellTemplate->heading_text = $data['heading_text'];
        $upsellTemplate->heading_text_style = $data['heading_text_style']; //normal, italic
        $upsellTemplate->heading_text_align = $data['heading_text_align']; //left, center, right
        $upsellTemplate->heading_text_color = $data['heading_text_color'];

        $upsellTemplate->subheading_text = serialize($subheading_text);
        $upsellTemplate->subheading_text_style = $data['subheading_text_style']; //normal, italic
        $upsellTemplate->subheading_text_align = $data['subheading_text_align']; //left, center, right
        $upsellTemplate->subheading_text_color = $data['subheading_text_color'];

        if (!empty($data['description_text'])) {
            $upsellTemplate->description_text = $data['description_text'];
            $upsellTemplate->description_text_style = $data['description_text_style']; //normal, italic
            $upsellTemplate->description_text_align = $data['description_text_align']; //left, center, right
            $upsellTemplate->description_text_color = $data['description_text_color'];
        }

        $upsellTemplate->package_text_style = $data['package_text_style']; //normal, italic
        $upsellTemplate->package_text_align = $data['package_text_align']; //left, center, right
        $upsellTemplate->package_text_color = $data['package_text_color'];

        $upsellTemplate->button_bg_color = $data['button_bg_color'];
        $upsellTemplate->button_text = $data['button_text'];
        $upsellTemplate->button_text_style = $data['button_text_style']; //normal, itallic
        $upsellTemplate->button_text_align = $data['button_text_align']; //left, center, right
        $upsellTemplate->button_text_color = $data['button_text_color'];

        $upsellTemplate->header_scripts = $data['header_scripts'];
        $upsellTemplate->footer_scripts = $data['footer_scripts'];

        $upsellTemplate->created_by = 1;
        $upsellTemplate->status = 'true';

        $upsellTemplate->save();

        return back()->with('success', 'Template Created Successfully');
    }


    //allDownsellTemplates
    public function allDownsellTemplates()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $downsellTemplates = UpsellSetting::where('type', 'downsell')->get();
        return view('pages.settings.downsell.allDownsellTemplates', \compact('authUser', 'user_role', 'downsellTemplates'));
    }

    public function singleDownsellTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $downsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if (!$downsellTemplate->exists()) {
            abort(404);
        }
        $downsellTemplate = $downsellTemplate->first();

        return view('pages.settings.downsell.singleDownsellTemplate', \compact('authUser', 'user_role', 'downsellTemplate'));
    }

    //allDownsellTemplates
    public function addDownsellTemplate()
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $string = 'kpups-' . date("Ymd") . '-' . date("his");
        $randomStrings = UpsellSetting::where('template_code', 'like', $string . '%')->pluck('template_code');

        do {
            $randomString = 'kpups-' . date("Ymd") . '-' . date("his");
        } while ($randomStrings->contains($randomString));

        $template_code = $randomString;
        return view('pages.settings.downsell.addDownsellTemplate', \compact('authUser', 'user_role', 'template_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDownsellTemplatePost(Request $request)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $data = $request->all();
        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|array',
            'description_text' => 'nullable|string',
        ]);

        $data = $request->all();

        $subheading_text = array_filter($data['subheading_text'], fn($value) => !is_null($value) && $value !== '');

        $downsellTemplate = new UpsellSetting();

        $downsellTemplate->type = 'downsell';
        $downsellTemplate->template_code = $data['template_code'];

        $downsellTemplate->body_bg_color = $data['body_bg_color'];
        $downsellTemplate->body_border_style = $data['body_border_style']; //solid, dotted, dashed
        $downsellTemplate->body_border_color = $data['body_border_color'];
        $downsellTemplate->body_border_thickness = $data['body_border_thickness']; //1px, 2px etc
        $downsellTemplate->body_border_radius = $data['body_border_radius']; //normal, rounded, rounded-pill

        $downsellTemplate->heading_text = $data['heading_text'];
        $downsellTemplate->heading_text_style = $data['heading_text_style']; //normal, italic
        $downsellTemplate->heading_text_align = $data['heading_text_align']; //left, center, right
        $downsellTemplate->heading_text_color = $data['heading_text_color'];
        $downsellTemplate->heading_text_weight = $data['heading_text_weight'];
        $downsellTemplate->heading_text_size = $data['heading_text_size'];

        $downsellTemplate->subheading_text = serialize($subheading_text);
        $downsellTemplate->subheading_text_style = $data['subheading_text_style']; //normal, italic
        $downsellTemplate->subheading_text_align = $data['subheading_text_align']; //left, center, right
        $downsellTemplate->subheading_text_color = $data['subheading_text_color'];
        $downsellTemplate->subheading_text_weight = $data['subheading_text_weight'];
        $downsellTemplate->subheading_text_size = $data['subheading_text_size'];

        if (!empty($data['description_text'])) {
            $downsellTemplate->description_text = $data['description_text'];
            $downsellTemplate->description_text_style = $data['description_text_style']; //normal, italic
            $downsellTemplate->description_text_align = $data['description_text_align']; //left, center, right
            $downsellTemplate->description_text_color = $data['description_text_color'];
            $downsellTemplate->description_text_weight = $data['description_text_weight'];
            $downsellTemplate->description_text_weight = $data['description_text_weight'];
        }

        $downsellTemplate->package_text_style = $data['package_text_style']; //normal, italic
        $downsellTemplate->package_text_align = $data['package_text_align']; //left, center, right
        $downsellTemplate->package_text_color = $data['package_text_color'];
        $downsellTemplate->package_text_weight = $data['package_text_weight'];
        $downsellTemplate->package_text_weight = $data['package_text_weight'];

        $downsellTemplate->before_button_text = $data['before_button_text'];
        $downsellTemplate->before_button_text_style = $data['before_button_text_style']; //normal, itallic
        $downsellTemplate->before_button_text_align = $data['before_button_text_align']; //left, center, right
        $downsellTemplate->before_button_text_color = $data['before_button_text_color'];
        $downsellTemplate->before_button_text_weight = $data['before_button_text_weight'];
        $downsellTemplate->before_button_text_weight = $data['before_button_text_weight'];

        $downsellTemplate->button_bg_color = $data['button_bg_color'];
        $downsellTemplate->button_text = $data['button_text'];
        $downsellTemplate->button_text_style = $data['button_text_style']; //normal, itallic
        $downsellTemplate->button_text_align = $data['button_text_align']; //left, center, right
        $downsellTemplate->button_text_color = $data['button_text_color'];
        $downsellTemplate->button_text_weight = $data['button_text_weight'];
        $downsellTemplate->button_text_weight = $data['button_text_weight'];

        $downsellTemplate->header_scripts = $data['header_scripts'];
        $downsellTemplate->footer_scripts = $data['footer_scripts'];

        $downsellTemplate->created_by = 1;
        $downsellTemplate->status = 'true';

        $downsellTemplate->save();

        return back()->with('success', 'Template Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDownsellTemplate($unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $downsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        // $sale_code = $sale->first()->sale_code;
        if (!$downsellTemplate->exists()) {
            abort(404);
        }
        $downsellTemplate = $downsellTemplate->first();


        return view('pages.settings.downsell.editDownsellTemplate', \compact('authUser', 'user_role', 'downsellTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDownsellTemplatePost(Request $request, $unique_key)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        $downsellTemplate = UpsellSetting::where('unique_key', $unique_key);
        if (!$downsellTemplate->exists()) {
            abort(404);
        }
        $downsellTemplate = $downsellTemplate->first();

        $request->validate([
            'heading_text' => 'required|string',
            'subheading_text' => 'required|array',
            'description_text' => 'nullable|string',
        ]);

        $data = $request->all();

        //remove empty or null values
        $subheading_text = array_filter($data['subheading_text'], fn($value) => !is_null($value) && $value !== '');

        $downsellTemplate->template_code = $data['template_code'];

        $downsellTemplate->body_bg_color = $data['body_bg_color'];
        $downsellTemplate->body_border_style = $data['body_border_style']; //solid, dotted, dashed
        $downsellTemplate->body_border_color = $data['body_border_color'];
        $downsellTemplate->body_border_thickness = $data['body_border_thickness']; //1px, 2px etc
        $downsellTemplate->body_border_radius = $data['body_border_radius']; //normal, rounded, rounded-pill

        $downsellTemplate->heading_text = $data['heading_text'];
        $downsellTemplate->heading_text_style = $data['heading_text_style']; //normal, italic
        $downsellTemplate->heading_text_align = $data['heading_text_align']; //left, center, right
        $downsellTemplate->heading_text_color = $data['heading_text_color'];

        $downsellTemplate->subheading_text = serialize($subheading_text);
        $downsellTemplate->subheading_text_style = $data['subheading_text_style']; //normal, italic
        $downsellTemplate->subheading_text_align = $data['subheading_text_align']; //left, center, right
        $downsellTemplate->subheading_text_color = $data['subheading_text_color'];

        if (!empty($data['description_text'])) {
            $downsellTemplate->description_text = $data['description_text'];
            $downsellTemplate->description_text_style = $data['description_text_style']; //normal, italic
            $downsellTemplate->description_text_align = $data['description_text_align']; //left, center, right
            $downsellTemplate->description_text_color = $data['description_text_color'];
        }

        $downsellTemplate->package_text_style = $data['package_text_style']; //normal, italic
        $downsellTemplate->package_text_align = $data['package_text_align']; //left, center, right
        $downsellTemplate->package_text_color = $data['package_text_color'];

        $downsellTemplate->button_bg_color = $data['button_bg_color'];
        $downsellTemplate->button_text = $data['button_text'];
        $downsellTemplate->button_text_style = $data['button_text_style']; //normal, itallic
        $downsellTemplate->button_text_align = $data['button_text_align']; //left, center, right
        $downsellTemplate->button_text_color = $data['button_text_color'];
        $downsellTemplate->header_scripts = $data['header_scripts'];
        $downsellTemplate->footer_scripts = $data['footer_scripts'];

        $downsellTemplate->created_by = 1;
        $downsellTemplate->status = 'true';

        $downsellTemplate->save();

        return back()->with('success', 'Template Created Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authUser = auth()->user();
        $user_role = $authUser->hasAnyRole($authUser->id) ? $authUser->role($authUser->id)->role : false;

        //
    }
}
