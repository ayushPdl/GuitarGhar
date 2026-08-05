# GuitarGhar — Bug Fix & Responsive UI Report

**Branch:** `Ayush`  
**Commit:** `054b0a9` — *Fix functional bugs and make UI responsive across devices.*  
**Date:** 5 August 2026  

This document lists what was broken, what was fixed, and how to verify each change.  
It is for local reference. Do **not** treat this file as something that must be pushed unless you choose to.

---

## Summary

GuitarGhar had broken redirects, a non-working mobile auth menu, missing homepage responsive CSS, lesson progress that did not save, tuner JS crashes for guests, unsafe build saves, and a hardcoded API key in source. Those issues are fixed, and the main pages now adapt to tablet and phone widths.

---

## Critical / Functional Fixes

### 1. Broken redirects
| File | Problem | Fix |
|------|---------|-----|
| `logout.php` | Redirected to `/Demo/index.php` | Redirects to `/guitarghar/index.php` |
| `my-designs.php` | Auth redirect used `/Demguitargharo/login.php` | Redirects to `/guitarghar/login.php` |

**Logout** now accepts **POST only** (GET just sends you home), which matches the navbar/footer logout forms and reduces accidental/CSRF-style logout via a simple link.

---

### 2. Login & register — “headers already sent”
| File | Problem | Fix |
|------|---------|-----|
| `login.php` | `navbar.php` rendered HTML **before** `header("Location: ...")` on success | Auth + redirect run **before** any HTML |
| `register.php` | Same pattern / unsafe `$_POST` access | Auth logic first; safe `isset()` for form fields |

Successful login now redirects reliably to the homepage.

---

### 3. Mobile navigation — Login / Register unreachable
| File | Problem | Fix |
|------|---------|-----|
| `includes/navbar.php` | Hamburger opened `#navbar`, but Login/Register lived in unused `#mobile-menu` | Login / Register / My Designs / Logout are inside the slide-out drawer |
| `css/style.css` | Dead `#mobile-menu` styles; no overlay | Overlay (`#nav-overlay`), body scroll lock, Escape to close |

On viewports ≤799px, open the hamburger menu to reach Login and Register.

---

### 4. Lessons — progress never saved + invalid HTML
| File | Problem | Fix |
|------|---------|-----|
| `lessons.php` | Nested `<html>` (page + navbar); progress only updated the DOM | Single document via navbar include; progress saved via AJAX |
| `save_progress.php` | *(new)* | Saves/clears progress using schema `lesson_id` values (`beg-01`, etc.) |
| `css/lessons.css` | Orphaned / unused dark styles | Wired as the lessons stylesheet with responsive rules |

Progress now uses the VARCHAR `lesson_id` column (not numeric `id`), matching `database/guitarghar.sql`.

---

### 5. Guitar tuner
| File | Problem | Fix |
|------|---------|-----|
| `js/tuner.js` | Guest view missing meter DOM → `TypeError` on tuning clicks | Null-safe `setText` / `setStyle` / `setClass` helpers |
| `js/tuner.js` | Flat/sharp feedback used cents vs **closest** note, not selected string | Cents calculated vs **target** string when selected |
| `js/tuner.js` | `showLoginModal()` looked for missing `#loginModal` | Redirects to `/guitarghar/login.php` |

---

### 6. Builder saves & My Designs
| File | Problem | Fix |
|------|---------|-----|
| `save_build.php` | No validation; raw `$_POST` could break JSON / store bad data | Whitelist shapes, woods, hardware; hex color `#RRGGBB` |
| `delete_build.php` | Could leak `mysqli_error()` to the client | Generic error messages only |
| `my-designs.php` | Unescaped `shape` (XSS risk); labels like “Lespaul” | `htmlspecialchars` + display names (Stratocaster, Les Paul, …) |
| `builder.php` | `builder.js` loaded after `</html>` | Script loads **before** footer |

---

### 7. Recommender security & reliability
| File | Problem | Fix |
|------|---------|-----|
| `recommender_api.php` | OpenRouter API key hardcoded in repo | Key loaded from `includes/config.php` or `OPENROUTER_API_KEY` env |
| `recommender_api.php` | No login check | Requires logged-in session |
| `recommender.php` | AI text via `innerHTML` (XSS); mojibake “Sorry!” | Safe `textContent` / DOM nodes |
| `includes/config.example.php` | *(new)* | Template for local API key setup |
| `.gitignore` | — | Ignores `includes/config.php` (secrets stay local) |

Successful recommendations are also written to the `recommendations` table when the DB is available.

**Local setup for AI recommender:**
```text
copy includes\config.example.php includes\config.php
```
Then put your OpenRouter key in `includes/config.php`.

---

### 8. Footer
| File | Problem | Fix |
|------|---------|-----|
| `includes/footer.php` | Links to missing `faq.php` / `contact.php` | Replaced with Help & Lessons; logout uses POST form |

---

## Responsive UI Fixes

### Homepage (`css/index.css`)
Previously had **no** `@media` rules. Added breakpoints at **1024 / 799 / 480**:

- Smaller hero padding and title sizes on mobile  
- Feature grid → **1 column** on phones  
- How-it-works arrows hidden; steps stack vertically  
- CTA / buttons stack full-width on small screens  

### Global / shared (`css/style.css`)
- Mobile drawer + overlay  
- Smaller navbar/footer logos  
- `overflow-x: hidden` on body to reduce horizontal scroll  

### Other pages
| Stylesheet | Changes |
|------------|---------|
| `css/recommender.css` | Header padding / title size on tablet & phone |
| `css/builder.css` | Shape grid less crowded on small screens |
| `css/my-designs.css` | Single-column cards; tighter padding under 480px |
| `css/login.css` / `css/register.css` | Smaller side guitar image on mobile |
| `css/lessons.css` | Tabs scroll; grid → 1 column under 900px |

### Marketing copy
- Homepage lesson count updated **17 → 30** to match the database.

---

## New / updated files

| Path | Role |
|------|------|
| `save_progress.php` | AJAX endpoint for lesson progress |
| `includes/config.example.php` | Example API key config (safe to commit) |
| `includes/config.php` | Local secrets only (**gitignored**, do not commit) |
| `FIXES.md` | This report |

---

## How to test (checklist)

- [ ] **Logout** → lands on GuitarGhar home (not `/Demo/`)
- [ ] Guest opens **My Designs** → redirected to login (not 404)
- [ ] Phone width: hamburger shows **Login** and **Register**
- [ ] Login with valid account → redirect to home works
- [ ] Lessons: Mark as Done → reload → still completed
- [ ] Tuner as guest: changing tuning does not throw console errors
- [ ] Tuner logged in: select a string, play it → flat/sharp vs that string
- [ ] Builder: save a build → appears on My Designs with correct shape name
- [ ] Homepage: resize to phone → features stack, hero readable
- [ ] Recommender (logged in): needs `includes/config.php` with a valid key

---

## Notes

1. Image assets under `/guitarghar/img/` must exist on the server (logo, hero, guitar shapes). Missing images are an asset/deploy issue, not covered by these code fixes.  
2. Rotate the OpenRouter key if it was ever committed in older history.  
3. On case-sensitive hosts (Linux), keep the URL path `/guitarghar/` aligned with the folder name.

---

## Commit reference

```
054b0a9 Fix functional bugs and make UI responsive across devices.
```

Corrected broken redirects, mobile auth nav, login/register header issues, lesson progress persistence, tuner target tuning, build validation/XSS, and moved the OpenRouter key out of tracked source.
