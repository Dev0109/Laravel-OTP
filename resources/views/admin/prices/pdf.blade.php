@extends('layouts.app')
@section('styles')
<style>
    #print_div {
        background-color: #fff;
        padding: 0.75rem;
    }
    #print_div .logo {
        margin-top: 0.5rem;
        display: block;
        text-align: center;
    }
    #print_div .logo img {
        max-width: 100%;
        height: 100px;
        display: inline-block;
        vertical-align: middle;
        margin-left: auto;
        margin-right: auto;
    }
    #print_div h3.title {
        color: #000;
        text-align: center;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        line-height: 1.25;
        font-size: 1.875rem;
        font-weight: 700;
    }
    #print_div .content {
        margin-top: 1.5rem;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
    }
    #print_div .content p {
        font-weight: 400;
        color: #000;
        padding-top: 0.125rem;
        padding-bottom: 0.125rem;
    }
    #print_div .content .problem {
        padding: 0.5rem 1rem;
        border: 2px solid #48bb78;
    }
    #print_div .content .problem_div {
        margin-top: -10px;
    }
    #print_div .content .problem_div p {
        font-weight: 400;
        color: #000;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    #print_div .content .problem_div > .problem {
        height: 150px;
    }
    
    #print_div .content .problem_div > .problem > textarea {
        background-color: transparent;
        border: 0px none transparent;
        outline: 0px none transparent;
        width: 100%;
        height: 100%;
        font-family: serif;
    }
    
    #print_div .content .problem_div .problem span {
        font-weight: 400;
        color: #000;
    }
    #print_div .content .remember {
        margin-top: 1.5rem;
    }
    #print_div .content .remember .checkbox_label {
        display: inline-flex;
        align-items: center;
    }
    #print_div .content .remember .checkbox_label .form-checkbox {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        display: inline-block;
        vertical-align: middle;
        background-origin: border-box;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        flex-shrink: 0;
        height: 1em;
        width: 1em;
        color: #4299e1;
        background-color: #4299e1;
        border-color: #4299e1;
        border-width: 1px;
        border-radius: 0.25rem;
    }
    #print_div .content .remember .checkbox_label span {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
        color: #000;
        font-weight: 400;
    }
    #print_div .content .contact div {
        padding: 0;
    }
    #print_div .content .contact div p {
        font-weight: 400;
        color: #000;
    }
    #print_div .content .contact div p span {
        margin-left: 3rem;
    }
    #print_div .content .contact div a {
        text-decoration: none;
        color: #000;
    }
    .attention {
        margin-top: 1.5rem;
    }
    .attention p {
        font-weight: 700;
        color: #e53e3e !important;
        padding-top: 0.125rem;
        padding-bottom: 0.125rem;
    }
    .signature {
        width: 120px;
        height: 60px;
        margin-top: -120px;
        margin-left: 415px;
    }
</style>
@endsection
@section('content')
    <div id="print_div">
        <div class="logo">
            @php
                $path = public_path('/img/logo_big.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $base64 }}" alt="logo">
        </div>
        <h3 class="title">Proces verbal de intrare in service</h3>
        <div class="content">
            <p>Nume / Prenume : {{ $scooter['name'] }}</p>
            <p>Telefon : {{ $scooter['phone'] }} <span style="margin-left: 280px;">SEMNATURA</span> </p>
            <p>CODBARE : {{ $scooter['barcode'] }}</p>
            <p>MODEL : {{ $scooter['model'] }}</p>
            <p>TERMEN APROXIMATIV  : {{ $scooter['termen'] }}</p>
            
            @php
                if (File::exists(public_path('/signatures/'.$scooter['barcode'].'.png'))) {
                    $path = public_path('/signatures/'.$scooter['barcode'].'.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $path = public_path('/signatures/notsign/77550871916021154.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            <img src="{{ $base64 }}" class="signature">
                        
            <div class="problem_div">
                <p>Probleme : </p>
                <div class="problem">
                    <textarea disabled>{{ $scooter['problem'] }}</textarea>
                </div>
            </div>
            <p>Cost reparatie : {{ $scooter['price'].' LEI' }}</p>
            <p style="color: red;">Am luat la cunostinta pretul si termenul de executie al lucrarii.</p>
            <div class="remember">
                <label class="checkbox_label">
                    <input type="checkbox" name="remember" id="remember" class="form-checkbox">
                    <span class="remember_text">Sunt de acord cu preluarea si prelucrarea datelor cu caracter personal conform procedurii GDPR</span>
                </label>
            </div>
            <div class="contact" style="display: block; margin-top: 1.5rem;">
                <div>
                    <p>DOCTOR TROTINETA <span>DATA : {{ date('d.M.Y', strtotime($scooter['updated_at'])) }}</span></p>
                    <p>0215553934/ 0723110511</p>
                    <p><a href="https://www.doctortrotineta.ro" target="_new">www.doctortrotineta.ro</a></p>
                </div>
                
            </div>
            <div class="attention">
                <p><strong>ATENTIE! Neridicarea trotinetelor in termen de 48h aduce o taxa suplimentara in valoare de 10lei/zi.</strong></p>
            </div>
        </div>
    </div>
@endsection