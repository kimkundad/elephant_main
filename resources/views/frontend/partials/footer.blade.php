<!-- FOOTER -->
   <footer style="margin-top: 0px;">
      <div class="container">
         <div class="footer-widgets">
            <div class="row">
               <!-- FOOTER COLUMN 1 -->
               <div class="col-md-3">
                  <div class="foo-block">
                     <div id="text-2" class="widget widget-footer widget_text">
                        <div class="textwidget">
                           <p><img class="size-full wp-image-665"
                                 src="https://www.phuketelephantsanctuary.org/wp-content/uploads/sites/7659/2025/01/45c7bf722bb167f407ce49150b85be7b.png?h=120&zoom=2"
                                 alt="" width="143" height="51"></p>
                           <p>{{ $siteSetting->footer_about ?? 'For a truly memorable dining experience reserve in advance a table as soon as you can. Come and taste our remarkable food and wine.' }}</p>
                        </div>
                     </div>
                  </div>
                  <!--foo-block-->
               </div>
               <!--col-md-3-->
               <!-- FOOTER COLUMN 2 -->
               <div class="col-md-3">
                  <div class="foo-block">
                     <div id="text-3" class="widget widget-footer widget_text">
                        <h5 class="widgettitle"><span>Address</span></h5>
                        <div class="textwidget">
                           <p>{!! nl2br(e($siteSetting->address ?? "58 Ralph Ave
New York, New York 1111")) !!}</p>
                           <p>
                              P: {{ $siteSetting->phone ?? '+1 800 000 111' }}<br>
                              E: <a href="mailto:{{ $siteSetting->email ?? 'infosmallelephants@gmail.com' }}">{{ $siteSetting->email ?? 'infosmallelephants@gmail.com' }}</a>
                           </p>
                        </div>
                     </div>
                  </div>
                  <!--foo-block-->
               </div>
               <!--col-md-3-->
               <!-- FOOTER COLUMN 3 -->
               <div class="col-md-3">
                  <div class="foo-block">
                     <div id="text-4" class="widget widget-footer widget_text">
                        <h5 class="widgettitle"><span>Hours</span></h5>
                        <div class="textwidget">
                           <p>{!! nl2br(e($siteSetting->hours ?? "Monday - Sunday
Lunch: 12PM - 2PM
Dinner: 6PM - 10PM")) !!}</p>
                        </div>
                     </div>
                  </div>
                  <!--foo-block-->
               </div>
               <!--col-md-3-->
               <!-- FOOTER COLUMN 4 -->
               <div class="col-md-3">
                  <div class="foo-block foo-last">
                     <div id="text-5" class="widget widget-footer widget_text">
                        <h5 class="widgettitle"><span>More Info</span></h5>
                        <div class="textwidget">
                           <ul>
                              <li><a href="#">Careers</a></li>
                              <li><a href="#">Get in Touch</a></li>
                              <li><a href="#">Privacy Policy</a></li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <!--foo-block-->
               </div>
               <!--col-md-3-->
            </div>
            <!--row-->
         </div>
         <!-- footer-widgets -->
         <div class="copyright">
            <!-- COPYRIGHT -->
            <div class="footer-copy">
               <p>{{ $siteSetting->copyright_text ?? 'Copyright � 2026, smallelephants.com' }}</p>
            </div>
            <!-- SOCIAL ICONS -->
            <ul class="footer-social">
               <li><a class="social-facebook" href="{{ $siteSetting->facebook_url ?? '#' }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
               <li><a class="social-instagram" href="{{ $siteSetting->instagram_url ?? '#' }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
            </ul>
         </div>
         <!--copyright-->
      </div>
      <!--container-->
   </footer>

