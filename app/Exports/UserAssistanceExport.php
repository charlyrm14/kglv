<?php

namespace App\Exports;

use App\Enums\StatusAssistance;
use App\Models\User;
use App\Services\DateService;
use App\Services\HelperService;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class UserAssistanceExport implements FromCollection, WithHeadings
{
    private User $user;
    private Collection $assistances;

    public function __construct(User $user, Collection $assistances)
    {
        $this->user = $user;
        $this->assistances = $assistances;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [];

        foreach ($this->assistances as  $assistance) {
            $get_day = DateService::getDayByDate($assistance->created_at);
        
            $data[] = [
                HelperService::getFullName($this->user),
                $get_day,
                StatusAssistance::from($assistance->assistance)->label(),
                Carbon::parse($assistance->created_at)->format('Y-m-d')
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Nombre alumno', 'Día', 'Asistencia', 'Fecha'];
    }
}
