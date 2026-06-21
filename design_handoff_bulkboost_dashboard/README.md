# Handoff: BulkBoost — Design Settings Dashboard

## Overview
A redesigned admin settings page for **BulkBoost**, a WordPress quantity-discount plugin. The page lets a merchant style the quantity-pricing widget that renders on a product page. It has a left sidebar nav, a content panel with three tabs (**Design Settings**, **Typographic**, **Badge**), and a persistent **live preview** rail on the right that reflects every control change in real time.

## About the Design Files
The file in this bundle (`BulkBoost Dashboard.dc.html`) is a **design reference created in HTML** — a working prototype showing the intended look and behavior. It is **not production code to copy directly**. The template uses a small custom runtime (`{{ }}` holes, `<sc-if>`, `<sc-for>`, a `Component` logic class). Ignore that runtime: treat the markup, inline styles, and the logic in `renderVals()` purely as a **spec**.

**Your task:** recreate this design in the target codebase's existing environment using its established patterns. For a WordPress plugin this typically means:
- A PHP-rendered admin page (`add_menu_page` / `add_submenu_page`) with the markup, **or**
- A React/Vue app mounted into the admin page (WordPress ships React as `@wordpress/element`).

Persist settings via the WordPress Settings API / `update_option` (or REST). If no front-end environment exists yet, plain PHP + a small vanilla-JS or `@wordpress/element` island is the most idiomatic choice. Reuse any existing component library / design tokens the plugin already has rather than hard-coding these values a second time.

## Fidelity
**High-fidelity (hifi).** Final colors, typography, spacing, radii, and interactions are all specified below and should be reproduced precisely. The one exception: the three nav icons and the brand mark are simple inline SVGs / CSS shapes — swap for the codebase's existing icon set if it has one.

---

## Screens / Views

This is a **single page** with three tab states. Shared chrome (sidebar + header + preview rail) stays mounted; only the center content panel swaps per tab.

### Global layout
- Root: `display:flex`, `min-height:100vh`, `background:#f3f3f0`, text color `#1b1c18`, font `'Plus Jakarta Sans', system-ui, sans-serif`.
- **Sidebar** (left): fixed `width:248px`, `flex:none`, `background:#fbfbf9`, `border-right:1px solid #ecebe5`, `position:sticky; top:0; height:100vh`, padding `20px 16px`, vertical flex with `gap:24px`.
- **Main** (right): `flex:1; min-width:0`, vertical flex. Contains a sticky header and the body.
- **Body**: `display:flex; gap:32px; align-items:flex-start; padding:28px 32px 56px`. Left = content `<section>` (`flex:1; min-width:0; max-width:700px`). Right = preview `<aside>` (`width:380px; flex:none; position:sticky; top:104px`).

### Sidebar components
- **Brand row**: `gap:11px`. Logo mark = `34×34`, `border-radius:9px`, `background:#10976a`, containing three white bars (a "boost" bar chart) aligned to the bottom — widths `4px`, heights `8 / 12 / 17px`, `border-radius:1.5px`, opacities `.55 / .8 / 1`. Next to it: "BulkBoost" (`15px / 700 / letter-spacing:-.01em`) over "Quantity discounts" (`11px / 500`, `#9a9c91`).
- **Nav section label**: "Settings", `11px / 700`, uppercase, `letter-spacing:.07em`, color `#b4b6aa`.
- **Nav items** (Design Settings, Typographic, Badge): each `display:flex; align-items:center; gap:11px; padding:10px 12px; border-radius:9px; font-size:14px; cursor:pointer`. Icon `18×18`, `stroke:currentColor`.
  - **Inactive**: `font-weight:500`, color `#6b6d63`, transparent background, `border:1px solid transparent`.
  - **Active**: `font-weight:600`, color `#1b1c18`, `background:#ffffff`, `box-shadow:0 1px 2px rgba(20,20,15,.06)`, `border:1px solid #ecebe5`.
  - Icons: Design = sliders (3 lines + knobs); Typographic = an "A" glyph with crossbar; Badge = a tag outline. Replace with the host icon set if available.
- **"Pro tip" card** (bottom, `margin-top:auto`): `background:#f3f3ee; border:1px solid #ecebe5; border-radius:12px; padding:14px`. Title "Pro tip" (`12.5px / 600`), body "Edit any field and watch the live preview update instantly." (`12px`, `#8a8c81`, `line-height:1.45`).

### Header (sticky, top of main)
- `position:sticky; top:0; z-index:5; background:rgba(243,243,240,.82); backdrop-filter:blur(10px); border-bottom:1px solid #ecebe5; padding:16px 32px; display:flex; justify-content:space-between; align-items:center`.
- **Left**: breadcrumb "BulkBoost / Settings" (`12px / 500`, `#a0a296`) over title "Design Settings" (`18px / 700 / letter-spacing:-.01em`).
- **Right** (`gap:18px`):
  - **Save status** — `13px`, `gap:7px`, with a `7×7` dot. Saved: text "All changes saved" `#8a8c81`, dot `#10976a`. Dirty: text "Unsaved changes" `#b9821b`, dot `#d99a14`.
  - **Discard** button: `height:38px; padding:0 16px; border:1px solid #e0dfd8; border-radius:9px; background:#fff; color:#5a5c52; font-size:13.5px; font-weight:600`. Resets all fields to defaults.
  - **Save Changes** button: `height:38px; padding:0 20px; border-radius:9px; background:#10976a; color:#fff; font-size:13.5px; font-weight:600; box-shadow:0 2px 8px -2px rgba(16,151,106,.5)`. Persists settings; sets status to saved.

### Content cards — shared pattern
Each settings group is a card: `background:#fff; border:1px solid #ecebe5; border-radius:16px; padding:6px 22px 16px; box-shadow:0 1px 2px rgba(20,20,15,.04); margin-bottom:20px`.
- **Card section label**: `12px / 700`, uppercase, `letter-spacing:.07em`, color `#a0a296`, padding `18px 0 6px`.
- **Setting row**: `display:flex; align-items:center; justify-content:space-between; gap:24px; padding:16–18px 0; border-top:1px solid #f0efe9`. Left = name (`14px / 600`) + optional helper (`12.5px`, `#8a8c81`, `margin-top:2px`). Right = the control.
- **Tab intro** (above the cards): `<h2>` `19px / 700 / -.01em` + `<p>` `13.5px`, `#8a8c81`, `max-width:520px`.

### Tab 1 — Design Settings
Intro: "Style the offer cards — colors, corners, borders and the selector control."
- **Card "Colors"** — four swatch rows:
  - Active background — swatches `#16231d, #1c1c22, #21303a, #2a1f2e`. Helper "Fill of the selected offer".
  - Active text — `#ffffff, #1b1c18`. Helper "Text color inside the selected offer".
  - Accent — `#10976a, #4f5bd5, #e8643c, #c2870e`. Helper "Selected ring & radio fill".
  - Inactive border — `#e6e5df, #cfd0c8, #10976a`. Helper "Outline of unselected offers".
- **Card "Shape & spacing"**:
  - Corner radius — slider, `min 0, max 24, step 1` (px). Default `14`.
  - Border width — slider, `min 0, max 4, step 0.5` (px). Default `1.5`.
  - Card spacing — slider, `min 4, max 24, step 1` (px). Default `12`. (Gap between preview offer cards.)
  - Selector style — segmented control: Radio / Checkbox / None. Default `Radio`.

### Tab 2 — Typographic (default tab)
Intro: "Set the weight and size of each text element in the offer cards."
- **Card "Text styles"** — rows for **Label**, **Description**, **Price**. Each row has two controls:
  - **Weight** select (`width:130px; height:38px`), options: Light `300`, Normal `400`, Medium `500`, Semibold `600`, Bold `700`.
  - **Size** stepper — a `94×38` bordered box containing a `number` input (mono font) + a "px" suffix (`11px`, `#a0a296`, mono).
  - Each control sits under an uppercase mini-label ("Weight" / "Size", `11px / 600`, `#9a9c91`, `letter-spacing:.05em`).
  - Helpers: Label "The offer title"; Description "Supporting line under the label"; Price "The current price".
- **Card "Old price"** (separate card):
  - Header row: name "Old price" + helper "Strikethrough compare-at price", with a **Show** toggle on the right (label "Show", `12.5px`, `#8a8c81`).
  - Below: "Old price style" row with Weight + Size controls (same pattern). When Show is off, this sub-block is dimmed `opacity:.4` and `pointer-events:none`.

Field defaults: Label `600 / 17px`; Description `400 / 13px`; Price `700 / 18px`; Old price `400 / 13px`, shown.

### Tab 3 — Badge
Intro: "Highlight an offer with a promotional badge."
- **Card** with an **Enable badge** toggle header (helper "Show a badge on a highlighted offer"). When disabled, all rows below are dimmed `opacity:.4` + `pointer-events:none`.
  - **Badge text** — text input (`width:240px; height:38px`). Default "MOST POPULAR".
  - **Background** — swatches `#10976a, #4f5bd5, #e8643c, #1b1c18`. Default `#10976a` (follows accent theme).
  - **Text color** — swatches `#ffffff, #1b1c18`. Default `#ffffff`.
  - **Position** — segmented: Left / Right / Ribbon. Default `Right`.
  - **Show on** — segmented: Active / All / Best value. Default `Active`.

---

## Form controls — exact specs

**Swatch**: `28×28; border-radius:8px; background:<color>; cursor:pointer`.
- Unselected: `box-shadow: inset 0 0 0 1px rgba(0,0,0,.14)`.
- Selected: `box-shadow: 0 0 0 2px #fff, 0 0 0 4px #1b1c18` (a dark ring with white gap).
- `transition: box-shadow .15s`.

**Segmented control**: track `display:flex; gap:3px; background:#f0efe9; padding:3px; border-radius:9px; width:264px`. Each option `flex:1; text-align:center; padding:7px 6px; font-size:12.5px; font-weight:600; border-radius:7px; cursor:pointer`. Active option: `color:#fff; background:<accent>`. Inactive: `color:#6b6d63; transparent`. `transition: all .15s`.

**Toggle switch**: track `42×24; border-radius:999px; position:relative; cursor:pointer`. Off `background:#d6d5cd`; on `background:<accent>`. Knob `18×18; border-radius:50%; background:#fff; position:absolute; top:3px; box-shadow:0 1px 3px rgba(0,0,0,.25)`; `left:3px` off → `left:21px` on. `transition: left .18s` (and `background .18s` on track).

**Select**: `height:38px; border:1px solid #e0dfd8; border-radius:9px; padding:0 32px 0 12px; font-size:13px; background:#fff`. Custom chevron via SVG `background-image` at `right 11px center` (`appearance:none`).

**Number stepper**: outer `94×38` box `border:1px solid #e0dfd8; border-radius:9px; overflow:hidden`; inner `number` input borderless, mono font; trailing "px" label.

**Range slider**: track `height:4px; border-radius:999px; background:#e2e1da`. Thumb `16×16; border-radius:50%; background:#fff; border:1px solid #cdccc3; box-shadow:0 1px 3px rgba(20,20,15,.2)`. `accent-color` set to the chosen accent. A mono value label (e.g. "14px") sits to the right, `width:44px; text-align:right; color:#6b6d63`.

**Buttons**: see Header. Primary = solid accent; secondary = white with `#e0dfd8` border.

---

## Live preview rail (right column)
A mock storefront product card that re-renders on every state change.
- Label: "Live preview" (`12px / 700`, uppercase, `#a0a296`) + a `6×6` `#10976a` dot.
- Card: `background:#fff; border:1px solid #ecebe5; border-radius:18px; padding:22px; box-shadow:0 16px 36px -22px rgba(20,20,15,.4)`.
- **Product header**: `64×64` image placeholder (`border-radius:13px`, diagonal striped `repeating-linear-gradient(45deg,#efeee8,#efeee8 6px,#e6e5dd 6px,#e6e5dd 12px)`) + name (`15px / 600`) over subtitle (`12.5px`, `#8a8c81`). Use a real product image in production.
- **Offer list**: vertical flex, `gap` = the "Card spacing" setting. Three offers (sample data):
  | Label | Description | Price | Old price |
  |---|---|---|---|
  | 1 item | Get 1 item and enjoy our product | $100 | $120 |
  | 2 items | Get 2 for a better price — only today | $150 | $200 |
  | 3 items | Best value — stock up and save big | $210 | $300 |
- **Offer card**: `position:relative; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:15px 17px; cursor:pointer`. `border-radius` = radius setting. Border = `<borderW>px solid <accent if selected, else inactiveBorder>`. Background = `activeBg` if selected, else `#fff`. Selected box-shadow `0 10px 26px -14px rgba(accent,.55)`; unselected `0 1px 2px rgba(20,20,15,.04)`. `transition: all .18s`.
  - **Left**: selector indicator + text block (`gap:13px`). Indicator `20×20; border:2px solid`; `border-radius:50%` for radio / `6px` for checkbox / `display:none` for none. Border color `activeText` when selected, else `#cfd0c8`. Inner dot `10×10`, `background:activeText`, scales `0→1` when selected (`transition: transform .15s`).
  - **Text**: Label uses the Label weight/size + `activeText` when selected else `#1b1c18` (ellipsis-truncated). Description uses Desc weight/size + `rgba(activeText,.68)` selected / `#7a7c71` else, `margin-top:3px; line-height:1.3`.
  - **Right** (`text-align:right`): old price (if shown) `line-through`, color `rgba(activeText,.5)` selected / `#a6a89d` else, `margin-bottom:2px`; then price using Price weight/size + `activeText` selected / `#1b1c18` else.
  - **Badge** (when enabled & this offer matches "Show on"): absolute pill, `font-size:10px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; background:badgeBg; color:badgeColor; box-shadow:0 3px 8px -2px rgba(0,0,0,.3)`.
    - Right: `top:-9px; right:16px; padding:4px 9px; border-radius:999px`.
    - Left: `top:-9px; left:16px; padding:4px 9px; border-radius:999px`.
    - Ribbon: `top:0; left:0; padding:4px 12px; border-radius:<radius>px 0 10px 0`.
- **Add to cart** button (bottom): `width:100%; height:46px; border-radius:12px; background:#1b1c18; color:#fff; font-size:14px; font-weight:600; margin-top:18px`.

---

## Interactions & Behavior
- **Tab nav**: clicking a sidebar item switches the active content panel. Default tab on load = **Typographic**.
- **Live binding**: every control writes to settings state; the preview recomputes immediately. No "apply" step inside the page — changes are visible instantly.
- **Selecting an offer**: clicking any offer card in the preview makes it the selected/active one (drives the active styling). Default selected = index 1 (the "2 items" offer).
- **Dirty tracking**: any edit flips status to "Unsaved changes" (amber). **Save** persists and returns to "All changes saved" (green). **Discard** resets every field to its default and marks saved.
- **Conditional dimming**: Old-price sub-fields dim when "Show" is off; all badge fields dim when "Enable badge" is off (`opacity:.4; pointer-events:none`).
- **Transitions**: swatches/segments/toggles `~.15–.18s`; offer cards `all .18s ease`; toggle knob `left .18s`; radio inner dot `transform .15s`.

## State Management
All settings live in one object (defaults shown). In production, hydrate from the saved option on load and persist on Save.

| Key | Default | Notes |
|---|---|---|
| `tab` | `typography` | UI only, not persisted |
| `selected` | `1` | active offer index in preview |
| `saved` | `true` | dirty flag |
| `labelWeight` / `labelSize` | `600` / `17` | |
| `descWeight` / `descSize` | `400` / `13` | |
| `priceWeight` / `priceSize` | `700` / `18` | |
| `showOld` | `true` | |
| `oldWeight` / `oldSize` | `400` / `13` | |
| `activeBg` / `activeText` | `#16231d` / `#ffffff` | |
| `accent` | `#10976a` | drives ring, radio fill, segments, toggles, sliders |
| `inactiveBorder` | `#e6e5df` | |
| `radius` | `14` | px |
| `borderW` | `1.5` | px |
| `gap` | `12` | px |
| `selector` | `radio` | `radio` \| `checkbox` \| `none` |
| `badgeOn` | `true` | |
| `badgeText` | `MOST POPULAR` | |
| `badgeBg` / `badgeColor` | `#10976a` / `#ffffff` | |
| `badgePos` | `right` | `left` \| `right` \| `ribbon` |
| `badgeTarget` | `active` | `active` \| `all` \| `best` |

**Theming props** (set at mount; the prototype exposes these as `accentTheme` and `previewProduct`):
- `accentTheme`: Emerald `#10976a` (default) / Indigo `#4f5bd5` / Coral `#e8643c` / Amber `#c2870e`. Seeds `accent` and `badgeBg`.
- `previewProduct`: changes only the preview product name/subtitle — Wireless Earbuds → "Premium Wireless Earbuds / Choose your bundle"; Skincare Serum → "Radiance Glow Serum / Pick your supply"; Coffee Beans → "Single-Origin Coffee / Select your stock".

## Design Tokens

**Colors**
- App background `#f3f3f0`; sidebar/surface tint `#fbfbf9`; card surface `#ffffff`; tip card `#f3f3ee`; segment/track `#f0efe9`.
- Text: primary `#1b1c18`; secondary `#5a5c52` / `#6b6d63`; muted `#7a7c71` / `#8a8c81`; faint `#9a9c91` / `#a0a296` / `#a6a89d`; faintest `#b4b6aa`.
- Borders: `#ecebe5` (card), `#f0efe9` (row divider), `#e0dfd8` (input), `#e2e1da` (slider track), `#cfd0c8` / `#cdccc3`.
- Brand / accent options: `#10976a` (Emerald, default), `#4f5bd5` (Indigo), `#e8643c` (Coral), `#c2870e` (Amber).
- Status: saved `#10976a`; unsaved text `#b9821b`, dot `#d99a14`.
- Active-card fills: `#16231d, #1c1c22, #21303a, #2a1f2e`. Add-to-cart / ink `#1b1c18`.

**Typography** — `'Plus Jakarta Sans'` (UI), `'JetBrains Mono'` (numeric inputs & value labels). Weights 300/400/500/600/700.
- Page title `18px/700`; tab `<h2>` `19px/700`; both `letter-spacing:-.01em`.
- Row name `14px/600`; helper `12.5px`; card section label `12px/700` uppercase `.07em`; mini field label `11px/600` uppercase `.05em`.
- Body/input `13px`; intro paragraph `13.5px`.

**Radii**: inputs/buttons `9px`; cards `16px`; preview card `18px`; tip `12px`; add-to-cart `12px`; pills `999px`.

**Shadows**: card `0 1px 2px rgba(20,20,15,.04)`; nav-active `0 1px 2px rgba(20,20,15,.06)`; primary button `0 2px 8px -2px rgba(16,151,106,.5)`; preview card `0 16px 36px -22px rgba(20,20,15,.4)`; selected offer `0 10px 26px -14px rgba(accent,.55)`; badge `0 3px 8px -2px rgba(0,0,0,.3)`.

**Spacing**: body gap `32px`; card padding `6px 22px 16px`; row padding `16–18px 0`; header padding `16px 32px`; body padding `28px 32px 56px`.

## Assets
- **Fonts**: Plus Jakarta Sans + JetBrains Mono (Google Fonts). In WordPress, enqueue locally or via `wp_enqueue_style` rather than a remote `<link>` for admin pages.
- **Icons**: 3 inline SVG nav icons (sliders / "A" glyph / tag) + the chevron in selects — all simple, swap for the host icon set (e.g. Dashicons / `@wordpress/icons`) if available.
- **Brand mark**: pure CSS (3 bars in a rounded square) — no image file.
- **Preview product image**: a CSS striped placeholder; supply the real product thumbnail in production.

## Files
- `BulkBoost Dashboard.dc.html` — the full interactive design reference (markup + inline styles + a `Component` logic class whose `renderVals()` contains the exact style/derivation logic for the preview). Read this alongside the README; every value above is reflected there.
