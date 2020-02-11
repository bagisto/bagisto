
{!! view_render_event('bagisto.admin.content.create_form_accordian.content.link.before') !!}

<div class="control-group" :class="[errors.has('page_link') ? 'has-error' : '']">
    <label for="page_link" class="required">
        {{ __('velocity::app.admin.contents.content.category-slug') }}
    </label>

    <input
        type="text"
        id="page_link"
        class="control"
        name="page_link"
        v-validate="'required|max:150'"
        value="{{ isset($locale) ? (old($locale)['page_link'] ?? $content->translate($locale)['page_link']) : '' }}"
        data-vv-as="&quot;{{ __('velocity::app.admin.contents.content.category-slug') }}&quot;" />

    <span class="control-error" v-if="errors.has('page_link')">
        @{{ errors.first('page_link') }}
    </span>
</div>

<div class="control-group">
    <label for="link_target">
        {{ __('velocity::app.admin.contents.content.link-target') }}
    </label>

    @php
       $linkTarget = isset($locale) ? (old($locale)['link_target'] ?? $content->translate($locale)['link_target']) : '';
    @endphp

    <select
        class="control"
        id="link_target"
        name="{{ isset($locale) ? $locale . '[link_target]': 'link_target' }}">
        <option value="0">
            {{ __('velocity::app.admin.contents.self') }}
        </option>
        <option value="1" @if ($linkTarget == 1) selected="selected" @endif>
            {{ __('velocity::app.admin.contents.new-tab') }}
        </option>
    </select>
</div>

{!! view_render_event('bagisto.admin.content.create_form_accordian.content.link.after') !!}