# CI

## Actions

### ci-dev

`php ci-dev.php`

Integrates newest commit from repo to dev server.
Takes the latest commit on the master.
Runs the tests.
Builds an artifact.
Sets the candidate for dev.
Deploys onto dev server.

### ci-staging

`php ci-staging.php`

Current dev-deploy artifact marks as prod-candidate.

### ci-prod

`php ci-prod.php`

Deploys prod candidate to prod.

## git mark

Each stage is tagged in the git repository.
In your local project direcotry run `git fetch --tags --force`.
Now check tags in git log.

U can use `git tree` alias for check log with tags.
https://gist.githubusercontent.com/xtompie/6040a2a3fb1202d0f882f0cff85da1ec/raw/26a5a7ca92a9b7babf377e258f7b43eec50fb98d/git-tree.md
