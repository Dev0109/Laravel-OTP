<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\ScooterStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScooterStatusRequest;
use App\Http\Requests\UpdateScooterStatusRequest;
use App\Http\Resources\Admin\ScooterStatusResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScooterStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scooter_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScooterStatusResource(ScooterStatus::all());
    }

    public function store(StoreScooterStatusRequest $request)
    {
        $scooterStatus = ScooterStatus::create($request->all());

        return (new ScooterStatusResource($scooterStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ScooterStatus $scooterStatus)
    {
        abort_if(Gate::denies('scooter_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScooterStatusResource($scooterStatus);
    }

    public function update(UpdateScooterStatusRequest $request, ScooterStatus $scooterStatus)
    {
        $scooterStatus->update($request->all());

        return (new ScooterStatusResource($scooterStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ScooterStatus $scooterStatus)
    {
        abort_if(Gate::denies('scooter_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scooterStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
