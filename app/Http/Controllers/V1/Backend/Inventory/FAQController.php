<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

use App\Http\Controllers\Controller;
use App\DataTables\FaqDataTable;
use App\Http\Requests\InventoryRequests\StoreFAQsRequest;
use App\Services\InventoryServices\FaqService;
use App\Models\Faq;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class FAQController extends Controller
{
        use ApiResponse;
        protected $faqService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(FaqService $faqService)
    {
        $this->middleware('auth');
        $this->faqService = $faqService;
    }
    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('backend.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $StoreFAQsRequest
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFAQsRequest $request)
    {
        $this->faqService->store($request);
        return $this->success(trans('admin.CREATE_FAQ'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faq = Faq::find($id);
        return view('backend.faqs.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faq = Faq::find($id);
        return view('backend.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreFAQsRequest $request, $id)
    {
        $this->faqService->update($request, $id);
        return $this->success(trans('admin.UPDATE_FAQ'), ['success' => true, 'data' => null]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $this->faqService->destroy($id);
            return $this->success(trans('admin.DELETE_FAQ'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) {
            return $this->error('Record not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
