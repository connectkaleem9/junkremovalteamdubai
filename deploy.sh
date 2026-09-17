#!/usr/bin/env bash
#
# Deploy junkremovalteamdubai.com
#
#   ./deploy.sh                 commit nothing, just push what is committed and publish
#   ./deploy.sh "message"       commit every change with that message, then push and publish
#   ./deploy.sh -n              dry run: show what would be published, change nothing
#
# How it works: your machine pushes to GitHub, the server pulls from GitHub, then
# public/ is copied into the web root. Images are only transferred when they change.
#
set -euo pipefail

KEY="$HOME/.ssh/junkremovalteamdubai_deploy"
HOST="u232094553@156.67.222.64"
PORT=65002
REMOTE_REPO="\$HOME/site"
REMOTE_WEB="\$HOME/domains/junkremovalteamdubai.com/public_html"
# app/ is published NEXT TO public_html, never inside it, so the PHP that holds
# database credentials and lead handling can never be fetched over HTTP.
REMOTE_APP="\$HOME/domains/junkremovalteamdubai.com/app"
SITE="https://junkremovalteamdubai.com"

bold=$'\033[1m'; green=$'\033[32m'; red=$'\033[31m'; yellow=$'\033[33m'; off=$'\033[0m'
step() { printf '\n%s==> %s%s\n' "$bold" "$1" "$off"; }
ok()   { printf '%s  ok%s  %s\n' "$green" "$off" "$1"; }
warn() { printf '%s  !!%s  %s\n' "$yellow" "$off" "$1"; }
die()  { printf '%s  xx%s  %s\n' "$red" "$off" "$1" >&2; exit 1; }

DRY_RUN=0
MESSAGE=""
case "${1:-}" in
  -n|--dry-run) DRY_RUN=1 ;;
  "") ;;
  *) MESSAGE="$1" ;;
esac

cd "$(dirname "$0")"
[ -f public/index.html ] || [ -f public/index.php ] || die "run this from the project root (public/ not found)"
[ -f "$KEY" ] || die "ssh key not found at $KEY"

SSH="ssh -i $KEY -p $PORT -o BatchMode=yes -o ConnectTimeout=20"

# ---------------------------------------------------------------- 1. commit
step "1/5  Local changes"
if [ -n "$(git status --porcelain)" ]; then
  git status --short
  if [ "$DRY_RUN" = "1" ]; then
    warn "dry run: not committing"
  elif [ -n "$MESSAGE" ]; then
    git add -A
    git commit -q -m "$MESSAGE"
    ok "committed: $MESSAGE"
  else
    die "you have uncommitted changes. Either commit them yourself, or run: ./deploy.sh \"your message\""
  fi
else
  ok "working tree clean"
fi

# ---------------------------------------------------------------- 2. push
step "2/5  Push to GitHub"
if [ "$DRY_RUN" = "1" ]; then
  warn "dry run: not pushing"
else
  git push -q origin main
  ok "pushed $(git rev-parse --short HEAD)"
fi

# ---------------------------------------------------------------- 3. pull on server
step "3/5  Pull on the server"
if [ "$DRY_RUN" = "1" ]; then
  warn "dry run: not touching the server"
else
  $SSH "$HOST" "cd $REMOTE_REPO && git fetch -q origin && git reset -q --hard origin/main && git rev-parse --short HEAD" \
    | sed 's/^/     server now at /'
  ok "server repo updated"

  # Lint every PHP file on the server's own PHP before anything goes live.
  # The web root has not been touched yet, so stopping here is safe.
  lint=$($SSH "$HOST" "cd $REMOTE_REPO && find app public -name '*.php' -print0 | xargs -0 -n1 php -l 2>&1 | grep -v '^No syntax errors'" || true)
  if [ -n "$lint" ]; then
    printf '%s\n' "$lint"
    die "PHP syntax errors - nothing was published"
  fi
  ok "PHP syntax ok"
fi

# ---------------------------------------------------------------- 4. publish
step "4/5  Publish"
if [ "$DRY_RUN" = "1" ]; then
  $SSH "$HOST" "rsync -a --delete --itemize-changes --dry-run $REMOTE_REPO/public/ $REMOTE_WEB/" | sed 's/^/     web  /'
  $SSH "$HOST" "rsync -a --delete --itemize-changes --dry-run $REMOTE_REPO/app/ $REMOTE_APP/" | sed 's/^/     app  /'
  warn "dry run: nothing was published"
else
  $SSH "$HOST" "rsync -a --delete --itemize-changes $REMOTE_REPO/public/ $REMOTE_WEB/" | sed 's/^/     web  /'
  $SSH "$HOST" "mkdir -p $REMOTE_APP && rsync -a --delete --itemize-changes $REMOTE_REPO/app/ $REMOTE_APP/ && chmod 750 $REMOTE_APP" | sed 's/^/     app  /'
  ok "published"
fi

# ---------------------------------------------------------------- 5. verify
step "5/5  Check the live site"
if [ "$DRY_RUN" = "1" ]; then
  warn "dry run: skipped"
else
  for path in "/" "/ar/"; do
    code=$(curl -sS -o /dev/null -w '%{http_code}' -L --max-time 30 "$SITE$path" || echo "000")
    if [ "$code" = "200" ]; then ok "$SITE$path -> $code"; else warn "$SITE$path -> $code"; fi
  done

  # Warn if the stylesheet changed but its ?v= number did not: returning
  # visitors would keep the old CSS and the layout would look broken.
  if git diff --name-only HEAD~1 HEAD 2>/dev/null | grep -q 'assets/css/site.css'; then
    if ! git diff HEAD~1 HEAD -- public/index.html 2>/dev/null | grep -q 'site.css?v='; then
      warn "site.css changed but ?v= was not bumped in index.html — cached visitors will see the old CSS"
    fi
  fi
fi

printf '\n%sDone.%s  %s\n\n' "$bold" "$off" "$SITE"
