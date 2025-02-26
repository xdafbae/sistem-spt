<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('roles', function ($user) {
                // Menampilkan roles user sebagai string
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('action', function ($user) {
                // Tombol Edit dan Delete
                return '
                    <button class="btn btn-warning btn-sm edit-btn" data-id="' . $user->id . '">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="' . $user->id . '">Delete</button>
                ';
            })
            ->rawColumns(['action']); // Mengizinkan HTML di kolom action
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        // Query untuk mengambil data user beserta roles
        return $model->newQuery()->with('roles');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create')->text('Tambah User')->action('addUser()'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('nama'),
            Column::make('email'),
            Column::make('roles')->title('Role'), // Kolom untuk roles
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }
}