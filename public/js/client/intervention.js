// Appel des éléments
const modalIntervention = document.getElementById('modalIntervention')
const btnEditIntervention = document.getElementById('btnEditIntervention')

modalIntervention.addEventListener('show.bs.modal', function (event){
    // Button that triggered the modal
    let button = event.relatedTarget
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
                const form = document.getElementById('ModalBodyaddstatutonclient').innerHTML = response.data.form;
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

                document.querySelectorAll('a.btnEditIntervention').forEach(function (link) {
                    link.addEventListener('click', editIntervention);
                })
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
                let list = document.getElementById('ModalBodyListintervonfiche').innerHTML = response.data.list;
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