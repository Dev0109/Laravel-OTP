<?php

namespace App\Http\Controllers\Admin;

use App\ScooterStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyScooterStatusRequest;
use App\Http\Requests\StoreScooterStatusRequest;
use App\Http\Requests\UpdateScooterStatusRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScooterStatusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scooter_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scooterStatuses = ScooterStatus::all();

        return view('admin.scooterStatuses.index', compact('scooterStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('scooter_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scooterStatuses.create');
    }

    public function store(StoreScooterStatusRequest $request, ScooterStatus $scooterStatus)
    {
        $scooterStatus = ScooterStatus::create($request->all());

        abort_if(Gate::denies('scooter_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scooterStatuses.show', compact('scooterStatus'));
    }

    public function edit(ScooterStatus $scooterStatus)
    {
        abort_if(Gate::denies('scooter_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scooterStatuses.edit', compact('scooterStatus'));
    }

    public function update(UpdateScooterStatusRequest $request, ScooterStatus $scooterStatus)
    {
        $scooterStatus->update($request->all());

        return redirect()->route('admin.scooter-statuses.index');
    }

    public function show(ScooterStatus $scooterStatus)
    {
        abort_if(Gate::denies('scooter_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.scooterStatuses.show', compact('scooterStatus'));
    }

    public function destroy(ScooterStatus $scooterStatus)
    {
        abort_if(Gate::denies('scooter_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scooterStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyScooterStatusRequest $request)
    {
        ScooterStatus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
