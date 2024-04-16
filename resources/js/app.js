import './bootstrap';

import Chart from 'chart.js/auto';

window.Chart = Chart;


$('#select-field').select2({
    theme: 'bootstrap-5'
});
$('#select-field-rate').select2({
    theme: 'bootstrap-5'
});

function toggleCurrencySelector() {
    var baseCurrencyDiv = document.querySelector('.base-currency');
    if (baseCurrencyDiv.style.display == 'none') {
        baseCurrencyDiv.style.display = 'initial';
    } else {
        baseCurrencyDiv.style.display = 'none';
    }
}

var myChart;

function setChart(currencyRates) {
    if (typeof myChart !== 'undefined' && myChart !== null) {

        myChart.destroy();
    }

    var labels = [];
    var askData = [];
    var bidData = [];
    var midpointData = [];


    var earliestDate = new Date(currencyRates[0].date_time);
    var latestDate = new Date(currencyRates[currencyRates.length - 1].date_time);


    var earliestDateString = earliestDate.toLocaleDateString('ru-RU');
    var latestDateString = latestDate.toLocaleDateString('ru-RU');

    $('.date-day:first').text(earliestDateString);
    $('.date-day:last').text(latestDateString);

    currencyRates.forEach(function (rate) {

        var date = new Date(rate.date_time);
        labels.push(date.toLocaleDateString('ru-RU'));


        askData.push(parseFloat(rate.ask));
        bidData.push(parseFloat(rate.bid));
        midpointData.push(parseFloat(rate.midpoint));
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ask',
                data: askData,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Bid',
                data: bidData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Midpoint',
                data: midpointData,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    type: 'linear',
                    ticks: {
                        stepSize: 0.01
                    },
                    bezierCurve: true,
                    bezierCurveTension: 0.01,
                },

            },
            bezierCurve: true,
            bezierCurveTension: 0.01,
        }
    });
}


function getDataByChart() {
    let BaseValue = $('#select-field').val();
    console.log('BaseValue', BaseValue);

    let RateValue = $('.selected-rate').text();
    console.log('RateValue', RateValue);

    let dateValue = $('.date-select').data('days');
    console.log('dateValue', dateValue);

    $.ajax({
        url: '/currencyrate/',
        method: 'GET',
        dataType: 'json',
        data: {
            base: BaseValue,
            rate: RateValue,
            date: dateValue,
        },
        success: function (data) {
            console.log(data);
            setChart(data);
        },
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                console.log('Not connect. Verify Network.');
            } else if (jqXHR.status == 404) {
                console.log('Requested page not found (404).');
            } else if (jqXHR.status == 500) {
                console.log('Internal Server Error (500).');
            } else if (exception === 'parsererror') {
                console.log('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                console.log('Time out error.');
            } else if (exception === 'abort') {
                console.log('Ajax request aborted.');
            } else {
                console.log('Uncaught Error. ' + jqXHR.responseText);
            }
        }
    });
}


$(document).on('click', '.other-base-currancy', function (event) {

    toggleCurrencySelector();
});

$(document).ready(function () {

    getDataByChart();
});

$('.span-rate--select-block span').on('click', function () {
    $('.span-rate--select-block span').removeClass('selected-rate');
    $('#select-field-rate ').find('option:selected').removeClass('selected-rate');
    $(this).addClass('selected-rate');
    getDataByChart();
});

$('.rate-date-block-select span').on('click', function () {
    console.log(`$('.rate-date-block-select span').on('click', function() {`)
    $('.rate-date-block-select span').removeClass('date-select');
    $(this).addClass('date-select');
    getDataByChart()
});

$('.currency-rate-sel').on('change', function () {
    console.log(`$('#select-field-rate').on('change', function() {`)
    $('.dinamic-item-block span').removeClass('selected-rate');

    $('.currency-rate-sel').find('option').removeClass('selected-rate');
    console.log('$(this)')
    console.log($(this))

    var selectedValue = $(this).val();
    console.log('Selected value:', selectedValue);

    var selectedOption = $(this).find('option:selected');

    console.log(selectedOption)

    var selectedValue = selectedOption.addClass('selected-rate');

    getDataByChart()
});
$('#select-field').on('change', function () {
    console.log(`$('#select-field').on('change', function() {`)
    var selectedRate = $(this).val();
    console.log('Selected rate:', selectedRate);

    getDataByChart()
});

function fillTableCurrency(data) {
    $('#dataTable tbody').empty();
    $.each(data, function (index, item) {
        $('.list-group').append(`<li  class="list-group-item" data-code="${item.code}" data-description="${item.description}">` + item.code + `<span> - </span>` + item.description + `</li>`);
    });
}

$('.table-container').on('click', '.list-group-item', function () {
    if ($(this).hasClass('new-currency-add-li')) {
        $(this).removeClass('new-currency-add-li');
    } else {
        $(this).addClass('new-currency-add-li');
    }

});

function getNewCurrency() {
    $.ajax({
        url: '/currensyadd/',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);
            fillTableCurrency(data);
        },
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                console.error('Not connect. Verify Network.');
            } else if (jqXHR.status == 404) {
                console.error('Requested page not found (404).');
            } else if (jqXHR.status == 500) {
                console.error('Internal Server Error (500).');
            } else if (exception === 'parsererror') {
                console.error('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                console.error('Time out error.');
            } else if (exception === 'abort') {
                console.error('Ajax request aborted.');
            } else {
                console.error('Uncaught Error. ' + jqXHR.responseText);
            }
        }
    });
}

getNewCurrency();

$('.add-new-currency-btn').on('click', function () {
    $('#currencyModal').css('display', 'block');
});

$('.close').on('click', function () {
    $('#currencyModal').css('display', 'none');
    $('.table-container li').removeClass('new-currency-add-li');
});

function saveNewCurrency(date) {
    $.ajax({
        url: '/currensyadd/save-new-currency',
        method: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(date),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (dataResult) {
            if (dataResult) {
                location.reload()
            }
            console.log('data', dataResult);

        },
        error: function (jqXHR, exception) {
            if (jqXHR.status === 0) {
                console.error('Not connect. Verify Network.');
            } else if (jqXHR.status == 404) {
                console.error('Requested page not found (404).');
            } else if (jqXHR.status == 500) {
                console.error('Internal Server Error (500).');
            } else if (exception === 'parsererror') {
                console.error('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                console.error('Time out error.');
            } else if (exception === 'abort') {
                console.error('Ajax request aborted.');
            } else {
                console.error('Uncaught Error. ' + jqXHR.responseText);
            }
        }
    });
}

$('.save-new-currency').on('click', function () {
    var count = $('.list-group-item.new-currency-add-li').length;

    if (count > 0) {
        let data = [];
        $('.list-group-item.new-currency-add-li').each(function () {

            var code = $(this).data('code');
            var description = $(this).data('description');
            console.log('code', code)
            console.log('description', description)
            data.push({
                'code': code,
                'description': description
            });
        });
        saveNewCurrency(data);
    } else {
        $('.modal-currency-new-block').css('background-color', 'rgba(168,118,123,0.75)');
        setTimeout(function () {
            $('.modal-currency-new-block').css('background-color', '');
        }, 700);
    }
});
