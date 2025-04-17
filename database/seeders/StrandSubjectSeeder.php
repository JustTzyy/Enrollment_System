<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrandSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $strands = [
            4 => [ // ABM
               // Major
               ['subject' => 'Business Mathematics', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
               ['subject' => 'Organization and Management', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
               ['subject' => 'Principles of Marketing', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
               ['subject' => 'Practical Research 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
               ['subject' => 'Practical Research 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
               ['subject' => 'Entrepreneurship', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
               ['subject' => 'Inquiries, Investigations, and Immersion', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ],

            5 => [ // STEM
                  // Major
                  ['subject' => 'General Biology 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'General Biology 2', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'General Chemistry 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'General Chemistry 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'General Physics 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'General Physics 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                  ['subject' => 'Pre-Calculus', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'Basic Calculus', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                  ['subject' => 'Practical Research 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                  ['subject' => 'Practical Research 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                  ['subject' => 'Inquiries, Investigations, and Immersion', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                  ['subject' => 'Disaster Readiness and Risk Reduction', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                  ['subject' => 'Empowerment Technologies', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ],

            6 => [ // HUMSS
                // Major
                ['subject' => 'Creative Nonfiction', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Disciplines and Ideas in the Social Sciences', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Disciplines and Ideas in the Applied Social Sciences', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Philippine Politics and Governance', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Trends, Networks, and Critical Thinking in the 21st Century', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Practical Research 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Practical Research 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Inquiries, Investigations, and Immersion', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],

                // Minor (same as other strands)
                // ... reuse the general subjects from ABM/STEM
            ],

            7 => [ // GAS
                // Major - usually flexible/general purpose
                ['subject' => 'Disaster Readiness and Risk Reduction', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Work Immersion', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Practical Research 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Practical Research 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],

                // Minor
                // ... reuse general subjects
            ],

            8 => [ // ICT
                // Major
                ['subject' => 'Computer Programming', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Animation', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Computer Systems Servicing', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Work Immersion', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
                ['subject' => 'Practical Research 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
                ['subject' => 'Practical Research 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],

                // Minor
                // ... reuse general subjects
            ],
        ];

        // Shared minor subjects (add these to each strand)
        $sharedMinors = [
            ['subject' => 'Oral Communication in Context', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Komunikasyon at Pananaliksik', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Reading and Writing Skills', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => '21st Century Literature', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Understanding Culture, Society, and Politics', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Earth and Life Science', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Physical Science', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'General Mathematics', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'Statistics and Probability', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'Personal Development', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'PE and Health 1', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'PE and Health 2', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 11'],
            ['subject' => 'PE and Health 3', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'PE and Health 4', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'English for Academic and Professional Purposes', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'Filipino sa Piling Larang', 'semester' => 'Sem 2', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
            ['subject' => 'Empowerment Technologies', 'semester' => 'Sem 1', 'time' => '1 Hour', 'gradeLevel' => 'Grade 12'],
        ];

        foreach ($strands as $strandID => &$subjects) {
            $subjects = array_merge($subjects, $sharedMinors);
        }

        foreach ($strands as $strandID => $subjects) {
            foreach ($subjects as $item) {
                $subjectID = DB::table('subjects')->where('subject', $item['subject'])->value('id');

                if ($subjectID) {
                    $subjectAssignmentID = DB::table('subject_assignments')->insertGetId([
                        'code' => '', // We'll fill this after getting the ID
                        'semester' => $item['semester'],
                        'time' => $item['time'],
                        'gradeLevel' => $item['gradeLevel'],
                        'subjectID' => $subjectID,
                        'strandID' => $strandID,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    // Now that we have the ID, update the 'code' field
                    DB::table('subject_assignments')
                        ->where('id', $subjectAssignmentID)
                        ->update(['code' => 'subj' . $subjectAssignmentID]);
                }
            }
        }
    }
}
