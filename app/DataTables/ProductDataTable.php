<?php

namespace App\DataTables;

use App\Models\Product;
use App\Models\ShippingType;
use App\Repositories\InventoryRepositories\ProductRepository;
use App\Models\ProductMedia;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
            ->addColumn('image', function($product){
                $product_image = ProductMedia::select('product_image')->where('product_id', $product->id)->first();
                getThumbnailAndOriginalImage($product_image, 'product_image');
                $thumbnail_image = '<a href="'. url($product_image['original_image']) .'" target="_blank"><img width="100px" src="'. url($product_image['thumb_image']) .'"></a>';
                return $thumbnail_image;
            })
            ->addColumn('action',  function($row){
                $btn = '<a href="'.route('admin.products.show', $row->id).'" data-id="'.$row->id.'" class="view-products gifter_hover_btn pr-2" data-toggle="tooltip" data-placement="buttom" title="View"><i class="ti-eye" style="font-size:17px;"></i></a>';
                $btn = $btn.'<a href="'.route('admin.products.edit', $row->id).'" data-id="'.$row->id.'" class="edit-products gifter_hover_btn pr-2" data-toggle="tooltip" data-placement="buttom" title="Edit"><i class="ti-pencil" style="font-size:17px;color:gray"></i></a>';
                $btn = $btn.'<a href="javascript:void(0)" data-id="'.$row->id.'" class="delete-products gifter_hover_btn" data-toggle="tooltip" data-placement="buttom" title="Delete"><i class="ti-trash" style="font-size:17px;color:red"></i></a>';
                return $btn;
            })->rawColumns(['image', 'action']);


    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload'),
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
            Column::computed('image')
                ->exportable(false)
                ->printable(false),
            Column::make('id'),
            Column::make('name')
                ->addClass('gifter_datatable_name_truncate'),
            Column::make('sku')
                ->addClass('gifter_datatable_sku_truncate'),
            Column::make('price'),
            Column::make('quantity'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center')
                  ->width("10%"),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Product_' . date('YmdHis');
    }
}
