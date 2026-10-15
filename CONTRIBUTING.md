# Bagisto Contribution Guide

## Before you start working

**Comment on the issue and wait to be assigned before you write any code.**

Several people often pick up the same issue, and the duplicated work is only discovered when the second pull request arrives. A short comment — "I'd like to take this" — is enough, and it saves someone else an evening.

Check the issue first for an assignee, or for someone who has already said they are on it. If it is claimed but has gone quiet, ask in the thread rather than opening a competing pull request.

For a feature, open a [feature request](https://github.com/bagisto/bagisto/issues/new/choose) if one does not already exist, and agree the approach there **before** building it. A feature that arrives as a surprise pull request is far more likely to be turned down on direction than on code.

## Bugs

Bagisto encourages pull requests, not just bug reports — a bug report may itself take the form of a pull request containing a failing test.

1. Search the [issues](https://github.com/bagisto/bagisto/issues) first. If no open issue covers the bug, [open a new one](https://github.com/bagisto/bagisto/issues/new/choose).
2. Check that the bug is a general issue and not specific to your own setup. For help with an individual setup, use the [Community Forum](https://forums.bagisto.com/).
3. Give the report a title, a clear description, the version you are on, and enough detail to reproduce it, including a code sample where one helps. The goal is to let someone else replicate the bug and build a fix.

## Which branch should you target?

The active branches are **`2.4`** and **`2.5`** (the current release lines) and **`master`** (the next release line, currently 2.6). Older lines — `1.x`, `2.1`, `2.2`, `2.3` — receive no new work.

- **Bug fixes** go to the release line the bug affects, usually `2.5`.
- **Minor, backwards-compatible improvements** go to the same release line.
- **Major features and breaking changes** go to `master`.

Fork the [repository](https://github.com/bagisto/bagisto), branch from the line you are targeting, and open the pull request against that same branch. There is no `development` branch. Keep each fix on its own branch, named for the issue — for example `issue-1234`.

Write commit subjects as [Conventional Commits](https://www.conventionalcommits.org/), which is what the repository history uses: `fix: <what changed>` for a bug fix, `feat: <what it adds>` for a feature.

## Core development ideas and discussion

If you propose a new feature, implement at least enough of it to show the shape of the solution. Informal discussion of bugs, new features and the implementation of existing ones happens in the comments of the issue.

## Compiled assets

Do not commit compiled files. Frontend sources live in `packages/Webkul/<Package>/src/Resources/assets/`, and the build output goes to `public/themes/*/*/build/`.

Compiled bundles are large, cannot realistically be reviewed, and would be an easy way to slip malicious code into Bagisto. Maintainers generate and commit them.

Build from within the package you changed, never from the repository root:

```bash
cd packages/Webkul/Admin && npm install && npm run build   # or Shop, or Installer
```

## Code style and conventions

Bagisto's conventions live in [`AGENTS.md`](AGENTS.md) — the single source of truth for contributors and coding agents alike, covering code style, the repository rule, database portability across MySQL, MariaDB and PostgreSQL, and the naming conventions. Read its **Code Style** section before writing PHP or Blade.

[Laravel Pint](https://laravel.com/docs/pint) with the `laravel` preset settles the mechanical questions, configured in `pint.json`. Run `vendor/bin/pint --test` before opening a pull request; CI runs the same form. Three things no tool checks for you:

- **Pint does not format `.blade.php`.** Blade style is applied by hand.
- **`@param` takes two spaces either side of the type** — `@param  string|array  $loader`. Type information belongs in the signature wherever the language can express it; `@param` and `@return` are for what a native type cannot say, such as the shape of an array.
- **A *new* locale needs its `vee-validate` messages** in the Admin, Shop and Installer builds, which `php artisan bagisto:translations:check` cannot see.

## Before you open the pull request

Run the gates listed in [`AGENTS.md`](AGENTS.md#before-marking-complete) — Pint, Pest, the translation check, and the Playwright suite of any package you touched. In CI, Playwright runs on a pull request only once it carries the **Need Playwright Testing** label, and again on every push while the label stays.

Fill in the [pull request template](.github/PULL_REQUEST_TEMPLATE.md), and say in the description which gates you ran, and which you skipped and why.
