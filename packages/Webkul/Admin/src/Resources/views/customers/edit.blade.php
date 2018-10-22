@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.edit-title') }}
@stop

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
                        {{ __('admin::app.customers.customers.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('admin::app.account.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                                <label for="first_name" class="required"> {{ __('admin::app.customers.customers.first_name') }}</label>
                                <input type="text"  class="control" name="first_name" v-validate="'required'" value="{{$customer->first_name}}"/>
                                <span class="control-error" v-if="errors.has('first_name')">@{{ errors.first('first_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('last_name') ? 'has-error' : '']">
                                <label for="last_name" class="required"> {{ __('admin::app.customers.customers.last_name') }}</label>
                                <input type="text"  class="control"  name="last_name"   v-validate="'required'" value="{{$customer->last_name}}"/>
                                <span class="control-error" v-if="errors.has('last_name')">@{{ errors.first('last_name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required"> {{ __('admin::app.customers.customers.email') }}</label>
                                <input type="email"  class="control"  name="email" v-validate="'required|email'" value="{{$customer->email}}"/>
                                <span class="control-error" v-if="errors.has('email')">@{{ errors.first('email') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="gender" class="required">{{ __('admin::app.customers.customers.gender') }}</label>
                                <select name="gender" class="control" v-validate="'gender'" value="{{ $customer->gender }}" v-validate="'required'">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                </select>
                                <span class="control-error" v-if="errors.has('gender')">@{{ errors.first('gender') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="dob">{{ __('admin::app.customers.customers.date_of_birth') }}</label>
                                <input type="date" class="control" name="date_of_birth" value="{{ $customer->date_of_birth }}">
                            </div>

                            <div class="control-group" :class="[errors.has('phone') ? 'has-error' : '']">
                                <label for="phone">{{ __('admin::app.customers.customers.phone') }}</label>
                                <input type="text" class="control" v-validate="'numeric'" name="phone" value="{{ $customer->phone }}">
                                <span class="control-error" v-if="errors.has('phone')">@{{ errors.first('phone') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="customerGroup" >{{ __('admin::app.customers.customers.customer_group') }}</label>

                                @if(!is_null($customer->customer_group_id))
                                    <?php $selectedCustomerOption = $customer->customerGroup->id ?>
                                @else
                                    <?php $selectedCustomerOption = '' ?>
                                @endif

                                <select  class="control" name="customer_group_id">
                                    @foreach($customerGroup as $group)
                                    <option value="{{ $group->id }}" {{ $selectedCustomerOption == $group->id ? 'selected' : '' }}>
                                        {{ $group->group_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group" :class="[errors.has('channel_id') ? 'has-error' : '']">
                                <label for="channel" >{{ __('admin::app.customers.customers.channel_name') }}</label>

                                @if(!is_null($customer->channel_id))
                                    <?php $selectedChannelOption = $customer->channel_id ?>
                                @else
                                    <?php $selectedChannelOption = $customer->channel_id ?>
                                @endif

                                <select  class="control" name="channel_id" v-validate="'required'">
                                    @foreach($channelName as $channel)
                                    <option value="{{ $channel->id }}" {{ $selectedChannelOption == $channel->id ? 'selected' : '' }}>
                                        {{ $channel->name}}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('channel_id')">@{{ errors.first('channel_id') }}</span>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop