<?php

namespace App\Exports;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TasksExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(
        private readonly array $filters = []
    ) {
    }

    public function query(): Builder
    {
        return Task::query()
            ->with(['category', 'creator', 'assignee', 'labels'])
            ->when(! empty($this->filters['scope_to_user']) && ! empty($this->filters['user_id']), function (Builder $query) {
                $query->where('assigned_to', $this->filters['user_id']);
            })
            ->when(($this->filters['mode'] ?? null) === 'date' && ! empty($this->filters['date_from']) && ! empty($this->filters['date_to']), function (Builder $query) {
                $from = Carbon::parse($this->filters['date_from'])->startOfDay();
                $to = Carbon::parse($this->filters['date_to'])->endOfDay();

                $query->whereBetween('created_at', [$from, $to]);
            })
            ->when(($this->filters['mode'] ?? null) === 'user' && ! empty($this->filters['user_id']) && empty($this->filters['scope_to_user']), function (Builder $query) {
                $query->where('assigned_to', $this->filters['user_id']);
            })
            ->when(($this->filters['mode'] ?? null) === 'status' && ! empty($this->filters['status']), function (Builder $query) {
                $query->where('status', $this->filters['status']);
            })
            ->when(! empty($this->filters['include_archived']), function (Builder $query) {
                $query->withTrashed();
            }, function (Builder $query) {
                $query->whereNull('archived_at');
            })
            ->orderByDesc('assigned_at')
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Status',
            'Priority',
            'Category',
            'Assigned By',
            'Assigned To',
            'Due Date',
            'Assigned At',
            'Created At',
            'Labels',
        ];
    }

    public function map($task): array
    {
        return [
            $task->id,
            $task->title,
            $task->description,
            ucfirst(str_replace('_', ' ', $task->status)),
            ucfirst($task->priority),
            $task->category?->name ?? '',
            $task->creator?->name ?? '',
            $task->assignee?->name ?? '',
            optional($task->due_date)->format('Y-m-d') ?? '',
            optional($task->assigned_at)->format('Y-m-d H:i:s') ?? '',
            optional($task->created_at)->format('Y-m-d H:i:s') ?? '',
            $task->labels->pluck('name')->implode(', '),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $headerRange = 'A1:'.$highestColumn.'1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                if ($highestRow >= 2) {
                    $sheet->getStyle('A2:'.$highestColumn.$highestRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                }

                for ($row = 2; $row <= $highestRow; $row++) {
                    $statusCell = 'D'.$row;
                    $priorityCell = 'E'.$row;

                    $status = strtolower(trim((string) $sheet->getCell($statusCell)->getValue()));
                    $priority = strtolower(trim((string) $sheet->getCell($priorityCell)->getValue()));

                    $sheet->getStyle($statusCell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $this->statusColor($status)],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle($priorityCell)->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $this->priorityColor($priority)],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                    ]);
                }
            },
        ];
    }

    private function statusColor(string $status): string
    {
        return match ($status) {
            'backlog'     => '6B7280',
            'todo'        => '2563EB',
            'in progress', 'in_progress' => 'D97706',
            'done'        => '16A34A',
            default       => '374151',
        };
    }

    private function priorityColor(string $priority): string
    {
        return match ($priority) {
            'low'      => '0EA5E9',
            'medium'   => '2563EB',
            'high'     => 'F59E0B',
            'critical' => 'DC2626',
            default    => '374151',
        };
    }
}
