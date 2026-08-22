## How to contribute to Bagisto

### **Before you start working**

**Comment on the issue and wait to be assigned before you write any code.**

Several people often pick up the same issue, and the duplicated work is only
discovered when the second pull request arrives. A short comment — "I'd like to
take this" — is enough, and it saves someone else an evening.

Check the issue first for an assignee or for someone who has already said they
are on it. If it is already claimed and there has been no progress for a while,
ask in the thread rather than opening a competing pull request.

The same applies to a feature: agree the approach in the issue before building
it. A feature that arrives as a surprise pull request is far more likely to be
rejected on direction rather than on code.

### **Bug reporting**

1. Verify that the bug was not already reported by searching the
   [Issues section](https://github.com/bagisto/bagisto/issues).
   If you cannot find an open issue,
   [open a new one](https://github.com/bagisto/bagisto/issues/new/choose).

2. Verify that the bug is a general issue and not specific to your own setup.
   For individual issues please use the [Community Forum](https://forums.bagisto.com/).

3. Include a title, a clear description, the version you are on, and the steps
   to reproduce. A bug report is only as useful as it is reproducible.

### **Did you fix a bug?**

1. Fork the [Bagisto repository](https://github.com/bagisto/bagisto), make your
   changes, and open a
   [pull request](https://help.github.com/articles/about-pull-requests/).

2. Branch from the release line you are targeting, and open the pull request
   against that same branch. The active lines are `2.4` and `master`; there is
   no `development` branch.

3. Keep each fix on its own branch, named for the issue — for example
   `issue-1234`.

4. Write the commit message as `fix: <what changed>`, following
   [Conventional Commits](https://www.conventionalcommits.org/), which is what
   the repository history uses.

5. Follow the pull request
   [template](https://github.com/bagisto/bagisto/blob/2.4/.github/PULL_REQUEST_TEMPLATE.md).

### **Did you create a new feature or enhancement?**

1. Open a [feature request](https://github.com/bagisto/bagisto/issues/new/choose)
   first if one does not already exist, and agree the approach there.

2. Fork the repository and set up your development environment.

3. Use `feat:` for the commit subject, and keep the message descriptive.

4. Follow the pull request
   [template](https://github.com/bagisto/bagisto/blob/2.4/.github/PULL_REQUEST_TEMPLATE.md).

### **Before you open the pull request**

```bash
vendor/bin/pint --test                       # code style
vendor/bin/pest                              # tests
php artisan bagisto:translations:check       # all 22 locales, if you touched any
```

Say in the description which of these you ran, and which you skipped and why.
