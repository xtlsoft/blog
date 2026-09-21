# blog.xtlsoft.top

Source for <https://blog.xtlsoft.top/>.

The site is currently **under construction**. The root of the domain serves a
single holding page; the entire previous blog has been archived and is still
reachable, unchanged in content, under `/old/`.

## Layout

| Path | Published at | Purpose |
| --- | --- | --- |
| `site/` | `/` | The under-construction holding page |
| `old/` | `/old/` | The previous blog, frozen as static output |
| `old/src/` | — | Original PHP + Markdown source of the previous blog (not published) |

`old/` is a frozen snapshot of what the PHP generator used to produce. It is
kept as committed output rather than rebuilt, because the original pipeline
targeted PHP 7.2 and its dependency install no longer succeeds.

Every root-absolute path inside `old/` (`href="/…"`, `src="/…"`, `jump('/…')`)
was rewritten to the `/old/` prefix so the archive renders and links correctly
from its new location. External and protocol-relative URLs were left untouched.

## Deploying

`.github/workflows/deploy.yml` assembles `dist/` from `site/` plus `old/`
(excluding `old/src/`) and publishes it to the `gh-pages` branch, which is what
GitHub Pages serves for `blog.xtlsoft.top`. Push to `master` to deploy.

To preview locally:

```sh
mkdir -p /tmp/preview && cp -r site/. /tmp/preview/ && mkdir -p /tmp/preview/old \
  && cp -r old/. /tmp/preview/old/ && rm -rf /tmp/preview/old/src
cd /tmp/preview && python3 -m http.server 8000
```

## History

The previous blog generator (`build.php`, `articles/`, `pages/`, `template/`)
is preserved on the `legacy-php-blog` branch and the `backup-pre-archive-2026`
tag, and in full under `old/src/`.
