<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasitahunan;
use \App\Models\Ekualisasiitem;
use \App\Models\Tahunan;
use \App\Models\Client;
use OpenAdmin\Admin\Widgets\Table;
use Illuminate\Support\Facades\DB;

class EkualisasitahunanController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi Tahunan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        
        $grid = new Grid(new Ekualisasitahunan());
        $grid->column('id', __('ID'));
        $grid->column('client_id', 'Client ID');
        $grid->column('tahun', 'Tahun');    
        $grid->column('keterangan', 'Nama Ekualisasi Tahunan');
        $grid->disableCreateButton();
        $grid->paginate(33);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableShow();
        });
        $grid->filter(function ($filter) {
            //$filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
            });
        });
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->client_id;
            return "<a href='tahunan-detail?client_id={$url}' class='btn btn-xs btn-primary'>Lihat Detail</a>";
        });

        $grid->disableActions();
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
        $show = new Show(Ekualisasitahunan::findOrFail($id));

        $show->field('client_id', __('Id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ekualisasitahunan());

        $form->text('item_pemeriksaan_id', __('Item_pemeriksaan'));

        return $form;
    }
}
