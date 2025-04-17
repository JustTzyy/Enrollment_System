<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear; // adjust this to your actual model

class AutoManageSchoolYear extends Command
{
    protected $signature = 'schoolyear:auto-manage';
    protected $description = 'Automatically manage school years yearly';

    public function handle()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $exists = SchoolYear::where('yearStart', $currentYear)->where('yearEnd', $nextYear)->exists();

        if (!$exists) {
            SchoolYear::create([
                'yearStart' => $currentYear,
                'yearEnd' => $nextYear,
                'status' => 'active'
            ]);

            $this->info("School year {$currentYear}-{$nextYear} created.");
        } else {
            $this->info("School year {$currentYear}-{$nextYear} already exists.");
        }

        $previousSchoolYear = SchoolYear::where('yearEnd', $currentYear)
                                        ->where('status', 'active')  
                                        ->first();

        if ($previousSchoolYear) {
            $previousSchoolYear->update([
                'status' => 'inactive'
            ]);
            $this->info("School year {$previousSchoolYear->yearStart}-{$previousSchoolYear->yearEnd} marked as inactive.");
        }
    }
}
