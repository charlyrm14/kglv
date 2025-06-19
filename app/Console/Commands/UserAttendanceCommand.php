<?php

namespace App\Console\Commands;

use App\Models\{ 
    User,
    UserAttendance,
    UserSchedule
};
use App\Services\DateService;
use Illuminate\Console\Command;

class UserAttendanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra las no asistencias de los alumnos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $status_records = [
            'sin_clases_asignadas' => 0,
            'si_asistieron' => 0,
            'no_asistieron' => 0,
        ];

        User::where('role_id', 3)->chunkById(10, function ($users) use (&$status_records) {
            foreach ($users as $user) {

                /**
                 * Get the current day of the week as a string, such as LUNES, MARTES, MIÉRCOLES, JUEVES, etc
                 */

                $today = DateService::getCurrentDay();

                /**
                 * Check if the user has a class assigned for the current day.
                 */
                $user_class = UserSchedule::getCurrentDaysClass($user->id, $today);

                if(!$user_class) {

                    /**
                     * Store a record with a status indicating that the user doesn't have any class assigned.
                     */
                    $check_duplicate_no_assigned_classes = UserAttendance::getAttendanceCurrentDayByUser($user->id, 2);

                    if(!$check_duplicate_no_assigned_classes) {
                        UserAttendance::create([
                            'user_id' => $user->id, 
                            'present' => 2
                        ]);
                    }

                    $status_records['sin_clases_asignadas']++;
                    continue;
                }

                /**
                 * Check if the user has an attendance record for the current day.
                 */
                $user_has_assistance_current_day = UserAttendance::getAttendanceCurrentDayByUser($user->id, 1);
                
                if($user_has_assistance_current_day) {
                    $status_records['si_asistieron']++;
                    continue;
                }

                /**
                 * Check for duplicate absences
                 */
                $check_duplicate_absences = UserAttendance::getAttendanceCurrentDayByUser($user->id, 0);

                if(!$check_duplicate_absences) {
                    UserAttendance::create([
                        'user_id' => $user->id, 
                        'present' => 0
                    ]);
                    $status_records['no_asistieron']++;
                }
            }

            $this->table(
                ['Status', 'Count'],
                collect($status_records)->map(fn($value, $key) => [$key, $value])->toArray()
            );
        });
    }
}
