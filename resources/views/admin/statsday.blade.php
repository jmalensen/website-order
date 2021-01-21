@extends('layouts.app')
@section('pagetitle')@lang('commun.stats_day')@endsection
@section('breadcrumbs')
    @include('layouts.inc.breadcrumbs', [
        'breadcrumbs' => [
            ['name' => 'commun.stats_day'],
        ]
        ])
@endsection

@section('content')

    <div class="block block-rounded block-bordered">
        <div class="block-content">
            <h2 class="font-w300 mb-2 mr-1">@lang('commun.day') {{ Helpers::date($date) }}</h2>

            {{Form::open(['url' => route('stats.day'), 'method' => 'GET'])}}
            {{Form::considerRequest(true)}}
                <div class="d-flex">
                    <div class="col-12 col-md-4 p-0">
                        {{Form::label('currentDay', __('Choix du jour') )}}
                    </div>
                    <div class="col-12 col-md-4">
                        {{Form::datepicker('currentDay', $dateView, ['id' => 'currentDay'])}}
                    </div>
                    <div class="col-12 col-md-4 p-0">
                        {{Form::submit()}}
                    </div>
                </div>
            {{Form::close()}}

            @include('layouts.stats.statsbyproducts', ['productWithOrder' => $products, 'currentDay' => $dateView, 'dateEnd' => false])

            @include('layouts.stats.statsbycustomers', ['customers' => $customersCurrentDay, 'currentDay' => $dateView, 'dateEnd' => false])

            @include('layouts.stats.statsproductsbycustomer', ['productByCustomer' => $productsCurrentDay, 'currentDay' => $dateView, 'dateEnd' => false])

            @include('layouts.stats.statsbyusers', ['users' => $usersCurrentDay, 'currentDay' => $dateView, 'dateEnd' => false])
        </div>
    </div>

@endsection

@push('js_after')
@endpush