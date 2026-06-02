#!/bin/bash
# Sätter upp lokal WordPress-miljö med WooCommerce och VillaGrus-temat
# Kör: bash docker-setup.sh

set -e

echo "🚀 Startar Docker-containrar..."
docker compose up -d

echo "⏳ Väntar på att WordPress ska bli redo..."
until docker compose exec wordpress wp --allow-root core is-installed 2>/dev/null; do
  sleep 3
done

echo "📦 Installerar WordPress..."
docker compose exec wordpress wp --allow-root core install \
  --url="http://localhost:8080" \
  --title="VillaGrus (Lokal)" \
  --admin_user="admin" \
  --admin_password="admin123" \
  --admin_email="hernangilangove@gmail.com" \
  --skip-email

echo "🌐 Sätter språk till svenska..."
docker compose exec wordpress wp --allow-root language core install sv_SE
docker compose exec wordpress wp --allow-root site switch-language sv_SE

echo "🛒 Installerar WooCommerce..."
docker compose exec wordpress wp --allow-root plugin install woocommerce --activate

echo "🎨 Aktiverar VillaGrus-temat..."
docker compose exec wordpress wp --allow-root theme activate villagrus

echo "⚙️  Grundinställningar..."
docker compose exec wordpress wp --allow-root option update blogdescription ""
docker compose exec wordpress wp --allow-root option update timezone_string "Europe/Stockholm"
docker compose exec wordpress wp --allow-root option update woocommerce_default_country "SE"
docker compose exec wordpress wp --allow-root option update woocommerce_currency "SEK"
docker compose exec wordpress wp --allow-root option update woocommerce_currency_pos "right_space"
docker compose exec wordpress wp --allow-root option update woocommerce_price_decimal_sep ","
docker compose exec wordpress wp --allow-root option update woocommerce_price_thousand_sep " "
docker compose exec wordpress wp --allow-root option update woocommerce_price_num_decimals "0"
docker compose exec wordpress wp --allow-root option update woocommerce_calc_taxes "yes"

echo "📄 Sätter startsida..."
docker compose exec wordpress wp --allow-root post create \
  --post_type=page --post_title="Startsida" --post_status=publish --post_name="home"
HOME_ID=$(docker compose exec wordpress wp --allow-root post list --post_type=page --name=home --field=ID 2>/dev/null | tr -d '[:space:]')
docker compose exec wordpress wp --allow-root option update show_on_front "page"
docker compose exec wordpress wp --allow-root option update page_on_front "$HOME_ID"

echo "🔗 Permalänkar..."
docker compose exec wordpress wp --allow-root rewrite structure "/%postname%/" --hard

echo ""
echo "✅ Klart! Lokal miljö körs på:"
echo "   WordPress:   http://localhost:8080"
echo "   Admin:       http://localhost:8080/wp-admin  (admin / admin123)"
echo "   phpMyAdmin:  http://localhost:8081"
echo ""
echo "Tema är live-mountat — ändringar i wp-theme/villagrus/ syns direkt."
