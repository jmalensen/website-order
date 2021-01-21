@extends('layouts.app')
@section('pagetitle')@lang('commun.stats_range')@endsection
@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.stats_range'],
        ]
        ])
@endsection

@section('content')

    <div class="block block-rounded block-bordered">
        <div class="block-content">
            <h2 class="font-w300 mb-2 mr-1">@lang('commun.periode') : {{ Helpers::date($dateStart) }} au {{ Helpers::date($dateEnd) }}</h2>

            {{Form::open(['url' => route('stats.range'), 'method' => 'GET'])}}
            {{Form::considerRequest(true)}}
                <div class="d-flex">
                    <div class="col-12 col-md-4 p-0">
                        {{Form::label('date_range', __('Choix de la période') )}}
                    </div>
                    <div class="col-12 col-md-4">
                        {{Form::datepickerrange('date_range', null, ['id' => 'date_range'])}}
                    </div>
                    <div class="col-12 col-md-4 p-0">
                        {{Form::submit()}}
                    </div>
                </div>
            {{Form::close()}}


            @include('layouts.stats.statsbyproducts', ['productWithOrder' => $products, 'currentDay' => $dateStartView, 'dateEnd' => $dateEndView])

            @include('layouts.stats.statsbycustomers', ['customers' => $customersAllDay, 'currentDay' => $dateStartView, 'dateEnd' => $dateEndView])

            @include('layouts.stats.statsproductsbycustomer', ['productByCustomer' => $productsAllDay, 'currentDay' => $dateStartView, 'dateEnd' => $dateEndView])

            @include('layouts.stats.statsbyusers', ['users' => $usersAllDay, 'currentDay' => $dateStartView, 'dateEnd' => $dateEndView])
        </div>
    </div>

@endsection

@push('js_after')
@endpush