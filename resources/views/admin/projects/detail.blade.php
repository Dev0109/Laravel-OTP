@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
    .chart-tab-active {
        display: block !important;
    }
    #pricetable tbody td {
        vertical-align: middle !important;
    }
    .price-image {
        width: 150px;
        margin: auto;
    }
    .units-table th {
        padding: 5px;
    }
    .units-table tbody td {
        padding: 5px;
    }
    .units-delivery-time-table th {
        padding: 5px;
    }
    .units-delivery-time-table tbody td {
        padding: 5px;
    }
</style>
<?php
    $units_list = json_decode($units);
?>
<div class="w-full my-3">
    <table class="display compact units-table datatable-t1" style="visibility: hidden; width: 100%;">
        <thead>
            <tr>
                <th>@lang('Unit Name')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ($units_list)
            @foreach ($units_list as $row)
            <tr class="unit-row" data-name="{{$row->name}}">
                <td>{{$row->name}}</td>
                <td align="center">
                    <button type="button" class="btn btn-primary" onclick="onViewUnit(`{{$row->name}}`)">View</button>
                    <button type="button" class="btn btn-success" onclick="onEditUnit(`{{$row->name}}`)">Edit</button>
                    <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`{{$row->name}}`)">Delete</button>
                    <button type="button" class="btn btn-info" onclick="onAddOffer(`{{$row->name}}`)">Add Offer</button>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
        <tfoot>
            @if ($units_list && count($units_list) > 0)
            <tr>
                <td colspan="2" align="center">
                    <a class="btn btn-success btn-block button-boxed" onclick="onNewUnit()">
                        <i class="fa fa-plus"></i>
                        <small>@lang('Add Unit')</small>
                    </a>
                </td>
            </tr>
            @endif
        </tfoot>
    </table>
</div>
<div class="main-card project-detail">    
    <div class="body">
        <div class="row">
            <div class="col-md-5" id="unitform" style="display: none;">
                <div class="form-group row">
                    <label for="unit_name" class="col-md-3 col-form-label">@lang('Unit Name')</label>
                    <div class="col-md-8">
                    <input type="text" class="form-control" id="unit_name" placeholder="Unit Name" value="" disabled="disabled">
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="tabs tabs-primary">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link active" href="#tab0" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('PROJECT REFERENCE')</a>
                    </li>
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link disabled" id="tab_unit_selection" href="#tab1" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('UNIT SELECTION')</a>
                    </li>
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link disabled" id="tab_results_table" href="#tab2" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('UNIT FEATURES')</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab0" class="tab-pane active pt-3" role="tabpanel">
                        <div class="border border-dark rounded px-5 py-1 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_name" class="text-xs">@lang('Project Name')</label>
                                    <input type="text" id="project_name" name="project_name" class="form-control" value="{{$project->name ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="project_desc" class="text-xs">@lang('Project Description')</label>
                                    <input type="text" id="project_desc" name="project_desc" class="form-control" value="{{$project->description ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="project_reference" class="text-xs">@lang('Project Reference')</label>
                                    <input type="text" id="project_reference" name="project_reference" class="form-control" value="{{$project->reference ?? ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_date" class="text-xs">@lang('Creation Date')</label>
                                    <?php
                                        $c_date = null;
                                        if(isset($project->created_at))
                                        {
                                            $date = strtotime($project->created_at);
                                            $c_date = date('m/d/Y', $date);
                                        } else {
                                            $c_date = date('m/d/Y'); 
                                        }
                                    ?>
                                    <input type="text" id="create_date" name="create_date" class="form-control" value="{{$c_date}}" readonly>
                                </div>
                                <div class="form-group">
                                    <?php
                                        $m_date = null;
                                        if(isset($project->updated_at))
                                        {
                                            $date = strtotime($project->updated_at);
                                            $m_date = date('m/d/Y', $date);
                                        } else {
                                            $m_date = date('m/d/Y'); 
                                        }
                                    ?>
                                    <label for="modify_date" class="text-xs">@lang('Last Modified Date')</label>
                                    <input type="text" id="modify_date" name="modify_date" class="form-control" value="{{$m_date}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab1" class="tab-pane pt-3 px-3" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12 col-xl-6">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('Airflow data')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4">
                                        <div class="text-center">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="title" class="text-xs">@lang('Application type')</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="p_layout">
                                                            <option value="C" selected>@lang('Centralized')</option>
                                                            <option value="D">@lang('Decentralized')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="title" class="text-xs">@lang('Installation type')</label>
                                                    <div class="form-group">
                                                        <label class="label-chb"><input type="radio" name="indoor" value="I" checked/> @lang('Indoor')</label>
                                                        <label class="label-chb"><input type="radio" name="indoor" value="O"/> @lang('Outdoor')</label>
                                                        <i class="fa fa-warning text-danger"></i>
                                                    </div>
                                                </div>            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div>
                                                        <label class="text-xs">@lang('Recovery technology')</label>
                                                        <i class="fa fa-warning text-danger"></i>
                                                    </div>
                                                    <div class="flex">
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/ST_LT.png') }}');background-position: center;background-size: 70%;"></div>
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="CF|LT" checked/> @lang('Standard plate')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/ST_EN.png') }}');background-position: center;background-size: 70%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="CF|EN"/> @lang('Entalphic plate')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/RT_LT.png') }}');background-position: center;background-size: 65%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="RT|LT"/> @lang('Standard rotary')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/RT_EN.png') }}');background-position: center;background-size: 65%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="RT|EN"/> @lang('Entalphic rotary')</label>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-lg-12 col-xl-6">
                                                        <h6>@lang("Supply")</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="p_airflow" class="text-xs">@lang('Airflow rate')(m3/h)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_airflow" name="p_airflow" class="form-control" value="200">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="p_pressure" class="text-xs">@lang('Airflow pressure')(Pa)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_pressure" name="p_pressure" class="form-control" value="50">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6">
                                                        <h6>@lang("Return")</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="p_r_airflow" class="text-xs">@lang('Airflow rate')(m3/h)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_r_airflow" name="p_r_airflow" class="form-control" value="200">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="p_r_pressure" class="text-xs">@lang('Airflow pressure')(Pa)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_r_pressure" name="p_r_pressure" class="form-control" value="50">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                 
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-3">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('Climatic data')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4 position-relative">
                                        <div>
                                            <h5>@lang("Winter Conditions")</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_w_Tfin" class="text-xs">@lang("Fresh Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Tfin" name="p_w_Tfin" class="form-control" min="-20" max="40"  value="-10">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_w_Trin" class="text-xs">@lang("Return Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Trin" name="p_w_Trin" class="form-control" min="-20" max="40"  value="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_w_Hfin" class="text-xs">@lang("Fresh Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Hfin" name="p_w_Hfin" class="form-control" min="5" max="98"  value="80">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_w_Hrin" class="text-xs">@lang("Return Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Hrin" name="p_w_Hrin" class="form-control" min="5" max="98"  value="60">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h5>@lang("Summer Conditions")</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_s_Tfin" class="text-xs">@lang("Fresh Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Tfin" name="p_s_Tfin" class="form-control" min="-20" max="40"  value="-10">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_s_Trin" class="text-xs">@lang("Return Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Trin" name="p_s_Trin" class="form-control" min="-20" max="40"  value="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_s_Hfin" class="text-xs">@lang("Fresh Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Hfin" name="p_s_Hfin" class="form-control" min="5" max="98"  value="80">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_s_Hrin" class="text-xs">@lang("Return Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Hrin" name="p_s_Hrin" class="form-control" min="5" max="98"  value="60">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-lg-12 col-xl-3">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('Accessories')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4 position-relative">
                                        <div class="mt-3">
                                            <p><label><input type="checkbox" checked> @lang('Without coil')</label></p>
                                            <p><label><input type="checkbox"> @lang('With defrosting electrical coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_w_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_w_i_Temperature" name="h_w_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_w_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_w_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_w_o_Temperature" name="h_w_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_w_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With heating coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Summer"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_i_Temperature" name="h_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_o_Temperature" name="h_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With cooling coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_i_Temperature" name="h_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_o_Temperature" name="h_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With reversible coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_w_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_w_i_Temperature" name="r_w_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_w_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_w_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_w_o_Temperature" name="r_w_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_w_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <h6>@lang("Temperature") (@lang("Summer"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_s_i_Temperature" name="r_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_s_o_Temperature" name="r_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-end">
                            <a class="btn btn-outline-secondary button-right btn-display-models" onclick="display_compatible_models(null)">@lang('Display compatible models')</a>
                        </div>
                    </div>
                    <div id="tab2" class="tab-pane" role="tabpanel">
                        <div class="w-full mt-3 models-tbl-container">
                            <table class="display compact project-table datatable-t1">
                                <thead>
                                    <tr>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full mt-3">
                            <div class="tabs tabs-primary graph-tabs hidden">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link active" href="#tab3" data-bs-toggle="tab" aria-selected="true" role="tab">
                                            @lang('PRESSURE CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab4" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('POWER CONSUMPTION CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab5" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('PSFP CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab6" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('EFFICIENCY CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab7" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('NOISE LEVEL')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab8" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('TECHNICAL INFORMATION')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#tab9" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('PRICE')</a>
                                    </li>
                                </ul>
                                <div class="tab-content chart-tab-content">
                                    <div id="tab3" class="tab-pane active pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <img id="render" src="">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="pressure_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div id="tab4" class="tab-pane pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                                                                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="power_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab5" class="tab-pane pt-3" role="tabpanel">
									    <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                     
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="psfp_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab6" class="tab-pane pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                                                       
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="efficiency_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab7" class="tab-pane pt-3" role="tabpanel">
                                    </div>
                                    <div id="tab8" class="tab-pane p-3" role="tabpanel">
                                    </div>
                                    <div id="tab9" class="tab-pane p-3" role="tabpanel">
                                        <div class="w-full" id="pricetable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- Modal -->
<div class="modal fade" 
    id="staticBackdrop" 
    data-backdrop="static" 
    data-keyboard="false" 
    tabindex="-1" 
    aria-labelledby="staticBackdropLabel" 
    aria-hidden="true"
    >
    <div class="modal-dialog">
        <div class="modal-content modal-content-add-unit">
            <div class="modal-body">
                @lang("Would you add more Unit?")
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary btn-multiple">Yes</button>
                <button type="button" class="btn btn-info btn-multiple">No</button>
            </div>
        </div>
        <div class="modal-content modal-content-save-delivery-time" style="display: none;">
            <div class="modal-body">
                <div class="w-full my-3">
                    <table class="display compact units-delivery-time-table datatable-t1" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('Unit Name')</th>
                                <th>@lang('Delivery Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary" onclick="onSaveDeliveryTime()">Save</button>
                <button type="button" class="btn btn-info btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
@endsection
@section('scripts')
    @parent
    <script>
        let table = null;
        var airflow = null;
        var pressure = null;
        var w_Trin = null;
        var w_Hrin = null;
        var w_Tfin = null;
        var w_Hfin = null;        
        var model_id = null;
        var selected_model = null;
        var dTable = null;
        var logoImgDataURL = null;
        var renderImgData = null;
        var savedDoc = null;
        var savedPreviewDoc = null;
        var powerconsumption = null;
        var regulation = null;
        var unitsel = null;
        var psfp = null;
        var multiple_selection = false;
        var saveLoading = false;
        var saveLoadingId = null;
        var doc = null;
        var pagenumber = 1;
        var totalpage = 3;
        var isNew = true;
        var isView = false;
        var pdf_units = [];
        var isLoadImage = false;
        var loadImageId = null;
        <?php
            $user_multiplier = auth()->user()->multiplier;
            if ($user_multiplier) {
                $user_multiplier = floatval(explode('_', $user_multiplier)[1]);
            } else {
                $user_multiplier = 1;
            }
        ?>
        <?php
            if($pid > 0){
                echo 'var units = JSON.parse(`'. $units .'`);';
            } else {
                echo 'var units = [];';
            }
        ?>
        var user_multiplier = parseFloat({{$user_multiplier}});
        var price_validity = "{{auth()->user()->delivery_time ?? ''}}"
        var unit_name = '';
        var edit_unit_name = '';
        var edit_unit_index = 0;

        function initBox() {
            $('.box .box-header .btn').on('click', function() {
                $(this).closest('.box').find('.box-body').slideToggle();
                $(this).find('i').toggleClass('fa-minus').toggleClass('fa-plus');
            });
        }
        
        $('a.image-item').on('click', function(e) {
            e.preventDefault();
            $(this).find('input')[0].checked = true;
        });

        function showSwalLoading(title = "@lang('Please wait...')", text = "@lang('Please wait...')") {
            Swal.fire({
                title: title,
                // text: text,                
                allowOutsideClick: false,
                showConfirmButton:false,
                showCancelButton:false,
                allowEscapeKey: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });   
        }

        function display_compatible_models(callback, showLoading = true) {
            unit_name = $('#unit_name').val().trim();
            if (unit_name === ''){
                document.querySelector('.nav-link[href="#tab1"]').click();
                alert("@lang('Please type Unit Name')");
                $('#unit_name').focus();
                return;
            }
            let n = units.length;
            if (!isView) {
                for (let i = 0; i < n; i++) {
                    if (!isNew && i == edit_unit_index) {
                        continue;
                    }
                    if (units[i].name == unit_name) {
                        alert(`Unit ${unit_name} already exists.`);
                        $('#unit_name').focus();
                        return;
                    }
                }
            }
            var params = {
                layout:$('#p_layout').val(),
                indoor:$('input[name=indoor]:checked').val(),
                ex1:$('input[name=ex]:checked').val().split('|')[1],
                ex2:$('input[name=ex]:checked').val().split('|')[0]
            };

            airflow = $.trim($('#p_airflow').val());
            if(airflow === '' || isNaN(parseFloat(airflow)) ||  parseFloat(airflow) <= 0) {
                alert("@lang('Airflow must be greater than 0')");
                $('#p_airflow').focus();
                return false;
            }
            params.airflow = parseFloat(airflow);

            pressure = $.trim($('#p_pressure').val());
            if(pressure === '' || isNaN(parseFloat(pressure)) ||  parseFloat(pressure) <= 0) {
                alert("@lang('Pressure must be greater than 0')");
                $('#p_pressure').focus();
                return false;
            }
            params.pressure = parseFloat(pressure);

            w_Trin = $.trim($('#p_w_Trin').val());
            if(w_Trin === '' || isNaN(parseFloat(w_Trin)) ||  parseFloat(w_Trin) < -20 ||  parseFloat(w_Trin) > 40) {
                alert("@lang('Exhaust Air temperature(°C) must be greater than or equal to -20 and less than or equal to 40')");
                $('#p_w_Trin').focus();
                return false;
            }
            params.Trin = parseFloat(w_Trin);

            w_Hrin = $.trim($('#p_w_Hrin').val());
            if(w_Hrin === '' || isNaN(parseFloat(w_Hrin)) ||  parseFloat(w_Hrin) < 5 || parseFloat(w_Hrin) > 98 ) {
                alert("@lang('Exhaust Air humidity(%) must be greater than or equal to 5 and less than or equal to 98')");
                $('#p_w_Hrin').focus();
                return false;
            }
            params.Hrin = parseFloat(w_Hrin);

            w_Tfin = $.trim($('#p_w_Tfin').val());
            if(w_Tfin === '' || isNaN(parseFloat(w_Tfin)) ||  parseFloat(w_Tfin) < -20 || parseFloat(w_Tfin) > 40) {
                alert("@lang('Fresh Air temperature(°C) must be greater than or equal to -20 and less than or equal to 40')");
                $('#p_w_Tfin').focus();
                return false;
            }
            params.Tfin = parseFloat(w_Tfin);

            w_Hfin = $.trim($('#p_w_Hfin').val());
            if(w_Hfin === '' || isNaN(parseFloat(w_Hfin)) ||  parseFloat(w_Hfin) < 5 ||  parseFloat(w_Hfin) > 98) {
                alert("@lang('Fresh Air humidity(%) must be greater than or equal to 5 and less than or equal to 98')");
                $('#p_w_Hfin').focus();
                return false;
            }
            params.Hfin = parseFloat(w_Hfin);

            // Todo: send and receive data from API
            if (showLoading)
                showSwalLoading(); 

            $.ajax({
                method: 'GET',
                url: '{{route('admin.projects.get.models')}}',
                data: params
            }).done(function (res) { 
                var result = res.result;
                
                $('#tab_results_table').removeClass("disabled");
                $('#tab_results_table').closest('ul.nav-tabs').find('.nav-link.active').removeClass('active');
                $('#tab_results_table').addClass('active');
                $('#tab_results_table').closest('.tabs').find('#tab0').removeClass('active'); 
                $('#tab_results_table').closest('.tabs').find('#tab1').removeClass('active'); 
                $('#tab_results_table').closest('.tabs').find('#tab2').addClass('active');
                if (result != null && result != 'Empty')
                    initTable(result, callback);
                if (showLoading)
                    swal.close();
            });

            $('.main-header h3').text("@lang('Project'): " + $('#project_name').val().trim() + ' ' + $('#project_reference').val().trim());
        }

        function initTable(data, callback) {
            $('.models-tbl-container').empty();
            var $dt = $(`<table class="display compact project-table datatable-t1">\
                    <thead>\
                        <tr>\
                            <th>@lang("Model")</th>\
                            <th>@lang("Airflow")<br/>[m³/h]</th>\
                            <th>@lang("Pressure")<br/>[Pa]</th>\
                            <th>@lang("Power")<br/>[W]</th>\
                            <th>@lang("Unit-SEL")<br/>[kW/m³/h]</th>\
                            <th>@lang("Efficiency")<br/>[%]</th>\
                            <th>@lang("Noise Power")<br/>[dB(A)]</th>\
                            <th>@lang("Noise Pressure") (@3m distance)<br/>[dB(A)]</th>\
                            <th>@lang("Reg. level")<br/>[%]</th>\
                        </tr>\
                    </thead>\
                    <tbody>\                                  
                    </tbody>\
                </table>`);
            $('.models-tbl-container').html($dt);
            for(var i=0;i<Object.keys(data).length;i++) {
                var $row = $('<tr></tr>');
                var model = Object.keys(data)[i];
                $row.append('<td data-id="' + data[model]["id"] +'">' + model + '</td>');
                $row.append('<td>' + data[model]["Airflow"] + '</td>');
                $row.append('<td>' + data[model]["Pressure"] + '</td>');
                $row.append('<td>' + data[model]["Power"] + '</td>');
                $row.append('<td>' + data[model]["Unit-SEL"] + '</td>');
                $row.append('<td>' + data[model]["Efficiency"] + '</td>');
                $row.append('<td>' + data[model]["Lw"] + '</td>');
                $row.append('<td>' + data[model]["Lp30"] + '</td>');
                $row.append('<td>' + data[model]["Reg"] + '</td>');
                $dt.find('tbody').append($row);

                if(callback !== undefined && callback !== null) {
                    callback(data[model]["id"], model, data[model]["Reg"]);
                }
                document.querySelector('.nav-link[href="#tab3"]').click();
                $('.tabs.graph-tabs').addClass("hidden");
            }

            dTable = $('.models-tbl-container').find('table').DataTable({
                dom:'t',
                scrollY: '200px',
                paging: false,
                responsive: true,
                select: ('{{$option}}' === 'readonly') ? false :{
                    style: 'single' // or 'multi'
                },
                rowCallback: function(row, data) {
                    if('{{$option}}' !== 'readonly') {
                        $(row).off();
                        $(row).on('click', function() {
                            var model = $(this).find('td:first-child').text();
                            selected_model = model;
                            var id = $(this).find('td:first-child').data('id');
                            var reg = $(this).find('td:last-child').text();
                            loadFromModel(id, reg, model);
                            initPriceTable(id);
                        });
                    }                    
                }
            });
        }

        function initPriceTable(id, price_id = 0, callback) {
            if (id == null){
                id = 0;
            }
            $.ajax({
                type: 'GET',
                url: "{{route('admin.projects.get.modelprice')}}",
                data: {
                    id: id,
                },
                success: function(res) {
                    res = JSON.parse(res);
                    console.log(1);
                    $('#pricetable').html("<table class='table display compact datatable-t1'>\
                            <thead>\
                                <tr>\
                                    <th></th>\
                                    <th>@lang('IMAGE')</th>\
                                    <th>@lang('ITEMCODE')</th>\
                                    <th>@lang('PRIMARY DESCRIPTION')</th>\
                                    <th>@lang('SECONDARY DESCRIPTION')</th>\
                                    <th>@lang('PRICE')</th>\
                                </tr>\
                            </thead>\
                            <tbody>\
                            </tbody>\
                        </table>");
                    if (res.length == 0){
                        $('#pricetable tbody').empty().append('<tr><td colspan="6"><p class="text-center">@lang("NO Data")</p></td></tr>');
                    } else {
                        var dt = $('#pricetable tbody');
                        dt.empty();
                        var i = 0;
                        for (row of res) {
                            var temprow = $('<tr></tr>');
                            if (row.description2 == null){
                                row.description2 = '';
                            }
                            if (row.id == price_id) {
                                temprow.append(`<td class="text-center"><input class="form-radio" type="radio" name="price" value="${row.id}" checked></td>`);
                            } else {
                                temprow.append(`<td class="text-center"><input class="form-radio" type="radio" name="price" value="${row.id}"></td>`);
                            }
                            let pos = row.image.indexOf('img');
                            if (pos != -1) {
                                temprow.append('<td>' + row.image + '</td>');
                            } else {
                                temprow.append('<td><img class="m-auto" src="' + window.location.origin + '/uploads/price/'+ row.image + '" width="50" height="50"></td>');
                            }
                            temprow.append('<td>' + row.itemcode + '</td>');
                            temprow.append('<td>' + row.description + '</td>');
                            temprow.append('<td>' + row.description2 + '</td>');
                            let temp_price = row.price * row.multiplier * user_multiplier;
                            temprow.append('<td>' + temp_price.toFixed(2)  + ' €</td>');
                            dt.append(temprow);
                        }
                        @if($project != null)
                        if ($(`input[name="price"][value="${price_id}"]`).length != 0){
                            $(`input[name="price"][value="${price_id}"]`)[0].setAttribute("checked", true);
                        }
                        else {
                            $('input[name="price"]')[0].setAttribute("checked", true);
                        }
                        @else
                            $('input[name="price"]')[0].setAttribute("checked", true);
                        @endif
                    }
                    if(callback !== undefined && callback !== null) {
                        callback();
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadFromModel(id, reg, model, showLoading = true) {
            model_id = id;
            if (showLoading)
                showSwalLoading();
            $.ajax({
                method: 'GET',
                url: '{{route('admin.projects.get.completedata')}}',
                data: {
                    id: id,
                    Reg: reg,
                    airflow: airflow,
                    pressure: pressure,
                    Trin: w_Trin,
                    Hrin: w_Hrin,
                    Tfin: w_Tfin,
                    Hfin: w_Hfin
                }
            }).done(function (res) { 
                var result = res.result;
                powerconsumption = result[model].Power;
                regulation = result[model].Reg;
                unitsel = result[model].Unit_SEL;
                psfp = result[model].PSFP;
                drawGraph(result[model]);
                
                $('.dataTable tr.selected').removeClass('selected');
                if(!$('.dataTable tr td[data-id=' + model_id + ']').closest('tr').hasClass('selected'))
                    $('.dataTable tr td[data-id=' + model_id + ']').closest('tr').addClass('selected');
                if('{{$option}}' === 'readonly') {
                    $('a.btn-display-models').hide();
                }
                dTable.draw(false);
                if (!isView) {
                    isView = true;
                    $('.btn-unit-save').show();
                }
                if (showLoading)
                    swal.close();
            });
            $('.btn-preview').show();
        }

        function mergeArr(x_arr, y_arr) {
            if(Object.keys(x_arr).length != Object.keys(y_arr).length)
                return null;
            var res = [];
            for(var i = 0; i < Object.keys(x_arr).length; i++) {
                var key = Object.keys(x_arr)[i];
                res.push({x: x_arr[key], y: y_arr[key]});
            }
            return res;
        }

        function drawGraph(data) {
            $('.tabs.graph-tabs').removeClass("hidden");
            var pressure_graph_data = [mergeArr(data.Max_Airflows, data.Max_Pressures), mergeArr(data.Regulate_Airflows, data.Regulate_Pressures),mergeArr([data.Airflow], [data.Pressure])];
			var power_graph_data = [mergeArr(data.Max_Airflows, data.Max_Powers),mergeArr(data.Regulate_Airflows, data.Regulate_Powers), mergeArr([data.Airflow], [data.Power])];
            var psfp_graph_data = [mergeArr(data.Max_PSFP_af, data.Max_PSFP),mergeArr(data.Regulate_PSFP_af, data.Regulate_PSFP), mergeArr([data.Airflow], [data.Unit_SEL])];
            var efficiency_graph_data = [mergeArr(data.Max_Airflows, data.ThermodynamicData.Efficiencies),  mergeArr([data.Airflow], [data.ThermodynamicData.efficiency])];

            document.getElementById('render').src = window.location.origin + '/uploads/price/' + data.IND_VarHor_Ceiling_Img;
            renderImgData = null;
            var render_img = new Image();

            // Set the image source to your URL            
            // Wait for the image to load
            render_img.onload = function() {                
                // Create a canvas element
                var canvas1 = document.createElement('canvas');
                
                // Set the canvas dimensions to the image dimensions
                canvas1.width = render_img.width;
                canvas1.height = render_img.height;
                // Draw the image onto the canvas
                var ctx1 = canvas1.getContext('2d');
                ctx1.drawImage(render_img, 0, 0);

                // Get the data URL from the canvas
                renderImgData = {
                    dataURL: canvas1.toDataURL(),
                    width: render_img.width,
                    height: render_img.height,
                };
                canvas1.remove();
                isLoadImage = true;
            };
            render_img.src = document.getElementById('render').src;
            
            initGraph('pressure_graph', pressure_graph_data, "@lang('Airflow rate')[m³/h]", "@lang('External Static Pressure') [Pa] - EN 13141-7");
            initGraph('power_graph', power_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Power Supply') [W] - EN 13141-7");
			initGraph('psfp_graph', psfp_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Global') PSFP [Ws/m³] - EN 13779");
            initGraph('efficiency_graph', efficiency_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Efficiency') [%] - EN 13141-7");

            // Draw Histogram Noise Level - GRAPH
            $('#tab7').empty();
            $('#tab7').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise1"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise2"></canvas></div></div>');
            $('#tab7').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise3"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise4"></canvas></div></div>');
            $('#tab7').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise5"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise6"></canvas></div></div>');

            initNoiseGraph('g_noise1', data.Soundtable.Breakout,  "@lang('Breakout noise level')", "@lang('Sound Level') [dB(A)] EN ISO 3744", "rgba(215, 38, 61, 0.8)");
            initNoiseGraph('g_noise2', data.Soundtable.Return, "@lang('Return in-duct noise level')", "@lang('Sound Level') [dB(A)]", "rgba(244, 96, 54, 0.8)");
            initNoiseGraph('g_noise3', data.Soundtable.Fresh, "@lang('Fresh in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(46,41,78,0.8)");
            initNoiseGraph('g_noise4', data.Soundtable.Supply, "@lang('Supply in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(27,153,139,0.8)");
            initNoiseGraph('g_noise5', data.Soundtable.Exhaust, "@lang('Exhaust in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(87,162,252,0.8)");
           

            // Show Technical Informations
            showTechInfo(data.ThermodynamicData);
        }        

        function initGraph(obj_id, data, xLabel, yLabel) {
            var _colors = ['dodgerblue', 'dodgerblue', 'dodgerblue'];
            var datasets = data.map((xy_arr, index) => {
			
			if(index === data.length-1)
			{
			        return {
                    data: xy_arr,
                    pointBackgroundColor: _colors[index],
                    fill: false,
                    showLine: false,
                    pointRadius: 4,
                }
			}
			else
			{
                return {
                    data: xy_arr,
                    borderColor: _colors[index],
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
					pointHoverRadius: 2,
					pointBorderWidth: 2,
                    pointRadius: 0,
                    borderDash: [index * 5]
                }
			}
            });
            var ctx = document.getElementById(obj_id).getContext('2d');
            var n = datasets.length;
            for (let i = 0; i < n; i++){
                if ( i != n-1){
                    datasets[i].borderWidth = 5;
                }
                else{
                    datasets[i].pointRadius = 7;
                }
            }
            // datasets[1].borderWidth = 5;
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: datasets,
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            type: 'linear', // make the scale linear
                            position: 'bottom',
                            scaleLabel: {
                                display: true,
                                labelString: xLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            },
                            ticks: {
                                fontColor: 'black',
                                fontSize: 15 // Set font size for y axis here
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            type: 'linear', // make the scale linear
                            scaleLabel: {
                                display: true,
                                labelString: yLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            },
                            ticks: {
                                fontColor: 'black',
                                fontSize: 16 // Set font size for y axis here
                            }
                        }]
                    }
                }
            });
        }       

        function initNoiseGraph(obj_id, data, xLabel, yLabel, color) {
            var ctx = document.getElementById(obj_id).getContext('2d');   
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            var myChart = new Chart(ctx, {
                type: 'bar',                
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: Object.keys(data).map((k) => {
                            return k.indexOf("Hz") >= 0 ? gradient : color;
                        }),
                        borderColor: color,
                        borderWidth: Object.keys(data).map((k) => {
                            return k.indexOf("Hz") >= 0 ? 3 : 0;
                        }),
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    }, 
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            position: 'bottom',                            
                            scaleLabel: {
                                display: true,
                                labelString: xLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 0,
                                fontSize: 16,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            ticks: {
                                beginAtZero: true,
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                fontSize: 16,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            },
                            scaleLabel: {
                                display: true,
                                labelString: yLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            }
                        }]
                    }
                },
            });
        }

        function showTechInfo(data) {
            var makeFormControl = (label, value) => {
                var id = "TI-" + $('#tab8 input.form-control').length;
                return `<div class="form-group row">
                    <label for="${id}" class="col-sm-6 col-form-label">${label}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="${id}" value="${value}" readonly>
                    </div>
                </div>`;
            }            

            $('#tab8').empty();
            $('#tab8').append('<div class="row"><div class="col-md-6 col-xl-3"></div><div class="col-md-6 col-xl-3"></div></div>');
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Supply Temperature') [°C]", data.Supply_outlet_temp));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Supply Humidity') [%]", data.Supply_outlet_rh));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Exhaust Temperature') [°C]", data.Exhaust_outlet_temp));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Exahust Humidity') [%]", data.Exhaust_outlet_rh));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Water produced') [l/h]", data.water_produced));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Return Temperature') [°C]", data.Return_inlet_temp));
            $('#tab8').find('.col-md-6:first-child').append(makeFormControl("@lang('Return Humidity') [%]", data.Return_inlet_rh));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Fresh Temperature') [°C]", data.Fresh_inlet_temp));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Fresh Humidity') [%]", data.Fresh_inlet_rh));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Efficiency') [%]", data.efficiency));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Heat Recovery') [W]", data.heat_recovery));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Sensible Heat') [W]", data.sensible_heat));
            $('#tab8').find('.col-md-6:last-child').append(makeFormControl("@lang('Latent Heat') [W]", data.latent_heat));
        }

        function showMultipleModal() {
            $('#staticBackdrop').modal('show');
        }

        function preview2PDF() {
            showSwalLoading();
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');
            setTimeout(() => {
                const project_name = $('#project_name').val().trim();
                const project_desc = $('#project_desc').val().trim();
                const project_refer = $('#project_reference').val().trim();
                const creation_date = $('#create_date').val().trim();
                const modify_date = $('#modify_date').val().trim();

                if (project_name === ''){
                    document.querySelector('.nav-link[href="#tab0"]').click();
                    alert("@lang('Please type Project Name')");
                    $('#project_name').focus();
                    return;
                }
                if (project_refer === ''){
                    document.querySelector('.nav-link[href="#tab0"]').click();
                    alert("@lang('Please type Project Reference')");
                    $('#project_reference').focus();
                    return;
                }
                var doc1 = new jsPDF('p', 'pt', [595, 842], true); // A4 Size
                var x = 20;
                var y = 20;

                doc1.addImage(logoImgData.dataURL, 'PNG', 30, y, logoImgData.width * 30 / logoImgData.height, 30, '', 'FAST');
                y += 30;

                doc1.setFontSize(7);
                doc1.setFontStyle('normal');
                y += 10;

                doc1.rect(20, y, 595 - 40, 25);
                y += 10;
                doc1.text("@lang('Project') : " + project_name  + ' - ' + project_desc, 30, y);
                doc1.text("@lang('Project reference') : " + project_refer, 595 / 3 + 10, y);
                doc1.text("@lang('Creation date') : " + creation_date, 595 * 2 / 3 + 10, y);
                y += 10;
                doc1.text("@lang('Last revistion') : " + modify_date, 30, y);
                doc1.text("@lang('SSW version') : {{$version ?? ''}}", 595 / 3 + 10, y);
                y += 10;

                var temp_y = y;

                doc1.rect(20, y, 595 - 40 - 350, 150);
                y += 10;
                doc1.setFontSize(10);
                doc1.setFontStyle('bold');
                doc1.text("@lang('SELECTED UNIT'): " + selected_model, 20 + (595 - 40 - 350) / 2, y, {align: 'center'});
                y += 5;
                doc1.line(20,  y, 595 - 20 - 350, y);
                doc1.setFontSize(7);
                doc1.setFontStyle('normal');
                var temp_price = $('input[name="price"]:checked').parents('tr').children('td');
                if (temp_price.length != 0){
                    y += 10;
                    doc1.text("@lang('Itemcode'):    " + temp_price[2].innerHTML, 30, y);
                    y += 10;
                    doc1.text("@lang('Description'): " + temp_price[3].innerHTML + (temp_price[4].innerHTML != '' ? ( ' - ' + temp_price[4].innerHTML) : ''), 30, y);
                }
                y += 10;
                doc1.addImage(renderImgData.dataURL, 'PNG', 20 + (595 - 40 - 350 - renderImgData.width / renderImgData.height * 100) / 2, y,  renderImgData.width / renderImgData.height * 100, 100, '', 'FAST');
                y += 110;

                
                doc1.rect(20, y, 595 - 40 - 350, 60);
                y += 10;
                doc1.setFontSize(10);
                doc1.setFontStyle('bold');
                doc1.text("@lang('WORKING POINT')", 20 + (595 - 40 - 350) / 2, y, {align: 'center'});
                y += 3;
                doc1.line(20, y, 595 - 20 - 350, y);
                // set font size to 10

                y += 10;
                doc1.setFontSize(8);
                doc1.setFontStyle('bold');
                doc1.text("@lang('Airflow data')", 30, y);
                y += 10;
                doc1.setFontSize(7);
                doc1.setFontStyle('normal');
                doc1.text("@lang('Airflow rate') : " + airflow +' [m³/h]', 30, y);
                doc1.text("@lang('Airflow pressure') : " + pressure +  ' [Pa]', 130, y);
                y += 10;
                doc1.setFontSize(7);
                doc1.setFontStyle('normal');
                doc1.text("@lang('Power consumption') : " + powerconsumption +' [W]', 30, y);
                doc1.text("@lang('Regulation') : " + regulation +  ' [%]', 130, y);
                y += 10;
                doc1.setFontSize(7);
                doc1.setFontStyle('normal');
                doc1.text("@lang('Unit SEL') : " + unitsel +' [J/m3]', 30, y);
                doc1.text("@lang('PSFP') : " + psfp +  ' [J/m3]', 130, y);


                doc1.rect(595 - 20 - 340, temp_y, 340, 180);
                temp_y += 10;
                doc1.setFontSize(10);
                doc1.setFontStyle('bold');
                doc1.text("@lang('THERMAL PERFORMANCE')", 595 - 20 - 340 / 2, temp_y, {align: 'center'});
                temp_y += 5;
                doc1.line(595 - 20 - 340, temp_y, 595 - 20, temp_y);

                // set font size to 10

                var drawGridText = (pdf, arrText, y) => {
                    var x0 = 245, x1 = 345, x2 = 365, x3 = 405, x4 = 505, x5 = 525;
                    pdf.text(arrText[0], x0, y);
                    pdf.text(arrText[1], x1, y);
                    pdf.text(arrText[2], x2, y);
                    pdf.text(arrText[3], x3, y);
                    pdf.text(arrText[4], x4, y);
                    pdf.text(arrText[5], x5, y);
                };

                temp_y += 10;
                doc1.setFontSize(7);
                doc1.setFontStyle('bold');
                doc1.text("@lang('WINTER OPERATION')", 595 - 20 - 340 + 10, temp_y);
                doc1.text(`@lang('imbalance ratio') : 90 %`, 595 - 20 - 340 + 100, temp_y);

                doc1.setFontStyle('normal');
                temp_y += 10;
                drawGridText(doc1, ["@lang('Supply Temperature')", $('#TI-0').val(), '[°C]', "@lang('Fresh Temperature')", $('#TI-7').val(), '[°C]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Supply Humidity')", $('#TI-1').val(), '[%]', "@lang('Fresh Humidity')", $('#TI-8').val(), '[%]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Exhaust Temperature')", $('#TI-2').val(), '[°C]', "@lang('Efficiency')", $('#TI-9').val(), '[%]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Exahust Humidity')", $('#TI-3').val(), '[%]', "@lang('Heat Recovery')", $('#TI-10').val(), '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Water produced')", $('#TI-4').val(), '[l/h]', "@lang('Sensible Heat')", $('#TI-11').val(), '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Return Temperature')", $('#TI-5').val(), '[°C]', "@lang('Latent Heat')", $('#TI-12').val(), '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Return Humidity')", $('#TI-6').val(), '[%]', '', '', ''], temp_y);

                temp_y += 10;
                doc1.setFontSize(7);
                doc1.setFontStyle('bold');
                doc1.text("@lang('SUMMER OPERATION')", 595 - 20 - 340 + 10, temp_y);
                doc1.text(`@lang('imbalance ratio') : 70 %`, 595 - 20 - 340 + 100, temp_y);

                doc1.setFontStyle('normal');
                temp_y += 10;
                drawGridText(doc1, ["@lang('Supply Temperature')", '', '[°C]', "@lang('Fresh Temperature')", '', '[°C]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Supply Humidity')", '', '[%]', "@lang('Fresh Humidity')", '', '[%]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Exhaust Temperature')", '', '[°C]', "@lang('Efficiency')", '', '[%]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Exahust Humidity')", '', '[%]', "@lang('Heat Recovery')", '', '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Water produced')", '', '[l/h]', "@lang('Sensible Heat')", '', '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Return Temperature')", '', '[°C]', "@lang('Latent Heat')", '', '[W]'], temp_y);
                temp_y += 10;
                drawGridText(doc1, ["@lang('Return Humidity')", '', '[%]', '', '', ''], temp_y);

                temp_y = y;

                y += 10;
                var _y = y;
                y += 20;
                doc1.line(20, y, 595 - 20, y);
                doc1.setFontSize(10);
                doc1.setFontStyle('bold');
                doc1.text("@lang('PERFORMANCE CURVES')", 30, y - 7);

                y += 10;
                var drawGraphOnPDF = (pdf, id, x, y) => {                    
                    var canvas = document.getElementById(id);
                    canvas.getContext('2d');
                    var image = canvas.toDataURL('image/png', 1.0);
                    var cw = 150;
                    var cy = cw * canvas.height / canvas.width;
                    
                    pdf.addImage(image, 'PNG', x, y, cw, cy, '', 'FAST');
                    return cy;
                }
                h1 = drawGraphOnPDF(doc1, 'pressure_graph', 105, y);
                h2 = drawGraphOnPDF(doc1, 'power_graph', 340, y);
                y = Math.max(h1, h2) + y + 10;

                h1 = drawGraphOnPDF(doc1, 'efficiency_graph', 105, y);
                h2 = drawGraphOnPDF(doc1, 'psfp_graph', 340, y);
                y = Math.max(h1, h2) + y + 10;
                
                doc1.rect(20, _y, 595 - 40, y - _y);

                y += 25;

                tempy = y - 15;

                doc1.line(20, y, 595 - 20, y);

                // set font size to 10
                doc1.setFontSize(10);
                doc1.setFontStyle('bold');
                doc1.text("@lang('ACOUSTIC CHARACTERISTICS')", 30, y - 3);
                y += 10;
                var h1 = drawGraphOnPDF(doc1, 'g_noise1', 20 + 105 / 4, y);
                var h2 = drawGraphOnPDF(doc1, 'g_noise2', 20 + 105 / 2 + 150, y);
                var h3 = drawGraphOnPDF(doc1, 'g_noise3', 20 + 105 / 4 * 3 + 150 * 2, y);
                var nextY = Math.max(h1, h2, h3) + y + 10;

                h1 = drawGraphOnPDF(doc1, 'g_noise4', 20 + 105 / 4, nextY);
                h2 = drawGraphOnPDF(doc1, 'g_noise5', 20 + 105 / 2 + 150, nextY);
                h3 = drawGraphOnPDF(doc1, 'g_noise6', 20 + 105 / 4 * 3 + 150 * 2, nextY);
                nextY = Math.max(h1, h2, h3) + nextY + 10;
                doc1.rect(20, tempy, 595 - 40, nextY - tempy);

                var filename = 'PREVIEW_REPORT_' + (new Date()).getTime() + '.pdf';
                savedPreviewDoc = {
                    'filename': filename,
                    'doc': doc1
                };
                savedPreviewDoc.doc.save(savedPreviewDoc.filename);
                $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
                swal.close();
            }, 100);
        }

        function export2PDF(final = false) {
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');
            setTimeout(() => {
                const project_name = $('#project_name').val().trim();
                const project_desc = $('#project_desc').val().trim();
                const project_refer = $('#project_reference').val().trim();
                const creation_date = $('#create_date').val().trim();
                const modify_date = $('#modify_date').val().trim();

                if (project_name === ''){
                    document.querySelector('.nav-link[href="#tab0"]').click();
                    alert("@lang('Please type Project Name')");
                    $('#project_name').focus();
                    return;
                }
                if (project_refer === ''){
                    document.querySelector('.nav-link[href="#tab0"]').click();
                    alert("@lang('Please type Project Reference')");
                    $('#project_reference').focus();
                    return;
                }
                if (!final) {
                    var x = 0;
                    var y = 20;
                    if (doc === null) {
                        doc = new jsPDF('p', 'pt', [595, 842], true); // A4 Size
                    } else {
                        doc.addPage(595, 842);
                    }

                    y = 20;
                    doc.addImage(logoImgData.dataURL, 'PNG', 30, y, logoImgData.width * 30 / logoImgData.height, 30, '', 'FAST');
                    y += 30;

                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    y += 10;

                    doc.rect(20, y, 595 - 40, 25);
                    y += 10;
                    doc.text("@lang('Project') : " + project_name  + ' - ' + project_desc, 30, y);
                    doc.text("@lang('Project reference') : " + project_refer, 595 / 3 + 10, y);
                    doc.text("@lang('Creation date') : " + creation_date, 595 * 2 / 3 + 10, y);
                    y += 10;
                    doc.text("@lang('Last revistion') : " + modify_date, 30, y);
                    doc.text("@lang('SSW version') : {{$version ?? ''}}", 595 / 3 + 10, y);
                    y += 10;

                    var temp_y = y;

                    doc.rect(20, y, 595 - 40 - 350, 150);
                    y += 10;
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text("@lang('SELECTED UNIT'): " + selected_model, (595 - 350) / 2, y, {align: 'center'});
                    y += 5;
                    doc.line(20,  y, 595 - 20 - 350, y);
                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    var temp_price = $('input[name="price"]:checked').parents('tr').children('td');
                    if (temp_price.length != 0){
                        y += 10;
                        doc.text("@lang('Itemcode'):    " + temp_price[2].innerHTML, 30, y);
                        y += 10;
                        doc.text("@lang('Description'): " + temp_price[3].innerHTML + (temp_price[4].innerHTML != '' ? ( ' - ' + temp_price[4].innerHTML) : ''), 30, y);
                    }
                    y += 10;
                    doc.addImage(renderImgData.dataURL, 'PNG', 20 + (595 - 40 - 350 - renderImgData.width / renderImgData.height * 100) / 2, y,  renderImgData.width / renderImgData.height * 100, 100, '', 'FAST');
                    y += 110;

                    
                    doc.rect(20, y, 595 - 40 - 350, 60);
                    y += 10;
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text("@lang('WORKING POINT')", (595 - 350) / 2, y, {align: 'center'});
                    y += 3;
                    doc.line(20, y, 595 - 20 - 350, y);
                    // set font size to 10

                    y += 10;
                    doc.setFontSize(8);
                    doc.setFontStyle('bold');
                    doc.text("@lang('Airflow data')", 30, y);
                    y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    doc.text("@lang('Airflow rate') : " + airflow +' [m³/h]', 30, y);
                    doc.text("@lang('Airflow pressure') : " + pressure +  ' [Pa]', 130, y);
                    y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    doc.text("@lang('Power consumption') : " + powerconsumption +' [W]', 30, y);
                    doc.text("@lang('Regulation') : " + regulation +  ' [%]', 130, y);
                    y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    doc.text("@lang('Unit SEL') : " + unitsel +' [J/m3]', 30, y);
                    doc.text("@lang('PSFP') : " + psfp +  ' [J/m3]', 130, y);


                    doc.rect(595 - 20 - 340, temp_y, 340, 180);
                    temp_y += 10;
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text("@lang('THERMAL PERFORMANCE')", 595 - 20 - 340 / 2, temp_y, {align: 'center'});
                    temp_y += 5;
                    doc.line(595 - 20 - 340, temp_y, 595 - 20, temp_y);

                    // set font size to 10

                    var drawGridText = (pdf, arrText, y) => {
                        var x0 = 245, x1 = 345, x2 = 365, x3 = 405, x4 = 505, x5 = 525;
                        pdf.text(arrText[0], x0, y);
                        pdf.text(arrText[1], x1, y);
                        pdf.text(arrText[2], x2, y);
                        pdf.text(arrText[3], x3, y);
                        pdf.text(arrText[4], x4, y);
                        pdf.text(arrText[5], x5, y);
                    };

                    temp_y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('bold');
                    doc.text("@lang('WINTER OPERATION')", 595 - 20 - 340 + 10, temp_y);
                    doc.text(`@lang('imbalance ratio') : 90 %`, 595 - 20 - 340 + 100, temp_y);

                    doc.setFontStyle('normal');
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Supply Temperature')", $('#TI-0').val(), '[°C]', "@lang('Fresh Temperature')", $('#TI-7').val(), '[°C]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Supply Humidity')", $('#TI-1').val(), '[%]', "@lang('Fresh Humidity')", $('#TI-8').val(), '[%]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Exhaust Temperature')", $('#TI-2').val(), '[°C]', "@lang('Efficiency')", $('#TI-9').val(), '[%]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Exahust Humidity')", $('#TI-3').val(), '[%]', "@lang('Heat Recovery')", $('#TI-10').val(), '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Water produced')", $('#TI-4').val(), '[l/h]', "@lang('Sensible Heat')", $('#TI-11').val(), '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Return Temperature')", $('#TI-5').val(), '[°C]', "@lang('Latent Heat')", $('#TI-12').val(), '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Return Humidity')", $('#TI-6').val(), '[%]', '', '', ''], temp_y);

                    temp_y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('bold');
                    doc.text("@lang('SUMMER OPERATION')", 595 - 20 - 340 + 10, temp_y);
                    doc.text(`@lang('imbalance ratio') : 70 %`, 595 - 20 - 340 + 100, temp_y);

                    doc.setFontStyle('normal');
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Supply Temperature')", '', '[°C]', "@lang('Fresh Temperature')", '', '[°C]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Supply Humidity')", '', '[%]', "@lang('Fresh Humidity')", '', '[%]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Exhaust Temperature')", '', '[°C]', "@lang('Efficiency')", '', '[%]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Exahust Humidity')", '', '[%]', "@lang('Heat Recovery')", '', '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Water produced')", '', '[l/h]', "@lang('Sensible Heat')", '', '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Return Temperature')", '', '[°C]', "@lang('Latent Heat')", '', '[W]'], temp_y);
                    temp_y += 10;
                    drawGridText(doc, ["@lang('Return Humidity')", '', '[%]', '', '', ''], temp_y);

                    temp_y = y;

                    y += 10;
                    var _y = y;
                    y += 20;
                    doc.line(20, y, 595 - 20, y);
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text("@lang('PERFORMANCE CURVES')", 30, y - 7);

                    y += 10;
                    var drawGraphOnPDF = (pdf, id, x, y) => {                    
                        var canvas = document.getElementById(id);
                        canvas.getContext('2d');
                        var image = canvas.toDataURL('image/png', 1.0);
                        var cw = 150;
                        var cy = cw * canvas.height / canvas.width;
                        
                        pdf.addImage(image, 'PNG', x, y, cw, cy, '', 'FAST');
                        return cy;
                    }
                    h1 = drawGraphOnPDF(doc, 'pressure_graph', 105, y);
                    h2 = drawGraphOnPDF(doc, 'power_graph', 340, y);
                    y = Math.max(h1, h2) + y + 10;

                    h1 = drawGraphOnPDF(doc, 'efficiency_graph', 105, y);
                    h2 = drawGraphOnPDF(doc, 'psfp_graph', 340, y);
                    y = Math.max(h1, h2) + y + 10;
                    
                    doc.rect(20, _y, 595 - 40, y - _y);

                    y += 25;

                    tempy = y - 15;

                    doc.line(20, y, 595 - 20, y);

                    // set font size to 10
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text("@lang('ACOUSTIC CHARACTERISTICS')", 30, y - 3);
                    y += 10;
                    var h1 = drawGraphOnPDF(doc, 'g_noise1', 20 + 105 / 4, y);
                    var h2 = drawGraphOnPDF(doc, 'g_noise2', 20 + 105 / 2 + 150, y);
                    var h3 = drawGraphOnPDF(doc, 'g_noise3', 20 + 105 / 4 * 3 + 150 * 2, y);
                    var nextY = Math.max(h1, h2, h3) + y + 10;

                    h1 = drawGraphOnPDF(doc, 'g_noise4', 20 + 105 / 4, nextY);
                    h2 = drawGraphOnPDF(doc, 'g_noise5', 20 + 105 / 2 + 150, nextY);
                    h3 = drawGraphOnPDF(doc, 'g_noise6', 20 + 105 / 4 * 3 + 150 * 2, nextY);
                    nextY = Math.max(h1, h2, h3) + nextY + 10;
                    doc.rect(20, tempy, 595 - 40, nextY - tempy);
                    saveLoading = false;
                    $('#unitform').hide();
                    $('#tab_unit_selection').addClass('disabled');
                    $('#tab_results_table').addClass('disabled');
                    document.querySelector('.nav-link[href="#tab0"]').click();
                    document.querySelector('#tab1').scrollTop = 0;
                    $('.btn-preview').hide();
                    $('.btn-unit-save').hide();
                } else {
                    doc.addPage(595, 842);

                    y = 0;
                    x = 20;
                    doc.setDrawColor(0,0,0);

                    y += 20;
                    doc.rect(20, y, 595 - 40, 105);
                    doc.line(595 / 2,  y, 595 / 2, y + 105);

                    y += 10;
                    doc.addImage(logoImgData.dataURL, 'PNG', 30, y, logoImgData.width * 30 / logoImgData.height, 30, '', 'FAST');
                    y += 30;

                    y += 10;
                    // set font size to 10
                    doc.setFontSize(10);
                    doc.setFontStyle('bold');
                    doc.text('{{$user->company_name ?? ""}}', 30, y);
                    doc.text('{{$company->name}}', 595 / 2 + 10, y);

                    y += 10;
                    doc.setFontSize(7);
                    doc.setFontStyle('normal');
                    doc.text('{{$user->company_address ?? ""}}', 30, y);
                    doc.text('{{$company->address}}', 595 / 2 + 10, y); //120
                    doc.text("@lang('Contact')", 180, y);

                    y += 10;
                    doc.text('{{$user->company_post_code ?? ""}}, {{$user->company_city ?? ""}}', 30, y);
                    doc.text('{{$settings->conname ?? ""}}', 180, y);

                    y += 10;
                    doc.text("{{$user->company_state ?? ""}}, {{$user->company_country ?? ""}}", 30, y);
                    doc.text("@lang('Tel. No.') : {{$user->company_tel ?? ''}}", 180, y);
                    doc.text("@lang('Tel. No.') : {{$company->phone}} ", 595 / 2 + 10, y);
                    
                    y += 10;
                    doc.text("@lang('VAT No.') {{$user->company_vat ?? ''}}", 30, y);
                    doc.text("@lang('Mobile') : {{$user->company_mobile ?? ''}}", 180, y);
                    doc.text("@lang('VAT') : {{$company->VAT}}" , 595 / 2 + 10, y);
                    
                    y += 10;
                    doc.text("{{$user->company_web_address ?? ''}}", 30, y);
                    doc.text('{{$user->email ?? ""}}', 180, y);
                    
                    y += 10;
                    doc.rect(20, y, 595 - 40, 25);
                    doc.line(595 / 2, y, 595 / 2, y + 25);

                    y += 10;
                    doc.text("@lang('Recipient') : {{$contact->firstname}}  {{$contact->secondname}}", 30, y);
                    doc.text("@lang('Mobile') : {{$contact->mobile}}" , 595 / 2 + 10, y);
                    y += 10;
                    doc.text("@lang('Tel. No.') : {{$contact->phone}}", 30, y);
                    doc.text("@lang('Mail') : {{$contact->email}}", 595 / 2 + 10, y);

                    y += 10;

                    doc.rect(20, y, 595 - 40, 25);
                    y += 10;
                    doc.text("@lang('Project') : " + project_name  + ' - ' + project_desc, 30, y);
                    doc.text("@lang('Project reference') : " + project_refer, 595 / 3 + 10, y);
                    doc.text("@lang('Creation date') : " + creation_date, 595 * 2 / 3 + 10, y);
                    y += 10;
                    doc.text("@lang('Last revistion') : " + modify_date, 30, y);
                    doc.text("@lang('SSW version') : {{$version ?? ''}}", 595 / 3 + 10, y);

                    y += 10;
                    var startCharCode = 65;
                    var i = 0;
                    var totalprice = 0;
                    for (const unit of pdf_units) {
                        y += 10;
                        var char = String.fromCharCode(startCharCode + i);
                        i++;
                        doc.text(`${char}) ${unit.name}`, 30, y);
                        y += 10;
                        doc.text(`${unit.itemcode} - ${unit.description}`, 50, y);
                        doc.text(`€`, 250, y);
                        let temp = unit.price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        doc.text(`${temp}`, 300, y, { align: "right" });
                        totalprice += unit.price;
                        doc.text(`@lang('Delivery Time'):`, 320, y);
                        temp = unit.delivery_time.split('_');
                        if (temp[1] == 1) {
                            doc.text(`${temp[0]} @lang('Days')`, 400, y, { align: "right" });
                        } else {
                            doc.text(`${temp[0]} @lang('Weeks')`, 400, y, { align: "right" });
                        }
                    }

                    doc.setFontStyle('bold');
                    y += 30;
                    doc.text(`@lang('Total')`, 50, y);
                    doc.text(`€`, 250, y);
                    let temp = totalprice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    doc.text(`${temp}`, 300, y, { align: "right" });

                    y += 30;
                    doc.text("@lang('Delivery Terms'):  {{$delivery_address->address ?? ''}},  {{$delivery_condition->cond ?? ''}}", 595 / 2, y, { align: "center" });

                    y += 20;
                    if (price_validity != '') {
                        temp = price_validity.split('_');
                        temp[1] = temp[1] == 1 ? 'Days' : 'Weeks';
                        doc.text(`@lang('Price Validity'):  ${temp[0]} ${temp[1]}`, 595 / 2, y, { align: "center" });
                    } else {
                        doc.text(`@lang('Price Validity'):`, 595 / 2, y, { align: "center" });
                    }

                    var currentPageNumber = doc.internal.getNumberOfPages();
                    doc.movePage(currentPageNumber, 1);

                    var totalPages = doc.internal.getNumberOfPages();

                    // Define the header and footer template function
                    var headerFooterTemplate = function(pageNumber, pageCount) {
                        // Footer
                        doc.setFontSize(10);
                        doc.text(pageNumber + " / " + pageCount, doc.internal.pageSize.width / 2 - 10, doc.internal.pageSize.height - 10, { align: "right" });
                    };

                    // Apply the header and footer template function to each page
                    for (var i = 1; i <= totalPages; i++) {
                        doc.setPage(i);
                        var pageInfo = doc.internal.getCurrentPageInfo();
                        
                        // Add the footer text to each page
                        headerFooterTemplate(pageInfo.pageNumber, totalPages);
                    }

                    var filename = 'REPORT_' + (new Date()).getTime() + '.pdf';
                    savedDoc = {
                        'filename': filename,
                        'doc': doc
                    };
                    
                    // prepare form data to send
                    var formData = new FormData();

                    formData.append('id', '{{$pid}}');
                    formData.append('company', '{{$company->id}}');
                    formData.append('contact', '{{$contact->id}}');

                    formData.append('name', $('#project_name').val());
                    formData.append('description', $('#project_desc').val());
                    formData.append('reference', $('#project_reference').val());

                    formData.append('pdf', doc.output('blob'), filename);                

                    formData.append('units', JSON.stringify(pdf_units));

                    showSwalLoading(); 
                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.projects.store.project')}}',
                        data: formData,
                        headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            swal.close();
                            console.log('File uploaded successfully.');
                            $('.btn-report').show();
                        },
                        error: function(xhr, status, error) {
                            swal.close();
                            console.log('An error occurred while uploading the file.');
                        }
                    });
                }

                $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
            }, final ? 100 : 2000);
        }

        function getCenteredTextPos(pdf, x, w, text) {
            var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize();
            var textX = x + (w - textWidth) / 2;            
            return textX;
        }

        initBox();

        function pdfReport() {
            if(savedDoc) {
                savedDoc.doc.save(savedDoc.filename);
            }
        }

        $(document).on('click', '.btn-cancel', (e) => {
            $('#staticBackdrop').modal('hide');
            $('#staticBackdrop .modal-content-add-unit').show();
            $('#staticBackdrop .modal-content-save-delivery-time').hide();
        });
        $(document).on('click', '.btn-multiple', (e) => {
            if ($(e.target).hasClass('btn-primary')){
                onNewUnit();
                $('#staticBackdrop').modal('hide');
            } else {
                $('#staticBackdrop .modal-content-add-unit').hide();
                $('#staticBackdrop .modal-content-save-delivery-time').show();
                $('.units-delivery-time-table tbody').html('');
                for (row of pdf_units) {
                    $('.units-delivery-time-table tbody').append(`
                        <tr data-name="${row.name}">
                            <td>${row.name}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="number" class="form-control delivery-time" value="1" min="1" max="320" maxlength="3">
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control delivery-time-type">
                                            <option value="0">Please select Time Type</option>
                                            <option value="1">Days</option>
                                            <option value="2">Weeks</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            }
        });
        $(document).on('dblclick', '.unit-row td:first-child', (e) => {
            let temp_unit_name = e.target.innerText;
            onViewUnit(temp_unit_name);
        });

        function onSaveDeliveryTime() {
            var temp_units_array = $('.units-delivery-time-table tbody tr');
            for (row of temp_units_array) {
                let temp_unit_name = $(row).attr('data-name');
                let temp_delivery_time = parseInt($(row).find('td:last-child .delivery-time').val());
                if (temp_delivery_time <= 0 || temp_delivery_time == '') {
                    $(row).find('td:last-child .delivery-time').focus();
                    alert("@lang('Please type Time')");
                    return;
                }
                let temp_delivery_time_type = $(row).find('td:last-child .delivery-time-type').val();
                if (temp_delivery_time_type == 0) {
                    $(row).find('td:last-child .delivery-time-type').focus();
                    alert("@lang('Please select Time Type')");
                    return;
                }
                pdf_units.map(row => {
                    if (row.name == temp_unit_name) {
                        row.delivery_time = `${temp_delivery_time}_${temp_delivery_time_type}`;
                    }
                });
            }
            $('#staticBackdrop').modal('hide');
            onSaveOffer();
        }

        function onNewUnit() {
            $('.btn-first-unit-next').hide();
            $('#unitform').show();
            isNew = true;
            isView = false;
            $('#tab_unit_selection').removeClass('disabled');
            $('#tab_results_table').addClass('disabled');
            document.querySelector('.nav-link[href="#tab1"]').click();
            document.querySelector('#tab1').scrollTop = 0;
            $('.btn-unit-save').hide();
            $('#unit_name').attr('disabled', false);
            $('#unit_name').val('');
            $('#unit_name').focus();
            $('#p_layout').val('C');
            $(`input[name=indoor][value="I"]`).prop('checked', true);
            $(`input[name=ex][value="CF|LT"]`).prop('checked', true);
            $('#p_airflow').val(200);
            $('#p_pressure').val(50);
            $('#p_w_Trin').val(20);
            $('#p_w_Hrin').val(60);
            $('#p_w_Tfin').val(-10);
            $('#p_w_Hfin').val(80);
        }

        function onViewUnit(unit_name){
            $('.btn-unit-save').hide();
            $('#tab_unit_selection').removeClass('disabled');
            $('#unitform').show();
            isNew = false;
            isView = true;
            var temp_unit = null;
            units.map(row => {
                if (row.name == unit_name) {
                    temp_unit = row;
                }
            });
            $('#unit_name').val(temp_unit.name);
            $('#unit_name').attr('disabled', true);
            $('#p_layout').val(temp_unit.layout);
            $(`input[name=indoor][value="${temp_unit.indoor}"]`).prop('checked', true);
            $(`input[name=ex][value="${temp_unit.ex2}|${temp_unit.ex1}"]`).prop('checked', true);
            $('#p_airflow').val(temp_unit.airflow);
            $('#p_pressure').val(temp_unit.pressure);
            $('#p_w_Trin').val(temp_unit.Trin);
            $('#p_w_Hrin').val(temp_unit.Hrin);
            $('#p_w_Tfin').val(temp_unit.Tfin);
            $('#p_w_Hfin').val(temp_unit.Trin);
            model_id = temp_unit.modelId;
            display_compatible_models(function(id, model, reg) {
                if(id == model_id) {
                    selected_model = model;
                    loadFromModel(id, reg, model);
                    initPriceTable(id, temp_unit.priceId);
                }
            });
        }

        function onEditUnit(unit_name){
            $('#tab_unit_selection').removeClass('disabled');
            $('#tab_results_table').addClass('disabled');
            $('#unitform').show();
            document.querySelector('#tab1').scrollTop = 0;
            document.querySelector('.nav-link[href="#tab1"]').click();
            isNew = false;
            isView = false;
            var temp_unit = null;
            units.map((row, index) => {
                if (row.name == unit_name) {
                    temp_unit = row;
                    edit_unit_index = index;
                }
            });
            edit_unit_name = temp_unit.name;
            $('#unit_name').attr('disabled', false);
            $('#unit_name').val(temp_unit.name);
            $('#p_layout').val(temp_unit.layout);
            $(`input[name=indoor][value="${temp_unit.indoor}"]`).prop('checked', true);
            $(`input[name=ex][value="${temp_unit.ex2}|${temp_unit.ex1}"]`).prop('checked', true);
            $('#p_airflow').val(temp_unit.airflow);
            $('#p_pressure').val(temp_unit.pressure);
            $('#p_w_Trin').val(temp_unit.Trin);
            $('#p_w_Hrin').val(temp_unit.Hrin);
            $('#p_w_Tfin').val(temp_unit.Tfin);
            $('#p_w_Hfin').val(temp_unit.Trin);
            model_id = temp_unit.modelId;
        }

        function onSaveUnit() {
            unit_name = $('#unit_name').val().trim();
            if (unit_name === ''){
                alert("@lang('Please type Unit Name')");
                $('#unit_name').focus();
                return;
            }
            if (!isNew) {
                units.map((row, index) => {
                    if (row.name == edit_unit_name) {
                        row.name = unit_name;
                        let temp_price = 0;
                        if ($('input[name="price"]:checked').length != 0) {
                            temp_price = parseFloat($('input[name="price"]:checked').parents('tr').find('td:last-child').html().slice(0, -2))
                        }
                        row.layout = $('#p_layout').val();
                        row.indoor = $('input[name=indoor]:checked').val();
                        row.ex1 = $('input[name=ex]:checked').val().split('|')[1];
                        row.ex2 = $('input[name=ex]:checked').val().split('|')[0];
                        row.airflow = airflow;
                        row.pressure = pressure;
                        row.Tfin = w_Tfin;
                        row.Trin = w_Trin;
                        row.Hfin = w_Hfin;
                        row.Hrin = w_Hrin;
                        row.modelId = model_id;
                        row.priceId = $('input[name="price"]:checked').length != 0 ? parseInt($('input[name="price"]:checked').val()) : 0;
                        row.price = temp_price;
                    }
                });
                if (edit_unit_name != unit_name) {
                    let temp_unit = $(`.unit-row[data-name="${edit_unit_name}"]`);
                    $(temp_unit).find('td')[0].innerText = unit_name;
                    $(temp_unit).find('td')[1].innerHTML = '\
                        <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                        <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                        <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                        <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                    ';
                    $(temp_unit).attr('data-name', unit_name);
                }
            } else {
                if (units.length == 0) {
                    $(`.units-table tbody`).html('\
                        <tr class="unit-row" data-name="'+ unit_name + '">\
                            <td>\
                            ' + unit_name +'\
                            </td>\
                            <td align="center">\
                                <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                                <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                                <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                                <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                            </td>\
                        </tr>\
                    ');
                    $(`.units-table tfoot`).html(`
                        <tr>
                            <td colspan="2" align="center">
                                <a class="btn btn-success btn-block button-boxed" onclick="onNewUnit()">
                                    <i class="fa fa-plus"></i>
                                    <small>@lang('Add Unit')</small>
                                </a>
                            </td>
                        </tr>
                    `);
                } else {
                    $(`.units-table tbody`).append('\
                        <tr data-name="'+ unit_name + '">\
                            <td>\
                            ' + unit_name +'\
                            </td>\
                            <td align="center">\
                                <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                                <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                                <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                                <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                            </td>\
                        </tr>\
                    ');
                }
                let temp_price = 0;
                if ($('input[name="price"]:checked').length != 0) {
                    temp_price = parseFloat($('input[name="price"]:checked').parents('tr').find('td:last-child').html().slice(0, -2))
                }
                units.push({
                    name: $('#unit_name').val(),
                    layout: $('#p_layout').val(),
                    indoor: $('input[name=indoor]:checked').val(),
                    ex1: $('input[name=ex]:checked').val().split('|')[1],
                    ex2: $('input[name=ex]:checked').val().split('|')[0],
                    airflow: airflow,
                    pressure: pressure,
                    Tfin: w_Tfin,
                    Trin: w_Trin,
                    Hfin: w_Hfin,
                    Hrin: w_Hrin,
                    modelId: model_id,
                    priceId: $('input[name="price"]:checked').length != 0 ? parseInt($('input[name="price"]:checked').val()) : 0,
                    price: temp_price,
                });
            }
            
            if (units.length == pdf_units.length) {
                $('.btn-offer-save').show();
            } else {
                $('.btn-offer-save').hide();
            }
            $('#unitform').hide();
            $('#tab_unit_selection').addClass('disabled');
            $('#tab_results_table').addClass('disabled');
            document.querySelector('.nav-link[href="#tab0"]').click();
            $('.btn-unit-save').hide();
            $('.btn-preview').hide();
        }

        function onDeleteUnit(unit_name){
            units.map((row, index) => {
                if (row.name == unit_name) {
                    units.splice(index, 1);
                    $(`.units-table tbody tr[data-name="${unit_name}"]`).remove();
                }
            });
            if (units.length == pdf_units.length && pdf_units.length > 0) {
                $('.btn-offer-save').show();
            } else {
                $('.btn-offer-save').hide();
            }
            if (units.length == 0) {
                $('.btn-first-unit-next').show();
                $('.units-table tfoot').html('');
            }
            $('#unitform').hide();
            $('#tab_unit_selection').addClass('disabled');
            $('#tab_results_table').addClass('disabled');
            document.querySelector('.nav-link[href="#tab0"]').click();
            document.querySelector('#tab1').scrollTop = 0;
            $('.btn-preview').hide();
            $('.btn-unit-save').hide();
        }

        function onSaveOffer() {
            export2PDF(true);
            $('.btn-offer-save').hide();
            $('.btn-report').show();
        }

        function onAddOffer(unit_name) {
            showSwalLoading();
            saveLoading = true;
            saveLoadingId = setInterval(() => {
                if (!saveLoading) {
                    swal.close();
                    clearInterval(saveLoadingId);
                }
            }, 1000);
            $(`.units-table tbody tr[data-name="${unit_name}"] td:last-child`).html('<button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>');
            isNew = false;
            isView = false;
            var temp_unit = null;
            var unit_index = 0;
            units.map((row, index) => {
                if (row.name == unit_name) {
                    temp_unit = row;
                    unit_index = index;
                }
            });
            $('#unit_name').val(temp_unit.name);
            $('#unit_name').attr('disabled', true);
            $('#p_layout').val(temp_unit.layout);
            $(`input[name=indoor][value="${temp_unit.indoor}"]`).prop('checked', true);
            $(`input[name=ex][value="${temp_unit.ex2}|${temp_unit.ex1}"]`).prop('checked', true);
            $('#p_airflow').val(temp_unit.airflow);
            $('#p_pressure').val(temp_unit.pressure);
            $('#p_w_Trin').val(temp_unit.Trin);
            $('#p_w_Hrin').val(temp_unit.Hrin);
            $('#p_w_Tfin').val(temp_unit.Tfin);
            $('#p_w_Hfin').val(temp_unit.Trin);
            model_id = temp_unit.modelId;
            isView = true;
            display_compatible_models(function(id, model, reg) {
                if(id == model_id) {
                    selected_model = model;
                    loadFromModel(id, reg, model, 0, false);
                    initPriceTable(id, temp_unit.priceId, function () {
                        var temp_price = $('input[name="price"]:checked').parents('tr').children('td');
                        if (temp_price.length != 0) {
                            units[unit_index].itemcode = temp_price[2].innerHTML;
                            units[unit_index].description = temp_price[3].innerHTML + (temp_price[4].innerHTML != '' ? ( ' - ' + temp_price[4].innerHTML) : '');
                        } else {
                            units[unit_index].itemcode = '';
                            units[unit_index].description = '';
                        }
                        pdf_units.push({
                            ...units[unit_index]
                        });
                        if (units.length == pdf_units.length) {
                            $('.btn-offer-save').show();
                        } else {
                            $('.btn-offer-save').hide();
                        }
                    });
                }
            }, false);
            loadImageId = setInterval(() => {
                if (isLoadImage) {
                    isLoadImage = false;
                    export2PDF();
                    clearInterval(loadImageId);
                }
            }, 1000);
        }

        $(document).ready(function() {
            $('.units-table').css('visibility', 'visible');
            if (units.length > 0) {
                $('.btn-first-unit-next').hide();
            } else {
                $('.btn-first-unit-next').show();
            }

            // Create a new Image object
            var img = new Image();

            // Set the image source to your URL            
            img.src = "<?=isset($settings) ? asset('/uploads/' . $settings->image) : asset('img/logo_dark.png') ?>";
            // Wait for the image to load
            img.onload = function() {                
                // Create a canvas element
                var canvas = document.createElement('canvas');
                
                // Set the canvas dimensions to the image dimensions
                canvas.width = img.width;
                canvas.height = img.height;

                // Draw the image onto the canvas
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                // Get the data URL from the canvas
                logoImgData = {
                    dataURL: canvas.toDataURL(),
                    width: img.width,
                    height: img.height
                };

                // Use the data URL as needed

                canvas.remove();
            };           
        });
        
    </script>
@endsection