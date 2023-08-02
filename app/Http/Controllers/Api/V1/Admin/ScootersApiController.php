<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScooterRequest;
use App\Http\Requests\UpdateScooterRequest;
use App\Http\Resources\Admin\ScooterResource;
use App\Scooter;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScootersApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('scooter_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScooterResource(Scooter::with(['users'])->get());
    }

    public function store(StoreScooterRequest $request, Scooter $scooter)
    {
        $scooter = Scooter::create($request->all());
        $scooter->users()->sync($request->input('users', []));

        return (new ScooterResource($scooter->load(['users'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    
    public function show(Scooter $scooter)
    {
        abort_if(Gate::denies('scooter_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ScooterResource($scooter->load(['users']));
    }

    public function update(UpdateScooterRequest $request, Scooter $scooter)
    {
        $scooter->update($request->all());
        $scooter->users()->sync($request->input('users', []));

        return (new ScooterResource($scooter))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Scooter $scooter)
    {
        abort_if(Gate::denies('scooter_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $scooter->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}