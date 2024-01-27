<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Kppar;
use \App\Models\Kpp;

class KpparController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Kppar';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Kppar());
        
        $grid->column('id', __('Id'));
        
        $grid->column('name_ar', __('Name ar'));
        $grid->column('telp_ar', __('Telp ar'));
        $grid->column('kpp_id', __('Lokasi KPP'))->display(function($kppId) {return Kpp::find($kppId)->name_kpp;});
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

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
        $show = new Show(Kppar::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_ar', __('Name ar'));
        $show->field('telp_ar', __('Telp ar'));
        $show->field('kpp_id', __('KPP'))->as(function ($kppId) {
            return Kpp::find($kppId)->name_kpp;;
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        //->css_file(Admin::asset("open-admin/css/pages/dashboard.css"))
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Kppar());
        $form->select('kpp_id', __("Lokasi KPP"))->options(Kpp::all()->pluck('name_kpp', 'id'));
        $form->text('name_ar', __('Name ar'));
        $form->text('telp_ar', __('Telp ar'));

        return $form;
    }
}
