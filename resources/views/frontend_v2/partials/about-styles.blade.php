<style>
.about-section{
  padding: 74px 0;
}
.about-section--soft{
  background: #f6f6f4;
}

.about-gap{ row-gap: 26px; }

.about-eyebrow{
  font-size:12px;
  letter-spacing:.24em;
  color:#777;
  margin-bottom:12px;
}
.about-eyebrow--light{ color:rgba(255,255,255,.75); }

.about-title{
  font-size:36px;
  line-height:1.15;
  margin-bottom:14px;
  color:#111;
}
.about-title--light{ color:#fff; }

.about-sub{
  color:#666;
  max-width: 860px;
  margin: 0 auto 18px;
  line-height:1.8;
}
.about-sub--light{ color:rgba(255,255,255,.85); }

.about-text{
  color:#444;
  line-height:1.9;
  margin-bottom:14px;
  font-size:16px;
}

.about-photo-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:14px;
}
.about-photo{
  border-radius:16px;
  min-height: 180px;
  background-size: cover;
  background-position:center;
  box-shadow:0 22px 50px rgba(0,0,0,.14);
  position:relative;
  overflow:hidden;
}
.about-photo::after{
  content:"";
  position:absolute;
  inset:0;
  background: radial-gradient(ellipse at center, rgba(0,0,0,0) 25%, rgba(0,0,0,.45) 100%);
}
.about-photo--big{ min-height: 270px; grid-row: span 2; }
.about-photo--wide{ grid-column: span 2; min-height: 190px; }

.about-caption{
  margin-top: 14px;
  color:#666;
  font-style: italic;
  line-height:1.7;
}

.about-cards{ margin-top: 22px; }
.about-card{
  background:#fff;
  border-radius:18px;
  padding:26px 22px;
  height:100%;
  box-shadow:0 20px 50px rgba(0,0,0,.10);
  border:1px solid rgba(0,0,0,.04);
}
.about-card__icon{
  width:44px;
  height:44px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:14px;
  background:rgba(0,0,0,.05);
  margin-bottom:14px;
  font-size:18px;
}
.about-card__title{
  font-size:18px;
  margin-bottom:8px;
  font-weight:800;
}
.about-card__text{
  color:#555;
  line-height:1.8;
  margin:0;
}

.about-impact{
  display:grid;
  grid-template-columns: repeat(4, 1fr);
  gap:14px;
}
.about-impact__item{
  border-radius:18px;
  padding:18px 16px;
  border:1px solid rgba(0,0,0,.06);
  background:#fff;
  box-shadow:0 18px 40px rgba(0,0,0,.08);
  text-align:center;
}
.about-impact__num{
  font-size:28px;
  font-weight:900;
  color:#111;
}
.about-impact__label{
  margin-top:4px;
  color:#666;
  font-size:13px;
}

.about-section--dark{
  position:relative;
  background-size:cover;
  background-position:center;
  padding: 86px 0;
}
.about-dark__overlay{
  position:absolute;
  inset:0;
  background:
    radial-gradient(ellipse at center, rgba(0,0,0,.15) 0%, rgba(0,0,0,.78) 100%),
    linear-gradient(to bottom, rgba(0,0,0,.35), rgba(0,0,0,.65));
}
.about-section--dark .container{ position:relative; z-index:1; }

.about-list{
  list-style:none;
  padding:0;
  margin: 20px 0 0;
}
.about-list li{
  display:flex;
  gap:10px;
  color:rgba(255,255,255,.88);
  line-height:1.9;
  padding:8px 0;
  border-bottom:1px solid rgba(255,255,255,.10);
}
.about-list--dark li{
  color:#222;
  border-bottom:1px solid rgba(0,0,0,.10);
}
.about-list--dark li span{
  color:#111;
}
.about-list li span{
  display:inline-flex;
  width:22px;
  height:22px;
  border-radius:999px;
  align-items:center;
  justify-content:center;
  background:rgba(255,255,255,.12);
  flex:0 0 22px;
}

.about-cta{
  background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.14);
  border-radius:18px;
  padding:28px 22px;
  backdrop-filter: blur(6px);
}
.about-cta--light{
  background:#ffffff;
  border:1px solid #e6e6e6;
  box-shadow:0 14px 30px rgba(0,0,0,.08);
  backdrop-filter: none;
}
.about-cta__title{
  color:#fff;
  font-size:20px;
  font-weight:900;
  margin-bottom:8px;
}
.about-cta__text{
  color:rgba(255,255,255,.85);
  line-height:1.8;
  margin-bottom:16px;
}
.about-cta--light .about-cta__title{ color:#111; }
.about-cta--light .about-cta__text{ color:#444; }
.about-cta--light .about-list li{ color:#444; }
.about-cta--light .about-list li span{ color:#111; }
.btn-primary-soft--full{
  width:100%;
  text-align:center;
}

@media (max-width: 991px){
  .about-impact{ grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575px){
  .about-section{ padding: 56px 0; }
  .about-title{ font-size:28px; }
}

@media (min-width: 1200px) {
  .container, .elementor-section.elementor-section-boxed > .elementor-container {
    max-width: 1140px;
  }
}

@media (min-width: 1500px) {
  .container, .elementor-section.elementor-section-boxed > .elementor-container {
    max-width: 1350px;
  }
}
@media (min-width: 768px) {
  .col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding-right: 15px;
    padding-left: 15px;
  }
}
.row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}
</style>
