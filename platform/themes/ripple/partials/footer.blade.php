<footer data-background="{{ Theme::asset()->url('images/page-intro-01.png') }}" class="page-footer bg-dark pt-50 bg-parallax">
    <div class="bg-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <aside class="widget widget--transparent widget__footer widget__about">
                    <div class="widget__content">
                        <header class="person-info">
                            <div class="person-info__thumbnail"><a href="{{ route('public.single') }}"><img src="{{ Theme::asset()->url('images/men.jpg') }}" alt="{{ theme_option('site_title') }}"></a></div>
                            <div class="person-info__content">
                                <h3 class="person-info__title">{{ theme_option('site_title') }}</h3>
                                <p class="person-info__description">{{ theme_option('site_description') }}</p>
                            </div>
                        </header>
                        <div class="person-detail">
                            <p><i class="ion-home"></i>{{ theme_option('address') }}</p>
                            <p><i class="ion-earth"></i><a href="{{ theme_option('website') }}">{{ theme_option('website') }}</a></p>
                            <p><i class="ion-email"></i><a href="mailto:{{ theme_option('contact_email') }}">{{ theme_option('contact_email') }}</a></p>
                        </div>
                    </div>
                </aside>
            </div>
            {!! dynamic_sidebar('footer_sidebar') !!}
        </div>
    </div>
    <div class="page-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="page-copyright">
                        <p>{!! clean(theme_option('copyright')) !!}</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="page-footer__social">
                        <ul class="social social--simple">
                            <li>
                                <a href="{{ theme_option('facebook') }}" title="Facebook" class="hi-icon fa fa-facebook"></a>
                            </li>
                            <li>
                                <a href="{{ theme_option('twitter') }}" title="Twitter" class="hi-icon fa fa-twitter"></a>
                            </li>
                            <li>
                                <a href="{{ theme_option('youtube') }}" title="Youtube" class="hi-icon fa fa-youtube"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div id="back2top"><i class="fa fa-angle-up"></i></div>
</div>

<!-- JS Library-->
{!! Theme::footer() !!}

@if (session()->has('success_msg'))
    <script type="text/javascript">
        swal('{{ __('Success') }}', "{{ session('success_msg', '') }}", 'success');
    </script>
@endif

@if (session()->has('error_msg'))
    <script type="text/javascript">
        swal('{{ __('Success') }}', "{{ session('error_msg', '') }}", 'error');
    </script>
@endif

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId={{ setting('facebook_app_id', config('plugins.facebook.general.app_id')) }}&autoLogAppEvents=1"></script>

<div class="fb-customerchat"
     attribution=setup_tool
     page_id="157007981299897"
     theme_color="#0084ff">
</div>

</body>
</html>
