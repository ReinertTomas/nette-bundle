/**
 * Naja conf file
 */
import naja from "naja";
import ModalExtension from "./naja-ext/ModalExtension";

// Register loader
naja.registerExtension(ModalExtension, '#layoutModal');

// We must attach Naja to window load event.
document.addEventListener('DOMContentLoaded', naja.initialize.bind(naja));

// export naja object
export default naja;