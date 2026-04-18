// ─── HAMBURGER MENU ─────────────────────────────────────────────
(function() {
  const hamburger = document.getElementById('hamburger');
  const drawer    = document.getElementById('navDrawer');
  const backdrop  = document.getElementById('navBackdrop');
  const closeBtn  = document.getElementById('navClose');
  if (!hamburger) return;

  function openMenu() {
    drawer.classList.add('open');
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    drawer.classList.remove('open');
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger.addEventListener('click', openMenu);
  closeBtn.addEventListener('click', closeMenu);
  backdrop.addEventListener('click', closeMenu);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });
  drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
})();

// ─── ZIP MODAL ──────────────────────────────────────────────────
(function() {
  const backdrop = document.getElementById('zipBackdrop');
  const modal    = document.getElementById('zipModal');
  const closeBtn = document.getElementById('zipClose');
  const form     = document.getElementById('zipForm');
  const input    = document.getElementById('zipInput');
  if (!modal) return;

  function openModal() {
    backdrop.classList.add('visible');
    modal.classList.add('visible');
    document.body.style.overflow = 'hidden';
    setTimeout(() => input && input.focus(), 400);
  }
  function closeModal() {
    backdrop.classList.remove('visible');
    modal.classList.remove('visible');
    document.body.style.overflow = '';
  }

  // Show after short delay on page load
  setTimeout(openModal, 800);

  closeBtn && closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  form && form.addEventListener('submit', () => {
    const val = input.value.replace(/\s/g, '');
    if (val.length >= 5) closeModal();
  });

  // Format input as "123 45"
  input && input.addEventListener('input', () => {
    let v = input.value.replace(/\D/g, '').slice(0, 5);
    if (v.length > 3) v = v.slice(0,3) + ' ' + v.slice(3);
    input.value = v;
  });
})();

// ─── HEADER SCROLL ─────────────────────────────────────────────
const header = document.getElementById('header');
const hasHero = !!document.querySelector('.hero');
if (!hasHero && header) header.classList.add('scrolled');
window.addEventListener('scroll', () => {
  if (hasHero && header) header.classList.toggle('scrolled', window.scrollY > 80);
}, { passive: true });

// ─── SCROLL REVEAL ─────────────────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      revealObserver.unobserve(e.target);
    }
  });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// ─── COUNTDOWN ─────────────────────────────────────────────────
const endDate = new Date();
endDate.setDate(endDate.getDate() + 7);
endDate.setHours(endDate.getHours() + 14);
endDate.setMinutes(endDate.getMinutes() + 32);

function updateCountdown() {
  const el = document.getElementById('cd-days');
  if (!el) return;
  const now = new Date();
  const diff = endDate - now;
  if (diff <= 0) return;
  const days    = Math.floor(diff / 86400000);
  const hours   = Math.floor((diff % 86400000) / 3600000);
  const minutes = Math.floor((diff % 3600000) / 60000);
  const seconds = Math.floor((diff % 60000) / 1000);
  el.textContent = String(days).padStart(2,'0');
  document.getElementById('cd-hours').textContent = String(hours).padStart(2,'0');
  document.getElementById('cd-mins').textContent  = String(minutes).padStart(2,'0');
  document.getElementById('cd-secs').textContent  = String(seconds).padStart(2,'0');
}
updateCountdown();
setInterval(updateCountdown, 1000);

// ─── LIFT SHADOW — pixel-exact positioning ─────────────────────
function positionLiftShadow(card) {
  const img    = card.querySelector('.img-main');
  const shadow = card.querySelector('.cat-lift-shadow');
  if (!img || !shadow || !img.complete || !img.naturalWidth) return;

  try {
    // Rita bilden på canvas för att hitta lägsta icke-transparenta rad
    const cv = document.createElement('canvas');
    cv.width  = img.naturalWidth;
    cv.height = img.naturalHeight;
    const ctx = cv.getContext('2d');
    ctx.drawImage(img, 0, 0);
    const data = ctx.getImageData(0, 0, cv.width, cv.height).data;

    let lowestRow = 0;
    outer: for (let y = cv.height - 1; y >= 0; y--) {
      for (let x = 0; x < cv.width; x++) {
        if (data[(y * cv.width + x) * 4 + 3] > 20) {
          lowestRow = y;
          break outer;
        }
      }
    }
    const contentBottomRatio = lowestRow / cv.height; // 0–1 uppifrån

    // Räkna ut kortets rendered-image area
    const cardH  = card.offsetHeight;
    const cardW  = card.offsetWidth;
    const pt = 24, pb = 88, pl = 16, pr = 16;
    const availW = cardW - pl - pr;
    const availH = cardH - pt - pb;
    const ratio  = img.naturalWidth / img.naturalHeight;
    let rendH;
    if (availW / availH <= ratio) {
      rendH = availW / ratio;
    } else {
      rendH = availH;
    }
    const vertMargin = (availH - rendH) / 2;

    // Stenens underkant relativt kortets botten
    const contentBottomFromTop = pt + vertMargin + contentBottomRatio * rendH;
    const bottomFromCard = cardH - contentBottomFromTop;

    shadow.style.bottom = (bottomFromCard + 4) + 'px';
  } catch(e) {
    // CORS-fallback om canvas blockeras
    shadow.style.bottom = '220px';
  }
}

function initLiftShadows() {
  document.querySelectorAll('.cat-card--lift').forEach(card => {
    const img = card.querySelector('.img-main');
    if (img && img.complete) {
      positionLiftShadow(card);
    } else if (img) {
      img.addEventListener('load', () => positionLiftShadow(card));
    }
  });
}

// ─── SYNKA CTA-BARENS HÖJD MOT CAT-INFO ───────────────────────
function syncCtaBarHeight() {
  document.querySelectorAll('.cat-card--lift').forEach(card => {
    const info   = card.querySelector('.cat-info');
    const ctaBar = card.querySelector('.cat-cta-bar');
    if (!info || !ctaBar) return;
    const h = info.offsetHeight;
    ctaBar.style.height = h + 'px';
  });
}

window.addEventListener('load', () => { initLiftShadows(); syncCtaBarHeight(); });
window.addEventListener('resize', () => {
  document.querySelectorAll('.cat-card--lift').forEach(c => positionLiftShadow(c));
  syncCtaBarHeight();
});

// ─── ALT CATEGORY CAROUSEL 2 ───────────────────────────────────
const altGrid2 = document.getElementById('altCatGrid2');
const altPrev2 = document.getElementById('altPrev2');
const altNext2 = document.getElementById('altNext2');
if (altGrid2 && altPrev2 && altNext2) {
  const scrollByCard2 = () => {
    const card = altGrid2.querySelector('.alt-cat-card');
    return card ? card.offsetWidth + 12 : 280;
  };
  altNext2.addEventListener('click', () => altGrid2.scrollBy({ left: scrollByCard2(), behavior: 'smooth' }));
  altPrev2.addEventListener('click', () => altGrid2.scrollBy({ left: -scrollByCard2(), behavior: 'smooth' }));
}

// ─── ALT CATEGORY CAROUSEL ─────────────────────────────────────
const altGrid = document.getElementById('altCatGrid');
const altPrev = document.getElementById('altPrev');
const altNext = document.getElementById('altNext');
if (altGrid && altPrev && altNext) {
  const scrollByCard = () => {
    const card = altGrid.querySelector('.alt-cat-card');
    return card ? card.offsetWidth + 12 : 280;
  };
  altNext.addEventListener('click', () => {
    altGrid.scrollBy({ left: scrollByCard(), behavior: 'smooth' });
  });
  altPrev.addEventListener('click', () => {
    altGrid.scrollBy({ left: -scrollByCard(), behavior: 'smooth' });
  });
}

// ─── BLOG CAROUSEL ─────────────────────────────────────────────
(function() {
  const posts = [
    {
      category: 'Tips & råd',
      title: 'Rätt jord för\nvarje trädgård',
      intro: 'Från gräsmattejord till planteringsjord — vi hjälper dig välja rätt för ditt projekt och din mark.',
      img: 'jord.jpg'
    },
    {
      category: 'Inspiration',
      title: 'Uppfart i makadam —\nhållbart & snyggt',
      intro: 'Makadam är ett av de mest populära valen för uppfarter. Här är allt du behöver veta för ett lyckat resultat.',
      img: 'makadam.jpg'
    },
    {
      category: 'Guide',
      title: 'Natursingel i\nmodern trädgård',
      intro: 'Natursingel passar i allt från minimalistiska till naturlika trädgårdar och kräver minimal skötsel.',
      img: 'natursingel.jpg'
    },
    {
      category: 'Inspiration',
      title: 'Dekorsten —\nskönhet utan skötsel',
      intro: 'Vit marmor, färgad singel och natursten ger trädgården ett tidlöst och elegant uttryck.',
      img: 'dekorsten.jpg'
    },
    {
      category: 'Guide',
      title: 'Stenmjöl som\nmarkbeläggning',
      intro: 'Kostnadseffektivt, naturligt och enkelt att lägga — perfekt för stigar, terrasser och parkeringsytor.',
      img: 'bergskross.jpg'
    }
  ];

  const N        = posts.length;
  const VISIBLE  = 4;   // cards shown = upcoming posts only (cur not included)
  const AUTO_MS  = 3500;
  const EASE     = 'cubic-bezier(0.16,1,0.3,1)';
  const ANIM_MS  = 950;
  const CLIP_MS  = 1050;

  let cur   = 0;
  let busy  = false;
  let timer = null;

  const section = document.getElementById('blog');
  if (!section) return;
  const track   = document.getElementById('bTrack');
  const bgs     = document.querySelectorAll('.blog-bg');
  const hero    = document.getElementById('bHero');
  const labelEl = document.getElementById('bLabel');
  const titleEl = document.getElementById('bTitle');
  const introEl = document.getElementById('bIntro');
  const ctrEl   = document.getElementById('bCounter');
  const bar     = document.getElementById('bBar');

  // Cards in track represent posts AFTER cur: cur+1, cur+2, ...
  function cardIdxForSlot(slot) { return (cur + 1 + slot) % N; }

  function cardStep() {
    const card = track.querySelector('.blog-card');
    return card ? card.offsetWidth + 14 : 186;
  }

  function makeCard(postIdx) {
    const p  = posts[postIdx % N];
    const el = document.createElement('div');
    el.className   = 'blog-card';
    el.dataset.idx = postIdx % N;
    el.innerHTML = `
      <div class="blog-card__img"><img src="${p.img}" alt="${p.category}"></div>
      <div class="blog-card__overlay"></div>
      <div class="blog-card__info">
        <span class="blog-card__cat">${p.category}</span>
        <h3 class="blog-card__name">${p.title.replace('\n','<br>')}</h3>
      </div>`;
    el.addEventListener('click', () => {
      // Find slot position (0 = first card = cur+1)
      const cards = Array.from(track.querySelectorAll('.blog-card'));
      const slot  = cards.indexOf(el);
      if (slot >= 0) goTo(slot + 1); // advance (slot+1) steps
    });
    return el;
  }

  function updateHero(idx, animate) {
    const p = posts[idx % N];
    if (!animate) {
      labelEl.textContent = p.category;
      titleEl.innerHTML   = p.title.replace('\n','<br>');
      introEl.textContent = p.intro;
      return;
    }

    // On mobile the hero is position:relative with transform:none
    // On desktop it's position:absolute centered with translateY(-50%)
    const isMobile = window.matchMedia('(max-width: 640px)').matches;
    const base     = isMobile ? '' : 'translateY(-50%)';
    const exitY    = isMobile ? 'translateY(14px)'              : 'translateY(calc(-50% + 14px))';
    const enterY   = isMobile ? 'translateY(-18px)'             : 'translateY(calc(-50% - 18px))';

    // Fade out + slide down slightly
    hero.style.transition = 'opacity 0.08s var(--ease), transform 0.08s var(--ease)';
    hero.style.opacity    = '0';
    hero.style.transform  = exitY;

    setTimeout(() => {
      // Swap content while invisible, position above for entrance
      labelEl.textContent = p.category;
      titleEl.innerHTML   = p.title.replace('\n','<br>');
      introEl.textContent = p.intro;
      // Instant jump to above position, then transition in
      hero.style.transition = 'none';
      hero.style.transform  = enterY;
      void hero.offsetHeight; // force reflow
      setTimeout(() => {
        hero.style.transition = 'opacity 0.65s var(--ease), transform 0.65s var(--ease)';
        hero.style.opacity    = '1';
        hero.style.transform  = base;
      }, 400);
    }, 100);
  }

  function setBg(idx) {
    bgs.forEach((bg, i) => {
      const on = i === idx;
      bg.classList.toggle('active', on);
      bg.style.opacity  = on ? '1' : '0';
      bg.style.clipPath = '';
      if (on) {
        const img = bg.querySelector('img');
        img.style.transition = '';
        img.style.transform  = '';
      }
    });
  }

  function updateCounter() {
    ctrEl.innerHTML = `<strong>${String(cur + 1).padStart(2,'0')}</strong> / ${String(N).padStart(2,'0')}`;
  }

  function startProgress() {
    bar.style.transition = 'none';
    bar.style.width      = '0';
    bar.offsetWidth;
    bar.style.transition = `width ${AUTO_MS}ms linear`;
    bar.style.width      = '100%';
  }

  function kick() { startProgress(); timer = setTimeout(() => go(1), AUTO_MS); }

  function go(dir) {
    if (busy) return;
    busy = true;
    clearTimeout(timer);
    bar.style.transition = 'none';
    bar.style.width = '0';

    const next   = (cur + dir + N) % N;
    const nextBg = bgs[next];
    const curBg  = bgs[cur];

    if (dir === 1) {
      // ── FLIP: first card in track expands to become new background ──
      const expandCard = track.firstElementChild;
      const cRect      = expandCard.getBoundingClientRect();
      const sRect      = section.getBoundingClientRect();

      const t = cRect.top    - sRect.top;
      const r = sRect.right  - cRect.right;
      const b = sRect.bottom - cRect.bottom;
      const l = cRect.left   - sRect.left;

      // Prepare new bg clipped to card position
      const nextImg = nextBg.querySelector('img');
      nextBg.style.transition = 'none';
      nextBg.style.opacity    = '1';
      nextBg.style.clipPath   = `inset(${t}px ${r}px ${b}px ${l}px round var(--radius))`;
      nextImg.style.transition = 'none';
      nextImg.style.transform  = 'scale(1)';
      nextBg.classList.add('active');

      // Fade out current bg
      curBg.style.transition = 'opacity 0.5s ease';
      curBg.classList.remove('active');
      curBg.style.opacity = '0';

      // Fade out the expanding card (it's becoming the bg)
      expandCard.style.transition = `opacity ${CLIP_MS * 0.5}ms ease`;
      expandCard.style.opacity    = '0';

      // Expand clip-path to fullscreen
      nextBg.offsetWidth;
      nextBg.style.transition = `clip-path ${CLIP_MS}ms cubic-bezier(0.16,1,0.3,1)`;
      nextBg.style.clipPath   = 'inset(0px 0px 0px 0px round 0px)';

      // Slide remaining cards left (without the first — it's fading)
      const step = cardStep();
      track.style.transition = `transform ${ANIM_MS}ms ${EASE}`;
      track.style.transform  = `translateX(-${step}px)`;

      // Update hero text mid-animation
      setTimeout(() => updateHero(next, true), 340);

      setTimeout(() => {
        // Restore next bg
        nextBg.style.transition = 'none';
        nextBg.style.clipPath   = '';
        nextImg.style.transition = '';
        nextImg.style.transform  = '';

        // Remove the consumed card, append new upcoming card at end
        // New list: cur+2, cur+3, ..., cur+VISIBLE+1
        track.firstElementChild.remove();
        const newCard = makeCard((next + VISIBLE) % N);
        newCard.style.opacity = '0';
        track.appendChild(newCard);
        track.style.transition = 'none';
        track.style.transform  = 'translateX(0)';
        // Fade in new card softly
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            newCard.style.transition = 'opacity 1.1s cubic-bezier(0.25,0.46,0.45,0.94)';
            newCard.style.opacity = '1';
          });
        });

        cur = next;
        updateCounter();
        setTimeout(() => { busy = false; kick(); }, 50);
      }, Math.max(ANIM_MS, CLIP_MS));

    } else {
      // ── PREV: crossfade bg + slide cards right ──────────────────
      curBg.style.transition  = 'opacity 0.7s ease';
      curBg.classList.remove('active');
      curBg.style.opacity     = '0';
      nextBg.style.transition = 'none';
      nextBg.style.opacity    = '1';
      nextBg.style.clipPath   = '';
      nextBg.classList.add('active');

      updateHero(next, true);

      // New list: next+1, next+2, ..., next+VISIBLE
      // = remove last card, insert at beginning: makeCard(next+1)
      track.lastElementChild.remove();
      track.insertBefore(makeCard((next + 1) % N), track.firstElementChild);

      const step = cardStep();
      track.style.transition = 'none';
      track.style.transform  = `translateX(-${step}px)`;
      track.offsetWidth;
      track.style.transition = `transform ${ANIM_MS}ms ${EASE}`;
      track.style.transform  = 'translateX(0)';

      setTimeout(() => {
        cur = next;
        updateCounter();
        setTimeout(() => { busy = false; kick(); }, 50);
      }, ANIM_MS);
    }
  }

  // ── Jump N steps forward quickly ────────────────────────────
  function goTo(steps) {
    if (steps <= 0) return;
    go(1);
    if (steps > 1) {
      // Chain remaining steps after current animation finishes
      const wait = Math.max(ANIM_MS, CLIP_MS) + 80;
      setTimeout(() => goTo(steps - 1), wait);
    }
  }

  // ── Init: background = posts[0], cards = posts[1..VISIBLE] ──
  setBg(0);
  for (let i = 0; i < VISIBLE; i++) track.appendChild(makeCard((i + 1) % N));
  updateHero(0, false);
  updateCounter();
  kick();

  document.getElementById('bPrev').addEventListener('click', () => go(-1));
  document.getElementById('bNext').addEventListener('click', () => go(1));

  // Pause on hover
  section.addEventListener('mouseenter', () => {
    clearTimeout(timer);
    bar.style.transition = 'none';
  });
  section.addEventListener('mouseleave', () => {
    bar.style.width = '0';
    bar.offsetWidth;
    kick();
  });
})();

// ─── HERO IMAGE PARALLAX (subtle) ──────────────────────────────
const heroBg = document.querySelector('.hero-bg img');
window.addEventListener('scroll', () => {
  const y = window.scrollY;
  if (y < window.innerHeight && heroBg) {
    heroBg.style.transform = `scale(1) translateY(${y * 0.3}px)`;
  }
}, { passive: true });

// ─── OFFER COUNTDOWN ───────────────────────────────────────────
(function() {
  // Target: 7 days from first page load (stored in sessionStorage)
  const KEY = 'vg_offer_end';
  let end = parseInt(sessionStorage.getItem(KEY));
  if (!end || end < Date.now()) {
    end = Date.now() + 7 * 24 * 60 * 60 * 1000;
    sessionStorage.setItem(KEY, end);
  }
  const pad = n => String(Math.max(0, n)).padStart(2, '0');
  function tick() {
    const diff = Math.max(0, end - Date.now());
    const days  = Math.floor(diff / 86400000);
    const hours = Math.floor((diff % 86400000) / 3600000);
    const mins  = Math.floor((diff % 3600000)  / 60000);
    const secs  = Math.floor((diff % 60000)    / 1000);
    ['cdDays','cdDays2'].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = pad(days); });
    ['cdHours','cdHours2'].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = pad(hours); });
    ['cdMins','cdMins2'].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = pad(mins); });
    ['cdSecs','cdSecs2'].forEach(id => { const el = document.getElementById(id); if(el) el.textContent = pad(secs); });
  }
  tick();
  setInterval(tick, 1000);
})();

/* ═══════════════════════════════════════════════════════════════
   PDP INFO POPOVERS
   ═══════════════════════════════════════════════════════════════ */
(function () {
  const configurator = document.getElementById('pdp-configurator');
  if (!configurator) return;

  const INFO = {
    leveranssatt: 'Välj hur du vill ta emot materialet. Tipplass passar bäst för stora ytor och volymer — lastbilen tippar direkt på plats. Storsäck är smidigare om du har begränsat utrymme eller vill hantera materialet i etapper.',
    sackstorlek: '500 kg passar mindre projekt som rabatter och gångar. 1 000 kg är ekonomiskt för större ytor. Varuprov är gratis och skickas med vid köp av annan produkt — perfekt för att testa färg och storlek.',
    kran: 'Standard kranbil når 3–5 m från närmaste farbar väg — räcker för de flesta villatomter. Välj Stor kranbil om du behöver placera säcken längre in på tomten, upp till 14 m (+150 kr/säck).',
    leveranstid: 'Standard leverans sker inom 3–5 arbetsdagar. Express levereras nästa vardag om du beställer före kl 12 (+495 kr). Leveransdag bekräftas via SMS.',
    mangd: 'Välj ton om du vet exakt hur mycket du behöver. Välj lass om du beräknar utifrån lastbilskapacitet — ett lass motsvarar 13 ton. Vid tvivel, välj hellre lite mer än lite för lite.',
  };

  let activeBtn = null;
  let activePopover = null;

  function allSteps() {
    return Array.from(configurator.querySelectorAll('.pdp-step'));
  }

  function closePopover() {
    if (activeBtn) activeBtn.classList.remove('is-open');
    if (activePopover) activePopover.classList.remove('is-visible');
    allSteps().forEach(s => s.classList.remove('is-dimmed'));
    activeBtn = null;
    activePopover = null;
  }

  configurator.addEventListener('click', function (e) {
    const btn = e.target.closest('.pdp-info-btn');
    if (!btn) { closePopover(); return; }
    e.stopPropagation();

    if (btn === activeBtn) { closePopover(); return; }

    closePopover();

    const key = btn.dataset.info;
    const text = INFO[key];
    if (!text) return;

    const step = btn.closest('.pdp-step');
    let popover = step.querySelector('.pdp-info-popover');
    if (!popover) {
      popover = document.createElement('div');
      popover.className = 'pdp-info-popover';
      step.appendChild(popover);
    }
    popover.textContent = text;

    // Dimma alla steg utom det aktiva
    allSteps().forEach(s => {
      if (s !== step) s.classList.add('is-dimmed');
    });

    btn.classList.add('is-open');
    activeBtn = btn;
    activePopover = popover;
    requestAnimationFrame(() => popover.classList.add('is-visible'));
  });

  document.addEventListener('click', function (e) {
    if (activePopover && !e.target.closest('.pdp-step')) closePopover();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closePopover();
  });
})();

/* ═══════════════════════════════════════════════════════════════
   PDP KONFIGURATOR
   ═══════════════════════════════════════════════════════════════ */
(function () {
  const pdp = document.getElementById('pdp-configurator');
  if (!pdp) return;

  /* ── Priser ─────────────────────────────────────────────────── */
  const PRICES = {
    bag: { 500: 2195, 1000: 2495, 0: 0 },
    craneSurcharge: 150,   // per säck
    expressSurcharge: 495
  };

  // Tipplass: nivåbaserat — 0-3 ton: 1800 grundpris + 280/ton, 4+ ton: 2100 grundpris + 280/ton
  function calcTippPrice(tons) {
    const base = tons <= 3 ? 1800 : 2100;
    return base + 280 * tons;
  }

  /* ── State ──────────────────────────────────────────────────── */
  let state = {
    delivery: null,     // 'storsack' | 'tipplass'
    // storsack
    bagSize: 500,
    bagCount: 1,
    varuprov: false,
    crane: 'standard',
    bagTime: 'standard',
    // tipplass
    tippType: 'ton',
    tippAmount: 1,
    tippTime: 'standard'
  };

  /* ── DOM refs ───────────────────────────────────────────────── */
  const el = {
    storsack:     document.getElementById('pdpStorsack'),
    tipplass:     document.getElementById('pdpTipplass'),
    countStep:    document.getElementById('pdpCountStep'),
    countLabel:   document.getElementById('pdpCountLabel'),
    priceNote:    document.getElementById('pdpPriceNote'),
    bagCountEl:   document.getElementById('bagCount'),
    tippCountEl:  document.getElementById('tippCount'),
    tippLabel:    document.getElementById('tippCountLabel'),
    tippPriceNote:document.getElementById('tippPriceNote'),
    total:        document.getElementById('pdpTotal'),
    addBtn:       document.getElementById('pdpAddBtn'),
    addBtnText:   document.getElementById('pdpAddBtnText')
  };

  /* ── Custom dropdown builder ─────────────────────────────────── */
  function buildDropdown(container, items, currentValue, onSelect) {
    if (!container) return;
    const trigger    = container.querySelector('.pdp-dropdown__trigger');
    const panel      = container.querySelector('.pdp-dropdown__panel');
    const panelInner = container.querySelector('.pdp-dropdown__panel-inner');
    const qtySpan    = trigger.querySelector('.pdp-dropdown__qty');
    const totSpan    = trigger.querySelector('.pdp-dropdown__total');

    function setSelected(val) {
      const item = items.find(i => i.value === val) || items[0];
      qtySpan.textContent = item.label;
      totSpan.textContent = item.price;
      panelInner.querySelectorAll('.pdp-dropdown__option').forEach(o => {
        o.classList.toggle('is-selected', parseInt(o.dataset.value) === item.value);
      });
    }

    function open() {
      // Avgör om panelen ryms nedåt, annars öppna uppåt
      const rect = container.getBoundingClientRect();
      const spaceBelow = window.innerHeight - rect.bottom;
      container.classList.toggle('is-above', spaceBelow < 320 && rect.top > spaceBelow);

      container.classList.add('is-open');
      trigger.setAttribute('aria-expanded', 'true');
      panel.removeAttribute('hidden');
      const sel = panelInner.querySelector('.is-selected');
      if (sel) sel.scrollIntoView({ block: 'nearest' });
    }

    function close() {
      container.classList.remove('is-open');
      trigger.setAttribute('aria-expanded', 'false');
      panel.setAttribute('hidden', '');
    }

    panelInner.innerHTML = '';
    items.forEach(item => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'pdp-dropdown__option' + (item.value === currentValue ? ' is-selected' : '');
      btn.dataset.value = item.value;
      btn.setAttribute('role', 'option');
      btn.innerHTML = `<span class="pdp-dropdown__opt-qty">${item.label}</span><span class="pdp-dropdown__opt-price">${item.price}</span>`;
      btn.addEventListener('click', e => {
        e.stopPropagation();
        setSelected(item.value);
        close();
        onSelect(item.value);
      });
      panelInner.appendChild(btn);
    });

    setSelected(currentValue);

    trigger.onclick = e => {
      e.stopPropagation();
      container.classList.contains('is-open') ? close() : open();
    };
  }

  // Stäng alla dropdowns vid klick utanför
  document.addEventListener('click', () => {
    document.querySelectorAll('.pdp-dropdown.is-open').forEach(d => {
      d.classList.remove('is-open');
      d.querySelector('.pdp-dropdown__trigger')?.setAttribute('aria-expanded', 'false');
      d.querySelector('.pdp-dropdown__panel')?.setAttribute('hidden', '');
    });
  });

  /* ── Populera dropdowns ─────────────────────────────────────── */
  function populateBagCount() {
    const pricePerBag = PRICES.bag[state.bagSize] + (state.crane === 'big' ? PRICES.craneSurcharge : 0);
    const sizeStr = state.bagSize >= 1000 ? '1 000' : String(state.bagSize);

    // Uppdatera label och prisnot direkt — oberoende av state.delivery
    el.countLabel.textContent = 'Antal säckar ' + sizeStr + ' kg';
    el.priceNote.textContent  = fmt(pricePerBag) + ' per ' + sizeStr + ' kg-säck inkl leverans';

    const items = [];
    for (let i = 1; i <= 20; i++) {
      items.push({
        value: i,
        label: i + (i === 1 ? ' säck' : ' säckar'),
        price: fmt(pricePerBag * i)
      });
    }
    buildDropdown(el.bagCountEl, items, state.bagCount, v => {
      state.bagCount = v;
      render();
    });
  }

  function populateTippCount() {
    const max = state.tippType === 'ton' ? 13 : 5;
    const items = [];
    for (let i = 1; i <= max; i++) {
      const price = state.tippType === 'ton'
        ? calcTippPrice(i)
        : calcTippPrice(13) * i; // 1 lass = 13 ton, skalat
      items.push({
        value: i,
        label: state.tippType === 'ton' ? i + ' ton' : i + ' lass',
        price: fmt(price)
      });
    }
    const clampedVal = Math.min(state.tippAmount, max);
    state.tippAmount = clampedVal;
    buildDropdown(el.tippCountEl, items, clampedVal, v => {
      state.tippAmount = v;
      render();
    });
  }

  /* ── Beräkna totalpris ──────────────────────────────────────── */
  function calcTotal() {
    if (!state.delivery) return null;
    if (state.delivery === 'storsack') {
      const base = PRICES.bag[state.bagSize] * state.bagCount;
      const crane = state.crane === 'big' ? PRICES.craneSurcharge * state.bagCount : 0;
      const express = state.bagTime === 'express' ? PRICES.expressSurcharge : 0;
      return base + crane + express;
    }
    if (state.delivery === 'tipplass') {
      const base = state.tippType === 'ton'
        ? calcTippPrice(state.tippAmount)
        : calcTippPrice(13) * state.tippAmount;
      const express = state.tippTime === 'express' ? PRICES.expressSurcharge : 0;
      return base + express;
    }
    return null;
  }

  /* ── Formatera pris ─────────────────────────────────────────── */
  function fmt(n) {
    return n.toLocaleString('sv-SE') + ' kr';
  }

  /* ── Render ─────────────────────────────────────────────────── */
  function render() {
    // Visa/dölj sektioner
    el.storsack.hidden = state.delivery !== 'storsack';
    el.tipplass.hidden  = state.delivery !== 'tipplass';

    if (state.delivery === 'storsack') {
      el.countStep.hidden = false;
      el.countLabel.textContent = 'Antal säckar ' + (state.bagSize >= 1000 ? '1 000' : state.bagSize) + ' kg';
      const pricePerBag = PRICES.bag[state.bagSize] + (state.crane === 'big' ? PRICES.craneSurcharge : 0);
      const sizeLabel = state.bagSize >= 1000 ? '1 000 kg' : '500 kg';
      el.priceNote.textContent = fmt(pricePerBag) + ' per ' + sizeLabel + '-säck inkl leverans';
    }

    if (state.delivery === 'tipplass') {
      el.tippLabel.textContent = state.tippType === 'ton' ? 'Antal ton' : 'Antal lass (13 ton/st)';
      el.tippPriceNote.innerHTML = state.tippType === 'ton'
        ? '1–3 ton: 1 800 kr grundpris + 280 kr/ton<br>4–13 ton: 2 100 kr grundpris + 280 kr/ton'
        : 'Pris per lass (13 ton) inkl leverans';
    }

    // Totalpris
    const total = calcTotal();
    if (total === null) {
      el.total.textContent = '— kr';
      el.addBtn.disabled = true;
      el.addBtnText.textContent = 'Välj leveranssätt';
    } else {
      el.total.textContent = fmt(total);
      el.addBtn.disabled = false;
      el.addBtnText.textContent = 'Lägg i varukorg';
    }
  }

  /* ── Toggle-grupp helper ─────────────────────────────────────── */
  function bindToggleGroup(groupId, onChange) {
    const group = document.getElementById(groupId);
    if (!group) return;
    group.querySelectorAll('.pdp-toggle-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        group.querySelectorAll('.pdp-toggle-btn').forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');
        onChange(btn.dataset);
      });
    });
  }

  /* ── Delivery card helper ────────────────────────────────────── */
  function bindDeliveryCards(selector, onChange) {
    document.querySelectorAll(selector).forEach(card => {
      card.addEventListener('click', () => {
        document.querySelectorAll(selector).forEach(c => c.classList.remove('is-active'));
        card.classList.add('is-active');
        onChange(card.dataset);
      });
    });
  }

  /* ── Event listeners ─────────────────────────────────────────── */

  // Leveranssätt
  bindDeliveryCards('[data-delivery]', ({ delivery }) => {
    state.delivery = delivery;
    render();
  });

  // Säckstorlek
  bindToggleGroup('bagSizeGroup', ({ size }) => {
    state.bagSize = parseInt(size);
    populateBagCount();
    render();
  });


  // Kran — rebuild dropdown så priser per säck uppdateras
  bindDeliveryCards('[data-crane]', ({ crane }) => {
    state.crane = crane;
    populateBagCount();
    render();
  });

  // Leveranstid storsäck
  bindToggleGroup('bagTimeGroup', ({ time }) => {
    state.bagTime = time;
    render();
  });

  // Tipplass-typ
  bindToggleGroup('tippTypeGroup', ({ tipp }) => {
    state.tippType = tipp;
    state.tippAmount = 1;
    populateTippCount();
    render();
  });

  // Leveranstid tipplass
  bindToggleGroup('tippTimeGroup', ({ time }) => {
    state.tippTime = time;
    render();
  });

  // Galleri thumbnails
  const mainImg = document.getElementById('pdpMainImg');
  if (mainImg) {
    document.querySelectorAll('.pdp-thumb').forEach(thumb => {
      thumb.addEventListener('click', () => {
        document.querySelectorAll('.pdp-thumb').forEach(t => t.classList.remove('is-active'));
        thumb.classList.add('is-active');
        mainImg.src = thumb.dataset.src;
      });
    });
  }

  // Tabs
  document.querySelectorAll('.pdp-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.pdp-tab').forEach(t => t.classList.remove('is-active'));
      document.querySelectorAll('.pdp-tab-content').forEach(c => c.classList.remove('is-active'));
      tab.classList.add('is-active');
      const target = document.getElementById('pdpTab' + tab.dataset.tab.charAt(0).toUpperCase() + tab.dataset.tab.slice(1));
      if (target) target.classList.add('is-active');
    });
  });

  // Accordion
  document.querySelectorAll('.pdp-accordion-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.pdp-accordion-item');
      const isOpen = item.classList.contains('is-open');
      item.classList.toggle('is-open', !isOpen);
      btn.setAttribute('aria-expanded', !isOpen);
    });
  });

  // Init
  populateBagCount();
  populateTippCount();
  render();
})();
