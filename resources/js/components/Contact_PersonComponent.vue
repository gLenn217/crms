<template>
    <div>
        <h2>Contact Person</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-flex justify-content-between">
                    <button v-on:click="loadCreateModal" class="btn btn-primary" style="width: 180px;">Add Contact Person</button>

                    <div>
                        <a :href="url" class="btn btn-secondary">Export to Excelbtn</a>
                        <a v-on:click="pdfModal" class="btn btn-dark">Print to PDFbtn</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <v-client-table
                    :data="tableData"
                    :columns="columns"
                    :options="options">
                   <template slot="actions" slot-scope="row">
                        <button v-on:click="loadUpdateModal(row.index-1)" class="btn btn-success mb-2">Edit</button>
                        <button v-on:click="deleteRecord(row.index-1)" class="btn btn-danger mb-2">Delete</button>
                    </template> 
                </v-client-table>
            </div>
        </div>

        <!--        modal window-->
        <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" v-if="print">Print Report</h5>

                        <div v-else>
                            <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Contact Person</h5>
                            <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Contact Person</h5>
                        </div>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="text-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{error[0]}}</li>
                            </ul>
                        </div>

                        <div class="form-group" v-if="print">
                            <label for="paper-size">Paper Size</label>

                            <select v-model="paperSize" id="paper-size" class="form-control">
                                <option>Letter</option>
                                <option>Legal</option>
                                <option>A4</option>
                            </select>

                            <label for="paper-orientation">Orientation</label>
                            <select v-model="paperOrientation" id="paper-orientation" class="form-control">
                                <option>Portrait</option>
                                <option>Landscape</option>
                            </select>
                        </div>

                        <div class="form-group" v-else>
                            <label for="lname">Lastname</label>
                            <input v-model="contact_person.lname" type="text" class="form-control" id="lname" maxlength="45">
                            <label for="fname">Firstname</label>
                            <input v-model="contact_person.fname" type="text" class="form-control" id="fname" maxlength="45">
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-if="print" @click="pdfPrint" type="button" class="btn btn-primary">Print</button>

                        <div v-else>
                            <button v-if="adding" @click="createRecord" type="button" class="btn btn-primary">Save changes Create</button>
                            <button v-else @click="updateRecord" type="button" class="btn btn-primary">Save changes Edit</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            columns: ['contact_id', 'lname', 'created_at', 'updated_at', 'actions'],
            tableData: [],
            options: {
                headings: {
                    id: 'ID',
                    name: 'Name',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['contact_id', 'lname'],
                filterable: ['contact_id', 'lname'],
                templates: {
                    hol_date: function(h, row) {
                        return row.hol_date !== null ? moment(row.hol_date).format('YYYY-MM-DD') : null;
                    },
                    created_at: function(h, row) {
                        return row.created_at !== null ? moment(row.created_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    },
                    updated_at: function(h, row) {
                        return row.updated_at !== null ? moment(row.updated_at).format('YYYY-MM-DD hh:mm:ss') : null;
                    }
                },
                texts : {
                    filter: 'Search:',
                }
            },

            url: './zipcode/export',
            url_pdf: './zipcode/pdf',
            errors: [],
            contact_person: [],
            adding: false,
            print: false,
            paperSize: 'Letter',
            paperOrientation: 'Portrait',
        }
    },

    methods: {
        errorHandler(errors){
            let error_handler = [];
            $.each(errors, function(key, value) {
                error_handler.push(value);
            });
            return error_handler;
        },

        loadRecords() {
            axios.get('contact_person/getrecord').then(function (res) {
                this.tableData = res.data.contact_persons;
                console.log(this.tableData);
            }.bind(this));
        },

        loadCreateModal() {
            this.zipcode = [];
            this.errors = [];
            this.adding = true;
            this.print = false;
            $('#create-modal').modal('show');
        },

        loadUpdateModal(index) {
            axios.get('contact_person/edit/' + this.tableData[index].contact_id)
                .then(response => {
                    this.errors = [];
                    this.adding = false;
                    this.contact_person.contact_id = response.data.contact_person.contact_id ;
                    this.contact_person.lname = response.data.contact_person.lname;
                    this.contact_person.fname = response.data.contact_person.fname;
                    this.print = false;
                    $("#create-modal").modal("show");
                })
        },

        pdfModal() {
            this.print = true;
            $('#create-modal').modal('show');
        },

        pdfPrint() {
            window.open('zipcode/pdf/' + this.paperSize + '/' + this.paperOrientation);
            $('#create-modal').modal('hide');
            this.print = false;
        },

        createRecord() {
            axios.post('contact_person/store',
                {
                    lname : this.contact_person.lname,
                    fname : this.contact_person.fname
                })
                .then(response => {
                    $("#create-modal").modal("hide");
                    this.contact_person = [];
                    toastr.success(response.data.message);
                    this.loadRecords();
                })
                .catch(error => {
                    this.errors = [];
                    this.errors = this.errorHandler(error.response.data.errors);
                });
        },

        updateRecord() {
            axios.patch('contact_person/update/' + this.contact_person.contact_id,
                {
                    contact_id: this.contact_person.contact_id,
                    lname: this.contact_person.lname,
                    fname: this.contact_person.fname
                })
                .then(response=>{
                    $("#create-modal").modal("hide");
                    toastr.success(response.data.message);
                    this.loadRecords();
                })
                .catch(error=>{
                    this.errors = [];
                    this.errors = this.errorHandler(error.response.data.errors);
                });
        },

        deleteRecord(index) {
            let confirmBox = confirm("Do you really want to delete this record?");
            if(confirmBox == true){
                axios.get('contact_person/destroy/' + this.tableData[index].contact_id)
                    .then(response=>{
                        this.loadRecords();
                        toastr.success(response.data.message);
                    }).catch(error=>{
                    console.log("Could not delete for some reason")
                });
            }
        }

    },

    mounted() {
        this.loadRecords();
    }
}
</script>
