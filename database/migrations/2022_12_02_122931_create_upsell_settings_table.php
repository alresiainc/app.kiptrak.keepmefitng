<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upsell_settings', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key')->nullable();

            $table->string('template_code')->nullable();

            $table->string('preview_image')->nullable();

            $table->string('body_bg_color')->nullable();
            $table->string('body_border_style')->nullable(); //solid, dotted, dashed
            $table->string('body_border_color')->nullable();
            $table->string('body_border_thickness')->nullable(); //1px, 2px etc
            $table->string('body_border_radius')->nullable(); //normal, rounded, rounded-pill

            $table->longText('heading_text')->nullable();
            $table->string('heading_text_style')->nullable(); //normal, italic
            $table->string('heading_text_align')->nullable(); //left, center, right
            $table->string('heading_text_color')->nullable();

            $table->longText('subheading_text')->nullable();
            $table->string('subheading_text_style')->nullable(); //normal, italic
            $table->string('subheading_text_align')->nullable(); //left, center, right
            $table->string('subheading_text_color')->nullable();

            $table->longText('description_text')->nullable();
            $table->string('description_text_style')->nullable(); //normal, italic
            $table->string('description_text_align')->nullable(); //left, center, right
            $table->string('description_text_color')->nullable();

            $table->longText('package_text_style')->nullable(); //normal, italic
            $table->longText('package_text_align')->nullable(); //left, center, right
            $table->longText('package_text_color')->nullable();

            $table->string('button_bg_color')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_text_style')->nullable(); //normal, itallic
            $table->string('button_text_align')->nullable(); //left, center, right
            $table->string('button_text_color')->nullable();

            $table->string('created_by')->nullable();
            $table->string('status')->nullable(); 
            $table->softDeletes();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upsell_settings');
    }
};
