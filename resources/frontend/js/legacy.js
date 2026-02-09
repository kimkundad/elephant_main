const runLegacy = (code) => {
  // Execute legacy UMD/IIFE bundles with window as `this`.
  const fn = new Function(code);
  fn.call(window);
};

import jqueryCode from './jquery.js?raw';
import jqueryMigrateCode from './jquery-migrate.min.js?raw';
import popperCode from './popper.min.js?raw';
import bootstrapCode from './bootstrap.min.js?raw';
import easingCode from './jquery.easing.min.js?raw';
import fitvidsCode from './jquery.fitvids.js?raw';
import owlCode from './owl.carousel.min.js?raw';
import magnificCode from './jquery.magnific-popup.min.js?raw';
import formCode from './jquery.form.min.js?raw';
import contactCode from './contactform-home.js?raw';
import initCode from './init.js?raw';

runLegacy(jqueryCode);
window.$ = window.jQuery = window.jQuery || window.$;
runLegacy(jqueryMigrateCode);
runLegacy(popperCode);
runLegacy(bootstrapCode);
runLegacy(easingCode);
runLegacy(fitvidsCode);
runLegacy(owlCode);
runLegacy(magnificCode);
runLegacy(formCode);
runLegacy(contactCode);
runLegacy(initCode);
