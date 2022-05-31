<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tag;

class UpdateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        {
            $tags = [
                [
                    'id' => 1,
                    'name' => 'Accounting',
                    'description' => 'Related to accounting principles and practices. This includes the recording, analyzing, and reporting of financial information.'
                ],
                [
                    'id' => 2,
                    'name' => 'Clerical',
                    'description' => 'Related to administrative and clerical procedures and systems such as word processing, managing files and records, stenography and transcription, designing forms, and other office procedures and terminology.'
                ],
                [
                    'id' => 3,
                    'name' => 'Computer and Information Systems',
                    'description' => 'Related to computer programming, hardware, and software.'
                ],
                [
                    'id' => 4,
                    'name' => 'Economics',
                    'description' => 'Related to economic theories, principles, and methods of analysis including simulation and forecasting techniques.'
                ],
                [
                    'id' => 5,
                    'name' => 'Education and Training',
                    'description' => 'Related principles and methods for curriculum and training design, teaching, and instruction for individuals and groups, and the measurement of training effects.'
                ],
                [
                    'id' => 6,
                    'name' => 'Finance',
                    'description' => 'Related to principles and practices of financial management, monitoring and accountability frameworks, reporting procedures, banking, and markets.'
                ],
                [
                    'id' => 7,
                    'name' => 'Human Resources',
                    'description' => 'Related to principles and procedures for personnel recruitment, selection, training, compensation and benefits, labor relations and negotiation, and personnel information systems.'
                ],
                [
                    'id' => 8,
                    'name' => 'Law',
                    'description' => 'Related to the legal system, laws, legal codes, court procedures, and precedents.'
                ],
                [
                    'id' => 9,
                    'name' => 'Marketing',
                    'description' => "Related to principles and practices for determining consumers' wants and needs, assessing and developing business opportunities, and advertising products and services."
                ],
                [
                    'id' => 10,
                    'name' => 'Sales',
                    'description' => 'Related to principles and practices for displaying, promoting, and selling products or services.'
                ],
            ];

            foreach($tags as $tag) {
                Tag::updateOrCreate([
                    'id' => $tag['id'],
                ], $tag);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
