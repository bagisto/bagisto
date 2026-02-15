#!/usr/bin/env bash

# =====================================================================
# Bagisto Local Auto-Setup Script for Ubuntu (dev only) + Nginx
# Requirements: fresh Ubuntu 22.04/24.04, sudo access, git-cloned Bagisto repo
#
# Usage:
#   chmod +x setup-ubuntu.sh
#   ./setup-ubuntu.sh
#
# To customise, edit the CONFIGURATION block below before running.
# =====================================================================

set -euo pipefail

# ────────────────────────────────────────────────
#  CONFIGURATION — change these before running
# ────────────────────────────────────────────────
PROJECT_PATH="${PROJECT_PATH:-$HOME/projects/bagisto}"   # cloned repo path
SITE_DOMAIN="${SITE_DOMAIN:-localhost}"                   # or bagisto.local
DB_NAME="${DB_NAME:-bagisto_db}"
DB_USER="${DB_USER:-bagisto_user}"
DB_PASS="${DB_PASS:-bagisto_strong_password_2026}"        # change!
MYSQL_ROOT_PASS="${MYSQL_ROOT_PASS:-rootDev2026}"         # change!
PHP_VERSION="${PHP_VERSION:-8.3}"                         # Bagisto 2.x needs >=8.2

# ────────────────────────────────────────────────
#  Helpers
# ────────────────────────────────────────────────
info()  { echo -e "\n\033[1;34m[INFO]\033[0m  $*"; }
ok()    { echo -e "\033[1;32m[OK]\033[0m    $*"; }
warn()  { echo -e "\033[1;33m[WARN]\033[0m  $*"; }
fail()  { echo -e "\033[1;31m[FAIL]\033[0m  $*" >&2; exit 1; }

# ────────────────────────────────────────────────
#  Pre-flight checks
# ────────────────────────────────────────────────
[[ $EUID -eq 0 ]] && fail "Do not run this script as root. Use a normal user with sudo."

info "Starting Bagisto + Nginx setup on Ubuntu"
echo "  Project path : $PROJECT_PATH"
echo "  Nginx site   : http://$SITE_DOMAIN"
echo "  PHP version  : $PHP_VERSION"
echo "  !! Dev machine only — change all passwords before production !!"

# ────────────────────────────────────────────────
# 1. Update system + basics
# ────────────────────────────────────────────────
info "Updating system packages"
sudo apt-get update -y && sudo apt-get upgrade -y
sudo apt-get install -y software-properties-common curl git unzip zip
ok "System packages updated"

# ────────────────────────────────────────────────
# 2. Install PHP + required extensions + FPM
# ────────────────────────────────────────────────
info "Installing PHP ${PHP_VERSION} and extensions"
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update -y

# Extensions aligned with Bagisto composer.json requirements:
#   ext-calendar, ext-curl, ext-intl, ext-mbstring, ext-openssl (built-in),
#   ext-pdo, ext-pdo_mysql, ext-tokenizer (built-in on most builds)
# Plus extras needed at runtime: gd, zip, xml, bcmath, soap, imagick
sudo apt-get install -y \
    "php${PHP_VERSION}" \
    "php${PHP_VERSION}-fpm" \
    "php${PHP_VERSION}-cli" \
    "php${PHP_VERSION}-curl" \
    "php${PHP_VERSION}-mbstring" \
    "php${PHP_VERSION}-xml" \
    "php${PHP_VERSION}-zip" \
    "php${PHP_VERSION}-gd" \
    "php${PHP_VERSION}-mysql" \
    "php${PHP_VERSION}-intl" \
    "php${PHP_VERSION}-bcmath" \
    "php${PHP_VERSION}-soap" \
    "php${PHP_VERSION}-imagick" \
    "php${PHP_VERSION}-calendar"

# php.ini tweaks for CLI + FPM
for ini in "/etc/php/${PHP_VERSION}/cli/php.ini" "/etc/php/${PHP_VERSION}/fpm/php.ini"; do
    if [[ -f "$ini" ]]; then
        sudo sed -i 's/^memory_limit = .*/memory_limit = 512M/' "$ini"
        sudo sed -i 's/^max_execution_time = .*/max_execution_time = 300/' "$ini"
    fi
done

sudo systemctl restart "php${PHP_VERSION}-fpm"

php -v | grep -q "${PHP_VERSION}" || fail "PHP ${PHP_VERSION} installation failed"
ok "PHP ${PHP_VERSION} installed"

# ────────────────────────────────────────────────
# 3. Install Composer
# ────────────────────────────────────────────────
info "Installing Composer"
if ! command -v composer &>/dev/null; then
    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [[ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]]; then
        rm -f composer-setup.php
        fail "Composer installer checksum verification failed"
    fi

    sudo php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
    rm -f composer-setup.php
fi
composer --version
ok "Composer ready"

# ────────────────────────────────────────────────
# 4. Install Node.js LTS
# ────────────────────────────────────────────────
info "Installing Node.js LTS"
if ! command -v node &>/dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi
node -v
npm -v
ok "Node.js ready"

# ────────────────────────────────────────────────
# 5. Install & configure MySQL
# ────────────────────────────────────────────────
info "Installing MySQL"
sudo apt-get install -y mysql-server
sudo systemctl enable mysql --now

# Set root password (safe for fresh installs; skip if already set)
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${MYSQL_ROOT_PASS}'; FLUSH PRIVILEGES;" 2>/dev/null || \
    warn "Could not set MySQL root password — may already be configured"

mysql -u root -p"${MYSQL_ROOT_PASS}" <<EOSQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOSQL

ok "MySQL configured (db: ${DB_NAME}, user: ${DB_USER})"

# ────────────────────────────────────────────────
# 6. Install Nginx
# ────────────────────────────────────────────────
info "Installing Nginx"
sudo apt-get install -y nginx
sudo systemctl enable nginx --now
ok "Nginx installed"

# ────────────────────────────────────────────────
# 7. Create Nginx vhost for Bagisto
# ────────────────────────────────────────────────
info "Configuring Nginx vhost"
NGINX_CONF="/etc/nginx/sites-available/bagisto"

sudo tee "$NGINX_CONF" >/dev/null <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${SITE_DOMAIN};

    root ${PROJECT_PATH}/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    client_max_body_size 100M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(jpg|jpeg|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|webp|woff|woff2|ttf|eot)\$ {
        expires 1y;
        access_log off;
        add_header Cache-Control "public";
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
EOF

sudo rm -f /etc/nginx/sites-enabled/default
sudo ln -sf "$NGINX_CONF" /etc/nginx/sites-enabled/bagisto

sudo nginx -t || fail "Nginx config test failed — check $NGINX_CONF"
sudo systemctl reload nginx
ok "Nginx configured for http://${SITE_DOMAIN}"

# ────────────────────────────────────────────────
# 8. Project dependencies + .env + Bagisto install
# ────────────────────────────────────────────────
info "Setting up Bagisto project"

[[ -d "$PROJECT_PATH" ]] || fail "Project directory not found: $PROJECT_PATH — clone the repo first"

cd "$PROJECT_PATH"

composer install --optimize-autoloader --no-interaction --prefer-dist

if [[ ! -f ".env" ]]; then
    cp .env.example .env
    ok "Created .env from .env.example"
fi

# Use pipe delimiter to avoid issues with special chars in passwords
sed -i "s|^APP_URL=.*|APP_URL=http://${SITE_DOMAIN}|"   .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|"       .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|"       .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|"       .env

php artisan key:generate --no-interaction --force

info "Running bagisto:install (this may take a few minutes)"
php artisan bagisto:install --no-interaction || {
    warn "bagisto:install may require interactive input in some versions."
    warn "If so, run manually:  cd $PROJECT_PATH && php artisan bagisto:install"
}

info "Building front-end assets"
npm install --no-audit --no-fund
npm run build

ok "Bagisto project set up"

# ────────────────────────────────────────────────
# 9. File permissions
# ────────────────────────────────────────────────
info "Setting file permissions"
sudo chown -R "$USER":www-data "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache" "$PROJECT_PATH/public"
sudo chmod -R 775 "$PROJECT_PATH/storage" "$PROJECT_PATH/bootstrap/cache"
ok "Permissions set"

# ────────────────────────────────────────────────
# 10. Final summary
# ────────────────────────────────────────────────
echo ""
echo "============================================================="
echo " Bagisto + Nginx setup complete!"
echo ""
echo " Frontend  :  http://$SITE_DOMAIN"
echo " Admin     :  http://$SITE_DOMAIN/admin"
echo ""
echo " MySQL DB  :  $DB_NAME"
echo " MySQL user:  $DB_USER"
echo ""
echo " Nginx conf:  $NGINX_CONF"
echo " PHP-FPM   :  php${PHP_VERSION}-fpm"
echo ""
echo " CHANGE ALL DEFAULT PASSWORDS before exposing to a network!"
echo "============================================================="
echo ""
echo "Tip: 'php artisan serve' still works for quick dev (bypasses Nginx)."
