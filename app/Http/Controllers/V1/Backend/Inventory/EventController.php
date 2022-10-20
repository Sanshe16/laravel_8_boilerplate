<?php

namespace App\Http\Controllers\V1\Backend\Inventory;

Use App\DataTables\EventDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InventoryServices\EventService;
use App\Http\Requests\InventoryRequests\StoreEventRequest;
use App\Models\Event;
use App\Models\EventMedia;
use App\Traits\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    use ApiResponse;

    protected $eventService;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(EventService $eventService)
    {
        $this->middleware('auth');
        $this->eventService = $eventService;
    }

    public function index(EventDataTable $dataTable)
    {
        return $dataTable->render('backend.events.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $this->eventService->store($request);
        return $this->success(trans('admin.CREATE_EVENT'), ['success' => true, 'data' => null]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        $event_image =  $event->eventMedia()->get();
        return view('backend.events.show', compact('event', 'event_image'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $event_image =  $event->eventMedia()->get();
        return view('backend.events.edit', compact('event', 'event_image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\StoreEventRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEventRequest $request, $id)
    {
        $this->eventService->update($request, $id);
        return $this->success(trans('admin.UPDATE_EVENT'), ['success' => true, 'data' => null]);
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
            $this->eventService->destroy($id);
            return $this->success(trans('admin.DELETE_EVENT'), ['success' => true, 'data' => null]);
        }
        catch (\Throwable $th) {
            return $this->error('Record not found', Response::HTTP_NOT_FOUND, ['success' => false, 'data' => null]);
        }
    }
}
