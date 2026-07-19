# Rogue

Rogue is a Serbian news portal built on WordPress. This repo holds two versions of its theme, side by side, because they are the same project at two points in its life rather than two separate projects.

Both are child themes of [OceanWP](https://oceanwp.org/). Only one can be active at a time.

## The two versions

| Initial | Editorial |
| --- | --- |
| ![Rogue Initial](screenshots/RØGUE0.png) | ![Rogue Editorial](screenshots/RØGUE1.png) |

**Rogue - Initial** (`wp-content/themes/rogue-initial`)

The first take. Purple and pink palette, rounded pill buttons, colored shadows, a custom cursor that replaces the mouse pointer, and emoji in the section labels. It worked, but it looked like a template demo rather than a news site.

**Rogue - Editorial** (`wp-content/themes/rogue-editorial`)

The redesign. Serif headlines (Newsreader) with Inter for UI text, an ink and paper palette with a single red accent, hairline rules instead of shadows, and no gradients anywhere. The custom cursor and emoji are gone. Article pages use serif body text with a drop cap, and posts without a featured image get a clean light header instead of a dark empty hero.

## Repo layout

```
wp-content/themes/
  rogue-initial/      the original design
  rogue-editorial/    the editorial redesign
screenshots/          homepage captures of both versions
```

WordPress core is not included. These are themes, not a full site, so there is nothing here you do not own.

## Requirements

- WordPress 5.6 or newer
- The [OceanWP](https://oceanwp.org/) theme installed (it is the parent, both child themes need it)

## Install

1. Install the OceanWP theme first. It only needs to be installed, not active.
2. Copy one of the theme folders into `wp-content/themes/` on your site.
3. In wp-admin go to Appearance, then Themes, and activate either **Rogue - Initial** or **Rogue - Editorial**.

The theme creates the news categories (Novosti, Mišljenja, Sport, Kultura, Zabava) and a primary menu on the first admin visit. If the site has no posts yet, visit `?seed_demo=1` while logged in to load demo content, and `?seed_demo=clear` to remove it.

## Notes

- Frontend strings are in Serbian, translated in `functions.php` since the theme has no language files.
- Both versions share the same templates and class names. The difference between them is almost entirely `assets/css/news.css`, `assets/js/news.js`, and the font loading in `functions.php`, which makes the two folders easy to diff if you want to see exactly what the redesign changed.
