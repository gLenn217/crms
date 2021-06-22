<template>
    <div>
        <h2>Citizen</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-flex justify-content-between">
                    <button v-on:click="loadCreateModal" class="btn btn-primary" style="width: 180px;">Add Citizen</button>

                    <div>
                        <a :href="url" class="btn btn-secondary">Export to Excel</a>
                        <a v-on:click="pdfModal" class="btn btn-dark">Print to PDF</a>
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
                            <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Citizen</h5>
                            <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Citizen</h5>
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
                            <input v-model="citizen.lname" type="text" class="form-control" id="lname" maxlength="45">
                            <label for="fname">Firstname</label>
                            <input v-model="citizen.fname" type="text" class="form-control" id="fname" maxlength="45">
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
            columns: ['citizen_id', 'lname', 'fname', 'mname', 'address', 'barangay', 'contact_no', 'created_at', 'updated_at', 'actions'],
            tableData: [],
            options: {
                headings: {
                    citizen_id: 'Citizen ID',
                    lname: 'Lastname',
                    fname: 'Firstname',
                    mname: 'Middlename',
                    address: 'Address',
                    barangay: 'Barangay',
                    contact_no: 'Contact #',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['citizen_id', 'lname'],
                filterable: ['citizen_id', 'lname'],
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
            citizen: [],
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
            axios.get('citizen/getrecord').then(function (res) {
                this.tableData = res.data.citizens;
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
            axios.get('citizen/edit/' + this.tableData[index].citizen_id)
                .then(response => {
                    this.errors = [];
                    this.adding = false;
                    this.citizen.citizen_id = response.data.citizen.citizen_id;
                    this.citizen.lname = response.data.citizen.lname;
                    this.citizen.fname = response.data.citizen.fname;
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
            axios.post('citizen/store',
                {
                    lname : this.citizen.lname,
                    fname : this.citizen.fname
                })
                .then(response => {
                    $("#create-modal").modal("hide");
                    this.citizen = [];
                    toastr.success(response.data.message);
                    this.loadRecords();
                })
                .catch(error => {
                    this.errors = [];
                    this.errors = this.errorHandler(error.response.data.errors);
                });
        },

        updateRecord() {
            axios.patch('citizen/update/' + this.citizen.citizen_id,
                {
                    citizen_id: this.citizen.citizen_id,
                    lname: this.citizen.lname,
                    fname: this.citizen.fname
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
                axios.get('citizen/destroy/' + this.tableData[index].citizen_id)
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
