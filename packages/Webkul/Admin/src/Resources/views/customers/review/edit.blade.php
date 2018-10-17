@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.reviews.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.review.update', $review->id) }}">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.customers.reviews.edit-title') }}
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
                                <label for="name" > {{ __('admin::app.customers.reviews.title') }}</label>
                                <input type="text"  class="control" id="name" name="name" value="{{$review->title}}" disabled/>
                            </div>

                            <div class="control-group">
                                <label for="name" >{{ __('admin::app.customers.reviews.rating') }}</label>
                                @for($i = 1; $i <= $review->rating ; $i++)
                                <span class="stars">
                                    <span class="icon star-icon"></span>
                                </span>
                                @endfor
                            </div>

                            <div class="control-group">
                                <label for="name" class="required">{{ __('admin::app.customers.reviews.status') }}</label>
                                <select  class="control" name="status">
                                    <option value="pending" {{ $review->status == "pending" ? 'selected' : ''}}>
                                        pending
                                    </option>
                                    <option value="approved" {{ $review->status == "approved" ? 'selected' : '' }}>
                                        approved
                                    </option>
                                </select>
                            </div>

                            <div class="control-group">
                                <label for="name" >{{ __('admin::app.customers.reviews.comment') }}</label>
                                <textarea  class="control" disabled> {{$review->comment}}</textarea>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop