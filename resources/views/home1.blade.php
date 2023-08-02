@extends('layouts.admin')
@section('content')
<h2>Dashboard</h2>
<div class="row">
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo1">ACER Statistics</button>
        <div id="demo1" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Customer login statistics</li>
                <li class="list-group-item">Total amount of the offer / month</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo2">SALES Statistics</button>
        <div id="demo2" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Customer turn over</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo3">QUALITY Statistics</button>
        <div id="demo3" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Component production</li>
                <li class="list-group-item">Programming</li>
                <li class="list-group-item">End line testing</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo4">WARRANTY Statuses</button>
        <div id="demo4" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">How many units are in the warranty in period</li>
                <li class="list-group-item">Open warranties</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo5">SERVICE Statuses</button>
        <div id="demo5" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Activities who is doing what</li>
                <li class="list-group-item">Open non conformities</li>
            </ul>
        </div>
    </div>
</div>
<hr>
<h2>Utilities</h2>
<div class="row">
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo6">SALE</button>
        <div id="demo6" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">news</li>
                <li class="list-group-item">tools</li>
                <li class="list-group-item">
                    <ul class="list-group pl-3">
                        <li class="list-group-item">virtual kts</li>
                        <li class="list-group-item">virtual kps</li>
                        <li class="list-group-item">sum</li>
                        <li class="list-group-item">upgrade</li>
                    </ul>
                </li>                
                <li class="list-group-item">services</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo7">PRODUCTION</button>
        <div id="demo7" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Table tracker</li>
                <li class="list-group-item">Production status</li>
            </ul>
        </div>
    </div>
    <div class="col-md-2">
        <button type="button" class="btn btn-info btn-block" data-toggle="collapse" data-target="#demo8">QUALITY</button>
        <div id="demo8" class="collapse mt-3">
            <ul class="list-group">
                <li class="list-group-item">Warranty management</li>
                <li class="list-group-item">Q1 decorder</li>
            </ul>
        </div>
    </div>
</div>

@endsection
