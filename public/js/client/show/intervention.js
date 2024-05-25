
// Appel des éléments liés aux interventions
const modalInterventionBs = new bootstrap.Modal(document.getElementById('modalIntervention'));
const modalIntervention = document.getElementById('modalIntervention');
const btnEditIntervention = document.getElementById('btnEditIntervention')

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
    let button = event.relatedTarget;
    // extraction de la variable
    let recipient = button.getAttribute('data-bs-whatever')
    let crud = recipient.split('-')[0]
    let contentTitle = recipient.split('-')[1]
    let id = recipient.split('-')[2]
    let namePage = recipient.split('-')[3]
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
                modalIntervention.querySelector('.modal-dialog').classList.add('modal-xl', 'modal-dialog-centered');
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
    link.addEventListener('click',editIntervention);
})

// fonctions
function editIntervention(event){
    event.preventDefault();
    modalInterventionBs.toggle();
    let modal = new bootstrap.Modal(document.getElementById('modalIntervention2'));
    modal.show();
    let modalIntervention2 = document.getElementById('modalIntervention2')
    // Button that triggered the modal
    let a = event.currentTarget;
    // extraction de la variable
    let url = a.href
    let recipient = a.getAttribute('data-bs-whatever')
    let crud = recipient.split('-')[0]
    let contentTitle = recipient.split('-')[1]
    let id = recipient.split('-')[2]
    let namePage = recipient.split('-')[3]
    modalIntervention2.querySelector('.modal-footer a').href = url
    if(crud === 'ADD'){
        let modalHeaderH5 = modalIntervention2.querySelector('.modal-title');
        modalHeaderH5.textContent = contentTitle;
    }else if(crud === 'EDIT'){
        let modalHeaderH5 = modalIntervention2.querySelector('.modal-title');
        modalHeaderH5.textContent = contentTitle;
        axios
            .post(url)
            .then(function(response){
                modalIntervention2.querySelector('.modal-body').innerHTML = response.data.form;
            })
            .catch(function(error){
                console.log(error)
            })
    }
}

function submitEditIntervention(event){
    event.preventDefault()
    let a = event.currentTarget.href.split('/');
    let id = a[7];
    let form = document.querySelector('#FormInterventionliste')
    let action = form.action
    let data = new FormData(form)
    let modalIntervention2 = document.getElementById('modalIntervention2')
    modalIntervention2.querySelector('.modal-body').innerHTML =
        '<div class="d-flex justify-content-center">\n' +
        '<div class="spinner-border text-primary" role="status">\n' +
        '<span class="sr-only">Loading...</span>\n' +
        '</div>\n' +
        '</div>'
    axios
        .post(action, data)
        .then(function(response){
            let modal = new bootstrap.Modal(document.getElementById('modalIntervention2'));
            modal.show();
            modalIntervention2.querySelector('.modal-dialog').classList.add('modal-xl', 'modal-dialog-centered');
            axios
                .get('/admin/intervention/listeinterveonclient/' + id )
                .then(function(response){
                    modalIntervention2.querySelector('.modal-body').innerHTML = response.data.list;
                    reloadInterventionEvent();
                })
                .catch(function(error){
                    console.log(error)
                })
        })
        .catch()
}

function hideModalIntervention2(event){}

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
    document.getElementById('SubmitEditIntervention').addEventListener('click', submitEditIntervention)
    document.querySelectorAll('a.btnEditIntervention').forEach(function (link) {
        link.addEventListener('click', editIntervention);
    })
}