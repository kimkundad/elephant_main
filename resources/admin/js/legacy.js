const runLegacy = (code, label) => {
  const source = label ? `${code}\n//# sourceURL=${label}` : code;
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
window.KTUtil = window.KTUtil || {
  onDOMContentLoaded: (cb) => {
    window.__ktDomReadyQueue.push(cb);
  },
};

const patchedScriptsBundle = scriptsBundle.replace(/KTComponents\.init\(\)/g, 'void 0');
runLegacy(patchedScriptsBundle, 'scripts.bundle.js');

if (window.__ktDomReadyQueue.length > 0 && window.KTUtil && typeof window.KTUtil.onDOMContentLoaded === 'function') {
  const queued = window.__ktDomReadyQueue.slice();
  window.__ktDomReadyQueue.length = 0;
  queued.forEach((cb) => window.KTUtil.onDOMContentLoaded(cb));
}
if (typeof window.KTComponents !== 'undefined' && typeof window.KTComponents.init === 'function') {
  window.KTComponents.init();
}
if (window.KTApp && typeof window.KTApp.initPageLoader === 'function') {
  window.KTApp.initPageLoader();
}
runLegacy(datatablesBundle, 'datatables.bundle.js');
runLegacy(fslightboxBundle, 'fslightbox.bundle.js');
runLegacy(fileManagerList, 'file-manager-list.js');
runLegacy(widgetsBundle, 'widgets.bundle.js');
runLegacy(customWidgets, 'custom.widgets.js');
runLegacy(chatApp, 'chat.app.js');
runLegacy(upgradePlan, 'upgrade-plan.js');
runLegacy(createApp, 'create-app.js');
runLegacy(usersSearch, 'users-search.js');
