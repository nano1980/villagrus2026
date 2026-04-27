# Midjourney-promptar — Grusad uppfart guide

## Referensstil — natursingel.jpg

Alla uppfartsbilder ska följa exakt denna visuella stil:

- **Kameravinkel:** extrem lågvinkel, kameran nästan i marknivå (ground-level)
- **Skärpedjup:** maximalt grunt — stenarna i förgrunden skarpa, allt annat mjukt bokeh
- **Ljus:** kylig, mulen nordisk dagsljus — inga varma soltoner, ingen golden hour
- **Bakgrund:** suddig, hus/häck/trädgård i mjuk bokeh, neutral grå-grön palett
- **Komposition:** stenar fyller undre 2/3, horisonten hög i bilden
- **Känsla:** editorial, lugnt, skandinavisk premiumkänsla — "så här ser det ut"

Tips: Lägg till `--sref [URL till natursingel.jpg]` om du hostar bilden, för att låsa stilen exakt. Använd `--seed XXXX` konsekvent för att hålla ljussättning och komposition enhetlig.

---

## Uppfartsbilder — inspirationsbilder till kalkylator-sektioner (16:9)

Syfte: inspirera kunden att föreställa sig resultatet på sin egen uppfart — leder till kalkylator-CTA.

### Gårdssingel 8–18 mm
```
ground-level perspective camera almost touching surface, extreme close-up of fine light grey and cream rounded river gravel 8-18mm driveway, smooth small pebbles filling entire lower frame in sharp focus, shallow depth of field, soft bokeh background with blurred white Scandinavian house and trimmed green hedge, overcast nordic daylight, cool grey tones, no direct sunlight, photorealistic editorial photography, cinematic --ar 16:9 --style raw --v 6.1
```

### Natursingel 16–32 mm
```
ground-level perspective camera almost touching surface, extreme close-up of mixed dark grey and light stone natural gravel 16-32mm driveway, medium rounded river stones with occasional white pebbles sharp in foreground, entire lower frame filled with stones, extremely shallow depth of field, blurred modern Scandinavian house and low green shrub in bokeh background, overcast cool nordic daylight, muted grey palette, photorealistic editorial photography, cinematic --ar 16:9 --style raw --v 6.1
```

### Natursingel 25–45 mm
```
ground-level perspective camera nearly touching surface, extreme close-up of chunky natural grey-brown gravel 25-45mm driveway, bold rounded stones sharp in foreground filling entire lower frame, angular and semi-rounded mixed pieces, extremely shallow depth of field, blurred Scandinavian villa and mature low hedge in soft bokeh background, overcast diffused nordic daylight, cool earthy grey tones, photorealistic editorial photography, cinematic --ar 16:9 --style raw --v 6.1
```

### Dekorsten 32–64 mm
```
ground-level perspective camera at surface level, extreme close-up of large dark anthracite angular crushed stone 32-64mm driveway, dramatic bold stone texture razor sharp in foreground filling lower two thirds of frame, occasional lighter stone among dark grey pieces, extremely shallow depth of field, blurred modern dark-toned Scandinavian house and ornamental garden in bokeh background, overcast cool daylight, moody dark grey palette, photorealistic editorial photography, cinematic --ar 16:9 --style raw --v 6.1
```

---

## Produkttumnaglar (isolerade, mörk bakgrund, 1:1)

Används i kalkylatorns Steg 3-kort (`.slitlager-card__img`).

### Gårdssingel 8–18 mm
```
small pile of fine rounded river gravel 8-18mm, light grey and cream tones, smooth pebbles, product photography, isolated on transparent background, soft studio lighting, top-down 30 degree angle, sharp macro detail, PNG format --ar 1:1 --style raw --v 6.1
```

### Natursingel 16–32 mm
```
small heap of natural mixed gravel 16-32mm, dark grey and occasional white stones, angular and semi-rounded pieces, product photography, isolated on transparent background, soft diffused studio light, slight overhead angle, ultra sharp macro detail, PNG format --ar 1:1 --style raw --v 6.1
```

### Natursingel 25–45 mm
```
pile of chunky natural grey-brown gravel stones 25-45mm, bold rounded river stones, heavier and more robust appearance, product photography, isolated on transparent background, clean studio lighting, 30 degree overhead angle, photorealistic, PNG format --ar 1:1 --style raw --v 6.1
```

### Dekorsten 32–64 mm
```
dramatic pile of large dark anthracite angular crushed stone 32-64mm, bold sharp-edged pieces, dark grey with subtle mineral surface, statement look, product photography, isolated on transparent background, moody directional studio lighting, slight overhead angle, ultra sharp, PNG format --ar 1:1 --style raw --v 6.1
```

---

## Bildmappning i koden

Uppfartsbilderna används som inspiration-hero och i kalkylatorflödet:

| Variabel i JS | Ersätt med MJ-bild |
|---|---|
| `gardsingel-818` | `gardsingel-818.jpg` |
| `natursingel-1632` | `natursingel-1632.jpg` |
| `natursingel-2545` | `natursingel-2545.jpg` |
| `dekorsten-3264` | `dekorsten-3264.jpg` |

Produkttumnaglarna används i `.slitlager-card__img`:
```html
<img class="slitlager-card__img" src="../../[filnamn].png" alt="...">
```
