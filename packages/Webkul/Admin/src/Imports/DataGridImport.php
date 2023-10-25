<?php

namespace Webkul\Admin\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataGridImport implements ToCollection, WithHeadingRow
{
    use Importable;

    /**
     * @param  Illuminate\Support\Collection  $row
     * @return void
     */
    public function collection(Collection $rows)
    {
    }
}
