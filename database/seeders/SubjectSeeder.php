<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;


class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [

            // MINOR SUBJECTS
            ['subject' => 'Oral Communication in Context', 'type' => 'minor', 'description' => 'Develops listening and speaking skills for effective communication.'],
            ['subject' => 'Komunikasyon at Pananaliksik', 'type' => 'minor', 'description' => 'Filipino communication and research in local culture.'],
            ['subject' => 'Reading and Writing Skills', 'type' => 'minor', 'description' => 'Improves academic reading and writing proficiency.'],
            ['subject' => '21st Century Literature', 'type' => 'minor', 'description' => 'Literary works of the 21st century from the Philippines and world.'],
            ['subject' => 'Understanding Culture, Society, and Politics', 'type' => 'minor', 'description' => 'Exploration of sociocultural and political aspects.'],
            ['subject' => 'Earth and Life Science', 'type' => 'minor', 'description' => 'Basic concepts in earth science and biology.'],
            ['subject' => 'Physical Science', 'type' => 'minor', 'description' => 'Basic concepts in chemistry and physics.'],
            ['subject' => 'General Mathematics', 'type' => 'minor', 'description' => 'Covers functions, business math, and logic.'],
            ['subject' => 'Statistics and Probability', 'type' => 'minor', 'description' => 'Data analysis and probability.'],
            ['subject' => 'Personal Development', 'type' => 'minor', 'description' => 'Covers psychological development and relationships.'],
            ['subject' => 'Physical Education and Health 1', 'type' => 'minor', 'description' => 'Fitness, wellness, and basic physical education.'],
            ['subject' => 'Physical Education and Health 2', 'type' => 'minor', 'description' => 'Rhythmic activities and fitness development.'],
            ['subject' => 'Physical Education and Health 3', 'type' => 'minor', 'description' => 'Team sports and recreational activities.'],
            ['subject' => 'Physical Education and Health 4', 'type' => 'minor', 'description' => 'Individual sports and lifelong fitness.'],
            ['subject' => 'English for Academic and Professional Purposes', 'type' => 'minor', 'description' => 'Advanced communication for academic and workplace use.'],
            ['subject' => 'Filipino sa Piling Larang', 'type' => 'minor', 'description' => 'Academic or technical writing in Filipino.'],
            ['subject' => 'Practical Research 1', 'type' => 'minor', 'description' => 'Introduction to research methods.'],
            ['subject' => 'Practical Research 2', 'type' => 'minor', 'description' => 'Implementation of research.'],
            ['subject' => 'Empowerment Technologies', 'type' => 'minor', 'description' => 'ICT applications for productivity and media.'],
            ['subject' => 'Entrepreneurship', 'type' => 'minor', 'description' => 'Business startup and operations basics.'],
            ['subject' => 'Inquiries, Investigations, and Immersion', 'type' => 'minor', 'description' => 'Field immersion and applied learning.'],

            // MAJOR SUBJECTS (ABM, STEM, HUMSS, GAS, TVL Samples)
            ['subject' => 'Business Mathematics', 'type' => 'major', 'description' => 'Math skills for business transactions.'],
            ['subject' => 'Organization and Management', 'type' => 'major', 'description' => 'Fundamentals of managing organizations.'],
            ['subject' => 'Principles of Marketing', 'type' => 'major', 'description' => 'Marketing strategies and business models.'],
            ['subject' => 'General Biology 1', 'type' => 'major', 'description' => 'Fundamentals of cell and molecular biology.'],
            ['subject' => 'General Chemistry 1', 'type' => 'major', 'description' => 'Chemical reactions and molecular structures.'],
            ['subject' => 'Pre-Calculus', 'type' => 'major', 'description' => 'Mathematical concepts for STEM.'],
            ['subject' => 'Creative Writing', 'type' => 'major', 'description' => 'Imaginative writing in multiple genres.'],
            ['subject' => 'Philippine Politics and Governance', 'type' => 'major', 'description' => 'Political systems in the Philippines.'],
            ['subject' => 'Disciplines and Ideas in the Social Sciences', 'type' => 'major', 'description' => 'Fields and theories in the social sciences.'],
            ['subject' => 'Disaster Readiness and Risk Reduction', 'type' => 'major', 'description' => 'Preparedness and response to disasters.'],
            ['subject' => 'Trends, Networks and Critical Thinking', 'type' => 'major', 'description' => 'Trends in society and critical thinking.'],
            ['subject' => 'Bread and Pastry Production NC II', 'type' => 'major', 'description' => 'Technical-vocational training in baking.'],
            ['subject' => 'Shielded Metal Arc Welding NC II', 'type' => 'major', 'description' => 'Basic welding skills for metalwork.'],
        ];

        DB::table('subjects')->insert($subjects);
    }
    
}
