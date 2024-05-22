// Appel des éléments liés aux Services
const btnAddService = document.getElementById('btnAddService');
// Appel des éléments liés aux interventions
const modalIntervention = document.getElementById('modalIntervention')
const btnEditIntervention = document.getElementById('btnEditIntervention')

const modalServ = new bootstrap.Modal(document.getElementById('modalServ'), {keyboard: false})
const modalServBs = document.getElementById('modalServ')

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

// --------------------------------------
// Code lié aux interventions
// --------------------------------------
modalIntervention.addEventListener('hidden.bs.modal', function(){
    modalIntervention.querySelector('.modal-dialog').classList.remove('modal-xl');
    modalIntervention.querySelector('.modal-body').innerHTML =
        '<div class="d-flex justify-content-center">\n' +
        '<div class="spinner-border text-primary" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>\n' +
        '</div>'
})

modalIntervention.addEventListener('show.bs.modal', function (event){
    // Button that triggered the modal
    let button = event.relatedTarget
    // extraction de la variable
    let recipient = button.getAttribute('data-bs-whatever')
    let crud = recipient.split('-')[0]
    let contentTitle = recipient.split('-')[1]
    let id = recipient.split('-')[2]
    let namePage = recipient.split('-')[3]
    console.log(crud)
    if(crud === 'ADD'){
        let modalHeaderH5 = modalIntervention.querySelector('.modal-title')
        modalHeaderH5.textContent = contentTitle
        axios
            .get('/admin/intervention/addinterveonclient/' + id )
            .then(function (response) {
                const form = document.getElementById('ModalBodyListintervonfiche').innerHTML = response.data.form;
                reloadInterventionEvent();
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
        // mise à jour du lien de soumission du formulaire.
        let modalSubmit = modalIntervention.querySelector('.modal-footer a')
        modalSubmit.href = '/admin/intervention/addinterveonclient/' + id
    }
    else if(crud === 'EDIT'){
        let modalHeaderH5 = modalIntervention.querySelector('.modal-title')
        modalHeaderH5.textContent = contentTitle
    }
    else if(crud === 'DEL'){
        let modalHeaderH5 = modalIntervention.querySelector('.modal-title')
        modalHeaderH5.textContent = contentTitle
    }
    else if(crud === 'LIST' ){
        let modalHeaderH5 = modalIntervention.querySelector('.modal-title')
        modalHeaderH5.textContent = contentTitle
        axios
            .get('/admin/intervention/listeinterveonclient/' + id )
            .then(function (response) {
                document.getElementById('ModalBodyListintervonfiche').innerHTML = response.data.list;
                reloadInterventionEvent();
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
    }
})

document.querySelectorAll('a.btnEditIntervention').forEach(function (link) {
    link.addEventListener('click', editIntervention);
})

// fonctions
function editIntervention(event){
    event.preventDefault();
    let url = this.href;
    axios
        .get(url)
        .then(function(response){
            document.querySelector('.modal-body').innerHTML = response.data.form
        })
        .catch(function(error){

        })
    ;
}

function btnSupprService(event){
    event.preventDefault()
}

function reloadInterventionEvent()
{
    // Ajout d'un évènement sur le click du selecteur ajout d'un service
    document.querySelectorAll('a.btnaddonclient').forEach(function (link) {
        link.addEventListener('click', btnaddonclient);
    })

    // Ajout d'un évènement sur le click de suppression service
    document.querySelectorAll('a.btnSupprService').forEach(function (link) {
        link.addEventListener('click', btnSupprService);
    })

    // Ajout d'un évènement sur le click du selecteur d'un statut
    document.querySelectorAll('a.btnaddstatutonclient').forEach(function (link) {
        link.addEventListener('click', btnaddstatutonclient);
    })
    let urlInvoice = document.getElementById('urlNewInvoice');
    if (urlInvoice !== null){
        document.getElementById('NewInvoice').href = urlInvoice.href;
    }
    document.querySelectorAll('a.btnEditIntervention').forEach(function (link) {
        link.addEventListener('click', editIntervention);
    })
}