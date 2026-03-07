const metronicGlobalPrelude = [
  'KTUtil',
  'KTApp',
  'KTMenu',
  'KTToggle',
  'KTDrawer',
  'KTScroll',
  'KTSticky',
  'KTSwapper',
  'KTDialer',
  'KTImageInput',
  'KTPasswordMeter',
  'KTSearch',
  'KTEventHandler',
  'KTCookie',
  'KTBlockUI',
  'KTThemeMode',
  'KTComponents',
]
  .map((name) => `var ${name}=globalThis.${name}||window.${name};`)
  .join('');

const metronicGlobals = [
  'KTUtil',
  'KTApp',
  'KTMenu',
  'KTToggle',
  'KTDrawer',
  'KTScroll',
  'KTSticky',
  'KTSwapper',
  'KTDialer',
  'KTImageInput',
  'KTPasswordMeter',
  'KTSearch',
  'KTEventHandler',
  'KTCookie',
  'KTBlockUI',
  'KTThemeMode',
  'KTComponents',
];

const metronicExposeSuffix = `;${metronicGlobals
  .map((name) => `if(typeof ${name}!=="undefined"){window.${name}=globalThis.${name}=${name};}`)
  .join('')}`;

const runLegacy = (code, label, injectMetronicGlobals = false) => {
  const runtimeCode = injectMetronicGlobals ? `${metronicGlobalPrelude}${code}` : code;
  const source = label ? `${runtimeCode}\n//# sourceURL=${label}` : runtimeCode;
  // Use indirect eval to run in global scope so UMD globals are attached to window.
  (0, eval)(source);
};

import pluginsBundle from '../assets/plugins/global/plugins.bundle.js?raw';
import scriptsBundle from '../assets/js/scripts.bundle.js?raw';
import datatablesBundle from '../assets/plugins/custom/datatables/datatables.bundle.js?raw';
import fslightboxBundle from '../assets/plugins/custom/fslightbox/fslightbox.bundle.js?raw';
import fileManagerList from '../assets/js/custom/apps/file-manager/list.js?raw';
import widgetsBundle from '../assets/js/widgets.bundle.js?raw';
import customWidgets from '../assets/js/custom/widgets.js?raw';
import chatApp from '../assets/js/custom/apps/chat/chat.js?raw';
import upgradePlan from '../assets/js/custom/utilities/modals/upgrade-plan.js?raw';
import createApp from '../assets/js/custom/utilities/modals/create-app.js?raw';
import usersSearch from '../assets/js/custom/utilities/modals/users-search.js?raw';

runLegacy(pluginsBundle, 'plugins.bundle.js');

window.__ktDomReadyQueue = [];

const patchedScriptsBundle = scriptsBundle
  .replace(
    'onDOMContentLoaded:function(e){"loading"===document.readyState?document.addEventListener("DOMContentLoaded",e):e()}',
    'onDOMContentLoaded:function(e){window.__ktDomReadyQueue=window.__ktDomReadyQueue||[],window.__ktDomReadyQueue.push(e)}'
  )
  .replace(
    'var KTThemeModeUser={init:function(){KTThemeMode.on("kt.thememode.change",(function(){var e=KTThemeMode.getMenuMode(),t=KTThemeMode.getMode();console.log("user selected theme mode:"+e),console.log("theme mode:"+t)}))}};',
    'var KTThemeModeUser={init:function(){window.KTThemeMode&&"function"==typeof KTThemeMode.on&&KTThemeMode.on("kt.thememode.change",(function(){var e=KTThemeMode.getMenuMode(),t=KTThemeMode.getMode();console.log("user selected theme mode:"+e),console.log("theme mode:"+t)}))}};'
  )
  .replace(/KTComponents\.init\(\)/g, 'void 0')
  .replace(
    'null!==o&&(o.on("kt.toggle.change"',
    'o&&"function"==typeof o.on&&(o.on("kt.toggle.change"'
  )
  .replace(
    ')),o.on("kt.toggle.changed"',
    ')),"function"==typeof o.on&&o.on("kt.toggle.changed"'
  )
  .concat(metronicExposeSuffix);
runLegacy(patchedScriptsBundle, 'scripts.bundle.js');

const normalizeGetInstance = (name) => {
  const component = window[name];

  if (!component || typeof component.getInstance !== 'function') {
    return;
  }

  const originalGetInstance = component.getInstance.bind(component);
  component.getInstance = (...args) => originalGetInstance(...args) ?? null;
};

[
  'KTMenu',
  'KTToggle',
  'KTDrawer',
  'KTScroll',
  'KTSticky',
  'KTSwapper',
  'KTDialer',
  'KTImageInput',
  'KTPasswordMeter',
].forEach(normalizeGetInstance);

if (typeof window.KTComponents !== 'undefined' && typeof window.KTComponents.init === 'function') {
  window.KTComponents.init();
}

if (window.__ktDomReadyQueue.length > 0 && window.KTUtil && typeof window.KTUtil.onDOMContentLoaded === 'function') {
  const queued = window.__ktDomReadyQueue.slice();
  window.__ktDomReadyQueue.length = 0;
  queued.forEach((cb) => window.KTUtil.onDOMContentLoaded(cb));
}
if (window.KTApp && typeof window.KTApp.initPageLoader === 'function') {
  window.KTApp.initPageLoader();
}
runLegacy(datatablesBundle, 'datatables.bundle.js', true);
runLegacy(fslightboxBundle, 'fslightbox.bundle.js', true);
runLegacy(fileManagerList, 'file-manager-list.js', true);
runLegacy(widgetsBundle, 'widgets.bundle.js', true);
runLegacy(customWidgets, 'custom.widgets.js', true);
runLegacy(chatApp, 'chat.app.js', true);
runLegacy(upgradePlan, 'upgrade-plan.js', true);
runLegacy(createApp, 'create-app.js', true);
runLegacy(usersSearch, 'users-search.js', true);
