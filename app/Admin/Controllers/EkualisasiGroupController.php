<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use App\Models\Client;
use App\Models\Ekualisasigroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Show;
use Illuminate\Http\Request;

use OpenAdmin\Admin\Widgets\Table;


class EkualisasiGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi Group';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
{
    $grid = new Grid(new Ekualisasigroup());

    // Define the columns for the grid
    $grid->column('id', 'Detail')->expand(function ($model) {
        // Mengambil semua data pemeriksaan yang terkait dengan client_id ini
        $details = $model->pemeriksaan()->with('MasaPajak', 'clientDataSummary')->get();
    
        // Fungsi untuk memproses detail dan mengisi variabel referensi
        $processDetails = function ($details) {
            return $details->map(function ($detail) {
                // Mengisi variabel dengan data dari $detail
                $client = $detail->clientDataSummary->nama_wp ?? 'Unknown';
                $masa_pajak_id = $detail->MasaPajak->masa_pajak ?? 'Unknown';
                $tanggal_masa_pajak = $detail->tanggal_masa_pajak;
                $diperiksa_oleh = $detail->diperiksa_oleh;
                $mengetahui = $detail->mengetahui;
                $status = $detail->status;
                
                // Set status with appropriate badge
                if ($status == "draft") {
                    $status = "<span class='badge bg-warning'>Draft</span>";
                } elseif ($status == "done") {
                    $status = "<span class='badge bg-success'>Done</span>";
                } else {
                    $status = "<span class='badge bg-danger'>-</span>";
                }
    
                return [
                    'Nama WP' => $client,
                    'Masa Pajak' => $masa_pajak_id,
                    'Tanggal Masa Pajak' => $tanggal_masa_pajak,
                    'Diperiksa Oleh' => $diperiksa_oleh,
                    'Mengetahui' => $mengetahui,
                    'Status' => $status,
                ];
            });
        };
    
        // Memproses data detail
        $data = $processDetails($details);
    
        // Menampilkan data dalam tabel dengan kolom yang telah ditentukan
        return new Table(['Nama WP', 'Masa Pajak', 'Tanggal Masa Pajak', 'Diperiksa Oleh', 'Mengetahui', 'Status'], $data->toArray());
    });
    
    // Define other columns
    $grid->column('client_id', __('ID Client'));
    $grid->column('nama_wp', __('Nama Client'));
    $grid->column('year', __('Year'));
    $grid->column('total_data', __('Total Data per Year'))->display(function() {
        return $this->total_data . ' Bulan';
    });

    // Customize actions
    $grid->actions(function ($actions) {
        $actions->disableDelete();
        $actions->disableEdit();
        $actions->disableShow();
    });

    // Filter section
    $grid->filter(function ($filter) {
        $filter->expand();

        $filter->column(1/2, function ($filter) {
            $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
        });

        $filter->column(1/2, function ($filter) {
            $filter->where(function ($query) {
                $query->whereHas('pemeriksaan', function ($query) {
                    $query->where('status', $this->input);
                });
            }, 'Status')->select(['draft' => 'Draft', 'done' => 'Done']);
        });
    });

    // Disable create button
    $grid->disableCreateButton();
    
    return $grid;
}

}