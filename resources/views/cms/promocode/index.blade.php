@extends('layouts.app')

@section('content')
  <div class="container spark-screen">
    @include('shared.alert')
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">Promo Codes List
          </div>

          <div class="panel-body">
            <table id="promocodes_list" class="display responsive nowrap" cellspacing="0" width="100%">
              <thead>
              <tr>
                <th>Code</th>
                <th>Text</th>
                <th>Can be used until</th>
                <th>Free access until</th>
                <th>Valid days after sign up</th>
                <th>Free days</th>
                <th>Discount</th>
                <th>Subscription</th>
                <th>Active</th>
              </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
