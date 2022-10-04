<div class="col-lg-4 col-md-12 col-sm-12 footer-ct-content">
	<div class="row">

        @if ($velocityMetaData)
            {!! DbView::make($velocityMetaData)->field('footer_middle_content')->render() !!}
        @else
            <div class="col-lg-6 col-md-12 col-sm-12 no-padding">
                <ul type="none">
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.about-us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.customer-service') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.whats-new') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.contact-us') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 no-padding">
                <ul type="none">
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.order-and-returns') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.payment-policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.shipping-policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about-us/company-profile') }}">
                            {{ __('velocity::app.admin.meta-data.footer-middle.privacy-and-cookies-policy') }}
                        </a>
                    </li>
                </ul>
            </div>
        @endif
	</div>
</div>