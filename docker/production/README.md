# Bagisto Production Docker Image

A **single-container**, ready-to-boot production Docker image for [Bagisto](https://github.com/bagisto/bagisto) — the open-source Laravel e-commerce platform.

One `docker run` gives you a fully installed, migrated, and seeded Bagisto store. No docker-compose, no external database, no first-run setup.

---

## Table of Contents

1. [What's Inside](#1-whats-inside)
2. [Repository Layout](#2-repository-layout)
3. [Quick Start](#3-quick-start)
4. [Building the Image](#4-building-the-image)
5. [Tagging Strategy](#5-tagging-strategy)
6. [Pushing to Docker Hub](#6-pushing-to-docker-hub)
7. [Full Release Workflow](#7-full-release-workflow)
8. [Running the Container](#8-running-the-container)
9. [Access & Default Credentials](#9-access--default-credentials)
10. [Persistence](#10-persistence)
11. [External Database Mode](#11-external-database-mode)
12. [Environment Variables](#12-environment-variables)
13. [First-Boot Behavior](#13-first-boot-behavior)
14. [Common Commands](#14-common-commands)
15. [Upgrading Bagisto Version](#15-upgrading-bagisto-version)
16. [Troubleshooting](#16-troubleshooting)
17. [FAQ](#17-faq)
18. [Notes & Limitations](#18-notes--limitations)
19. [Support](#19-support)

---

## 1. What's Inside

| Component | Details |
|---|---|
| **Base OS** | Ubuntu 24.04 |
| **Web Server** | Nginx (listening on port 80) |
| **PHP** | 8.3 FPM with bcmath, calendar, curl, exif, gd, gmp, intl, mbstring, mysql, pdo, soap, sockets, xml, zip, imagick |
| **Database** | MySQL 8.0 (internal, pre-installed with Bagisto migrations + seed data already applied) |
| **Process Manager** | Supervisor (manages `mysql`, `php-fpm`, and `nginx`) |
| **Application** | Bagisto — fully installed at build time |

### Two database modes

| Mode | When it activates | Behavior |
|---|---|---|
| **Internal MySQL** (default) | `DB_HOST` is unset, `127.0.0.1`, or `localhost` | Built-in MySQL runs inside the container. Database is already populated from the build. |
| **External MySQL** | `DB_HOST` is set to anything else | Internal MySQL is disabled. The container connects to your external database instead. |

---

## 2. Repository Layout

All files live under **`docker/production/`** inside the Bagisto repository:

```
bagisto/
├── .github/workflows/
│   └── docker-publish.yml  # Auto-builds & pushes the image on every `v*` tag
└── docker/production/
    ├── Dockerfile          # Single-stage production image definition
    ├── build-install.sh    # Runs at build time: installs Bagisto, seeds DB, bakes MySQL data into the image layer
    ├── entrypoint.sh       # Runs at container start: applies env overrides and hands off to Supervisor
    ├── mysql-init.sql      # SQL to create the `bagisto` database + user on first MySQL init
    ├── nginx.conf          # Nginx server block (port 80 → /var/www/bagisto/public)
    ├── php.ini             # Production PHP settings (opcache, limits, error handling)
    ├── php-fpm.conf        # PHP-FPM pool config (www, unix socket, dynamic PM)
    ├── supervisord.conf    # Supervisor config for mysql + php-fpm + nginx
    ├── .dockerignore       # Excludes unneeded files from the build context
    └── README.md           # This file
```

**Releases are automated.** Pushing a `v*` Git tag to the Bagisto repo (e.g. `v2.4.0`) triggers the GitHub Actions workflow, which builds the multi-arch image and pushes it to Docker Hub. See [§7 — Full Release Workflow](#7-full-release-workflow).

For local builds, run `docker build` from `docker/production/`.

---

## 3. Quick Start

### Pull from Docker Hub

```bash
docker pull webkul/bagisto:latest
```

### Run on port 80

```bash
docker run -d --name bagisto -p 80:80 webkul/bagisto:latest
```

Open `http://localhost` — your store is live.

### Run on a different host port

```bash
docker run -d --name bagisto -p 8080:80 webkul/bagisto:latest
```

Then visit `http://localhost:8080`.

---

## 4. Building the Image

> **For releases, you do not need to build manually.** A GitHub Actions workflow (`.github/workflows/docker-publish.yml`) builds and pushes the multi-arch image to Docker Hub automatically when a `v*` tag is pushed. See [§7 — Full Release Workflow](#7-full-release-workflow).
>
> The instructions below cover **local development builds** — testing Dockerfile changes, debugging the install flow, or producing a single-arch image for a private registry.

All commands below assume you are inside `docker/production/`:

```bash
cd docker/production
```

### Build with the default Bagisto version

```bash
docker build -t bagisto-prod .
```

This uses the default `BAGISTO_VERSION` set in the Dockerfile (currently **`v2.4.0`**).

### Build with a specific Bagisto version

```bash
docker build -t bagisto-prod --build-arg BAGISTO_VERSION=v2.4.0 .
```

Replace `v2.4.0` with any valid Git tag from https://github.com/bagisto/bagisto/tags.

### Build with a version already in the image tag

```bash
docker build -t bagisto-prod:2.4.0 --build-arg BAGISTO_VERSION=v2.4.0 .
```

### Build arguments

| Build arg | Default | Description |
|---|---|---|
| `BAGISTO_VERSION` | `v2.4.0` | Git tag to clone from the Bagisto repository. |
| `PHP_VERSION` | `8.3` | PHP version to install. Only change if you know what you're doing. |

> **Note**: Bagisto is fully installed *during the build* (migrations, seeding, indexing). Expect build times of 5–10 minutes depending on your machine.

> **Building for multiple architectures (amd64 + arm64) locally?** See [§6 — Multi-architecture builds](#multi-architecture-builds-amd64--arm64). `docker build` produces a single-arch image; multi-arch requires `docker buildx` and is published in one step with `--push`. The CI workflow handles this automatically for releases.

---

## 5. Tagging Strategy

The CI workflow applies this scheme automatically. The details below explain what it produces and why, plus how to match it for any manual builds.

### `v`-prefix convention

| Where | Format | Example |
|---|---|---|
| Bagisto Git tag (used in `--build-arg`) | **with** `v` prefix | `v2.4.0` |
| Docker image tag | **without** `v` prefix | `2.4.0` |

The `v` prefix is a Git convention. Docker Hub tags are plain version numbers.

### Tags published per release (automated)

| Git tag pushed | Docker Hub tags published |
|---|---|
| `v2.4.0` (stable) | `webkul/bagisto:2.4.0` **and** `webkul/bagisto:latest` |
| `v2.4.0-rc1`, `v2.4.0-beta`, etc. (pre-release) | `webkul/bagisto:2.4.0-rc1` only — `latest` is **not** updated |

| Tag | Purpose |
|---|---|
| `2.4.0` | Immutable. Always points to this exact build. |
| `latest` | Mutable. Always points to the most recently pushed stable release. |

### Manual tagging (local builds)

```bash
# Tag an existing local image for Docker Hub
docker tag bagisto-prod <your-dockerhub-username>/bagisto:2.4.0
docker tag bagisto-prod <your-dockerhub-username>/bagisto:latest
```

Or build directly with the final name (skips the retag step):

```bash
docker build -t <your-dockerhub-username>/bagisto:2.4.0 --build-arg BAGISTO_VERSION=v2.4.0 .
docker tag   <your-dockerhub-username>/bagisto:2.4.0 <your-dockerhub-username>/bagisto:latest
```

### Avoid these tag formats

| Bad | Why |
|---|---|
| `bagisto:v2.4.0` | Inconsistent with Docker Hub convention (no `v` prefix). |
| `bagisto:bagisto-2.4.0` | Redundant — the repository name already says `bagisto`. |
| `bagisto:prod-2.4.0` | Unnecessary prefix — all images in this repo are production. |

---

## 6. Pushing to Docker Hub

### Automated (the standard path)

Pushes to Docker Hub happen automatically via GitHub Actions on every `v*` tag pushed to the Bagisto repo. You do not need to run `docker login` / `docker push` by hand.

**One-time setup** (already done on `bagisto/bagisto`, repeat only if forking or self-hosting):

GitHub repo → **Settings** → **Secrets and variables** → **Actions** → add two repository secrets:

| Secret | Value |
|---|---|
| `DOCKERHUB_USERNAME` | Docker Hub user/org with push access to `webkul/bagisto` |
| `DOCKERHUB_TOKEN` | Docker Hub access token with **Read & Write** scope (generate at https://hub.docker.com/settings/security) |

That's it. The next `git push origin v<version>` builds and pushes the image.

**Manual re-run / hotfix**: from the GitHub Actions UI, run the **"Build & Publish Docker Image"** workflow via `workflow_dispatch` and override `bagisto_version` and/or `push_latest`.

### Manual fallback (local push)

Use only if CI is unavailable or you're pushing to a private registry.

#### Step 1 — Log in

```bash
docker login
```

Or, using an access token (recommended over password):

```bash
docker login -u <your-dockerhub-username>
```

Generate an access token at https://hub.docker.com/settings/security and paste it when prompted.

#### Step 2 — Build with the Docker Hub name

```bash
docker build -t <your-dockerhub-username>/bagisto:2.4.0 \
  --build-arg BAGISTO_VERSION=v2.4.0 .
```

#### Step 3 — Also tag as `latest`

```bash
docker tag <your-dockerhub-username>/bagisto:2.4.0 \
           <your-dockerhub-username>/bagisto:latest
```

#### Step 4 — Push both tags

```bash
docker push <your-dockerhub-username>/bagisto:2.4.0
docker push <your-dockerhub-username>/bagisto:latest
```

#### Step 5 — Verify

```bash
docker manifest inspect <your-dockerhub-username>/bagisto:2.4.0
```

Or visit `https://hub.docker.com/r/<your-dockerhub-username>/bagisto/tags` in your browser.

### Multi-architecture builds (amd64 + arm64)

The flow above publishes a **single-architecture** image — whichever arch the build host runs (typically `linux/amd64`). To publish a single tag that works on both Intel/AMD servers **and** Apple Silicon / ARM cloud instances, use `docker buildx`.

The Dockerfile itself needs no changes — `ubuntu:24.04`, `mysql-server`, the `ondrej/php` PPA, and the imagick PECL build all work cleanly on `arm64`.

#### One-time setup

```bash
# Install QEMU emulators so one host can build other architectures
docker run --privileged --rm tonistiigi/binfmt --install all

# Create a buildx builder that supports multi-platform
# (the default `docker` driver cannot — see Troubleshooting in §16)
docker buildx create --name multiarch --driver docker-container --bootstrap
docker buildx use multiarch

# Verify — the asterisk should be on `multiarch`
docker buildx ls
```

#### Build & push both archs in one command

```bash
docker login -u <your-dockerhub-username>

docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --build-arg BAGISTO_VERSION=v2.4.0 \
  -t <your-dockerhub-username>/bagisto:2.4.0 \
  -t <your-dockerhub-username>/bagisto:latest \
  --push .
```

`buildx` builds both architectures in parallel (the non-native one runs under QEMU) and pushes a single multi-arch manifest. `docker pull` then automatically selects the correct variant for whatever host runs the image.

#### Verify the manifest

```bash
docker buildx imagetools inspect <your-dockerhub-username>/bagisto:2.4.0
```

You should see entries for both `linux/amd64` and `linux/arm64`.

#### Notes

- **Don't use `--load`** with two platforms — the local Docker daemon can only hold one arch at a time. `--push` writes straight to the registry, which is what you want.
- **Build time roughly doubles** — closer to 3× for the arm64 leg under QEMU, since `composer install`, migrations, and seeding all run twice. Expect 20–40 minutes on a typical machine. For faster builds, use a native arm64 runner (M-series Mac or an arm64 cloud VM) and `buildx` will dispatch each platform to its native builder.
- **No need to drop the single-arch flow** — `docker build` + `docker push` is still fine for amd64-only releases. Use `buildx` only when you want both archs under one tag.

---

## 7. Full Release Workflow

### Automated flow (standard)

Releasing Bagisto `2.4.0` after previously having `2.3.12` is now a single tag push:

```bash
# From the Bagisto repo root
git tag v2.4.0
git push origin v2.4.0
```

That's the entire release. The GitHub Actions workflow at `.github/workflows/docker-publish.yml` then:

1. Validates the tag matches `vX.Y.Z` (or `vX.Y.Z-suffix` for pre-releases).
2. Sets up QEMU + Buildx for cross-architecture builds.
3. Logs in to Docker Hub using the `DOCKERHUB_USERNAME` / `DOCKERHUB_TOKEN` repo secrets.
4. Builds `docker/production/Dockerfile` for `linux/amd64` and `linux/arm64` in parallel with `BAGISTO_VERSION=v2.4.0`.
5. Pushes a single multi-arch manifest as `webkul/bagisto:2.4.0` and (for stable tags) also `webkul/bagisto:latest`.
6. Caches buildx layers in GitHub Actions cache for faster subsequent builds.

Track progress in the repo's **Actions** tab. Build duration is typically **30–60 minutes** because the arm64 leg runs under QEMU emulation and Bagisto is fully installed (migrations, seeders, indexers) during the build.

After the run finishes:

- `webkul/bagisto:2.3.12` still exists and still works (tags are immutable once pushed).
- `webkul/bagisto:2.4.0` points to the new build (both archs).
- `webkul/bagisto:latest` now points to 2.4.0 (both archs).

### Pre-release / RC tags

Tags with a suffix do **not** update `latest`:

```bash
git tag v2.4.0-rc1
git push origin v2.4.0-rc1
# → webkul/bagisto:2.4.0-rc1   (latest is untouched)
```

### Manual re-run via workflow_dispatch

Need to rebuild a previously-released version without retagging? Go to **Actions → Build & Publish Docker Image → Run workflow** and fill in:

| Input | Value |
|---|---|
| `bagisto_version` | e.g. `v2.4.0` |
| `push_latest` | `true` / `false` |

### Manual fallback (local machine)

If CI is down or you're publishing to a private registry, fall back to the manual flow:

```bash
cd docker/production

# 1. Build with Docker Hub name
docker build -t <your-dockerhub-username>/bagisto:2.4.0 \
  --build-arg BAGISTO_VERSION=v2.4.0 .

# 2. Re-tag as latest
docker tag <your-dockerhub-username>/bagisto:2.4.0 \
           <your-dockerhub-username>/bagisto:latest

# 3. Push both tags
docker push <your-dockerhub-username>/bagisto:2.4.0
docker push <your-dockerhub-username>/bagisto:latest

# 4. Verify
docker manifest inspect <your-dockerhub-username>/bagisto:2.4.0
```

#### Manual multi-arch release (amd64 + arm64)

To publish both architectures under one tag without CI (one-time `buildx` setup in [§6](#multi-architecture-builds-amd64--arm64)):

```bash
cd docker/production

docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --build-arg BAGISTO_VERSION=v2.4.0 \
  -t <your-dockerhub-username>/bagisto:2.4.0 \
  -t <your-dockerhub-username>/bagisto:latest \
  --push .

docker buildx imagetools inspect <your-dockerhub-username>/bagisto:2.4.0
```

---

## 8. Running the Container

### Basic run

```bash
docker run -d --name bagisto -p 80:80 bagisto-prod
```

### Different host port

```bash
docker run -d --name bagisto -p 8080:80 bagisto-prod
```

### In the foreground (stream logs directly)

```bash
docker run --name bagisto -p 80:80 bagisto-prod
```

### With environment overrides

```bash
docker run -d --name bagisto -p 80:80 \
  -e APP_URL=http://my-store.local \
  -e APP_TIMEZONE=Asia/Kolkata \
  -e APP_CURRENCY=INR \
  bagisto-prod
```

### With persistent volumes (recommended)

```bash
docker run -d --name bagisto -p 80:80 \
  -v bagisto-mysql:/var/lib/mysql \
  -v bagisto-storage:/var/www/bagisto/storage \
  bagisto-prod
```

---

## 9. Access & Default Credentials

### Storefront

```
http://localhost
```

(or `http://localhost:<port>` if you mapped a different host port)

### Admin panel

```
http://localhost/admin/login
```

### Default admin credentials

| Field | Value |
|---|---|
| Email | `admin@example.com` |
| Password | `admin123` |

> **Change the default admin password immediately after first login** in any real deployment.

The admin path `/admin` is the Bagisto default. To change it, pass `-e APP_ADMIN_URL=backend` at runtime — the admin panel will then be served at `/backend/login`.

---

## 10. Persistence

The `Dockerfile` intentionally does **not** declare any `VOLUME` directives. Persistence is entirely opt-in at `docker run` time.

### Named volumes (recommended for production)

```bash
docker run -d --name bagisto -p 80:80 \
  -v bagisto-mysql:/var/lib/mysql \
  -v bagisto-storage:/var/www/bagisto/storage \
  bagisto-prod
```

| Volume | Container path | What it persists |
|---|---|---|
| `bagisto-mysql` | `/var/lib/mysql` | MySQL database files. Without this, all data is lost on `docker rm`. |
| `bagisto-storage` | `/var/www/bagisto/storage` | Uploaded files, product images, logs, sessions, cached views. |

### Bind mounts (use host paths)

```bash
docker run -d --name bagisto -p 80:80 \
  -v /path/on/host/mysql-data:/var/lib/mysql \
  -v /path/on/host/bagisto-storage:/var/www/bagisto/storage \
  bagisto-prod
```

### No volumes

```bash
docker run -d --name bagisto -p 80:80 bagisto-prod
```

Everything lives inside the container's writable layer. Data survives `docker stop` + `docker start`, but is **lost on `docker rm`**. Suitable for demos, testing, and quick evaluations only.

---

## 11. External Database Mode

To connect to an external MySQL server instead of using the built-in one:

```bash
docker run -d --name bagisto -p 80:80 \
  -v bagisto-storage:/var/www/bagisto/storage \
  -e DB_HOST=your-rds-host.amazonaws.com \
  -e DB_PORT=3306 \
  -e DB_DATABASE=bagisto \
  -e DB_USERNAME=bagisto_user \
  -e DB_PASSWORD=your_secure_password \
  -e APP_URL=https://your-domain.com \
  bagisto-prod
```

When `DB_HOST` is set to anything other than `127.0.0.1` or `localhost`:

- Internal MySQL is **not started** (`MYSQL_AUTOSTART=false`).
- The entrypoint waits up to 60 seconds for the external MySQL to be reachable.
- No `/var/lib/mysql` volume is needed.

> **Important**: For external database mode, you must **create the database and user yourself** before starting the container. The internal `mysql-init.sql` only runs against the built-in MySQL.

```sql
CREATE DATABASE bagisto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bagisto_user'@'%' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON bagisto.* TO 'bagisto_user'@'%';
FLUSH PRIVILEGES;
```

You will also need to run Bagisto's migrations against the external database on first boot. Shell into the container and run:

```bash
docker exec -it bagisto php /var/www/bagisto/artisan migrate --force
docker exec -it bagisto php /var/www/bagisto/artisan db:seed --force
```

---

## 12. Environment Variables

### Build arguments (`docker build --build-arg`)

| Variable | Default | Description |
|---|---|---|
| `BAGISTO_VERSION` | `v2.4.0` | Git tag cloned from the Bagisto repository. |
| `PHP_VERSION` | `8.3` | PHP version to install. |

### Runtime environment (`docker run -e`)

| Variable | Default | Description |
|---|---|---|
| `DB_HOST` | `127.0.0.1` | Database host. Anything other than `127.0.0.1` / `localhost` switches to external-DB mode. |
| `DB_PORT` | `3306` | Database port. |
| `DB_DATABASE` | `bagisto` | Database name. |
| `DB_USERNAME` | `bagisto` | Database user. |
| `DB_PASSWORD` | `bagisto` | Database password. |
| `APP_URL` | `http://localhost` | Public-facing URL of the store. |
| `APP_KEY` | baked at build | Laravel encryption key. Generated during build; override for fixed keys. |
| `APP_LOCALE` | from `.env.example` | Application locale (e.g., `en`, `fr`, `de`). |
| `APP_CURRENCY` | from `.env.example` | Default currency (e.g., `USD`, `INR`, `EUR`). |
| `APP_TIMEZONE` | from `.env.example` | Timezone (e.g., `UTC`, `Asia/Kolkata`). |
| `APP_ADMIN_URL` | `admin` | Admin panel URL path. |

---

## 13. First-Boot Behavior

Because Bagisto is fully installed at **build time**, first boot is fast — there is no migration or seeding step at runtime.

When the container starts, `entrypoint.sh` does this:

| Step | What happens |
|---|---|
| 1 | Reads `DB_HOST` to determine internal vs. external MySQL mode. |
| 2 | Sets `MYSQL_AUTOSTART=true` or `false` so Supervisor knows whether to start MySQL. |
| 3 | Writes any runtime overrides (`DB_*`, `APP_URL`, `APP_KEY`, `APP_LOCALE`, `APP_CURRENCY`, `APP_TIMEZONE`) into `/var/www/bagisto/.env`. |
| 4 | If env vars were overridden, runs `php artisan optimize:clear` + `optimize` to refresh caches. |
| 5 | (External MySQL mode only) Waits up to 60 seconds for the external database to accept connections. |
| 6 | Hands off to Supervisor, which starts `mysql` (if internal), `php-fpm`, and `nginx`. |

### Why the build-time install?

`build-install.sh` runs *during `docker build`* and does the full Bagisto installation inside the image:

1. Initializes `/var/lib/mysql`.
2. Starts MySQL in the background.
3. Creates the `bagisto` database and user from `mysql-init.sql`.
4. Runs `php artisan key:generate`.
5. Runs `php artisan bagisto:install` (migrations + core seeding).
6. Runs the Bagisto product seeder for sample data.
7. Runs `php artisan index:index --mode=full` for search indexing.
8. Shuts MySQL down cleanly.

The populated `/var/lib/mysql` directory is saved as part of the Docker image layer. So when you `docker run`, the database already has tables, admin user, products, and indexes.

---

## 14. Common Commands

### View logs

```bash
docker logs bagisto              # all logs (nginx + php-fpm + mysql + entrypoint)
docker logs -f bagisto           # follow in real time
docker logs --tail 100 bagisto   # last 100 lines
```

### Shell into the container

```bash
docker exec -it bagisto bash
```

### Check Supervisor-managed services

```bash
docker exec bagisto supervisorctl status
```

Expected output (internal MySQL mode):

```
mysql                            RUNNING   pid 45, uptime 0:05:23
nginx                            RUNNING   pid 47, uptime 0:05:23
php-fpm                          RUNNING   pid 46, uptime 0:05:23
```

### Restart individual services

```bash
docker exec bagisto supervisorctl restart nginx
docker exec bagisto supervisorctl restart php-fpm
docker exec bagisto supervisorctl restart mysql
```

### Restart the whole container

```bash
docker restart bagisto
```

### Stop and remove

```bash
docker stop bagisto
docker rm bagisto
```

### Stop, remove, and wipe volumes

```bash
docker stop bagisto
docker rm bagisto
docker volume rm bagisto-mysql bagisto-storage
```

### Run an artisan command

```bash
docker exec bagisto php /var/www/bagisto/artisan migrate:status
docker exec bagisto php /var/www/bagisto/artisan cache:clear
docker exec bagisto php /var/www/bagisto/artisan config:clear
```

### Check PHP version / extensions

```bash
docker exec bagisto php -v
docker exec bagisto php -m
```

### Connect to internal MySQL from the host

By default MySQL only listens on `127.0.0.1` inside the container. To reach it from your host for debugging, expose port 3306:

```bash
docker run -d -p 80:80 -p 3306:3306 bagisto-prod
mysql -h 127.0.0.1 -P 3306 -u bagisto -pbagisto bagisto
```

---

## 15. Upgrading Bagisto Version

The Bagisto source is baked into the image at build time. New official versions are published to Docker Hub automatically when the corresponding `v*` Git tag is pushed to `bagisto/bagisto` — just `docker pull webkul/bagisto:2.4.0` (or `:latest`) to get the new image.

For custom or local rebuilds:

```bash
cd docker/production
docker build -t bagisto-prod:2.4.0 --build-arg BAGISTO_VERSION=v2.4.0 .
```

Then stop and replace the running container:

```bash
docker stop bagisto && docker rm bagisto

docker run -d --name bagisto -p 80:80 \
  -v bagisto-mysql:/var/lib/mysql \
  -v bagisto-storage:/var/www/bagisto/storage \
  bagisto-prod:2.4.0
```

If the schema changed between versions, you may need to run Bagisto's migrations against the existing data:

```bash
docker exec bagisto php /var/www/bagisto/artisan migrate --force
```

> **Back up your MySQL volume before upgrading production data**:
> ```bash
> docker run --rm -v bagisto-mysql:/data -v $(pwd):/backup alpine \
>   tar czf /backup/mysql-backup.tar.gz /data
> ```

---

## 16. Troubleshooting

### Port 80 is already in use

**Symptom**: `docker run` fails with `bind: address already in use`.

**Fix** — map to a different host port:

```bash
docker run -d --name bagisto -p 8080:80 bagisto-prod
```

Or find what's holding port 80:

```bash
sudo lsof -i :80
```

### Container exits immediately / MySQL not ready

**Symptom**: Logs show `ERROR: MySQL did not start within 60 seconds`.

**Fix** — the MySQL data directory may be corrupted. Recreate the volume:

```bash
docker stop bagisto && docker rm bagisto
docker volume rm bagisto-mysql
docker run -d --name bagisto -p 80:80 -v bagisto-mysql:/var/lib/mysql bagisto-prod
```

### Permissions errors on `storage/` or `bootstrap/cache/`

```bash
docker exec bagisto bash -c "chown -R www-data:www-data \
  /var/www/bagisto/storage /var/www/bagisto/bootstrap/cache && \
  chmod -R 775 /var/www/bagisto/storage /var/www/bagisto/bootstrap/cache"
```

### External database connection fails

**Symptom**: `ERROR: Cannot reach external MySQL at ... after 60s`.

**Checks**:

1. External MySQL is running and reachable from the Docker host.
2. Credentials are correct.
3. The database exists (see the SQL in section 11).
4. If MySQL is on the host machine, use `host.docker.internal` instead of `localhost`:

```bash
docker run -d --name bagisto -p 80:80 \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=bagisto \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=root \
  bagisto-prod
```

### Nginx / PHP-FPM / MySQL not running

```bash
docker exec bagisto supervisorctl status
```

If a service shows `FATAL` or `STOPPED`:

```bash
docker logs bagisto 2>&1 | grep -i error
docker exec bagisto supervisorctl restart php-fpm
docker exec bagisto supervisorctl restart nginx
```

### Container keeps restarting

```bash
docker inspect bagisto --format='{{.State.ExitCode}}'
docker logs --tail 50 bagisto
```

Common causes: corrupted MySQL volume, missing `.env`, port conflict.

### Multi-platform build is not supported for the docker driver

**Symptom**: `docker buildx build --platform linux/amd64,linux/arm64 ...` fails with:

```
ERROR: Multi-platform build is not supported for the docker driver.
Switch to a different driver, or turn on the containerd image store, and try again.
```

**Cause**: `buildx` is using the default `docker` driver, which can only handle one architecture.

**Fix** — create and switch to a `docker-container` builder:

```bash
docker buildx ls                                                      # check active driver
docker buildx create --name multiarch --driver docker-container --bootstrap
docker buildx use multiarch
docker buildx ls                                                      # asterisk should now be on `multiarch`
```

Alternative (Docker Desktop only): Settings → General → check **"Use containerd for pulling and storing images"** → Apply & restart. The default `docker` driver can then handle multi-platform.

### Docker Hub push errors

| Error | Cause | Fix |
|---|---|---|
| `repository does not exist or may require 'docker login'` | Repo not pushed yet, typo in name, or not logged in. | Run `docker login`, verify image name with `docker images`. |
| `denied: requested access to the resource is denied` | Not logged in, or pushing to a repo you don't own. | Run `docker login`; push only to `<your-username>/...`. |
| `manifest for ...:tag not found` | That specific tag was never pushed. | Build and push the missing tag. |
| `You have reached your pull rate limit` | Docker Hub free tier limit. | Wait, authenticate, or upgrade your plan. |
| `No such image: ...:tag` | Image doesn't exist locally. | Build it first, or `docker tag` an existing image. |

---

## 17. FAQ

### Why is the image ~1.5 GB?

Because it contains a fully installed, ready-to-boot Bagiso application with MySQL, PHP, Nginx, and all runtime dependencies bundled in one container. Approximate size distribution:

| Component | Size |
|---|---|
| MySQL 8.0 server + pre-seeded Bagisto database | ~400–500 MB |
| PHP 8.3 + all required extensions | ~150–200 MB |
| Bagisto source + Composer vendor | ~250–300 MB |
| System libraries (ImageMagick, ICU, libpng, libwebp, libzip, etc.) | ~100–150 MB |
| Ubuntu 24.04 base | ~77 MB |
| Nginx + Supervisor | ~15–20 MB |

The current Dockerfile already does `--no-install-recommends`, purges dev/build packages in the same layer, removes Composer and git after use, strips `.git` and tests from vendor, and cleans the apt cache. The remaining size is irreducible runtime dependency.

### Why Ubuntu 24.04 and not Alpine?

| Factor | Alpine | Ubuntu 24.04 |
|---|---|---|
| Base image size | ~5 MB | ~77 MB |
| C library | musl libc | glibc |
| MySQL availability | Not packaged — must use MariaDB | Native `mysql-server` |
| PHP packages | Community packages | Ondrej PPA (the standard for production PHP) |
| Imagick + intl | Known musl compilation issues | Compiles cleanly |

Alpine would save ~72 MB on the base — about **2%** of the total image. That's not enough to justify rewriting around MariaDB and debugging musl-related PHP extension bugs.

### Why bundle MySQL inside the image?

The goal is a Docker Hub-style "appliance" experience:

```bash
docker run -p 80:80 bagisto-prod
# Open browser → Bagisto is running
```

No docker-compose, no external DB to provision, no knowledge of database administration required. This mirrors how the official WordPress image and similar "just run it" containers work.

For production at scale, set `DB_HOST` to an external managed database (AWS RDS, Cloud SQL, DigitalOcean, etc.) and the internal MySQL is automatically skipped.

### Why Supervisor?

Docker containers are designed for a single process, but this image runs three (MySQL, PHP-FPM, Nginx). Supervisor is the standard solution: it starts services in the right order, restarts crashed processes, exposes `supervisorctl` for inspection, and pipes all output to stdout/stderr so `docker logs` works normally.

### Why is Bagisto installed at build time instead of runtime?

Because runtime installation would:

- Take 2–5 minutes on every first start.
- Repeat the same install on every new container.
- Make startup failures harder to debug.
- Break image immutability.

Build-time install means `docker run` is instant and the image is fully reproducible.

### Why is `build-install.sh` a separate script?

Docker's `RUN` executes via `/bin/sh -c "…"`, which doesn't cleanly handle shell backgrounding (`&`). Since the build needs to start MySQL in the background, run commands against it, then shut it down, a dedicated bash script is the cleanest way to do that.

### Can I run Composer inside the running container?

Composer is removed from the final image to save space. If you need it temporarily:

```bash
docker exec -it bagisto bash
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
composer <command>
```

### Is HTTPS supported directly?

No. The container serves plain HTTP on port 80. For HTTPS, place a reverse proxy (Nginx, Traefik, Caddy, or a cloud load balancer) in front of the container and terminate SSL there.

### Are the default MySQL credentials safe?

Internal MySQL (`bagisto`/`bagisto`) is safe **for the default internal mode** — MySQL only listens on `127.0.0.1` inside the container, so it's not reachable from outside unless you explicitly map port 3306. For external MySQL mode, always use strong credentials via env vars.

### Is Elasticsearch included?

No. Bagisto works without it (falling back to MySQL full-text search). If you need Elasticsearch, run it as a separate container and configure Bagisto to connect to it via env vars.

---

## 18. Notes & Limitations

### Configurable at runtime

- Database connection (host, port, database, user, password)
- Application URL, locale, currency, timezone
- Laravel encryption key
- Admin panel URL path

### NOT configurable at runtime (set at build time)

- Bagisto version (via `--build-arg BAGISTO_VERSION`)
- PHP version (via `--build-arg PHP_VERSION`)
- Nginx / PHP / Supervisor config files (bake into the image; override by mounting replacements over `/etc/nginx/conf.d/bagisto.conf`, `/etc/php/8.3/fpm/conf.d/99-production.ini`, `/etc/supervisor/conf.d/bagisto.conf`)

### Logging

All services log to stdout/stderr via Supervisor, so `docker logs bagisto` captures everything in one stream. For structured log shipping, mount `/var/log/bagisto` as a volume or forward to a log aggregator.

### Security checklist for real deployments

- Change the default admin password (`admin@example.com` / `admin123`) after first login.
- Set `APP_URL` to your real domain with HTTPS.
- Use external MySQL with strong credentials for anything beyond a demo.
- Put the container behind a reverse proxy that terminates SSL.
- Never expose port 3306 publicly.

---

## 19. Support

- Email: **support@bagisto.com**
- Support portal: [https://webkul.uvdesk.com/](https://webkul.uvdesk.com/)
- Documentation: [https://devdocs.bagisto.com](https://devdocs.bagisto.com)
- Bagisto source: [https://github.com/bagisto/bagisto](https://github.com/bagisto/bagisto)

---

Thank you for choosing Bagisto!
