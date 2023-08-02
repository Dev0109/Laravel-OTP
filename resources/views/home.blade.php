@extends('layouts.admin')
@section('content')
<h1>@lang('DASHBOARD')</h1>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('ACER STATISTICS')</h4>
            <p class="card-text">(login status)</p>
            <ul>
                <li><a href="{{ route('admin.history') }}" >
                    <i class="fas fa-fw fa-history"></i>
                    @lang('History')
                </a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('CUSTOMER STATISTICS')</h4>
            <p class="card-text">customer t.o.</p>
            <ul>
                <li><a href="#" >@lang('price list validity')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('QUALITY')</h4>
            <p class="card-text">process notification status</p>
            <ul>
                <li><a href="#" >@lang('component production')</a></li>
                <li><a href="#" >@lang('programming')</a></li>
                <li><a href="#" >@lang('end line testing')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('MAINTENANCE')</h4>
            <p class="card-text">status</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('WARRANTY STATUS')</h4>
            <p class="card-text">how many units are in the warranty period</p>
            <a href="#" >@lang('open warranties')</a>                
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('SERVICE STATUS')</h4>
            <p class="card-text">activities who is doing what</p>
            <a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/warranty_procedure/" >@lang('Service procedures')</a>
            <ul class="ml-3">
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/error_list/" >@lang('error list')</a></li>
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/accessory/" >@lang('how to')</a></li>
            </ul>
        </div>
    </div>
</div>
<hr>
<h1>@lang('UTILITIES')</h1>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">SALE</h4>
            <ul>
                <li><a href="#" >@lang('news')</a>
                    <ul class="ml-3">
                        <li><a href="#">@lang('forum')</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" >@lang('tools')</a>
                    <ul class="ml-3">
                        <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/tools/KTS_virtual/KTS_AV_2_32.zip" >@lang('KTS')</a></li>
                        <li><a href="https://dev-ktp-web-new.onrender.com" >@lang('KTP')</a></li>
                        <li><a href="#" >@lang('sum')</a></li>
                        <li><a href="#" >@lang('upgrade')</a></li>
                    </ul>
                </li>
                <li><a href="#" >@lang('services')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('PRODUCTION')</h4>
            <ul>
                <li><a href="#" >@lang('table tracker')</a></li>                
                <li><a href="#" >@lang('production status')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('MAINTENANCE AND SERVICE')</h4>
            <ul>
                <li><a href="#" >@lang('contracts')</a></li>                
                <li><a href="#" >@lang('agreements')</a></li>
                <li><a href="#" >@lang('Configuration App')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('QUALITY')</h4>
            <p class="card-text">@lang('warranty management')</p>            
        </div>
    </div>    
</div>

@endsection
