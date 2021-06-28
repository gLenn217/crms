<template>
    <div>
        <h2>Request Application</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group d-flex justify-content-between">
                    <button v-on:click="loadCreateModal" class="btn btn-primary" style="width: 180px;">Add Request Application</button>

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
                        <button v-on:click="" class="btn btn-success mb-2">Edit</button>
                        <button v-on:click="" class="btn btn-danger mb-2">Delete</button>
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
                            <h5 class="modal-title" id="exampleModalLabel" v-if="adding">Add Request Apps</h5>
                            <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Request Apps</h5>
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
                            <label for="request_id">Lastname</label>
                            <input v-model="request_app.request_id" type="text" class="form-control" id="request_id" maxlength="80">

                                                  
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-if="print" @click="pdfPrint" type="button" class="btn btn-primary">Print</button>

                        <div v-else>
                            <button v-if="adding" @click="" type="button" class="btn btn-primary">Save changes Create</button>
                            <button v-else @click="" type="button" class="btn btn-primary">Save changes Edit</button>
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
            columns: ['request_id', 'citizen_id', 'office_id', 'purpose', 'status_id', 'created_at', 'updated_at', 'actions'],
            tableData: [],
            options: {
                headings: {
                    request_id: 'Request ID',
                    citizen_id: 'Citizen',
                    office_id: 'Office',
                    purpose: 'Purpose',
                    status_id: 'Status',
                    created_at: 'Created At',
                    updated_at: 'Updated At',
                    action: 'Action',
                },
                sortable: ['request_id', 'citizen_id'],
                filterable: ['request_id', 'citizen_id'],
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
            request_app: [],
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
            axios.get('request_app/getrecord').then(function (res) {
                this.tableData = res.data.request_apps;
                console.log(this.tableData);
            }.bind(this));
        },

        loadCreateModal() {
            this.request_app = [];
            this.errors = [];
            this.adding = true;
            this.print = false;
            $('#create-modal').modal('show');
        },

        loadUpdateModal(index) {
            axios.get('request_app/edit/' + this.tableData[index].request_id)
                .then(response => {
                    this.errors = [];
                    this.adding = false;
                    this.request_app.request_id = response.data.request_app.request_id;
                    
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

        

        

        

    },

    mounted() {
        this.loadRecords();
    }
}
</script>
