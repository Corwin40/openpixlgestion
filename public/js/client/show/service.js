const modalServ = new bootstrap.Modal(document.getElementById('modalServ'), {keyboard: false})
const modalServBs = document.getElementById('modalServ')
// Appel des éléments liés aux Services
const btnAddService = document.getElementById('btnAddService');

btnAddService.addEventListener('click', addService)

modalServBs.addEventListener('hidden.bs.modal', function(){
    modalServ.querySelector('.modal-dialog').classList.remove('modal-xl', 'modal-dialog-centered')
    modalServ.querySelector('.modal-header').innerHTML =
        '<h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>\n' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
    ;
    modalServ.querySelector('.modal-body').innerHTML =
        '<div class="d-flex justify-content-center">\n' +
        '<div class="spinner-border text-primary" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>\n' +
        '</div>'
    ;
    modalServ.querySelector('.modal-footer').innerHTML =
        '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>\n' +
        '<button type="button" class="btn btn-primary">Save changes</button>'
    ;
})

function addService(event){
    event.preventDefault();
    let opt = this.getAttribute('data-bs-whatever');
    let crud = opt.split('-')[0];
    let contentTitle = opt.split('-')[1];
    let url = this.href;
    modalServ.show();
    modalServBs.querySelector('.modal-dialog').classList.add('modal-xl', 'modal-dialog-centered');
    modalServBs.querySelector('.modal-title').textContent = contentTitle;
}