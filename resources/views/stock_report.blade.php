@extends('layouts.outer')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="d-flex align-items-end flex-wrap">
                        <div class="me-md-3 me-xl-5">
                            <form method="post" action="{{route('search_stock')}}" class="row g-3">
                                {{ csrf_field() }}
                                <div class="col-auto" >
                                <input type="date" id="fromDate" name="fromdate" value="<?php if(isset($from_date)) echo $from_date; ?>" class="form-control" placeholder="From Date" aria-label="search" aria-describedby="search">                                                  
                                </div>
                                <div class="col-auto">
                                <input type="date" id="toDate" name="todate" value="<?php if(isset($to_date)) echo $to_date; ?>" class="form-control" placeholder="To Date" aria-label="search" aria-describedby="search">                    
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="code" name="code" value="{{ old('code', $code ?? '') }}" class="form-control" placeholder="Enter Code">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="status_message">
            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Order List -->
        <div class="row">
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">List Order</p>
                        <div class="table-responsive">
                            <table id="orderTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th>SI.No.</th>
                                        <th>Name</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="cus_results">
                                    @include('stocklist_results',['materials'=>$materials])
                                </tbody>
                            </table>
                            {{ $materials->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
