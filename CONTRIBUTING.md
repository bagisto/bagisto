# Bagisto Contribution Guide

## Before you start working

**Comment on the issue and wait to be assigned before you write any code.**

Several people often pick up the same issue, and the duplicated work is only
discovered when the second pull request arrives. A short comment — "I'd like to
take this" — is enough, and it saves someone else an evening.

Check the issue first for an assignee, or for someone who has already said they
are on it. If it is claimed but has gone quiet, ask in the thread rather than
opening a competing pull request.

For a feature, agree the approach in the issue **before** building it. A feature
that arrives as a surprise pull request is far more likely to be turned down on
direction than on code.

## Bugs

Bagisto encourages pull requests, not just bug reports — a bug report may itself
take the form of a pull request containing a failing test.

A bug report needs a title, a clear description, the version you are on, and
enough detail to reproduce it, including a code sample where one helps. The goal
is to let someone else replicate the bug and build a fix.

## Which branch should you target?

The active branches are **`2.4`** (the current release line) and **`master`**
(the next major). Older lines — `1.x`, `2.1`, `2.2`, `2.3` — receive no new work.

- **Bug fixes** go to the release line the bug affects, usually `2.4`.
- **Minor, backwards-compatible improvements** go to the same release line.
- **Major features and breaking changes** go to `master`.

Branch from the line you are targeting and open the pull request against that
same branch.

## Core development ideas and discussion

If you propose a new feature, implement at least enough of it to show the shape
of the solution. Informal discussion of bugs, new features and the implementation
of existing ones happens in the comments of the issue.

## Compiled assets

Do not commit compiled files. Frontend sources live in
`packages/Webkul/<Package>/src/Resources/assets/`, and the build output goes to
`public/themes/*/build/`.

Compiled bundles are large, cannot realistically be reviewed, and would be an
easy way to slip malicious code into Bagisto. Maintainers generate and commit
them.

Build from within the package you changed, never from the repository root:

```bash
cd packages/Webkul/Admin && npm install && npm run build   # or Shop, or Installer
```

## Code style

Bagisto uses **[Laravel Pint](https://laravel.com/docs/pint)** with the `laravel`
preset, configured in `pint.json`. Run it before opening a pull request — CI runs
the same check:

```bash
vendor/bin/pint          # fix
vendor/bin/pint --test   # confirm; this is the form CI uses
```

Pint does not format `.blade.php`. Blade style is applied by hand.

Some rules Pint cannot enforce, and a reviewer will ask for:

- Every method and property carries a docblock, whatever its visibility.
- Class members run constants → properties → constructor → public → protected →
  private.
- **No comments inside method bodies**, in PHP, Blade, JS or Vue. If a line needs
  prose to be understood, extract a named method instead.
- All database access goes through a repository; the one exception is a
  DataGrid's `prepareQueryBuilder()`.
- Every user-facing string goes through `trans()`, with the key added to all
  **22** locales.

## PHPDoc

A valid Bagisto doc block. Note that `@param` is followed by two spaces, the
type, two more spaces, then the variable name:

```php
/**
 * Register a service with CoreServiceProvider.
 *
 * @param  string|array  $loader
 * @param  \Closure|string|null  $concrete
 * @param  bool  $shared
 * @return void
 *
 * @throws \Exception
 */
protected function registerFacades($loader, $concrete = null, $shared = false)
{
    //
}
```

Type information belongs in the signature where the language can express it —
add `@param` and `@return` for what a native type cannot say, such as the shape
of an array.

## Before you open the pull request

```bash
vendor/bin/pint --test                       # code style
vendor/bin/pest                              # tests
php artisan bagisto:translations:check       # all 22 locales, if you touched any
```

End-to-end tests run per package, from that package's directory:

```bash
cd packages/Webkul/Admin && npx playwright test --config=tests/e2e-pw/playwright.config.ts
```

Say in the description which of these you ran, and which you skipped and why.
