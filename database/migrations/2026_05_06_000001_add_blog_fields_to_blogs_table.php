<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Disable strict mode for this session so existing '0000-00-00' date rows don't block ALTER TABLE
        \DB::statement("SET SESSION sql_mode = ''");

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('slug', 220)->nullable()->unique()->after('title');
            $table->text('excerpt')->nullable()->after('slug');
            $table->longText('content')->nullable()->after('excerpt');
            $table->string('hero_image')->nullable()->after('content');
            $table->string('hero_image_alt')->nullable()->after('hero_image');
            $table->string('author_name')->nullable()->after('hero_image_alt');
            $table->string('category', 100)->nullable()->after('author_name');
            $table->string('tags')->nullable()->after('category');
            $table->string('status', 20)->default('draft')->after('tags');
            $table->string('meta_title')->nullable()->after('status');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
            $table->timestamp('published_at')->nullable()->after('og_image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'excerpt', 'content', 'hero_image', 'hero_image_alt',
                'author_name', 'category', 'tags', 'status',
                'meta_title', 'meta_description', 'og_image',
                'published_at', 'created_at', 'updated_at',
            ]);
        });
    }
};
