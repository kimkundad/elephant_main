<article class="nav-menu v2-menu" id="v2-menu">
    <div class="box-menu">
        <div class="col-xs-6">
            <div>
                <div class="image-hover-active">
                    <figure>
                        <div class="reveal-block"></div>
                        <img src="{{ asset('samet/assets/cover-active.jpg') }}" alt="Small Elephants">
                    </figure>
                </div>
                <div class="image-hover">
                    <figure>
                        <img src="{{ asset('samet/assets/gallery.jpg') }}" alt="Small Elephants">
                    </figure>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <nav>
                <ul>
                    <li class="nav-link">
                        <a href="{{ route('frontend.home') }}">
                            <div class="real">Home</div>
                            <div class="hover">
                                <span>Home</span>
                                <div class="cover-hover"></div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="{{ route('frontend.tours.index') }}">
                            <div class="real">Tours</div>
                            <div class="hover">
                                <span>Tours</span>
                                <div class="cover-hover"></div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="{{ route('frontend.about') }}">
                            <div class="real">About</div>
                            <div class="hover">
                                <span>About</span>
                                <div class="cover-hover"></div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="{{ route('frontend.contact') }}">
                            <div class="real">Contact</div>
                            <div class="hover">
                                <span>Contact</span>
                                <div class="cover-hover"></div>
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</article>
