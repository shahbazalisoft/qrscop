<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QrTemplate;

class QrTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            ['name' => 'Dark Luxury', 'style' => 1, 'status' => 1],
            ['name' => 'Emerald Nature', 'style' => 2, 'status' => 1],
            ['name' => 'Sunset Warm', 'style' => 3, 'status' => 1],
            ['name' => 'Modern Gradient', 'style' => 4, 'status' => 1],
            ['name' => 'Fast Food Poster', 'style' => 5, 'status' => 1],
        ];

        foreach ($templates as $template) {
            QrTemplate::updateOrCreate(
                ['style' => $template['style']],
                $template
            );
        }
    }
}
