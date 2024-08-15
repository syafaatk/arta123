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
        $grid->column('client_id', 'Detail')->expand(function ($model) {
            // Mengambil semua data pemeriksaan yang terkait dengan client_id ini
            $details = $model->pemeriksaan()->get();
        
            // Fungsi untuk memproses detail dan mengisi variabel referensi
            $processDetails = function ($details) {
                return $details->map(function ($detail) {
                    // Mengisi variabel dengan data dari $detail
                    $client = $detail->clientDataSummary->nama_wp ?? 'Unknown';
                    //dd($client_id);
                    $masa_pajak_id = $detail->MasaPajak->masa_pajak;
                    $tanggal_masa_pajak = $detail->tanggal_masa_pajak;
                    $diperiksa_oleh = $detail->diperiksa_oleh;
                    $mengetahui = $detail->mengetahui;
                    $status = $detail->status;
        
                    return [
                        'Nama WP' => $client,
                        'Masa Pajak ID' => $masa_pajak_id,
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
            return new Table(['Nama WP','Masa Pajak ID', 'Tanggal Masa Pajak', 'Diperiksa Oleh', 'Mengetahui', 'Status'], $data->toArray());
        });
        
        
        $grid->column('nama_wp', __('Nama Client'));
        $grid->column('year', __('Year'));
        $grid->column('total_data', __('Total Data per Year'));

        return $grid;
    }
}