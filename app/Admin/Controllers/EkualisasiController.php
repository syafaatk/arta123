<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Client;
use \App\Models\Ekualisasi;
use \App\Models\Ekualisasiitem;
use \App\Models\Masapajak;
use Illuminate\Http\Request;
use \App\Models\Ekualisasidetail;
use OpenAdmin\Admin\Widgets\Table;


class EkualisasiController extends AdminController
{
     /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    
     protected function grid()
    {
        $grid = new Grid(new Ekualisasi());

        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [1, 2])->get();
            $details45 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [4, 5])->get();
            $details8 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [8])->get();
            $details910 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [9, 10])->get();
            $details12 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [12])->get();
            $details1314 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [13, 14])->get();
            $details161718 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [16, 17, 18])->get();
            $details19 = $model->ekualisasiDetails()->whereIn('item_pemeriksaan_id', [19])->get();

        
            // Function to process details and calculate quantities
            $processDetails = function ($details, &$quantity, &$jumlah, &$dpp, &$dppg, &$ppn) {
                return $details->map(function ($detail) use (&$quantity, &$jumlah, &$dpp, &$dppg, &$ppn) {
                    $itemName = $detail->item_ekualisasi->item_pemeriksaan;
        
                    if ($detail->item_pemeriksaan_id == 1) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 2) {
                        $quantity -= $detail->quantity;
                        $jumlah -= $detail->jumlah;
                        $dpp -= $detail->dpp_faktur_pajak;
                        $dppg -= $detail->dpp_gunggung;
                        $ppn -= $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 4) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 5) {
                        $quantity += $detail->quantity;
                        $jumlah += $detail->jumlah;
                        $dpp += $detail->dpp_faktur_pajak;
                        $dppg += $detail->dpp_gunggung;
                        $ppn += $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 8) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 9) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 10) {
                        $quantity += $detail->quantity;
                        $jumlah += $detail->jumlah;
                        $dpp += $detail->dpp_faktur_pajak;
                        $dppg += $detail->dpp_gunggung;
                        $ppn += $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 12) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 13) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 14) {
                        $quantity += $detail->quantity;
                        $jumlah += $detail->jumlah;
                        $dpp += $detail->dpp_faktur_pajak;
                        $dppg += $detail->dpp_gunggung;
                        $ppn += $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 16) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 17) {
                        $quantity += $detail->quantity;
                        $jumlah += $detail->jumlah;
                        $dpp += $detail->dpp_faktur_pajak;
                        $dppg += $detail->dpp_gunggung;
                        $ppn += $detail->ppn_pph;
                    } elseif ($detail->item_pemeriksaan_id == 18) {
                        $quantity += $detail->quantity;
                        $jumlah += $detail->jumlah;
                        $dpp += $detail->dpp_faktur_pajak;
                        $dppg += $detail->dpp_gunggung;
                        $ppn += $detail->ppn_pph;
                    }  elseif ($detail->item_pemeriksaan_id == 19) {
                        $quantity = $detail->quantity;
                        $jumlah = $detail->jumlah;
                        $dpp = $detail->dpp_faktur_pajak;
                        $dppg = $detail->dpp_gunggung;
                        $ppn = $detail->ppn_pph;
                    }
        
                    return [
                        'ID' => $detail->item_pemeriksaan_id,
                        'item_pemeriksaan' => $itemName,
                        'quantity' => number_format($detail->quantity, 0, ",", "."),
                        'jumlah' => number_format($detail->jumlah, 0, ",", "."),
                        'dpp_faktur_pajak' => number_format($detail->dpp_faktur_pajak, 0, ",", "."),
                        'dpp_gunggung' => number_format($detail->dpp_gunggung, 0, ",", "."),
                        'ppn_pph' => number_format($detail->ppn_pph, 0, ",", "."),
                        'keterangan' => $detail->keterangan,
                        // 'created_at' => $detail->created_at,
                    ];
                });
            };
        
            $data = $processDetails($details, $quantity12, $jumlah12, $dpp12, $dppg12, $ppn12);
        
            // Add the row for item_pemeriksaan_id 3 with the summed quantity
            $data[] = [
                'ID' => 3,
                'item_pemeriksaan' => '<b>Selisih (1-2)</b>',
                'quantity' => '<b>'.number_format($quantity12, 0, ",", "."),
                'jumlah' => '<b>'.number_format($jumlah12, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp12, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg12, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn12, 0, ",", "."),
                'keterangan' => 'Selisih 1 dan 2',
                // 'created_at' => now(),
            ];
        
            $data45 = $processDetails($details45, $quantity45, $jumlah45, $dpp45, $dppg45, $ppn45);
            $data = $data->merge($data45);
        
            // Add the row for item_pemeriksaan_id 6 with the summed quantity
            $data[] = [
                'ID' => 6,
                'item_pemeriksaan' => '<b>Total Nilai Transaksi (4+5)</b>',
                'quantity' => '<b>'.number_format($quantity45, 0, ",", "."),
                'jumlah' => '<b>'.number_format($jumlah45, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp45, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg45, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn45, 0, ",", "."),
                'keterangan' => 'Total 4 dan 5',
                // 'created_at' => now(),
            ];
            $data[] = [
                'ID' => 7,
                'item_pemeriksaan' => '<b>Selisih PPN Keluaran dan Total Transaksi (3-6)</b>',
                'quantity' => '<b>'.number_format($quantity12-$quantity45, 0, ",", ".").'</b>',
                'jumlah' => '<b>'.number_format($jumlah12-$jumlah45, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp12-$dpp45, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg12-$dppg45, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn12-$ppn45, 0, ",", "."),
                'keterangan' => 'Selisih 3 dan 6',
                // 'created_at' => now(),
            ];
            $data8 = $processDetails($details8, $quantity8, $jumlah8, $dpp8, $dppg8, $ppn8);
            $data = $data->merge($data8);
            $data910 = $processDetails($details910, $quantity910, $jumlah910, $dpp910, $dppg910, $ppn910);
            $data = $data->merge($data910);

            $data[] = [
                'ID' => 11,
                'item_pemeriksaan' => '<b>------</b>',
                'quantity' => '',
                'jumlah' => '',
                'dpp_faktur_pajak' => '',
                'dpp_gunggung' => '',
                'ppn_pph' => '',
                'keterangan' => '---------',
                // 'created_at' => now(),
            ];

            $data12 = $processDetails($details12, $quantity12, $jumlah12, $dpp12, $dppg12, $ppn12);
            $data = $data->merge($data12);
            $data1314 = $processDetails($details1314, $quantity1314, $jumlah1314, $dpp1314, $dppg1314, $ppn1314);
            $data = $data->merge($data1314);

            $data[] = [
                'ID' => 15,
                'item_pemeriksaan' => '<b>Selisih (8-12)</b>',
                'quantity' => '<b>'.number_format($quantity8-$quantity12, 0, ",", "."),
                'jumlah' => '<b>'.number_format($jumlah8-$jumlah12, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp8-$dpp12, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg8-$dppg12, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn8-$ppn12, 0, ",", "."),
                'keterangan' => 'Selisih (8-12)',
                // 'created_at' => now(),
            ];

            $data161718 = $processDetails($details161718, $quantity161718, $jumlah161718, $dpp161718, $dppg161718, $ppn161718);
            $data = $data->merge($data161718);

            $data19 = $processDetails($details19, $quantity19, $jumlah19, $dpp19, $dppg19, $ppn19);
            $data = $data->merge($data19);

            $data[] = [
                'ID' => 20,
                'item_pemeriksaan' => '<b>Total Ekspedisi & Pengangkutan di SPT PPh 21, 23 & 4(2) (16+17+18)</b>',
                'quantity' => '<b>'.number_format($quantity161718, 0, ",", "."),
                'jumlah' => '<b>'.number_format($jumlah161718, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp161718, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg161718, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn161718, 0, ",", "."),
                'keterangan' => 'Total (16+17+18)',
                // 'created_at' => now(),
            ];

            $data[] = [
                'ID' => 21,
                'item_pemeriksaan' => '<b>Selisih Biaya Ekspedisi & Pengangkutan di SPT PPh 21 & 23  dan L/R ((16+17+18)-19)</b>',
                'quantity' => '<b>'.number_format($quantity161718-$quantity19, 0, ",", "."),
                'jumlah' => '<b>'.number_format($jumlah161718-$jumlah19, 0, ",", "."),
                'dpp_faktur_pajak' => '<b>'.number_format($dpp161718-$dpp19, 0, ",", "."),
                'dpp_gunggung' => '<b>'.number_format($dppg161718-$dppg19, 0, ",", "."),
                'ppn_pph' => '<b>'.number_format($ppn161718-$ppn19, 0, ",", "."),
                'keterangan' => 'Selisih (16+17+18)-19',
                // 'created_at' => now(),
            ];
        
            return new Table(['ID', 'Item Ekualisasi', 'quantity', 'jumlah', 'dpp faktur pajak', 'dpp gungung', 'ppn pph', 'keterangan'], $data->toArray());
        });
        

        $grid->column('client_id', __('Nama Client'))->display(function($clientId) {return Client::find($clientId)->nama_wp;});
        $grid->column('masa_pajak_id', __('Masa Pajak'))->display(function($masapajakId) {return Masapajak::find($masapajakId)->masa_pajak;});
        $grid->column('tanggal_masa_pajak', __('Tanggal Masa Pajak'))->filter('range', 'date');;
        $grid->column('diperiksa_oleh', __('Diperiksa Oleh'));
        $grid->column('mengetahui', __('Mengetahui'));
        
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('deleted_at', __('Deleted at'));

        $grid->filter(function ($filter) {
            //$filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
                $filter->equal('masa_pajak_id')->select(Masapajak::all()->pluck('masa_pajak', 'id'));
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->between('tanggal_masa_pajak')->datetime();
            });
        });

        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->id;
            return "<a href='detail?pemeriksaan_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>";
        });

        
            //dd($data);
            $style = <<<STYLE
            <style>
                .table-responsive tr th {
                    text-align: center;
                }
                .table-responsive th,
                .table-responsive td {
                    border: 0.5px solid #ddd; /* Add border to both th and td elements */
                }

                .table-responsive {
                    border-collapse: collapse; /* Collapse borders for better styling */
                    width: 100%; /* Set width to 100% */
                }
            </style>
            STYLE;

            // Add custom styles to the table
            echo $style;

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Ekualisasi::with('ekualisasiDetails')->findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('client_id', __('Data Client'))->as(function ($client_id) {
            $client = Client::find($client_id);
        
            if ($client) {
                $nama_wp = $client->nama_wp;
                $npwp_wp = $client->npwp_wp;        
                return "$nama_wp - $npwp_wp";
            } else {
                return '';
            }
        });

        $show->field('masa_pajak_id', __('Masa Pajak'))->as(function($masapajakId) {return Masapajak::find($masapajakId)->masa_pajak;});
        $show->field('tanggal_masa_pajak', __('Tanggal Masa Pajak'));
        $show->field('diperiksa_oleh', __('Diperiksa Oleh'));
        $show->field('mengetahui', __('Mengetahui'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        return $show;
        
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ekualisasi());
        // $form->select('client_id', __("Nama Client"))->options(Client::all()->pluck('nama_wp', 'id'));
        $form->select('client_id',__("Nama Client"))->options(
            Client::select('id','nama_wp','npwp_wp')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->nama_wp . ' - ' . $item->npwp_wp];
                })
        );
        $form->select('masa_pajak_id', __("Masa Pajak"))->options(Masapajak::all()->pluck('masa_pajak', 'id'));
        $form->date('tanggal_masa_pajak', __('Tanggal Masa Pajak'))->default(date('Y-m-dd'));
        $form->text('diperiksa_oleh', __('Diperiksa Oleh'));
        $form->text('mengetahui', __('Mengetahui'));
        // Form untuk detail Ekualisasi
        $items = Ekualisasiitem::all()->pluck('item_pemeriksaan', 'id');

        for ($i = 1; $i <= 31; $i++) {
            if($i >= 1 and $i <= 3 ):
                $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 4 && $i <= 7 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 8 && $i <= 11 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 12 && $i <= 15 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 16 && $i <= 21 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 22 && $i <= 24 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 25 && $i <= 27 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            elseif($i >= 28 && $i <= 30 ):
            $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            endif;

            
            
            // Tambahkan kolom-kolom lainnya sesuai kebutuhan
        }
        return $form;
    }

    public function editall($id)
    {
        $form = $this->form()->edit($id);

        return $form;
    }

    public function storeall(Request $request)
    {
        // Validate your request data as needed

        // Create Ekualisasi record
        $ekualisasi = Ekualisasi::create([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
        ]);

        // Save DetailEkualisasi records
        $details = $request->input('detail_pemeriksaan');

        foreach ($details as $detail) {
            Ekualisasidetail::create([
                'pemeriksaan_id' => $ekualisasi->id,
                'item_pemeriksaan_id' => $detail['item_pemeriksaan_id'],
                'quantity' => $detail['quantity'],
                'jumlah' => $detail['jumlah'],
                'dpp_faktur_pajak' => $detail['dpp_faktur_pajak'],
                'dpp_gunggung' => $detail['dpp_gunggung'],
                'ppn_pph' => $detail['ppn_pph'],
                'keterangan' => $detail['keterangan'],
                // Add other fields as needed
            ]);
        }

        return redirect()->to('admin/ekualisasi/masters');
    }

    public function updateall(Request $request, $id)
    {
        // Validate your request data as needed

        // Find the existing Ekualisasi record
        $ekualisasi = Ekualisasi::findOrFail($id);

        // Update Ekualisasi record
        $ekualisasi->update([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
            // Add other fields as needed
        ]);

        // // Update or create DetailEkualisasi records
        // for ($i = 1; $i <= 30; $i++) {
        //     Ekualisasidetail::updateOrInsert(
        //         ['pemeriksaan_id' => $ekualisasi->id, 'item_pemeriksaan_id' => $request->input("detail_pemeriksaan.$i.item_pemeriksaan_id")],
        //         [
        //             'quantity' => $request->input("detail_pemeriksaan.$i.quantity"),
        //             'jumlah' => $request->input("detail_pemeriksaan.$i.jumlah"),
        //             'dpp_faktur_pajak' => $request->input("detail_pemeriksaan.$i.dpp_faktur_pajak"),
        //             'dpp_gunggung' => $request->input("detail_pemeriksaan.$i.dpp_gunggung"),
        //             'ppn_pph' => $request->input("detail_pemeriksaan.$i.ppn_pph"),
        //             'keterangan' => $request->input("detail_pemeriksaan.$i.keterangan"),
        //             // Add other fields as needed
        //         ]
        //     );
        // }

        return redirect()->to('admin/ekualisasi/masters');
    }

}