export default class ModalExtension {
    constructor(naja, selector) {
        this.selector = selector;
        naja.addEventListener('success', this.showModal.bind(this));
    }

    showModal(event) {
        if (event.detail.payload.snippets) {
            if (event.detail.payload.snippets['snippet--modal']) {
                $(this.selector).modal('show');
            }
        }
    }
}