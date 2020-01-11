/**
 * Naja conf file
 */
import naja from "naja";

// Register loader
// naja.registerExtension(LoaderExtension, '#loader');

// We must attach Naja to window load event.
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));

// export naja object
export default naja;