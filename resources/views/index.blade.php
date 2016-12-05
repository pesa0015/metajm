<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Metajm</title>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/ionicons/ionicons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/timepicker/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/niftymodal/component.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/datepicker/datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/tooltipster/tooltipster.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
</head>
<body>
    <div id="csrf-token" data-value="{{ Session::token() }}"></div>
    <div id="payment"></div>
    <div class="md-modal md-effect-1" id="login-modal">
        <div class="md-content">
            <h1>Bokning</h1>
            <form action="#" method="post" id="login-form">
                <div>Logga in för att fortsätta:</div>
                <input type="hidden" id="login-action">
                <input type="text" id="email">
                <input type="password" id="password">
                <input type="submit" value="Logga in">
                <div>Ny kund?</div>
            </form>
        </div>
    </div>
    <div class="md-modal md-effect-1" id="register-modal">
        <div class="md-content">
            <h1>Registrera</h1>
            <form action="#" method="post" id="register-form">
                <input type="hidden" id="register-action">
                <input type="text" id="email">
                <input type="password" id="password">
                <input type="submit" value="Registrera">
                <span>Jag har redan konto</span>
            </form>
        </div>
    </div>
    <div class="md-modal md-effect-1" id="confirm-modal">
        <div class="md-content">
            <h1>Boka</h1>
            <div id="booking-info">
                <div id="company"></div>
                <div id="service"></div>
                <div id="stylist"></div>
                <div id="time"></div>
                <div id="address"></div>
            </div>
            <hr />
            <div>
                <div id="my-name"></div>
                <div id="my-email"></div>
                <div id="my-tel"></div>
            </div>
            <br />
            <div>Betalning måste slutföras inom 15 minuter.</div>
            <br />
            <div id="wait" class="showbox hide">
                <div class="loader">
                    <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                    </svg>
                </div>
            </div>
            <form name="paymentForm" id="startPayment" method="post" action="#">
                <input type="submit" id="start_booking" name="start_booking" value="Till betalning">
            </form>
        </div>
    </div>
    <div class="md-overlay"></div>
    <div id="start">
        <img src="{{ URL::asset('img/test_bild_2.jpg') }}" id="a" style="display:none;">
        <div id="fade" style="display:none;"></div>
        <div id="start-background">
            <div id="no-position">
                <nav>
                    <div id="logo">
                        <img src="img/image.jpg" alt="">
                    </div>
                    <ul>
                        <li><a href="">Boka direkt</a></li>
                        <li><a href="">Vad är me tajm?</a></li>
                        <li><a href="">Presentkort</a></li>
                        <li><a href="">Villkor</a></li>
                        <li><a href="">Kontakt</a></li>
                        <li><a href="#" id="login-type">Logga in</a>
                            <ul id="login-as">
                                <li><a href="/logga-in/privat">Privatperson</a></li>
                                <li><a href="/logga-in/foretag">Företag</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div id="intro">För er som vill boka den bästa tiden inom skönhet och hälsa, varför jaga slutet på regnbågen när vi har kartan dit.</div>
                <form id="geolocation">
                    <input type="text" id="search-text" class="address" placeholder="Sök salong eller tjänst:" value="Ciceros Hårvårdsateljé" autocomplete="off">
                    <input type="hidden" id="lat" name="lat">
                    <input type="hidden" id="lng" name="lng">  
                    <button id="search-button">
                        <i class="ion-ios-search-strong"></i>
                    </button>
                    <ul id="results"></ul>
                    <div id="cities-and-day">
                        <i class="ion-android-alert tooltip" title="Välj stad"></i>
                        <select id="choose-city">
                            <option value="0">Välj stad</option>
                            <option value="stockholm" data-lat="59.329324" data-lng="18.068581">Stockholm</option>
                            <option value="göteborg" data-lat="57.70887" data-lng="11.97456">Göteborg</option>
                            <option value="malmö" data-lat="55.604981" data-lng="13.003822">Malmö</option>
                        </select>
                        <input type="text" id="choose-day" class="timestamp" value="Alla dagar"><i class="ion-ios-close tooltip" title="Återställ datum"></i>
                    </div>
                    <div id="services-quicksearch">
                        <span>snabbsök via tjänst</span>
                        <input type="checkbox" name="hair" data-title="Frisör" value="valuable" id="hair"/><label class="services" for="hair"></label>
                        <input type="checkbox" name="nail" data-title="Nagelvård" value="valuable" id="nail"/><label class="services" for="nail"></label>
                        <input type="checkbox" name="dental" data-title="Tandvård" value="valuable" id="dental"/><label class="services" for="dental"></label>
                        <input type="checkbox" name="tattoo" data-title="Tatuering" value="valuable" id="tattoo"/><label class="services" for="tattoo"></label>
                    </div> 
                </form>
            </div>
            <div class="arrow bounce not"></div>
            <div id="video" data-vide-bg="{{ URL::asset('vendor/vide_js/blurry_street_view.mp4') }}"></div>
            <div id="selected-company">
                <div id="company-data">
                    <!-- <i class="ion-ios-information-outline"></i> -->
                    <h3 id="company-name"></h3>
                    <div id="company-address"></div>
                    <div id="open">Öppet idag: <span id="hour-start"></span> - <span id="hour-close"></span></div>
                    <div id="company-tel"><i class="ion-ios-telephone"></i><span></span></div>
                    <div id="company-mail"><i class="ion-email"></i><span></span></div>
                    <div id="choose-buttons">
                        <!-- <div id="go-to-days">Välj dag <i class="fa fa-calendar-o"></i></div> -->
                        <input type="text" id="go-to-days" class="timestamp" value="Alla dagar"><i class="ion-ios-close tooltip" title="Återställ datum"></i>
                        <!-- <div><i class="ion-android-alert"></i></div> -->
                        <div id="go-to-times">Välj tid <i class="ion-ios-clock-outline"></i></div>
                        <ul id="select-time"></ul>
                        <div id="onselectTarget"></div>
                    </div>
                </div>
                <div id="choose-service">
                    <!-- <i class="ion-compose"></i> -->
                    <div id="book-top">
                        <div id="book-question">Vad önskar du boka?</div>
                        <div id="go-to-stylist">Välj stylist <i class="ion-android-star-half"></i></div>
                        <ul id="select-stylist"></ul>
                    </div>
                    <div id="services"></div>
                    <div id="go-to-booking" class="disabled" disabled>Boka <i class="ion-checkmark-round"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div id="custom-address">
        <span>Adress:</span><input type="text" id="address">
        <span>Radie (m):</span><input type="number" id="radius" min="500" max="7000" step="500" value="1000">
    </div>
    <div id="map"></div>
    <div id="company-list"></div>
<script src="{{ URL::asset('vendor/jquery/jquery-1.12.0.min.js') }}"></script>
<script src="{{ URL::asset('vendor/moment_js/moment.js') }}"></script>
<script src="{{ URL::asset('vendor/moment_js/locale/sv.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery/jquery-ui.min.js') }}"></script>
<script src="{{ URL::asset('vendor/noty_js/jquery.noty.packaged.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyCnC6NfMTBYVxiADGlb63in57NiN5l59Bg&libraries=places"></script>
<script src="{{ URL::asset('vendor/locationpicker/locationpicker.jquery.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.4/dist/typeahead.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead-addresspicker/0.1.4/typeahead-addresspicker.min.js"></script>
<script src="{{ URL::asset('vendor/niftymodal/classie.js') }}"></script>
<script src="{{ URL::asset('vendor/niftymodal/modalEffects.js') }}"></script>
<script src="{{ URL::asset('vendor/vide_js/jquery.vide.js') }}"></script>
<script src="{{ URL::asset('vendor/tooltipster/jquery.tooltipster.min.js') }}"></script>
<script src="{{ URL::asset('js/script.js') }}"></script>
</body>
</html>