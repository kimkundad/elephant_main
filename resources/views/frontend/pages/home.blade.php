@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')


    <!-- HOME SLIDER -->
   <div class="video-container" style="    margin-bottom: 108px;">
      <video id="video" class="video" preload="metadata" autoplay="" loop="" muted=""
         poster="https://matchthemes.com/demowp/caverta/wp-content/uploads/caverta-video-img.jpg">
         <source src="{{ asset('frontend/images/112696-695433068_tiny.mp4') }}" type="video/mp4">
      </video>
      <div class="slider-caption">
         <div class="slider-text">
            <div class="slider-text">
               <div class="intro-txt">Elephants in Phuket</div>
               <h2>Elephants</h2>
               <p>Phuket Elephant Sanctuary is the first ethical elephant sanctuary in Phuket.</p>
               <a href="#" class="slider-btn">Contact Us</a>
            </div>
         </div>
      </div>
   </div>
   <!-- /HOME SLIDER -->
   <!-- WRAP CONTENT -->
   <div id="wrap-content" class="page-content custom-page-template">
      <!-- SECTION 1 -->
      <div id="home-content-1" class="home-section home-section-1">
         <div class="container">
            <div class="row align-items-center">

               <div class="col-md-6 mobile-order2">
                  <div class="row">
                     <div class="col-6 col-md-6 margin-tn-30">
                        <img src="{{ asset('frontend/images/welcome-x1.png') }}" alt="Welcome 1">
                     </div>
                     <!-- /col-md-6 -->
                     <div class="col-6 col-md-6">
                        <img src="{{ asset('frontend/images/welcome-x2.png') }}" alt="Welcome 2">
                     </div>
                     <!-- /col-md-6 -->
                  </div>
                  <!-- /row -->
               </div>


               <!-- <div class="col-md-3">
                  <img src="./index_files/images/welcome-2.png" alt="Welcome 1">
               </div> -->
               <!-- /col-md-3 -->
               <div class="col-md-6 mobile-order1">
                  <div class="alignc">
                     <div class="smalltitle margin-b16">Welcome</div>
                     <h2 class="home-title">Elephants in Phuket</h2>
                     <p>For a truly memorable dining experience, cuisine and atmosphere are paired as thoughtfully as
                        food and wine. Ut enim ad minim veniam, quis nostrud exercitation ullamco. Quia consequuntur
                        magni dolores eos qui ratione voluptatem sequi nesciunt. Animi, id est laborum et dolorum fuga.
                        Nam libero.</p>
                     <p><a class="view-more margin-t24" href="">Book a Table</a></p>
                  </div>
               </div>
               <!-- /col-md-6 -->
               <!-- <div class="col-md-3">
                  <img src="./index_files/images/welcome-4.png" alt="Welcome 2">
               </div> -->
               <!-- /col-md-3 -->
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 1 -->
      <!-- SECTION 2 -->
      <div id="home-content-2" class="home-section home-section-2 parallax">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc">
                     <div class="smalltitle white margin-b16">Come and See</div>
                     <h2>We create delicious experiences</h2>
                  </div>
               </div>
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 2 -->
      <!-- SECTION 3 -->


      <div id="home-content-28" class="home-section home-section-28">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc mobile-margin-b48">
                     <div class="smalltitle margin-b16">Elephants</div>
                     <h2 class="home-title">Meet Our Elephants</h2>
                  </div>
               </div>
               <!-- /col-md-12 -->
            </div>
            <!-- /row -->
            <div class="blog-3col-grid home-blog-grid">
               <div class="row">
                  <div class="col-md-4">
                     <article class="blog-item blog-item-3col-grid">
                        <a href="">
                           <div class="post-image"><img src="./index_files/images/sroy_fah-1.webp" class="img-fluid"
                                 alt="Chef New Summer Dish"></div>
                        </a>
                        <div class="post-holder post-content content-grid">
                           <ul class="post-meta">
                              <li class="meta-date">RESCUED 8 FEBRUARY 2018</li>
                           </ul>
                           <h2 class="article-title"><a href="blog-single-post.html">SROY FAH</a></h2>
                           <p>Maecenas ornare varius mauris eu commodo. Aenean nibh risus, rhoncus eget consectetur ac.
                              Consectetur adipiscing elit. Vivamus auctor condimentum sem et gravida. Maecenas id enim
                              pharetra, sollicitudin dui eget, blandit ...</p>
                           <a class="view-more" href="blog-single-post.html">Read More</a>
                        </div>
                     </article>
                  </div>
                  <div class="col-md-4">
                     <article class="blog-item blog-item-3col-grid">
                        <a href="">
                           <div class="post-image"><img src="./index_files/images/madee-2.webp" class="img-fluid"
                                 alt="Chef New Summer Dish"></div>
                        </a>
                        <div class="post-holder post-content content-grid">
                           <ul class="post-meta">
                              <li class="meta-date">RESCUED 8 FEBRUARY 2018</li>
                           </ul>
                           <h2 class="article-title"><a href="blog-single-post.html">SROY FAH</a></h2>
                           <p>Maecenas ornare varius mauris eu commodo. Aenean nibh risus, rhoncus eget consectetur ac.
                              Consectetur adipiscing elit. Vivamus auctor condimentum sem et gravida. Maecenas id enim
                              pharetra, sollicitudin dui eget, blandit ...</p>
                           <a class="view-more" href="blog-single-post.html">Read More</a>
                        </div>
                     </article>
                  </div>
                  <div class="col-md-4">
                     <article class="blog-item blog-item-3col-grid">
                        <a href="">
                           <div class="post-image"><img src="./index_files/images/sri_nual-1.webp" class="img-fluid"
                                 alt="Chef New Summer Dish"></div>
                        </a>
                        <div class="post-holder post-content content-grid">
                           <ul class="post-meta">
                              <li class="meta-date">RESCUED 8 FEBRUARY 2018</li>
                           </ul>
                           <h2 class="article-title"><a href="blog-single-post.html">SROY FAH</a></h2>
                           <p>Maecenas ornare varius mauris eu commodo. Aenean nibh risus, rhoncus eget consectetur ac.
                              Consectetur adipiscing elit. Vivamus auctor condimentum sem et gravida. Maecenas id enim
                              pharetra, sollicitudin dui eget, blandit ...</p>
                           <a class="view-more" href="blog-single-post.html">Read More</a>
                        </div>
                     </article>
                  </div>
               </div>
               <!-- /row -->
            </div>
            <!-- /home-blog-grid -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 3 -->
      <!-- SECTION 4 -->
      <div id="home-content-4" class="home-section home-section-4 parallax">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc">
                     <div class="smalltitle white margin-b16">Now booking</div>
                     <h2>Private Dinners &amp; Happy Hours</h2>
                  </div>
               </div>
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 4 -->
      <!-- SECTION 5 -->

      <div id="home-content-3" class="home-section home-section-3">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc">
                     <div class="smalltitle margin-b16">Explore How We’re Different</div>
                     <h2 class="home-title">Pioneering Ethical Elephant Tourism In Phuket</h2>
                     <div class="width60">Phuket Elephant Sanctuary is the first ethical elephant sanctuary in Phuket.
                        Set on 30 acres of lush tropical jungle
                        bordering the Khao Prae Teao National Park, we provide a final home for retired working
                        elephants. Observe how elephants rehabilitate into
                        forest life after decades of hard work, and experience how incredible the largest land mammal on
                        earth is during a day at our sanctuary.</div>
                  </div>
                  <!-- MENU TAB -->
                  <ul class="nav nav-tabs menuTab" id="menuTab" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="starters-tab" data-toggle="tab" href="#starters" role="tab"
                           aria-controls="starters" aria-selected="true">STARTERS</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="main-course-tab" data-toggle="tab" href="#main-course" role="tab"
                           aria-controls="main-course" aria-selected="false">MAIN COURSE</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="soups-tab" data-toggle="tab" href="#soups" role="tab"
                           aria-controls="soups" aria-selected="false">SOUPS</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="desserts-tab" data-toggle="tab" href="#desserts" role="tab"
                           aria-controls="desserts" aria-selected="false">DESSERTS</a>
                     </li>
                  </ul>
                  <div class="tab-content" id="menuTabContent">
                     <!-- STARTERS -->
                     <div class="tab-pane fade show active" id="starters" role="tabpanel"
                        aria-labelledby="starters-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Tomato Bruschetta</span><span class="menu-price">$4.00</span>
                              </h4>
                              <div class="menu-text">Tomatoes, Olive Oil, Cheese</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Avocado &amp; Mango Salsa</span><span
                                    class="menu-price">$5.00</span></h4>
                              <div class="menu-text">Avocado, Mango, Tomatoes</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Marinated Grilled Shrimp</span><span
                                    class="menu-price">$7.00</span></h4>
                              <div class="menu-text">Fresh Shrimp, Oive Oil, Tomato Sauce</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Baked Potato Skins</span><span
                                    class="menu-price">$9.00</span></h4>
                              <div class="menu-text">Potatoes, Oil, Garlic</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Maitake Mushroom</span><span class="menu-price">$10.00</span>
                              </h4>
                              <div class="menu-text">Whipped Miso, Yaki Sauce, Sesame</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Lobster Picante</span><span class="menu-price">$12.00</span>
                              </h4>
                              <div class="menu-text">Grilled Corn Elote, Queso Cotija, Chili</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Jambon Iberico</span><span class="menu-price">$10.00</span>
                              </h4>
                              <div class="menu-text">Smoked Tomato Aioli, Idizabal Cheese, Spiced Pine Nuts</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Garlic Baked Cheese</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Finnish Squeaky Cheese, Eggplant Conserva, Black Pepper</div>
                           </li>
                        </ul>
                     </div>
                     <!-- MAIN COURSE -->
                     <div class="tab-pane fade" id="main-course" role="tabpanel" aria-labelledby="main-course-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Braised Pork Chops</span><span
                                    class="menu-price">$21.00</span></h4>
                              <div class="menu-text">4 bone-in pork chops, olive oil, garlic, onion </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Coconut Fried Chicken </span><span
                                    class="menu-price">$19.00</span></h4>
                              <div class="menu-text">8 chicken pieces, coconut milk, oil </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Chicken with Garlic &amp; Tomatoes </span><span
                                    class="menu-price">$15.00</span></h4>
                              <div class="menu-text">Chicken, cherry tomatoes, olive oil, dry white wine</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Prime Rib</span><span class="menu-price">$25.00</span></h4>
                              <div class="menu-text">Rib, rosemary, black pepper, red wine </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Sriracha Beef Skewers</span><span
                                    class="menu-price">$18.00</span></h4>
                              <div class="menu-text">Beef, garlic, sesame oil, vinegar </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Crispy Tuna Fregola</span><span
                                    class="menu-price">$22.00</span></h4>
                              <div class="menu-text">Fregola, Baby Arugula Roasted, Green Olives, Pine Nuts</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Charred Lamb Ribs</span><span
                                    class="menu-price">$20.00</span></h4>
                              <div class="menu-text">Za’atar, Turkish BBQ, Sesame Yoghurt</div>
                           </li>
                        </ul>
                     </div>
                     <!-- SOUPS -->
                     <div class="tab-pane fade" id="soups" role="tabpanel" aria-labelledby="soups-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Terrific Turkey Chili</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Turkey, oregano, tomato paste, peppers </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Cream of Asparagus Soup</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Asparagus, potato, celery, onion, pepper </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Creamy Chicken &amp; Wild Rice Soup</span><span
                                    class="menu-price">$9.00</span></h4>
                              <div class="menu-text">Cooked chicken, salt, butter, heavy cream </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Italian Sausage Tortellini</span><span
                                    class="menu-price">$8.00</span></h4>
                              <div class="menu-text">Cheese tortellini, sausage, garlic, carrots, zucchini</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Italian Sausage Soup</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Italian sausage, garlic, carrots, zucchini </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Ham and Potato Soup</span><span
                                    class="menu-price">$11.00</span></h4>
                              <div class="menu-text">Potatoes, ham, celery, onion, milk </div>
                           </li>
                        </ul>
                     </div>
                     <!-- DESSERTS -->
                     <div class="tab-pane fade" id="desserts" role="tabpanel" aria-labelledby="desserts-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Summer Berry and Coconut Tart</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Raspberries, blackberries, blueberries, graham cracker</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Double Chocolate Cupcakes</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Chocolate, eggs, vanilla, milk </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Pumpkin Cookies Cream Cheese</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Pumpkin, sugar, butter, eggs </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <!-- /MENU TAB -->
               </div>
               <!-- /col-md-12 -->
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>



      <!-- /SECTION 5 -->
      <!-- SECTION 6 -->
      <div id="home-content-6" class="home-section home-section-6 parallax " style="margin-bottom: 0px;">
         <div class="container">
            <div class="row">
               <div class="col-md-10 offset-md-1">

                  <div class="owl-carousel owl-theme testimonial-slider owl-loaded owl-drag">


                     <div class="elfsight-app-286b5f4d-0bc2-452d-b1b8-65c738b271d2"></div>


                  </div>
                  <!-- /testimonial-slider -->
               </div>
               <!-- /col-md-12 -->
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 6 -->


   </div>
   <!-- /WRAP CONTENT -->

@endsection
