// Builds the "Foundations" and "Components" pages from design-system/tokens/**/*.json,
// copied directly into this file (the Figma Plugin API can't read files from disk).
// Reuses existing local styles by exact name instead of duplicating them, so it's safe
// to run after tokens were already partially set up by hand via Tokens Studio.

// ── Foundations data ──────────────────────────────────────────────────────

const CORE_COLOR = {
  blue: { '50': '#EEF2FF', '100': '#E0E7FF', '200': '#C7D2FE', '300': '#A5B4FC', '500': '#4361EE', '600': '#3451D1' },
  slate: { '50': '#F8FAFC', '100': '#F1F5F9', '200': '#E2E8F0', '300': '#CBD5E1', '400': '#94A3B8', '500': '#64748B', '600': '#475569', '700': '#334155', '800': '#1E293B', '900': '#0F172A' },
  emerald: { '50': '#ECFDF5', '100': '#D1FAE5', '500': '#10B981', '600': '#059669', '700': '#047857' },
  red: { '50': '#FEF2F2', '100': '#FEE2E2', '400': '#ED6873', '500': '#E63946', '600': '#E63946', '700': '#C42430' },
  amber: { '400': '#F4A261' },
  'brand-ink': { '900': '#1A1D2E' },
  neutral: { white: '#FFFFFF', canvas: '#F7F8FA', line: '#E4E7EE' },
};

const SEMANTIC_COLOR = [
  ['bg/canvas', '#F7F8FA'], ['bg/surface', '#FFFFFF'], ['bg/subtle', '#F8FAFC'], ['bg/sidebar', '#1A1D2E'],
  ['border/default', '#E4E7EE'], ['border/subtle', '#F1F5F9'], ['border/strong', '#E2E8F0'],
  ['text/primary', '#1A1D2E'], ['text/secondary', '#475569'], ['text/muted', '#94A3B8'], ['text/on-accent', '#FFFFFF'],
  ['action/primary/default', '#4361EE'], ['action/primary/hover', '#3451D1'], ['action/primary/subtle', '#EEF2FF'],
  ['action/primary/subtle-hover', '#E0E7FF'], ['action/primary/border', '#C7D2FE'], ['action/primary/border-hover', '#A5B4FC'],
  ['feedback/success/default', '#059669'], ['feedback/success/bg', '#ECFDF5'], ['feedback/success/text', '#047857'], ['feedback/success/border', '#D1FAE5'],
  ['feedback/danger/default', '#E63946'], ['feedback/danger/bg', '#FEF2F2'], ['feedback/danger/text', '#E63946'], ['feedback/danger/border', '#FEE2E2'],
  ['feedback/warning/default', '#F4A261'],
];

const TYPOGRAPHY = [
  { name: 'heading/page', family: 'DM Sans', style: 'Bold', size: 24, lineHeightPct: 120, letterSpacing: 0, letterSpacingUnit: 'PIXELS', sample: 'Título de página' },
  { name: 'heading/section', family: 'DM Sans', style: 'Bold', size: 20, lineHeightPct: 120, letterSpacing: 0, letterSpacingUnit: 'PIXELS', sample: 'Título de seção' },
  { name: 'body/default', family: 'DM Sans', style: 'Regular', size: 14, lineHeightPct: 150, letterSpacing: 0, letterSpacingUnit: 'PIXELS', sample: 'Texto padrão de corpo' },
  { name: 'body/small', family: 'DM Sans', style: 'Medium', size: 12, lineHeightPct: 150, letterSpacing: 0, letterSpacingUnit: 'PIXELS', sample: 'Texto pequeno' },
  { name: 'label/field', family: 'DM Sans', style: 'Bold', size: 12, lineHeightPct: null, letterSpacing: 10, letterSpacingUnit: 'PERCENT', sample: 'LABEL DE CAMPO' },
  { name: 'mono/initials', family: 'DM Mono', style: 'Medium', size: 14, lineHeightPct: null, letterSpacing: -0.5, letterSpacingUnit: 'PIXELS', sample: 'FP' },
];

const SPACING = [
  ['0', 0], ['50', 2], ['100', 4], ['150', 6], ['200', 8], ['250', 10], ['300', 12],
  ['350', 14], ['400', 16], ['500', 20], ['600', 24], ['700', 28], ['800', 32], ['900', 36],
];

const RADIUS = [
  ['sm', 6], ['md', 8], ['lg', 10], ['xl', 12], ['2xl', 16], ['3xl', 20], ['full', 9999],
];

const SHADOWS = [
  { name: 'sm', effects: [{ x: 0, y: 1, blur: 2, spread: 0, color: [0, 0, 0, 0.05] }] },
  { name: 'md', effects: [{ x: 0, y: 1, blur: 3, spread: 0, color: [0, 0, 0, 0.10] }] },
  { name: 'lg', effects: [{ x: 0, y: 8, blur: 24, spread: 0, color: [0, 0, 0, 0.12] }] },
  { name: 'xl', effects: [{ x: 0, y: 20, blur: 40, spread: 0, color: [0, 0, 0, 0.15] }] },
  { name: 'card', effects: [
    { x: 0, y: 1, blur: 3, spread: 0, color: [26 / 255, 29 / 255, 46 / 255, 0.08] },
    { x: 0, y: 4, blur: 16, spread: 0, color: [26 / 255, 29 / 255, 46 / 255, 0.06] },
  ] },
  { name: 'card-elevated', effects: [{ x: 0, y: 4, blur: 32, spread: 0, color: [26 / 255, 29 / 255, 46 / 255, 0.08] }] },
  { name: 'button-accent', effects: [{ x: 0, y: 4, blur: 12, spread: 0, color: [67 / 255, 97 / 255, 238 / 255, 0.30] }] },
];

// ── Components data ───────────────────────────────────────────────────────

const BUTTON_SIZES = { sm: { paddingX: 14, paddingY: 6, fontSize: 14 }, md: { paddingX: 20, paddingY: 10, fontSize: 14 } };
const BUTTON_VARIANTS = {
  primary: { bg: '#4361EE', text: '#FFFFFF', border: null, hoverBg: '#3451D1', hoverText: '#FFFFFF', hoverBorder: null },
  secondary: { bg: '#EEF2FF', text: '#4361EE', border: '#C7D2FE', hoverBg: '#E0E7FF', hoverText: '#3451D1', hoverBorder: '#A5B4FC' },
  ghost: { bg: null, text: '#94A3B8', border: '#E4E7EE', hoverBg: '#F7F8FA', hoverText: '#1A1D2E', hoverBorder: '#E4E7EE' },
  danger: { bg: null, text: '#E63946', border: '#E63946', hoverBg: '#E63946', hoverText: '#FFFFFF', hoverBorder: '#E63946' },
};
const BUTTON_STATES = ['default', 'hover', 'disabled'];

const INPUT_STATES = {
  default: { border: '#E4E7EE' },
  focus: { border: '#4361EE' },
  error: { border: '#E63946' },
  disabled: { border: '#E4E7EE', opacity: 0.5 },
};

// Real icon paths copied from resources/views/layouts/app.blade.php and
// resources/views/transactions/index.blade.php (heroicons-style, stroke-only).
const ICONS = [
  { name: 'home', strokeWidth: 1.75, d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
  { name: 'user', strokeWidth: 1.75, d: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z' },
  { name: 'transactions', strokeWidth: 1.75, d: 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4' },
  { name: 'tag', strokeWidth: 1.75, d: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z' },
  { name: 'refresh', strokeWidth: 1.75, d: 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15' },
  { name: 'shield', strokeWidth: 1.75, d: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z' },
  { name: 'chevron-down', strokeWidth: 2, d: 'M19 9l-7 7-7-7' },
  { name: 'search', strokeWidth: 2.5, d: 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z' },
  { name: 'edit', strokeWidth: 2, d: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z' },
  { name: 'duplicate', strokeWidth: 2, d: 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2' },
  { name: 'trash', strokeWidth: 2, d: 'M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0' },
  { name: 'plus', strokeWidth: 2.5, d: 'M12 4.5v15m7.5-7.5h-15' },
  { name: 'close', strokeWidth: 2, d: 'M6 18L18 6M6 6l12 12' },
  { name: 'arrow-income', strokeWidth: 2, d: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' },
  { name: 'arrow-expense', strokeWidth: 2, d: 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' },
  { name: 'logout', strokeWidth: 1.75, d: 'M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75' },
];

const iconCache = {};

// ── Helpers ────────────────────────────────────────────────────────────────

function hexToRgb(hex) {
  const n = parseInt(hex.replace('#', ''), 16);
  return { r: ((n >> 16) & 255) / 255, g: ((n >> 8) & 255) / 255, b: (n & 255) / 255 };
}

function getOrCreatePaintStyle(name, hex) {
  const existing = figma.getLocalPaintStyles().find((s) => s.name === name);
  if (existing) return existing;
  const style = figma.createPaintStyle();
  style.name = name;
  style.paints = [{ type: 'SOLID', color: hexToRgb(hex) }];
  return style;
}

async function getOrCreateTextStyle(t) {
  const existing = figma.getLocalTextStyles().find((s) => s.name === t.name);
  if (existing) return existing;
  await figma.loadFontAsync({ family: t.family, style: t.style });
  const style = figma.createTextStyle();
  style.name = t.name;
  style.fontName = { family: t.family, style: t.style };
  style.fontSize = t.size;
  style.lineHeight = t.lineHeightPct ? { value: t.lineHeightPct, unit: 'PERCENT' } : { unit: 'AUTO' };
  style.letterSpacing = { value: t.letterSpacing || 0, unit: t.letterSpacingUnit || 'PIXELS' };
  return style;
}

function getOrCreateEffectStyle(s) {
  const existing = figma.getLocalEffectStyles().find((x) => x.name === s.name);
  if (existing) return existing;
  const style = figma.createEffectStyle();
  style.name = s.name;
  style.effects = s.effects.map((e) => ({
    type: 'DROP_SHADOW',
    color: { r: e.color[0], g: e.color[1], b: e.color[2], a: e.color[3] },
    offset: { x: e.x, y: e.y },
    radius: e.blur,
    spread: e.spread,
    visible: true,
    blendMode: 'NORMAL',
  }));
  return style;
}

function autoFrame(name, mode) {
  const f = figma.createFrame();
  f.name = name;
  f.layoutMode = mode;
  f.primaryAxisSizingMode = 'AUTO';
  f.counterAxisSizingMode = 'AUTO';
  f.fills = [];
  return f;
}

async function label(text, opts) {
  opts = opts || {};
  const family = opts.family || 'Inter';
  const style = opts.style || 'Regular';
  await figma.loadFontAsync({ family, style });
  const t = figma.createText();
  t.fontName = { family, style };
  t.fontSize = opts.size || 11;
  t.characters = text;
  if (opts.color) t.fills = [{ type: 'SOLID', color: hexToRgb(opts.color) }];
  if (opts.letterSpacing) t.letterSpacing = { value: opts.letterSpacing, unit: opts.letterSpacingUnit || 'PERCENT' };
  return t;
}

function sectionShell(title) {
  const shell = autoFrame(title, 'VERTICAL');
  shell.itemSpacing = 20;
  shell.paddingTop = shell.paddingBottom = shell.paddingLeft = shell.paddingRight = 40;
  shell.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
  shell.cornerRadius = 16;
  return shell;
}

let cursorY = 0;
function positionSection(shell) {
  shell.x = 0;
  shell.y = cursorY;
  cursorY += shell.height + 40;
}

// Generic auto-layout container. Always resizes BEFORE turning on Auto Layout
// sizing modes — Figma throws if you resize an axis that's already set to AUTO.
function makeContainer(kind, name, mode, opts) {
  opts = opts || {};
  const node = kind === 'component' ? figma.createComponent() : figma.createFrame();
  node.name = name;
  if (opts.width != null || opts.height != null) {
    node.resize(opts.width != null ? opts.width : node.width, opts.height != null ? opts.height : node.height);
  }
  if (mode) {
    node.layoutMode = mode;
    node.primaryAxisSizingMode = opts.primarySizing || 'AUTO';
    node.counterAxisSizingMode = opts.counterSizing || 'AUTO';
    if (opts.itemSpacing != null) node.itemSpacing = opts.itemSpacing;
    if (opts.primaryAlign) node.primaryAxisAlignItems = opts.primaryAlign;
    if (opts.counterAlign) node.counterAxisAlignItems = opts.counterAlign;
    if (opts.wrap) {
      node.layoutWrap = 'WRAP';
      if (opts.counterSpacing != null) node.counterAxisSpacing = opts.counterSpacing;
    }
  }
  if (opts.padding != null) node.paddingLeft = node.paddingRight = node.paddingTop = node.paddingBottom = opts.padding;
  if (opts.paddingX != null) node.paddingLeft = node.paddingRight = opts.paddingX;
  if (opts.paddingY != null) node.paddingTop = node.paddingBottom = opts.paddingY;
  node.fills = opts.fills || [];
  if (opts.cornerRadius != null) node.cornerRadius = opts.cornerRadius;
  if (opts.strokeColor) {
    node.strokes = [{ type: 'SOLID', color: hexToRgb(opts.strokeColor) }];
    node.strokeWeight = opts.strokeWeight || 1;
  }
  return node;
}

function iconSvgMarkup(d, strokeWidth, color) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${color}" stroke-width="${strokeWidth}" stroke-linecap="round" stroke-linejoin="round"><path d="${d}"/></svg>`;
}

let iconMasterCount = 0;

// Builds (and caches) the master Icon component the first time each name is
// requested, so every later call just returns a fresh instance of it. Masters
// are parked off to the left, out of the sections' content area (x: 0-1200) —
// they only need to exist as a source for instances, not be seen.
function getIconInstance(name, color) {
  if (!iconCache[name]) {
    const def = ICONS.find((i) => i.name === name);
    if (!def) throw new Error('Unknown icon: ' + name);
    const svg = iconSvgMarkup(def.d, def.strokeWidth, '#475569');
    const raw = figma.createNodeFromSvg(svg);
    const comp = figma.createComponent();
    comp.name = `Icon/${def.name}`;
    comp.resize(raw.width, raw.height);
    comp.fills = [];
    for (const child of [...raw.children]) comp.appendChild(child);
    raw.remove();
    comp.x = -200;
    comp.y = iconMasterCount * 40;
    iconMasterCount += 1;
    iconCache[name] = comp;
  }
  const instance = iconCache[name].createInstance();
  if (color) tintIconInstance(instance, color);
  return instance;
}

function tintIconInstance(instance, hex) {
  const vector = instance.findOne((n) => n.type === 'VECTOR');
  if (vector) vector.strokes = [{ type: 'SOLID', color: hexToRgb(hex) }];
  return instance;
}

// Looks up an existing master Component anywhere in the file by name (so
// Patterns reuse whatever "Construir Components" already built instead of
// creating duplicate masters), falling back to building a fresh one.
function findComponentByName(name) {
  return figma.root.findOne((n) => n.type === 'COMPONENT' && n.name === name);
}

async function getOrBuildInstance(name, builderFn) {
  let master = findComponentByName(name);
  if (!master) master = await builderFn();
  return master.createInstance();
}

async function overrideInstanceText(instance, newText) {
  const textNode = instance.findOne((n) => n.type === 'TEXT');
  if (textNode) {
    await figma.loadFontAsync(textNode.fontName);
    textNode.characters = newText;
  }
  return instance;
}

async function drawAvatarCircle(size, initials) {
  await figma.loadFontAsync({ family: 'DM Mono', style: 'Medium' });
  const box = figma.createFrame();
  box.name = 'avatar';
  box.resize(size, size);
  box.cornerRadius = 9999;
  box.fills = [{ type: 'SOLID', color: hexToRgb('#4361EE') }];

  const text = figma.createText();
  text.fontName = { family: 'DM Mono', style: 'Medium' };
  text.fontSize = Math.round(size * 0.35);
  text.characters = initials || 'FP';
  text.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
  text.letterSpacing = { value: -0.5, unit: 'PIXELS' };
  text.textAutoResize = 'NONE';
  text.resize(size, size);
  text.textAlignHorizontal = 'CENTER';
  text.textAlignVertical = 'CENTER';
  text.x = 0;
  text.y = 0;

  box.appendChild(text);
  return box;
}

// ── Foundations sections ────────────────────────────────────────────────────

async function makeSwatch(name, hex, asStyle) {
  const frame = autoFrame(name, 'VERTICAL');
  frame.itemSpacing = 6;

  const rect = figma.createRectangle();
  rect.resize(72, 72);
  rect.cornerRadius = 8;
  rect.strokeWeight = 1;
  rect.strokes = [{ type: 'SOLID', color: hexToRgb('#E4E7EE') }];
  if (asStyle) {
    const style = getOrCreatePaintStyle(name, hex);
    rect.fillStyleId = style.id;
  } else {
    rect.fills = [{ type: 'SOLID', color: hexToRgb(hex) }];
  }

  const text = await label(name, { size: 10, color: '#475569' });
  text.resize(72, text.height);
  text.textAlignHorizontal = 'CENTER';

  frame.appendChild(rect);
  frame.appendChild(text);
  return frame;
}

async function buildColorsSection() {
  const shell = sectionShell('Colors');
  shell.appendChild(await label('Colors', { style: 'Bold', size: 20 }));

  shell.appendChild(await label('Semantic', { style: 'Bold', size: 14, color: '#64748B' }));
  const semanticGrid = autoFrame('semantic-grid', 'HORIZONTAL');
  semanticGrid.layoutWrap = 'WRAP';
  semanticGrid.primaryAxisSizingMode = 'FIXED';
  semanticGrid.counterAxisSizingMode = 'AUTO';
  semanticGrid.resize(1200, 100);
  semanticGrid.itemSpacing = 16;
  semanticGrid.counterAxisSpacing = 16;
  for (const [name, hex] of SEMANTIC_COLOR) {
    semanticGrid.appendChild(await makeSwatch(name, hex, true));
  }
  shell.appendChild(semanticGrid);

  shell.appendChild(await label('Core (primitives)', { style: 'Bold', size: 14, color: '#64748B' }));
  for (const [group, shades] of Object.entries(CORE_COLOR)) {
    const row = autoFrame(group, 'HORIZONTAL');
    row.itemSpacing = 12;
    for (const [shade, hex] of Object.entries(shades)) {
      row.appendChild(await makeSwatch(`${group}/${shade}`, hex, false));
    }
    shell.appendChild(row);
  }

  positionSection(shell);
}

async function buildTypographySection() {
  const shell = sectionShell('Typography');
  shell.appendChild(await label('Typography', { style: 'Bold', size: 20 }));

  for (const t of TYPOGRAPHY) {
    const style = await getOrCreateTextStyle(t);

    const row = autoFrame(t.name, 'HORIZONTAL');
    row.itemSpacing = 24;
    row.counterAxisAlignItems = 'CENTER';

    const sample = figma.createText();
    sample.characters = t.sample;
    sample.textStyleId = style.id;
    sample.resize(360, sample.height);

    const meta = await label(
      `${t.name} · ${t.size}px${t.lineHeightPct ? ' / ' + t.lineHeightPct / 100 : ''} · ${t.family} ${t.style}`,
      { color: '#94A3B8' }
    );

    row.appendChild(sample);
    row.appendChild(meta);
    shell.appendChild(row);
  }

  positionSection(shell);
}

async function buildSpacingSection() {
  const shell = sectionShell('Spacing');
  shell.appendChild(await label('Spacing', { style: 'Bold', size: 20 }));

  for (const [name, value] of SPACING) {
    const row = autoFrame(`spacing-${name}`, 'HORIZONTAL');
    row.itemSpacing = 16;
    row.counterAxisAlignItems = 'CENTER';

    const bar = figma.createRectangle();
    bar.resize(Math.max(value, 1), 16);
    bar.cornerRadius = 2;
    bar.fills = [{ type: 'SOLID', color: hexToRgb('#4361EE') }];

    const text = await label(`${name} · ${value}px`, { color: '#475569' });

    row.appendChild(bar);
    row.appendChild(text);
    shell.appendChild(row);
  }

  positionSection(shell);
}

async function buildRadiusSection() {
  const shell = sectionShell('Radius');
  shell.appendChild(await label('Radius', { style: 'Bold', size: 20 }));

  const grid = autoFrame('radius-grid', 'HORIZONTAL');
  grid.itemSpacing = 24;
  for (const [name, value] of RADIUS) {
    const item = autoFrame(`radius-${name}`, 'VERTICAL');
    item.itemSpacing = 8;

    const box = figma.createRectangle();
    box.resize(64, 64);
    box.cornerRadius = value; // Figma clamps to half the box size, so `full` renders as a circle.
    box.fills = [{ type: 'SOLID', color: hexToRgb('#F1F5F9') }];
    box.strokeWeight = 1;
    box.strokes = [{ type: 'SOLID', color: hexToRgb('#CBD5E1') }];

    const text = await label(`${name} · ${value}`, { size: 10, color: '#475569' });
    text.resize(64, text.height);
    text.textAlignHorizontal = 'CENTER';

    item.appendChild(box);
    item.appendChild(text);
    grid.appendChild(item);
  }
  shell.appendChild(grid);

  positionSection(shell);
}

async function buildShadowSection() {
  const shell = sectionShell('Shadow');
  shell.appendChild(await label('Shadow / Elevation', { style: 'Bold', size: 20 }));

  const grid = autoFrame('shadow-grid', 'HORIZONTAL');
  grid.layoutWrap = 'WRAP';
  grid.primaryAxisSizingMode = 'FIXED';
  grid.counterAxisSizingMode = 'AUTO';
  grid.resize(1200, 100);
  grid.itemSpacing = 24;
  grid.counterAxisSpacing = 24;

  for (const s of SHADOWS) {
    const item = autoFrame(`shadow-${s.name}`, 'VERTICAL');
    item.itemSpacing = 8;

    const card = figma.createRectangle();
    card.resize(140, 90);
    card.cornerRadius = 12;
    card.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
    const style = getOrCreateEffectStyle(s);
    card.effectStyleId = style.id;

    const text = await label(s.name, { size: 10, color: '#475569' });
    text.resize(140, text.height);
    text.textAlignHorizontal = 'CENTER';

    item.appendChild(card);
    item.appendChild(text);
    grid.appendChild(item);
  }
  shell.appendChild(grid);

  positionSection(shell);
}

async function buildFoundationsPage() {
  await figma.loadFontAsync({ family: 'Inter', style: 'Regular' });
  await figma.loadFontAsync({ family: 'Inter', style: 'Bold' });

  let page = figma.root.children.find((p) => p.name === 'Foundations');
  if (!page) {
    page = figma.createPage();
    page.name = 'Foundations';
  }
  figma.currentPage = page;
  cursorY = 0;

  await buildColorsSection();
  await buildTypographySection();
  await buildSpacingSection();
  await buildRadiusSection();
  await buildShadowSection();

  figma.viewport.scrollAndZoomIntoView(page.children);
}

// ── Component builders (atoms) ──────────────────────────────────────────────

async function buildButton(variantName, sizeName, state) {
  const v = BUTTON_VARIANTS[variantName];
  const s = BUTTON_SIZES[sizeName];
  const comp = figma.createComponent();
  comp.name = `Button/${variantName}/${sizeName}/${state}`;
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.primaryAxisAlignItems = 'CENTER';
  comp.counterAxisAlignItems = 'CENTER';
  comp.paddingLeft = comp.paddingRight = s.paddingX;
  comp.paddingTop = comp.paddingBottom = s.paddingY;
  comp.cornerRadius = 10;

  const isHover = state === 'hover';
  const bg = isHover ? v.hoverBg : v.bg;
  const text = isHover ? v.hoverText : v.text;
  const border = isHover ? v.hoverBorder : v.border;

  comp.fills = bg ? [{ type: 'SOLID', color: hexToRgb(bg) }] : [];
  if (border) {
    comp.strokes = [{ type: 'SOLID', color: hexToRgb(border) }];
    comp.strokeWeight = 1.5;
  }
  if (state === 'disabled') comp.opacity = 0.5;

  comp.appendChild(await label(variantName === 'danger' ? 'Excluir' : 'Botão', { family: 'DM Sans', style: 'Bold', size: s.fontSize, color: text }));
  return comp;
}

async function buildInput(state) {
  const cfg = INPUT_STATES[state];
  const comp = figma.createComponent();
  comp.name = `Input/${state}`;
  comp.resize(220, 40);
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'FIXED';
  comp.counterAxisSizingMode = 'AUTO';
  comp.paddingLeft = comp.paddingRight = 16;
  comp.paddingTop = comp.paddingBottom = 10;
  comp.cornerRadius = 10;
  comp.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
  comp.strokes = [{ type: 'SOLID', color: hexToRgb(cfg.border) }];
  comp.strokeWeight = 1.5;
  if (cfg.opacity) comp.opacity = cfg.opacity;

  const text = state === 'error' ? 'Valor inválido' : 'Placeholder';
  const color = state === 'error' ? '#1A1D2E' : '#94A3B8';
  comp.appendChild(await label(text, { family: 'DM Sans', style: 'Regular', size: 14, color }));
  return comp;
}

async function buildSelect(state) {
  const comp = figma.createComponent();
  comp.name = `Select/${state}`;
  comp.resize(220, 40);
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'FIXED';
  comp.counterAxisSizingMode = 'AUTO';
  comp.primaryAxisAlignItems = 'SPACE_BETWEEN';
  comp.counterAxisAlignItems = 'CENTER';
  comp.paddingLeft = 16;
  comp.paddingRight = 12;
  comp.paddingTop = comp.paddingBottom = 10;
  comp.cornerRadius = 10;
  comp.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
  comp.strokes = [{ type: 'SOLID', color: hexToRgb(state === 'focus' ? '#4361EE' : '#E4E7EE') }];
  comp.strokeWeight = 1.5;
  if (state === 'disabled') comp.opacity = 0.5;

  comp.appendChild(await label('Selecionar', { family: 'DM Sans', style: 'Regular', size: 14, color: '#1A1D2E' }));
  comp.appendChild(await label('⌄', { size: 14, color: '#64748B' }));
  return comp;
}

async function buildFieldLabel() {
  const comp = figma.createComponent();
  comp.name = 'Label/field';
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.fills = [];
  comp.appendChild(await label('Rótulo do campo', { family: 'DM Sans', style: 'Bold', size: 13, color: '#1A1D2E' }));
  return comp;
}

function buildToggle(on) {
  const comp = figma.createComponent();
  comp.name = `Toggle/${on ? 'on' : 'off'}`;
  comp.resize(44, 24);
  comp.cornerRadius = 9999;
  comp.fills = [{ type: 'SOLID', color: hexToRgb(on ? '#EEF2FF' : '#E2E8F0') }];
  if (on) {
    comp.strokes = [{ type: 'SOLID', color: hexToRgb('#C7D2FE') }];
    comp.strokeWeight = 2;
  }
  const thumb = figma.createEllipse();
  thumb.resize(18, 18);
  thumb.fills = [{ type: 'SOLID', color: hexToRgb(on ? '#4361EE' : '#FFFFFF') }];
  thumb.x = on ? 44 - 18 - 3 : 3;
  thumb.y = 3;
  comp.appendChild(thumb);
  return comp;
}

async function buildAvatarInitials() {
  const comp = figma.createComponent();
  comp.name = 'Avatar/initials';
  comp.resize(34, 34);
  comp.fills = [];
  const visual = await drawAvatarCircle(34, 'FP');
  comp.appendChild(visual);
  return comp;
}

async function buildIconButton(kind) {
  const cfg = {
    edit: { icon: 'edit', bg: '#EEF2FF', hoverBg: '#E0E7FF', color: '#4361EE' },
    duplicate: { icon: 'duplicate', bg: '#ECFDF5', hoverBg: '#D1FAE5', color: '#059669' },
    delete: { icon: 'trash', bg: '#FEF2F2', hoverBg: '#FEE2E2', color: '#E63946' },
  }[kind];
  const comp = figma.createComponent();
  comp.name = `IconButton/${kind}`;
  comp.resize(28, 28);
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'FIXED';
  comp.counterAxisSizingMode = 'FIXED';
  comp.primaryAxisAlignItems = 'CENTER';
  comp.counterAxisAlignItems = 'CENTER';
  comp.cornerRadius = 8;
  comp.fills = [{ type: 'SOLID', color: hexToRgb(cfg.bg) }];

  const icon = getIconInstance(cfg.icon, cfg.color);
  icon.resize(16, 16);
  comp.appendChild(icon);
  return comp;
}

function buildDivider() {
  const comp = figma.createComponent();
  comp.name = 'Divider/horizontal';
  comp.resize(220, 1);
  comp.fills = [{ type: 'SOLID', color: hexToRgb('#E4E7EE') }];
  return comp;
}

async function buildLink(state) {
  const comp = figma.createComponent();
  comp.name = `Link/${state}`;
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.fills = [];
  const text = await label('Ver detalhes', { family: 'DM Sans', style: 'Medium', size: 14, color: state === 'hover' ? '#3451D1' : '#4361EE' });
  text.textDecoration = 'UNDERLINE';
  comp.appendChild(text);
  return comp;
}

function buildColorDot() {
  const comp = figma.createComponent();
  comp.name = 'ColorDot/default';
  comp.resize(10, 10);
  comp.cornerRadius = 9999;
  comp.fills = [{ type: 'SOLID', color: hexToRgb('#10B981') }];
  return comp;
}

async function buildTypeBadge(kind) {
  const isVisible = kind === 'visible';
  const comp = figma.createComponent();
  comp.name = `Badge/${kind}`;
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.paddingLeft = comp.paddingRight = 8;
  comp.paddingTop = comp.paddingBottom = 2;
  comp.cornerRadius = 9999;
  comp.fills = [{ type: 'SOLID', color: hexToRgb(isVisible ? '#ECFDF5' : '#F1F5F9') }];
  comp.appendChild(await label(isVisible ? 'Visível' : 'Oculto', { family: 'DM Sans', style: 'Bold', size: 12, color: isVisible ? '#047857' : '#64748B' }));
  return comp;
}

async function buildAlert(kind) {
  const isSuccess = kind === 'success';
  const comp = figma.createComponent();
  comp.name = `Alert/${kind}`;
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.itemSpacing = 8;
  comp.counterAxisAlignItems = 'CENTER';
  comp.paddingLeft = comp.paddingRight = 16;
  comp.paddingTop = comp.paddingBottom = 12;
  comp.cornerRadius = 10;
  comp.fills = [{ type: 'SOLID', color: hexToRgb(isSuccess ? '#ECFDF5' : '#FEF2F2') }];
  comp.strokes = [{ type: 'SOLID', color: hexToRgb(isSuccess ? '#D1FAE5' : '#FEE2E2') }];
  comp.strokeWeight = 1;
  comp.appendChild(await label(
    isSuccess ? 'Alteração salva com sucesso.' : 'Não foi possível salvar. Tente novamente.',
    { family: 'DM Sans', style: 'Medium', size: 13, color: isSuccess ? '#047857' : '#E63946' }
  ));
  return comp;
}

async function buildCard() {
  const comp = figma.createComponent();
  comp.name = 'Card/default';
  comp.resize(320, 100);
  comp.layoutMode = 'VERTICAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'FIXED';
  comp.itemSpacing = 8;
  comp.paddingLeft = comp.paddingRight = comp.paddingTop = comp.paddingBottom = 24;
  comp.cornerRadius = 16;
  comp.fills = [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }];
  comp.strokes = [{ type: 'SOLID', color: hexToRgb('#E4E7EE') }];
  comp.strokeWeight = 1;
  const shadowStyle = getOrCreateEffectStyle(SHADOWS.find((s) => s.name === 'card'));
  comp.effectStyleId = shadowStyle.id;

  const title = await label('Título do card', { family: 'DM Sans', style: 'Bold', size: 18, color: '#1A1D2E' });
  comp.appendChild(title);
  title.layoutSizingHorizontal = 'FILL';

  const desc = await label('Conteúdo de exemplo dentro do card, usando o token de sombra card.', { family: 'DM Sans', style: 'Regular', size: 14, color: '#475569' });
  comp.appendChild(desc);
  desc.layoutSizingHorizontal = 'FILL';

  return comp;
}

async function buildDropdownMenuItemRow(iconName, text, danger) {
  const row = makeContainer('frame', `item-${text}`, 'HORIZONTAL', {
    width: 220, primarySizing: 'FIXED', counterSizing: 'AUTO',
    counterAlign: 'CENTER', itemSpacing: 10, paddingX: 16, paddingY: 11,
  });
  const icon = getIconInstance(iconName, danger ? '#E63946' : '#64748B');
  icon.resize(16, 16);
  row.appendChild(icon);
  row.appendChild(await label(text, { family: 'DM Sans', style: 'Medium', size: 13, color: danger ? '#E63946' : '#1A1D2E' }));
  return row;
}

async function buildDropdownMenu() {
  const comp = makeContainer('component', 'DropdownMenu/user', 'VERTICAL', {
    width: 220, primarySizing: 'AUTO', counterSizing: 'FIXED',
    cornerRadius: 12, strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  const shadowStyle = getOrCreateEffectStyle({ name: 'dropdown', effects: [{ x: 0, y: 8, blur: 24, spread: 0, color: [0, 0, 0, 0.12] }] });
  comp.effectStyleId = shadowStyle.id;

  comp.appendChild(await buildDropdownMenuItemRow('user', 'Meu Perfil', false));
  comp.appendChild(await buildDropdownMenuItemRow('shield', 'Privacidade & Cookies', false));

  const divider = figma.createRectangle();
  divider.name = 'divider';
  divider.resize(220, 1);
  divider.fills = [{ type: 'SOLID', color: hexToRgb('#E4E7EE') }];
  comp.appendChild(divider);
  divider.layoutSizingHorizontal = 'FILL';

  comp.appendChild(await buildDropdownMenuItemRow('logout', 'Sair', true));

  return comp;
}

async function buildSelectOpen() {
  const comp = makeContainer('component', 'Select/open', 'VERTICAL', { primarySizing: 'AUTO', counterSizing: 'AUTO' });

  // A plain frame, not a Component — Figma doesn't allow nesting a master
  // Component (like the "Select/focus" one built elsewhere) inside another.
  const trigger = makeContainer('frame', 'trigger', 'HORIZONTAL', {
    width: 220, primarySizing: 'FIXED', counterSizing: 'AUTO',
    primaryAlign: 'SPACE_BETWEEN', counterAlign: 'CENTER',
    paddingX: 16, paddingY: 10, cornerRadius: 10,
    strokeColor: '#4361EE', strokeWeight: 1.5,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  trigger.paddingRight = 12;
  trigger.appendChild(await label('Selecionar', { family: 'DM Sans', style: 'Regular', size: 14, color: '#1A1D2E' }));
  trigger.appendChild(await label('⌄', { size: 14, color: '#64748B' }));
  comp.appendChild(trigger);

  const options = makeContainer('frame', 'options', 'VERTICAL', {
    width: 220, primarySizing: 'FIXED', counterSizing: 'AUTO',
    cornerRadius: 10, strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });

  const optionLabels = ['Todos', 'Receita', 'Despesa'];
  for (let i = 0; i < optionLabels.length; i++) {
    const row = makeContainer('frame', `option-${optionLabels[i]}`, 'HORIZONTAL', {
      width: 220, primarySizing: 'FIXED', counterSizing: 'AUTO',
      counterAlign: 'CENTER', paddingX: 16, paddingY: 10,
      fills: i === 0 ? [{ type: 'SOLID', color: hexToRgb('#EEF2FF') }] : [],
    });
    row.appendChild(await label(optionLabels[i], { family: 'DM Sans', style: i === 0 ? 'Bold' : 'Regular', size: 14, color: i === 0 ? '#4361EE' : '#1A1D2E' }));
    options.appendChild(row);
  }
  comp.appendChild(options);

  return comp;
}

async function buildBarChart() {
  const wrap = makeContainer('component', 'Chart/bar-monthly', 'VERTICAL', { itemSpacing: 8 });

  const months = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul'];
  const heights = [140, 115, 175, 140, 125, 135, 120];

  const barsRow = makeContainer('frame', 'bars', 'HORIZONTAL', {
    width: 7 * 40 + 6 * 12, height: 180, primarySizing: 'FIXED', counterSizing: 'FIXED',
    counterAlign: 'MAX', itemSpacing: 12,
  });
  const labelsRow = makeContainer('frame', 'labels', 'HORIZONTAL', { itemSpacing: 12 });

  for (let i = 0; i < months.length; i++) {
    const bar = figma.createRectangle();
    bar.name = `bar-${months[i]}`;
    bar.resize(40, heights[i]);
    bar.cornerRadius = 4;
    bar.fills = [{ type: 'SOLID', color: hexToRgb('#A5B4FC') }];
    barsRow.appendChild(bar);

    const lbl = await label(months[i], { size: 11, color: '#94A3B8' });
    lbl.resize(40, lbl.height);
    lbl.textAlignHorizontal = 'CENTER';
    labelsRow.appendChild(lbl);
  }

  wrap.appendChild(barsRow);
  wrap.appendChild(labelsRow);
  return wrap;
}

async function miniActionButton(name, bg, text) {
  // Plain frame, not a Component — it only ever lives inside the Topbar
  // component, and Figma disallows a Component anywhere in another
  // Component's descendant tree (not just as a direct child).
  const comp = figma.createFrame();
  comp.name = name;
  comp.layoutMode = 'HORIZONTAL';
  comp.primaryAxisSizingMode = 'AUTO';
  comp.counterAxisSizingMode = 'AUTO';
  comp.primaryAxisAlignItems = 'CENTER';
  comp.counterAxisAlignItems = 'CENTER';
  comp.paddingLeft = comp.paddingRight = 14;
  comp.paddingTop = comp.paddingBottom = 7;
  comp.cornerRadius = 10;
  comp.fills = [{ type: 'SOLID', color: hexToRgb(bg) }];
  comp.appendChild(await label(text, { family: 'DM Sans', style: 'Bold', size: 13, color: '#FFFFFF' }));
  return comp;
}

async function buildSidebar() {
  const comp = makeContainer('component', 'Sidebar/default', 'VERTICAL', {
    width: 240, height: 640, primarySizing: 'FIXED', counterSizing: 'FIXED',
    fills: [{ type: 'SOLID', color: hexToRgb('#1A1D2E') }],
  });

  const logoRow = makeContainer('frame', 'logo', 'HORIZONTAL', { counterAlign: 'CENTER', itemSpacing: 10, paddingX: 20, paddingY: 24 });
  const logoBox = figma.createFrame();
  logoBox.name = 'logo-icon';
  logoBox.resize(36, 36);
  logoBox.cornerRadius = 10;
  logoBox.fills = [{ type: 'SOLID', color: hexToRgb('#4361EE') }];
  logoRow.appendChild(logoBox);
  logoRow.appendChild(await label('Finanças FP', { family: 'DM Sans', style: 'Bold', size: 15, color: '#FFFFFF' }));
  comp.appendChild(logoRow);
  logoRow.layoutSizingHorizontal = 'FILL';

  const navGroups = [
    { section: 'PRINCIPAL', items: [
      { icon: 'home', text: 'Dashboard', active: true },
      { icon: 'transactions', text: 'Transações', active: false },
    ] },
    { section: 'CONFIGURAÇÕES', items: [
      { icon: 'tag', text: 'Categorias', active: false },
      { icon: 'refresh', text: 'Recorrentes', active: false },
      { icon: 'shield', text: 'Privacidade', active: false },
    ] },
  ];

  const nav = makeContainer('frame', 'nav', 'VERTICAL', { itemSpacing: 2, paddingX: 12, paddingY: 8 });
  for (const group of navGroups) {
    const sectionLabel = await label(group.section, { family: 'DM Sans', style: 'Bold', size: 10, color: '#FFFFFF' });
    sectionLabel.opacity = 0.3;
    nav.appendChild(sectionLabel);

    for (const item of group.items) {
      const row = makeContainer('frame', `nav-${item.text}`, 'HORIZONTAL', {
        counterAlign: 'CENTER', itemSpacing: 10, paddingX: 12, paddingY: 10, cornerRadius: 8,
        fills: item.active ? [{ type: 'SOLID', color: hexToRgb('#4361EE'), opacity: 0.25 }] : [],
      });
      const icon = getIconInstance(item.icon, item.active ? '#4361EE' : '#FFFFFF');
      icon.resize(18, 18);
      if (!item.active) icon.opacity = 0.6;
      row.appendChild(icon);
      const lbl = await label(item.text, { family: 'DM Sans', style: 'Medium', size: 14, color: item.active ? '#4361EE' : '#FFFFFF' });
      if (!item.active) lbl.opacity = 0.6;
      row.appendChild(lbl);
      nav.appendChild(row);
      row.layoutSizingHorizontal = 'FILL';
    }
  }
  comp.appendChild(nav);
  nav.layoutSizingHorizontal = 'FILL';

  const spacer = figma.createFrame();
  spacer.name = 'spacer';
  spacer.resize(1, 1);
  spacer.fills = [];
  comp.appendChild(spacer);
  spacer.layoutGrow = 1;

  const userRow = makeContainer('frame', 'user-menu', 'HORIZONTAL', { counterAlign: 'CENTER', itemSpacing: 10, paddingX: 12, paddingY: 16 });
  userRow.appendChild(await drawAvatarCircle(34, 'FP'));
  const userInfo = makeContainer('frame', 'user-info', 'VERTICAL', { itemSpacing: 2 });
  userInfo.appendChild(await label('Fernando Pinhel', { family: 'DM Sans', style: 'Bold', size: 13, color: '#FFFFFF' }));
  const email = await label('fernandopinhelll@gma...', { family: 'DM Sans', style: 'Regular', size: 11, color: '#FFFFFF' });
  email.opacity = 0.4;
  userInfo.appendChild(email);
  userRow.appendChild(userInfo);
  comp.appendChild(userRow);
  userRow.layoutSizingHorizontal = 'FILL';

  return comp;
}

async function buildTopbar() {
  const comp = makeContainer('component', 'Topbar/default', 'HORIZONTAL', {
    width: 900, height: 64, primarySizing: 'FIXED', counterSizing: 'FIXED',
    primaryAlign: 'SPACE_BETWEEN', counterAlign: 'CENTER', paddingX: 32,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  comp.strokeBottomWeight = 1;
  comp.strokeTopWeight = 0;
  comp.strokeLeftWeight = 0;
  comp.strokeRightWeight = 0;
  comp.strokes = [{ type: 'SOLID', color: hexToRgb('#E4E7EE') }];

  comp.appendChild(await label('Dashboard', { family: 'DM Sans', style: 'Bold', size: 18, color: '#1A1D2E' }));

  const actions = makeContainer('frame', 'actions', 'HORIZONTAL', { itemSpacing: 12, counterAlign: 'CENTER' });
  actions.appendChild(await miniActionButton('Topbar/excel-button', '#059669', 'Excel'));
  actions.appendChild(await miniActionButton('Topbar/pdf-button', '#E63946', 'PDF'));

  comp.appendChild(actions);
  return comp;
}

// ── Components sections ─────────────────────────────────────────────────────

async function buildIconsSection() {
  const shell = sectionShell('Icons');
  shell.appendChild(await label('Icons', { style: 'Bold', size: 20 }));
  shell.appendChild(await label('Copiados de resources/views/layouts/app.blade.php e transactions/index.blade.php', { size: 11, color: '#94A3B8' }));

  const grid = makeContainer('frame', 'icons-grid', 'HORIZONTAL', {
    width: 1200, primarySizing: 'FIXED', counterSizing: 'AUTO', wrap: true, itemSpacing: 20, counterSpacing: 20,
  });
  for (const def of ICONS) {
    const item = makeContainer('frame', `icon-item-${def.name}`, 'VERTICAL', { itemSpacing: 6, counterAlign: 'CENTER' });
    const icon = getIconInstance(def.name);
    icon.resize(24, 24);
    item.appendChild(icon);
    const lbl = await label(def.name, { size: 10, color: '#94A3B8' });
    item.appendChild(lbl);
    grid.appendChild(item);
  }
  shell.appendChild(grid);

  positionSection(shell);
}

async function buildIconButtonsSection() {
  const shell = sectionShell('Icon Buttons');
  shell.appendChild(await label('Icon Buttons', { style: 'Bold', size: 20 }));

  const row = makeContainer('frame', 'icon-button-row', 'HORIZONTAL', { itemSpacing: 12, counterAlign: 'CENTER' });
  row.appendChild(await buildIconButton('edit'));
  row.appendChild(await buildIconButton('duplicate'));
  row.appendChild(await buildIconButton('delete'));
  shell.appendChild(row);

  positionSection(shell);
}

async function buildDropdownSection() {
  const shell = sectionShell('Dropdown & Select (open)');
  shell.appendChild(await label('Dropdown & Select (open)', { style: 'Bold', size: 20 }));

  const row = makeContainer('frame', 'dropdown-row', 'HORIZONTAL', { itemSpacing: 40 });
  row.appendChild(await buildDropdownMenu());
  row.appendChild(await buildSelectOpen());
  shell.appendChild(row);

  positionSection(shell);
}

async function buildChartSection() {
  const shell = sectionShell('Chart');
  shell.appendChild(await label('Chart (mockup estático — não é um componente de DS de verdade)', { style: 'Bold', size: 16, color: '#64748B' }));
  shell.appendChild(await buildBarChart());
  positionSection(shell);
}

async function buildOrganismsSection() {
  const shell = sectionShell('Organisms');
  shell.appendChild(await label('Organisms', { style: 'Bold', size: 20 }));

  const row = makeContainer('frame', 'organisms-row', 'HORIZONTAL', { itemSpacing: 24 });
  row.appendChild(await buildSidebar());
  row.appendChild(await buildTopbar());
  shell.appendChild(row);

  positionSection(shell);
}

async function buildButtonsSection() {
  const shell = sectionShell('Buttons');
  shell.appendChild(await label('Buttons', { style: 'Bold', size: 20 }));

  for (const variantName of Object.keys(BUTTON_VARIANTS)) {
    const row = autoFrame(`button-row-${variantName}`, 'HORIZONTAL');
    row.itemSpacing = 16;
    row.counterAxisAlignItems = 'CENTER';
    row.appendChild(await label(variantName, { style: 'Bold', size: 12, color: '#64748B' }));
    for (const sizeName of Object.keys(BUTTON_SIZES)) {
      for (const state of BUTTON_STATES) {
        row.appendChild(await buildButton(variantName, sizeName, state));
      }
    }
    shell.appendChild(row);
  }

  positionSection(shell);
}

async function buildFormControlsSection() {
  const shell = sectionShell('Form Controls');
  shell.appendChild(await label('Form Controls', { style: 'Bold', size: 20 }));

  shell.appendChild(await buildFieldLabel());

  const inputRow = autoFrame('input-row', 'HORIZONTAL');
  inputRow.itemSpacing = 16;
  for (const state of Object.keys(INPUT_STATES)) inputRow.appendChild(await buildInput(state));
  shell.appendChild(inputRow);

  const selectRow = autoFrame('select-row', 'HORIZONTAL');
  selectRow.itemSpacing = 16;
  for (const state of ['default', 'focus', 'disabled']) selectRow.appendChild(await buildSelect(state));
  shell.appendChild(selectRow);

  const toggleRow = autoFrame('toggle-row', 'HORIZONTAL');
  toggleRow.itemSpacing = 16;
  toggleRow.counterAxisAlignItems = 'CENTER';
  toggleRow.appendChild(buildToggle(false));
  toggleRow.appendChild(buildToggle(true));
  shell.appendChild(toggleRow);

  positionSection(shell);
}

async function buildMiscAtomsSection() {
  const shell = sectionShell('Misc Atoms');
  shell.appendChild(await label('Avatar · Divider · Link · Color Dot', { style: 'Bold', size: 20 }));

  const row = autoFrame('misc-row', 'HORIZONTAL');
  row.itemSpacing = 24;
  row.counterAxisAlignItems = 'CENTER';
  row.appendChild(await buildAvatarInitials());
  row.appendChild(buildDivider());
  row.appendChild(await buildLink('default'));
  row.appendChild(await buildLink('hover'));
  row.appendChild(buildColorDot());
  shell.appendChild(row);

  positionSection(shell);
}

async function buildBadgesAndAlertsSection() {
  const shell = sectionShell('Badges & Alerts');
  shell.appendChild(await label('Badges & Alerts', { style: 'Bold', size: 20 }));

  const badgeRow = autoFrame('badge-row', 'HORIZONTAL');
  badgeRow.itemSpacing = 12;
  badgeRow.appendChild(await buildTypeBadge('visible'));
  badgeRow.appendChild(await buildTypeBadge('hidden'));
  shell.appendChild(badgeRow);

  const alertCol = autoFrame('alert-col', 'VERTICAL');
  alertCol.itemSpacing = 12;
  alertCol.appendChild(await buildAlert('success'));
  alertCol.appendChild(await buildAlert('error'));
  shell.appendChild(alertCol);

  positionSection(shell);
}

async function buildCardSection() {
  const shell = sectionShell('Card');
  shell.appendChild(await label('Card', { style: 'Bold', size: 20 }));
  shell.appendChild(await buildCard());
  positionSection(shell);
}

async function buildComponentsPage() {
  await figma.loadFontAsync({ family: 'Inter', style: 'Regular' });
  await figma.loadFontAsync({ family: 'Inter', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Regular' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Medium' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Mono', style: 'Medium' });

  let page = figma.root.children.find((p) => p.name === 'Components');
  if (!page) {
    page = figma.createPage();
    page.name = 'Components';
  }
  figma.currentPage = page;
  cursorY = 0;

  await buildIconsSection();
  await buildButtonsSection();
  await buildIconButtonsSection();
  await buildFormControlsSection();
  await buildMiscAtomsSection();
  await buildBadgesAndAlertsSection();
  await buildCardSection();
  await buildDropdownSection();
  await buildChartSection();
  await buildOrganismsSection();

  figma.viewport.scrollAndZoomIntoView(page.children);
}

// ── Changelog ────────────────────────────────────────────────────────────
// Copied from `git log --date=short --pretty=format:'%ad|%s' -- design-system/`
// — update this array by hand whenever a token/component change lands.
const CHANGELOG = [
  {
    date: '2026-07-06',
    title: 'Plugin do Figma pra gerar Foundations e Components',
    desc: 'Automatiza a criação dos Color/Text/Effect Styles e dos Components (Button, Input, Sidebar, Topbar, etc.) a partir dos tokens e do código real das views.',
  },
  {
    date: '2026-07-06',
    title: 'Unificação das cores duplicadas',
    desc: 'Resolve as 3 inconsistências de cor (azul primário, verde de sucesso, vermelho de erro), sobrescrevendo as escalas indigo/red do Tailwind e realinhando --fp-success para emerald.',
  },
  {
    date: '2026-07-01',
    title: 'Proposta inicial do Design System',
    desc: 'Estrutura de tokens (core/semantic/component) em Tokens Studio, hierarquia de componentes e roteiro de sincronização Figma ⇄ GitHub.',
  },
];

async function buildChangelogPage() {
  await figma.loadFontAsync({ family: 'Inter', style: 'Regular' });
  await figma.loadFontAsync({ family: 'Inter', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Regular' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Mono', style: 'Medium' });

  // Figma's Starter plan caps files at 3 pages — Foundations/Components/
  // Patterns already use all 3, so Changelog rides along as one more section
  // at the bottom of Foundations instead of getting its own page.
  let page = figma.root.children.find((p) => p.name === 'Changelog');
  if (!page) page = figma.root.children.find((p) => p.name === 'Foundations');
  if (!page) throw new Error('Rode "Construir Foundations" primeiro.');
  figma.currentPage = page;
  cursorY = page.children.reduce((max, n) => Math.max(max, n.y + n.height), 0) + 40;

  const shell = sectionShell('Changelog');
  shell.appendChild(await label('Changelog', { style: 'Bold', size: 20 }));
  shell.appendChild(await label('Correlacionado com os commits que tocam design-system/ no GitHub', { size: 12, color: '#94A3B8' }));

  for (const entry of CHANGELOG) {
    const row = makeContainer('frame', `entry-${entry.date}-${entry.title}`, 'HORIZONTAL', { itemSpacing: 20 });

    const dateLabel = await label(entry.date, { family: 'DM Mono', style: 'Medium', size: 12, color: '#94A3B8' });
    dateLabel.textAutoResize = 'HEIGHT';
    dateLabel.resize(90, dateLabel.height);
    row.appendChild(dateLabel);

    const textCol = makeContainer('frame', 'text', 'VERTICAL', { itemSpacing: 4 });
    textCol.appendChild(await label(entry.title, { family: 'DM Sans', style: 'Bold', size: 14, color: '#1A1D2E' }));
    const desc = await label(entry.desc, { family: 'DM Sans', style: 'Regular', size: 13, color: '#475569' });
    desc.textAutoResize = 'HEIGHT';
    desc.resize(560, desc.height);
    textCol.appendChild(desc);
    row.appendChild(textCol);

    shell.appendChild(row);
  }

  positionSection(shell);
  figma.viewport.scrollAndZoomIntoView(page.children);
}

// ── Patterns ─────────────────────────────────────────────────────────────

async function buildStatCard(label_, amount, color, iconName) {
  const card = makeContainer('frame', `stat-${label_}`, 'HORIZONTAL', {
    width: 340, primarySizing: 'FIXED', counterSizing: 'AUTO',
    primaryAlign: 'SPACE_BETWEEN', counterAlign: 'CENTER', padding: 20, cornerRadius: 16,
    strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });

  const textCol = makeContainer('frame', 'text', 'VERTICAL', { itemSpacing: 6 });
  textCol.appendChild(await label(label_, { family: 'DM Sans', style: 'Medium', size: 13, color: '#64748B' }));
  textCol.appendChild(await label(amount, { family: 'DM Sans', style: 'Bold', size: 22, color }));
  card.appendChild(textCol);

  const iconBox = makeContainer('frame', 'icon-box', 'HORIZONTAL', {
    width: 48, height: 48, primarySizing: 'FIXED', counterSizing: 'FIXED',
    primaryAlign: 'CENTER', counterAlign: 'CENTER', cornerRadius: 12,
    fills: [{ type: 'SOLID', color: hexToRgb(color), opacity: 0.1 }],
  });
  const icon = getIconInstance(iconName, color);
  icon.resize(20, 20);
  iconBox.appendChild(icon);
  card.appendChild(iconBox);

  return card;
}

async function buildRadioCard(text, checked, color) {
  const card = makeContainer('frame', `radio-${text}`, 'VERTICAL', {
    itemSpacing: 4, padding: 16, cornerRadius: 12,
    strokeColor: checked ? color : '#E4E7EE', strokeWeight: checked ? 2 : 1.5,
    fills: [{ type: 'SOLID', color: hexToRgb(checked ? color : '#FFFFFF'), opacity: checked ? 0.08 : 1 }],
  });
  card.appendChild(await label(text, { family: 'DM Sans', style: 'Bold', size: 14, color: checked ? color : '#1A1D2E' }));
  return card;
}

async function buildListRow(desc, category, isExpense, amount) {
  const row = makeContainer('frame', 'row', 'HORIZONTAL', {
    primarySizing: 'AUTO', counterSizing: 'AUTO', counterAlign: 'CENTER',
    itemSpacing: 16, paddingX: 20, paddingY: 14, strokeColor: '#F1F5F9', strokeWeight: 0,
  });
  row.strokeBottomWeight = 1;
  row.strokeTopWeight = 0;
  row.strokeLeftWeight = 0;
  row.strokeRightWeight = 0;

  const descText = await label(desc, { family: 'DM Sans', style: 'Medium', size: 14, color: '#1A1D2E' });
  descText.textAutoResize = 'HEIGHT';
  descText.resize(280, descText.height);
  row.appendChild(descText);

  const catRow = makeContainer('frame', 'category', 'HORIZONTAL', { itemSpacing: 6, counterAlign: 'CENTER' });
  catRow.appendChild(await getOrBuildInstance('ColorDot/default', () => buildColorDot()));
  catRow.appendChild(await label(category, { family: 'DM Sans', style: 'Regular', size: 13, color: '#475569' }));
  row.appendChild(catRow);

  const typeBadge = makeContainer('frame', 'type', 'HORIZONTAL', {
    paddingX: 10, paddingY: 4, cornerRadius: 9999,
    fills: [{ type: 'SOLID', color: hexToRgb(isExpense ? '#FEF2F2' : '#ECFDF5') }],
  });
  typeBadge.appendChild(await label(isExpense ? 'Despesa' : 'Receita', { family: 'DM Sans', style: 'Bold', size: 12, color: isExpense ? '#E63946' : '#047857' }));
  row.appendChild(typeBadge);

  row.appendChild(await label(amount, { family: 'DM Sans', style: 'Bold', size: 14, color: isExpense ? '#E63946' : '#059669' }));

  const actions = makeContainer('frame', 'actions', 'HORIZONTAL', { itemSpacing: 8 });
  actions.appendChild(await getOrBuildInstance('IconButton/edit', () => buildIconButton('edit')));
  actions.appendChild(await getOrBuildInstance('IconButton/duplicate', () => buildIconButton('duplicate')));
  actions.appendChild(await getOrBuildInstance('IconButton/delete', () => buildIconButton('delete')));
  row.appendChild(actions);

  return row;
}

async function buildAppShell(pageTitle) {
  const frame = makeContainer('frame', `Pattern/${pageTitle}`, 'HORIZONTAL', {
    width: 1440, height: 900, primarySizing: 'FIXED', counterSizing: 'FIXED',
    fills: [{ type: 'SOLID', color: hexToRgb('#F7F8FA') }],
  });

  const sidebar = await getOrBuildInstance('Sidebar/default', buildSidebar);
  frame.appendChild(sidebar);

  const main = makeContainer('frame', 'main', 'VERTICAL', {});
  frame.appendChild(main);
  main.layoutSizingHorizontal = 'FILL';
  main.layoutSizingVertical = 'FILL';

  const topbar = await getOrBuildInstance('Topbar/default', buildTopbar);
  await overrideInstanceText(topbar, pageTitle);
  main.appendChild(topbar);
  topbar.layoutSizingHorizontal = 'FILL';

  const content = makeContainer('frame', 'content', 'VERTICAL', { itemSpacing: 24, padding: 32 });
  main.appendChild(content);
  content.layoutSizingHorizontal = 'FILL';
  content.layoutSizingVertical = 'FILL';

  return { frame, content };
}

async function buildLoginPattern() {
  const frame = makeContainer('frame', 'Pattern/Login', 'HORIZONTAL', {
    width: 1440, height: 900, primarySizing: 'FIXED', counterSizing: 'FIXED',
    primaryAlign: 'CENTER', counterAlign: 'CENTER',
    fills: [{ type: 'SOLID', color: hexToRgb('#F7F8FA') }],
  });

  const card = makeContainer('frame', 'guest-card', 'VERTICAL', {
    width: 440, primarySizing: 'FIXED', counterSizing: 'AUTO',
    padding: 32, itemSpacing: 20, cornerRadius: 20,
    strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  card.effectStyleId = getOrCreateEffectStyle(SHADOWS.find((s) => s.name === 'card-elevated')).id;

  const logoRow = makeContainer('frame', 'logo', 'HORIZONTAL', { counterAlign: 'CENTER', itemSpacing: 12 });
  const logoBox = figma.createFrame();
  logoBox.name = 'logo-icon';
  logoBox.resize(44, 44);
  logoBox.cornerRadius = 13;
  logoBox.fills = [{ type: 'SOLID', color: hexToRgb('#4361EE') }];
  logoRow.appendChild(logoBox);
  logoRow.appendChild(await label('Finanças FP', { family: 'DM Sans', style: 'Bold', size: 20, color: '#1A1D2E' }));
  card.appendChild(logoRow);

  card.appendChild(await label('Bem-vindo de volta', { family: 'DM Sans', style: 'Bold', size: 18, color: '#1A1D2E' }));

  for (const [labelText, placeholder] of [['E-mail', 'seu@email.com'], ['Senha', '••••••••']]) {
    const field = makeContainer('frame', `field-${labelText}`, 'VERTICAL', { itemSpacing: 6 });
    field.appendChild(await label(labelText, { family: 'DM Sans', style: 'Bold', size: 13, color: '#1A1D2E' }));
    const input = await getOrBuildInstance('Input/default', () => buildInput('default'));
    await overrideInstanceText(input, placeholder);
    field.appendChild(input);
    input.layoutSizingHorizontal = 'FILL';
    card.appendChild(field);
    field.layoutSizingHorizontal = 'FILL';
  }

  const submitBtn = await getOrBuildInstance('Button/primary/md/default', () => buildButton('primary', 'md', 'default'));
  await overrideInstanceText(submitBtn, 'Entrar');
  card.appendChild(submitBtn);
  submitBtn.layoutSizingHorizontal = 'FILL';

  const linkRow = makeContainer('frame', 'link-row', 'HORIZONTAL', { primaryAlign: 'CENTER' });
  linkRow.appendChild(await getOrBuildInstance('Link/default', () => buildLink('default')));
  card.appendChild(linkRow);
  linkRow.layoutSizingHorizontal = 'FILL';

  frame.appendChild(card);
  return frame;
}

async function buildDashboardPattern() {
  const { frame, content } = await buildAppShell('Dashboard');

  content.appendChild(await label('Olá, Fernando Pinhel', { family: 'DM Sans', style: 'Bold', size: 22, color: '#1A1D2E' }));

  const statsRow = makeContainer('frame', 'stats', 'HORIZONTAL', { itemSpacing: 20 });
  statsRow.appendChild(await buildStatCard('Receitas', 'R$ 102.011,92', '#059669', 'arrow-income'));
  statsRow.appendChild(await buildStatCard('Despesas', 'R$ 97.141,51', '#E63946', 'arrow-expense'));
  statsRow.appendChild(await buildStatCard('Saldo Atual', 'R$ 4.870,41', '#4361EE', 'transactions'));
  content.appendChild(statsRow);

  const chartCard = makeContainer('frame', 'chart-card', 'VERTICAL', {
    itemSpacing: 16, padding: 24, cornerRadius: 16, strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  chartCard.appendChild(await label('Gastos por Mês (2026)', { family: 'DM Sans', style: 'Bold', size: 16, color: '#1A1D2E' }));
  chartCard.appendChild(await getOrBuildInstance('Chart/bar-monthly', buildBarChart));
  content.appendChild(chartCard);

  return frame;
}

async function buildListPattern() {
  const { frame, content } = await buildAppShell('Transações');

  const headerRow = makeContainer('frame', 'header', 'HORIZONTAL', { primaryAlign: 'SPACE_BETWEEN', counterAlign: 'CENTER' });
  headerRow.appendChild(await label('Histórico de Transações', { family: 'DM Sans', style: 'Bold', size: 20, color: '#1A1D2E' }));
  const newBtn = await getOrBuildInstance('Button/primary/md/default', () => buildButton('primary', 'md', 'default'));
  await overrideInstanceText(newBtn, '+ Nova Transação');
  headerRow.appendChild(newBtn);
  content.appendChild(headerRow);
  headerRow.layoutSizingHorizontal = 'FILL';

  const filterRow = makeContainer('frame', 'filters', 'HORIZONTAL', { itemSpacing: 12 });
  const searchInput = await getOrBuildInstance('Input/default', () => buildInput('default'));
  await overrideInstanceText(searchInput, 'Buscar por descrição...');
  filterRow.appendChild(searchInput);
  filterRow.appendChild(await getOrBuildInstance('Select/default', () => buildSelect('default')));
  filterRow.appendChild(await getOrBuildInstance('Select/default', () => buildSelect('default')));
  content.appendChild(filterRow);

  const tableCard = makeContainer('frame', 'table', 'VERTICAL', {
    cornerRadius: 16, strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  const rows = [
    ['IPTU', 'Apartamento', true, '- R$ 186,90'],
    ['Cartão Bradesco/Nubank', 'Cartão', true, '- R$ 3.067,87'],
    ['Saque Aniversário', 'Receita extra', false, '+ R$ 5.900,00'],
    ['Celular', 'Vivo', true, '- R$ 82,44'],
  ];
  for (const [desc, cat, isExpense, amt] of rows) {
    const row = await buildListRow(desc, cat, isExpense, amt);
    tableCard.appendChild(row);
    row.layoutSizingHorizontal = 'FILL';
  }
  content.appendChild(tableCard);
  tableCard.layoutSizingHorizontal = 'FILL';

  return frame;
}

async function buildFormPattern() {
  const { frame, content } = await buildAppShell('Nova Transação');

  const formCard = makeContainer('frame', 'form-card', 'VERTICAL', {
    width: 600, primarySizing: 'FIXED', counterSizing: 'AUTO',
    itemSpacing: 20, padding: 32, cornerRadius: 20, strokeColor: '#E4E7EE', strokeWeight: 1,
    fills: [{ type: 'SOLID', color: hexToRgb('#FFFFFF') }],
  });
  formCard.effectStyleId = getOrCreateEffectStyle(SHADOWS.find((s) => s.name === 'card')).id;

  formCard.appendChild(await label('Nova Transação', { family: 'DM Sans', style: 'Bold', size: 18, color: '#1A1D2E' }));

  const typeRow = makeContainer('frame', 'type-selector', 'HORIZONTAL', { itemSpacing: 12 });
  const incomeCard = await buildRadioCard('Receita', true, '#059669');
  const expenseCard = await buildRadioCard('Despesa', false, '#E63946');
  typeRow.appendChild(incomeCard);
  incomeCard.layoutSizingHorizontal = 'FILL';
  typeRow.appendChild(expenseCard);
  expenseCard.layoutSizingHorizontal = 'FILL';
  formCard.appendChild(typeRow);
  typeRow.layoutSizingHorizontal = 'FILL';

  for (const [labelText, placeholder] of [['Descrição', 'Ex: Supermercado'], ['Valor', 'R$ 0,00'], ['Data', '10/07/2026']]) {
    const field = makeContainer('frame', `field-${labelText}`, 'VERTICAL', { itemSpacing: 6 });
    field.appendChild(await label(labelText, { family: 'DM Sans', style: 'Bold', size: 13, color: '#1A1D2E' }));
    const input = await getOrBuildInstance('Input/default', () => buildInput('default'));
    await overrideInstanceText(input, placeholder);
    field.appendChild(input);
    input.layoutSizingHorizontal = 'FILL';
    formCard.appendChild(field);
    field.layoutSizingHorizontal = 'FILL';
  }

  const actionsRow = makeContainer('frame', 'actions', 'HORIZONTAL', { itemSpacing: 12, primaryAlign: 'MAX' });
  const cancelBtn = await getOrBuildInstance('Button/ghost/md/default', () => buildButton('ghost', 'md', 'default'));
  await overrideInstanceText(cancelBtn, 'Cancelar');
  const saveBtn = await getOrBuildInstance('Button/primary/md/default', () => buildButton('primary', 'md', 'default'));
  await overrideInstanceText(saveBtn, 'Salvar');
  actionsRow.appendChild(cancelBtn);
  actionsRow.appendChild(saveBtn);
  formCard.appendChild(actionsRow);
  actionsRow.layoutSizingHorizontal = 'FILL';

  content.appendChild(formCard);
  formCard.layoutSizingHorizontal = 'FILL';

  return frame;
}

async function buildPatternsPage() {
  await figma.loadFontAsync({ family: 'Inter', style: 'Regular' });
  await figma.loadFontAsync({ family: 'Inter', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Regular' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Medium' });
  await figma.loadFontAsync({ family: 'DM Sans', style: 'Bold' });
  await figma.loadFontAsync({ family: 'DM Mono', style: 'Medium' });

  let page = figma.root.children.find((p) => p.name === 'Patterns');
  if (!page) {
    page = figma.createPage();
    page.name = 'Patterns';
  }
  figma.currentPage = page;
  cursorY = 0;

  positionSection(await buildLoginPattern());
  positionSection(await buildDashboardPattern());
  positionSection(await buildListPattern());
  positionSection(await buildFormPattern());

  figma.viewport.scrollAndZoomIntoView(page.children);
}

// ── Entry point ──────────────────────────────────────────────────────────

figma.showUI(__html__, { width: 260, height: 260 });

figma.ui.onmessage = async (msg) => {
  try {
    if (msg.type === 'build-foundations') {
      await buildFoundationsPage();
      figma.notify('Página Foundations construída ✅');
    } else if (msg.type === 'build-components') {
      await buildComponentsPage();
      figma.notify('Página Components construída ✅');
    } else if (msg.type === 'build-patterns') {
      await buildPatternsPage();
      figma.notify('Página Patterns construída ✅');
    } else if (msg.type === 'build-changelog') {
      await buildChangelogPage();
      figma.notify('Página Changelog construída ✅');
    }
  } catch (e) {
    console.error(e);
    const msg = (e && (e.message || e.toString())) || JSON.stringify(e);
    figma.notify('Erro: ' + msg, { error: true, timeout: 8000 });
  }
};
