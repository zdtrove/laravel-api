<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\ImportLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class AdminsImport implements ToCollection, WithChunkReading, WithHeadingRow, WithCustomCsvSettings
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.' . IMPORT_EMAIL_COLUMN_NUMBER => 'required|email',
        ])->validate();

        $filename = $this->filename;
        $user = auth()->user();

        DB::transaction(function () use ($rows, $filename, $user) {
            try {
                foreach ($rows as $row) {
                    Admin::withTrashed()->updateOrCreate(
                        ['email' => $row[IMPORT_EMAIL_COLUMN_NUMBER]],
                        ['deleted_at' => null, 'status' => 1]
                    );
                }
            } catch (\Exception $e) {
                throw new \Exception(__('api.management.import.error'));
            }
        });
    }

    public function headingRow(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @return array
     */
    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'shift-jis'
        ];
    }
}
