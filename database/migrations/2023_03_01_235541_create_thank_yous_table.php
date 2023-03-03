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
        Schema::create('thank_yous', function (Blueprint $table) {
            $table->id();

            $table->string('unique_key')->nullable();
            $table->string('template_name')->nullable(); //unique

            $table->longText('url')->nullable();
            $table->longText('embedded_tag')->nullable();
            $table->longText('iframe_tag')->nullable();
            
            $table->string('body_bg_color')->nullable();
            $table->string('body_border_style')->nullable();
            $table->string('body_border_color')->nullable();
            $table->string('body_border_thickness')->nullable();
            $table->string('body_border_radius')->nullable();
            $table->string('heading_text')->nullable();

            $table->string('heading_text_style')->nullable();
            $table->string('heading_text_align')->nullable();
            $table->string('heading_text_color')->nullable();
            $table->string('heading_text_weight')->nullable();
            $table->string('heading_text_size')->nullable();

            $table->string('subheading_text')->nullable();
            $table->string('subheading_text_style')->nullable();
            $table->string('subheading_text_color')->nullable();
            $table->string('subheading_text_weight')->nullable();
            $table->string('subheading_text_size')->nullable();
            $table->string('subheading_text_align')->nullable();

            $table->string('button_text')->nullable();
            $table->string('button_bg_color')->nullable();
            $table->string('button_text_style')->nullable();
            $table->string('button_text_align')->nullable();
            $table->string('button_text_color')->nullable();
            $table->string('button_text_weight')->nullable();
            $table->string('button_text_size')->nullable();

            $table->string('onhover_button_bg_color')->nullable();
            $table->string('onhover_button_border_color')->nullable();
            $table->string('onhover_button_text_style')->nullable();
            $table->string('onhover_button_text_color')->nullable();
            $table->string('onhover_button_text_weight')->nullable();
            $table->string('onhover_button_text_size')->nullable();

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
        Schema::dropIfExists('thank_yous');
    }
};
