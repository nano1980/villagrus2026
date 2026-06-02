<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- POSTNUMMER MODAL -->
<div class="zip-backdrop" id="zipBackdrop"></div>
<div class="zip-modal" id="zipModal" role="dialog" aria-modal="true" aria-label="Kontrollera leverans">
  <button class="zip-modal__close" id="zipClose" aria-label="Stäng">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
  </button>
  <div class="zip-modal__icon">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
  </div>
  <h2 class="zip-modal__title">Levererar vi till dig?</h2>
  <p class="zip-modal__sub">Ange ditt postnummer för att se om vi levererar till din adress.</p>
  <form class="zip-modal__form" id="zipForm" onsubmit="return false">
    <input class="zip-modal__input" id="zipInput" type="text" inputmode="numeric" maxlength="6" placeholder="123 45" autocomplete="postal-code">
    <button class="zip-modal__btn" type="submit" id="zipSubmit">
      Kontrollera
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
    </button>
  </form>
  <p class="zip-modal__note">Vi levererar till hela Sverige inom 48h.</p>
</div>

<!-- MOBILE NAV DRAWER -->
<div class="nav-backdrop" id="navBackdrop"></div>
<div class="nav-drawer" id="navDrawer" aria-label="Meny">
  <div class="nav-drawer__top">
    <span class="nav-drawer__heading">Navigering</span>
    <button class="nav-drawer__close" id="navClose" aria-label="Stäng meny">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <div class="nav-drawer__body">
    <nav class="nav-drawer__nav">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <span class="nav-drawer__icon"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M3 9l7-7 7 7v9a1 1 0 01-1 1H4a1 1 0 01-1-1V9z"/><path d="M7 19V12h6v7"/></svg></span>
        Hem
        <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
      </a>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">
        <span class="nav-drawer__icon"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="2" y="2" width="7" height="7" rx="1"/><rect x="11" y="2" width="7" height="7" rx="1"/><rect x="2" y="11" width="7" height="7" rx="1"/><rect x="11" y="11" width="7" height="7" rx="1"/></svg></span>
        Produkter
        <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
      </a>
      <a href="<?php echo esc_url(home_url('/inspiration/')); ?>">
        <span class="nav-drawer__icon"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="2" y="3" width="16" height="12" rx="1"/><path d="M6 9h8M6 12h5"/></svg></span>
        Inspiration
        <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
      </a>
      <a href="<?php echo esc_url(home_url('/om-oss/')); ?>">
        <span class="nav-drawer__icon"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="10" cy="7" r="3"/><path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6"/></svg></span>
        Om oss
        <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
      </a>
    </nav>
  </div>
  <div class="nav-drawer__bottom">
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="nav-drawer__link">
      <span class="nav-drawer__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg></span>
      Varukorg
      <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
    </a>
    <div class="nav-drawer__divider"></div>
    <?php if (is_user_logged_in()): ?>
    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="nav-drawer__link">
    <?php else: ?>
    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="nav-drawer__link">
    <?php endif; ?>
      <span class="nav-drawer__icon"><svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="10" cy="7" r="3"/><path d="M4 17c0-2.8 2.7-5 6-5s6 2.2 6 5"/></svg></span>
      <?php echo is_user_logged_in() ? 'Mitt konto' : 'Logga in'; ?>
      <span class="nav-drawer__arrow"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg></span>
    </a>
    <div class="nav-drawer__divider"></div>
    <a href="#" class="nav-drawer__cta contact-btn">
      Kontakta oss
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 8h10M9 4l4 4-4 4"/></svg>
    </a>
  </div>
</div>

<header id="header">
  <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="VillaGrus" class="logo-img" width="120" height="40">
  </a>
  <nav>
    <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo is_front_page() ? '<strong>Hem</strong>' : 'Hem'; ?></a>
    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Produkter</a>
    <a href="<?php echo esc_url(home_url('/inspiration/')); ?>">Inspiration</a>
    <a href="<?php echo esc_url(home_url('/om-oss/')); ?>">Om oss</a>
  </nav>
  <div class="header-right">
    <button class="nav-zip-btn" id="navZipBtn" aria-label="Ange leveranspostnummer">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="18" height="18">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
        <circle cx="12" cy="9" r="2.5"/>
      </svg>
      <span class="nav-badge" id="navZipBadge"></span>
    </button>
    <?php villagrus_cart_icon(); ?>
    <a href="#" class="contact-btn">Kontakta oss</a>
    <button class="hamburger" id="hamburger" aria-label="Öppna meny">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
