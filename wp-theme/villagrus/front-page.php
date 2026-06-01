<?php get_header(); ?>

<?php
$shop_url    = get_permalink(wc_get_page_id('shop'));
$theme_uri   = get_template_directory_uri();
$images      = $theme_uri . '/assets/images';
?>

<!-- 1. HERO -->
<section class="hero">
  <div class="hero-bg">
    <video autoplay muted loop playsinline>
      <source src="<?php echo esc_url($theme_uri . '/assets/video/hero.mp4'); ?>" type="video/mp4">
    </video>
  </div>
  <div class="hero-gradient"></div>
  <div class="hero-content">
    <div class="hero-tag">Naturens material sedan 2014</div>
    <h1>Naturens<br>material.<br><em>Direkt hem</em><br>till dig.</h1>
    <p class="hero-sub">Premiumprodukter inom dekorsten, natursingel, bergskross och trädgårdsjord — levererat direkt till din adress.</p>
    <a href="<?php echo esc_url($shop_url); ?>" class="hero-cta">
      Se alla produkter
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
    </a>
  </div>
  <div class="hero-scroll">Scrolla</div>
  <div class="usp-strip">
    <div class="usp-item">
      <div class="usp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
      <div class="usp-text"><h4>Klarna Betalning</h4><p>Dela upp på 3 räntefria delar</p></div>
    </div>
    <div class="usp-item">
      <div class="usp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
      <div class="usp-text"><h4>Kostnadsfri rådgivning</h4><p>Vi hjälper dig välja rätt produkt</p></div>
    </div>
    <div class="usp-item">
      <div class="usp-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
      <div class="usp-text"><h4>Hemleverans</h4><p>Leverans direkt till din adress</p></div>
    </div>
  </div>
</section>

<!-- 2. KATEGORIER -->
<section class="categories-section">
  <div class="categories-header">
    <div>
      <div class="section-label reveal">Sortiment</div>
      <h2 class="section-title reveal">Vilken produkttyp<br><em>söker du?</em></h2>
    </div>
    <a href="<?php echo esc_url($shop_url); ?>" class="see-all reveal">
      Se alla produkter <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 7h10M8 3l4 4-4 4"/></svg>
    </a>
  </div>
  <div class="alt-cat-grid" id="altCatGrid">
    <?php
    $cat_images = [
        'Jord'                => ['img' => 'jord.jpg',       'desc' => 'Gräsmatte-, trädgårds- &amp; rabattjord',    'pos' => 'left bottom'],
        'Makadam'             => ['img' => 'makadam.jpg',    'desc' => 'Krossat berg för uppfarter &amp; dränering'],
        'Natursingel'         => ['img' => 'natursingel.jpg','desc' => 'Naturligt rundad singel i flera fraktioner'],
        'Dekorsten'           => ['img' => 'dekorsten.jpg',  'desc' => 'Vit marmor, färgad singel och natursten'],
        'Bergkross & Stenmjöl'=> ['img' => 'bergskross.jpg', 'desc' => 'Packning, dränering &amp; markarbeten'],
    ];
    $delay = 0;
    $terms = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => true, 'number' => 8]);
    if ($terms && !is_wp_error($terms)):
        foreach ($terms as $i => $term):
            $cfg   = $cat_images[$term->name] ?? ['img' => 'bergskross.jpg', 'desc' => esc_html($term->description)];
            $img   = $images . '/' . $cfg['img'];
            $style = isset($cfg['pos']) ? ' style="object-position:' . $cfg['pos'] . '"' : '';
            $d     = $i > 0 ? ' reveal-delay-' . min($i, 4) : '';
    ?>
    <div class="alt-cat-card reveal<?php echo $d; ?>">
      <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($term->name); ?>"<?php echo $style; ?>>
      <div class="alt-cat-overlay"></div>
      <div class="alt-cat-content">
        <h3><?php echo esc_html($term->name); ?></h3>
        <p><?php echo $cfg['desc']; ?></p>
      </div>
      <div class="alt-cat-footer">
        <a href="<?php echo esc_url(get_term_link($term)); ?>" class="alt-cat-btn">Utforska <span class="arr">›</span></a>
      </div>
    </div>
    <?php endforeach; endif; ?>
  </div>
  <div class="alt-cat-nav" id="altCatNav">
    <button class="alt-nav-btn" id="altPrev"><svg viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4l-5 5 5 5"/></svg></button>
    <button class="alt-nav-btn" id="altNext"><svg viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 4l5 5-5 5"/></svg></button>
  </div>
</section>

<!-- 3. POPULÄRA PRODUKTER -->
<section class="products-section">
  <div class="products-header">
    <div>
      <div class="section-label reveal">Populärt just nu</div>
      <h2 class="section-title reveal">Utvalda<br><em>produkter</em></h2>
    </div>
    <a href="<?php echo esc_url($shop_url); ?>" class="see-all reveal">
      Se alla <svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 7h10M8 3l4 4-4 4"/></svg>
    </a>
  </div>
  <div class="plp-grid">
    <?php
    $featured = wc_get_products(['limit' => 4, 'status' => 'publish', 'orderby' => 'popularity']);
    if (empty($featured)):
        $featured = wc_get_products(['limit' => 4, 'status' => 'publish']);
    endif;
    $delays = ['', ' reveal-delay-1', ' reveal-delay-2', ' reveal-delay-3'];
    foreach ($featured as $i => $product):
        $thumb = $product->get_image('medium', ['class' => '']);
        $cats  = wp_get_post_terms($product->get_id(), 'product_cat', ['number' => 1]);
        $cat_name = $cats ? $cats[0]->name : '';
        $price = $product->get_price();
        $url   = $product->get_permalink();
    ?>
    <article class="plp-card reveal<?php echo $delays[$i] ?? ''; ?>">
      <a href="<?php echo esc_url($url); ?>" class="plp-card__img-wrap">
        <?php echo $thumb; ?>
        <?php if ($cat_name): ?><span class="plp-card__badge"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
      </a>
      <div class="plp-card__body">
        <h3 class="plp-card__name"><?php echo esc_html($product->get_name()); ?></h3>
        <p class="plp-card__frac"><?php echo wp_strip_all_tags($product->get_short_description()); ?></p>
        <?php if ($cat_name): ?><div class="plp-card__cat"><?php echo esc_html($cat_name); ?></div><?php endif; ?>
        <div class="plp-card__footer">
          <div class="plp-card__price">från <strong><?php echo villagrus_format_price($price); ?></strong></div>
          <a href="<?php echo esc_url($url); ?>" class="plp-card__cta">
            Välj produkt <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
          </a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<!-- 4. INSPIRATION CAROUSEL -->
<section class="blog-carousel" id="blog">
  <div class="blog-progress"><div class="blog-progress__bar" id="bBar"></div></div>
  <div class="blog-bgs" id="blogBgs">
    <?php
    $blog_imgs = ['jord.jpg','makadam.jpg','natursingel.jpg','dekorsten.jpg','bergskross.jpg'];
    foreach ($blog_imgs as $bi => $bimg):
    ?>
    <div class="blog-bg<?php echo $bi === 0 ? ' active' : ''; ?>">
      <img src="<?php echo esc_url($images . '/' . $bimg); ?>" alt="">
    </div>
    <?php endforeach; ?>
  </div>
  <div class="blog-overlay"></div>
  <div class="blog-hero" id="bHero">
    <span class="blog-hero__label" id="bLabel"></span>
    <h2 class="blog-hero__title" id="bTitle"></h2>
    <p class="blog-hero__intro" id="bIntro"></p>
    <a href="<?php echo esc_url(home_url('/inspiration/')); ?>" class="blog-hero__cta">
      Läs mer <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
    </a>
  </div>
  <div class="blog-cards-outer">
    <div class="blog-cards-track" id="bTrack"></div>
    <div class="blog-controls">
      <button class="blog-nav-btn" id="bPrev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 19l-7-7 7-7"/></svg></button>
      <button class="blog-nav-btn" id="bNext"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5l7 7-7 7"/></svg></button>
    </div>
  </div>
  <div class="blog-counter" id="bCounter"></div>
</section>

<!-- 5. FEATURED BANNER -->
<section class="feat-banner">
  <div class="feat-banner__img-wrap">
    <img src="<?php echo esc_url($images . '/jord-4.jpg'); ?>" alt="Rätt jord för varje ändamål">
    <div class="feat-banner__overlay"></div>
    <div class="feat-banner__card reveal">
      <span class="feat-banner__num">Utvalda produkter</span>
      <h2 class="feat-banner__title">Rätt jord.<br><em>För varje ändamål.</em></h2>
      <p class="feat-banner__sub">Från gräsmattejord till rabattjord — vi har vad din trädgård behöver.</p>
      <?php
      $jord_term = get_term_by('name', 'Jord', 'product_cat');
      $jord_url  = $jord_term ? get_term_link($jord_term) : $shop_url;
      ?>
      <a href="<?php echo esc_url($jord_url); ?>" class="feat-banner__cta">
        Utforska jordtyper
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- 6. PULL QUOTE -->
<section class="pull-quote reveal">
  <div class="pull-quote__num">20</div>
  <div class="pull-quote__body">
    <span class="pull-quote__mark">"</span>
    <p class="pull-quote__text">Rätt material gör skillnaden mellan en trädgård som kräver tid — och en som ger tid tillbaka.</p>
    <p class="pull-quote__author">— Alexander Jonsson, VD Villagrus</p>
  </div>
</section>

<!-- 7. OM VILLAGRUS -->
<section class="about-section">
  <div class="about-image reveal">
    <img src="<?php echo esc_url($images . '/bergskross.jpg'); ?>" alt="Om VillaGrus">
  </div>
  <div class="about-content">
    <div class="section-label reveal">Om VillaGrus</div>
    <h2 class="section-title reveal">Vi levererar<br><em>naturen</em><br>till dig.</h2>
    <p class="reveal reveal-delay-1">VillaGrus grundades med ett enkelt mål — att göra det enkelt och prisvärt att förskönna din trädgård med naturens egna material.</p>
    <p class="reveal reveal-delay-2">Med fri rådgivning och smidig hemleverans ser vi till att du alltid hittar rätt produkt för ditt projekt.</p>
    <div class="stats-grid">
      <div class="stat-item reveal"><div class="stat-num">500+</div><div class="stat-label">Produkter</div></div>
      <div class="stat-item reveal reveal-delay-1"><div class="stat-num">10+</div><div class="stat-label">Års erfarenhet</div></div>
      <div class="stat-item reveal reveal-delay-2"><div class="stat-num">4.9</div><div class="stat-label">Kundbetyg</div></div>
    </div>
  </div>
</section>

<!-- 8. EDITORIAL QUOTE -->
<section class="editorial-quote editorial-quote--dark">
  <h2 class="editorial-quote__heading reveal">Leverans som når hela vägen fram — oavsett hur din tomt ser ut.</h2>
  <div class="editorial-quote__right reveal reveal-delay-1">
    <p class="editorial-quote__body">Vi levererar med kranbil — liten eller stor beroende på vad din tomt kräver — och för dig som behöver större volymer kör vi även ut tipplass. Enkelt, smidigt och alltid på dina villkor.</p>
    <a href="<?php echo esc_url(home_url('/leveransinformation/')); ?>" class="editorial-quote__link">Läs mer om leverans</a>
  </div>
</section>

<!-- 9. KONTAKT -->
<section class="contact-section">
  <div class="contact-inner">
    <div class="contact-left reveal">
      <div class="section-label">Kontakt</div>
      <h2 class="section-title">Har du en fråga?<br><em>Vi hjälper dig.</em></h2>
      <p>Oavsett om det gäller produktval, leverans eller mängdberäkning — hör av dig så hjälper vi dig hitta rätt.</p>
      <ul class="contact-details">
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>info@villagrus.se</span>
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Mån–Fre 08:00–17:00</span>
        </li>
      </ul>
    </div>
    <form class="contact-form reveal reveal-delay-1" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <?php wp_nonce_field('villagrus_contact', 'contact_nonce'); ?>
      <input type="hidden" name="action" value="villagrus_contact">
      <div class="contact-form__group">
        <label for="contact_name">Namn</label>
        <input type="text" id="contact_name" name="contact_name" placeholder="Ditt namn" required>
      </div>
      <div class="contact-form__group">
        <label for="contact_email">E-post</label>
        <input type="email" id="contact_email" name="contact_email" placeholder="din@email.se" required>
      </div>
      <div class="contact-form__group">
        <label for="contact_message">Meddelande</label>
        <textarea id="contact_message" name="contact_message" rows="5" placeholder="Hur kan vi hjälpa dig?" required></textarea>
      </div>
      <button type="submit" class="contact-form__btn">
        Skicka meddelande
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
      </button>
    </form>
  </div>
</section>

<?php get_footer(); ?>
