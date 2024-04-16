<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Currency</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @vite('resources/js/app.js')
</head>
<body class="antialiased">
<form method="POST" action="/your-route">
@csrf

</form>
<div class="main-block">
    <div class="add-new-currency-block">
        <div  type="button" class="btn btn-primary add-new-currency-btn">Добавить новую валюту</div>
        <div class="modal" id="currencyModal">
            <div class="modal-content modal-currency-new-block">
                <div class="head-modal-btn">
                    <span class="close">&times;</span>
                    <span type="button" class="btn btn-success save-new-currency">Добавить</span>
                </div>

                <!-- Здесь разместите содержимое вашего модального окна -->
                <div>
                    <ul class="list-group table-container"></ul>
                </div>
            </div>
        </div>
    </div>

    <div class="other-base-currancy">Другие валюты </div>
    <div class="base-currency" style="display: none;">
{{--    <div class="base-currency" >--}}
        <select id="select-field"  class="form-control select2 base-currency" name="state" >
            @foreach($currency as $item)
                     <option  value="{{$item['code']}}" @if($item['code'] == 'USD') selected @endif ><span>{{$item['code']}}</span><span> </span><span>{{$item['description']}}</span></option>
            @endforeach
        </select>
    </div>


    <div class="dinamic">
        <span>Динамика среднего курса валют в банках</span>
        <div class="dinamic-item-block">
        <?php $count= 0;?>
            @if($count < 3 )
                <div class="span-rate--select-block">
                    @foreach($currency as $item)
                        @if($count < 3 )
                            <span @if($count == 0) class="selected-rate"  @endif>{{$item['code']}}</span>
                            <?php $count++;?>
                        @endif
                    @endforeach
                </div>

            @endif
            @if($count >= 3 )
                <div class="currency-rate">
                    <select id="select-field-rate"  class="form-control select2 currency-rate-sel" name="currency" >
                        <option class="option-rate"  value="."></option>
                        @foreach($currency as $key => $item)
                            @if($key+1 > 3)
                            <option class="option-rate"  value="{{$item['code']}}" ><span>{{$item['code']}}</span></option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
    <div class="rate-chart">
        <canvas id="myChart"></canvas>
    </div>
    <div class="rate-date">
        <div class="rate-date-block-select">
            <span class="day-btn date-select" data-days="3">3 дня</span>
            <span class="day-btn" data-days="5">5 дня</span>
            <span class="day-btn" data-days="7">7 дня</span>
            <span class="day-btn" data-days="10">10 дня</span>
            <span class="day-btn" data-days="max">макс.</span>
        </div>
       <div>
           <span class="date-day"></span><span class="date-day"></span>
       </div>

    </div>
</div>
</body>
</html>
