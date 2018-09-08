@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.update', $customer->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.customers.customers.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.account.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group">
                                <label for="name" > {{ __('admin::app.customers.customers.first_name') }}</label>
                                <input type="text"  class="control" name="first_name" value="{{$customer->first_name}}"/>
                            </div>

                            <div class="control-group">
                                <label for="name" > {{ __('admin::app.customers.customers.last_name') }}</label>
                                <input type="text"  class="control"  name="last_name" value="{{$customer->last_name}}"/>
                            </div>

                            <div class="control-group">
                                <label for="name" > {{ __('admin::app.customers.customers.email') }}</label>
                                <input type="text"  class="control"  name="email" value="{{$customer->email}}"/>
                            </div>

                            <div class="control-group">
                                <label for="email">{{ __('admin::app.customers.customers.gender') }}</label>
                                <select name="gender" class="control" value="{{ $customer->gender }}" v-validate="'required'">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" name="date_of_birth" value="{{ $customer->date_of_birth }}" v-validate="'required'">
                            </div>

                            <div class="control-group">
                                <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                                <input type="text" class="control" name="phone" value="{{ $customer->phone }}" v-validate="'required'">
                            </div>

                            <div class="control-group">
                                <label for="name" >{{ __('admin::app.customers.customers.customer_group') }}</label>
                                <select  class="control" name="customer_group_id">
                                    <option value="1"> 1 </option>
                                    <option value="2"> 2 </option>
                                    <option value="3"> 3 </option>
                                    <option value="4"> 4 </option>
                                </select>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop