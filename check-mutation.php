<?php
include "config/autoload.php";
include "common/header.php";

$case = $_GET['case'] ?? '';

$dists = $db->select('ai_districts', [], false, 'dist_name ASC')->result();
$items = $db->select('ai_sites', ['status' => 1], "0, 10", "id DESC")->result();
?>
<style>
    .form-input {
        width: 160px;
        padding: 3px 8px;
    }
</style>
<div id="origin" class="container">
    <div class="row">
        <?= front_view('common/home-menu'); ?>
        <div class="col-sm-9">
            <div class="py-3">
                <h1 class="h5 mb-4 text-center text-success">Application Status of Mutations</h1>
                <div class="search-area bg-white mb-2 shadow shadow-sm p-2 rounded">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-sm-3">
                                <select @change="set_zones" name="dist" v-model="dist_id" class="form-select">
                                    <option value="">Select District</option>
                                    <?php
                                    foreach ($dists as $item) {
                                    ?>
                                        <option value="<?= $item->id; ?>"><?= $item->dist_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="zone" v-model="zone_id" class="form-select">
                                    <option value="">Select Circle</option>
                                    <option v-for="item in zones" :value="item.id">{{ item.zone_name }}</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="budget" class="form-select">
                                    <option value="">Financial Year</option>
                                    <?php
                                    for ($i = date("Y") - 50; $i <= date("Y"); $i++) {
                                        $yr = $i . '-' . $i + 1;
                                    ?>
                                        <option value="<?= $yr; ?>"><?= $yr; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3 d-grid">
                                <button name="search" value="Search" class="btn btn-primary btn-sm">Proceed</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="clearfix bg-white mb-3 p-4 shadow-sm rounded-2">
                <table class="table">
                    <tbody>
                        <tr>
                            <td> <label for="case_input"><input type="radio" id="case_input" v-model="search_by" name="case_type" value="case_no"> केस नंबर से खोजें</label> </td>
                            <td> <input v-model="case_no" :disabled="search_by!='case_no'" class="form-control form-input" /> </td>
                            <td> <label for="deed_input"><input type="radio" id="deed_input" v-model="search_by" name="case_type" value="deed_no"> डीड नंबर से खोजें</label> </td>
                            <td> <input v-model="deed_no" :disabled="search_by!='deed_no'" class="form-control form-input" /> </td>
                        </tr>
                        <!-- <tr>
                            <td> <label for="mauja_input"><input type="radio" id="mauja_input" name="case_type" v-model="search_by" value="mauja_no"> मौजा नंबर से खोजें </label></td>
                            <td> <input v-model="mauja_no" :disabled="search_by!='mauja_no'" class="form-control form-input" /> </td>
                            <td> <label for="plot_input"><input type="radio" id="plot_input" name="case_type" v-model="search_by" value="plot_no"> प्लाट नंबर से खोजें</label> </td>
                            <td> <input v-model="plot_no" :disabled="search_by!='plot_no'" class="form-control form-input" /> </td>
                        </tr> -->
                        <tr>
                            <td colspan="4">
                                <div class="text-center">
                                    <button @click="actionSearch" class="btn btn-primary">SEARCH</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center text-danger">{{ errmsg }}</div>
            </div>

            <div v-if="searching==1" class="bg-white mb-3 p-4 shadow-sm rounded-2 text-center text-primary">
                Please wait... We are fetching your details.
            </div>
            <div v-if="searching==2" class="bg-white mb-3 p-4 shadow-sm rounded-2">
                <div class="alert alert-danger m-0">No Record Found</div>
            </div>
            <div v-if="searching==3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Case No</th>
                            <th>Deed No</th>
                            <th>Created</th>
                            <th>Mutation Status</th>
                            <th>Comments</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ rowItem.case_no }}</td>
                            <td>{{ rowItem.deed_no }}</td>
                            <td>{{ rowItem.created }}</td>
                            <td>
                                <span v-if="rowItem.status==0" class="badge bg-warning">Pending</span>
                                <span v-if="rowItem.status==1" class="badge bg-success">Approved</span>
                                <span v-if="rowItem.status==2" class="badge bg-info">Processing</span>
                                <span v-if="rowItem.status==3" class="badge bg-danger">Rejected</span>
                                <span v-if="rowItem.status==4" class="badge bg-secondary">Additional Info Required</span>
                            </td>
                            <td>{{ rowItem.comments }}</td>
                            <td><a v-if="rowItem.attachment.length>0" :href="rowItem.attachment">Download</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
include "common/footer.php";
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            zones: [],
            dist_id: '',
            zone_id: '',
            btncls: '',
            errmsg: '',
            errcls: '',
            search_by: 'case_no',
            searching: -1,
            items: [],
            case_no: '<?= $case; ?>',
            deed_no: '',
            mauja_no: '',
            plot_no: '',
            errmsg: '',
            rowItem: {}
        },
        methods: {
            set_zones: function() {
                this.zones = [];
                api_call('zones', {
                    dist_id: this.dist_id
                }).then(resp => {
                    if (resp.success) this.zones = resp.data;
                })
            },
            actionSearch: function() {
                this.errmsg = ''
                let s = this.search_by;
                if (s == 'case_no' && this.case_no == '') {
                    this.errmsg = 'Enter case no and click to search';
                    return;
                } else if (s == 'deed_no' && this.deed_no == '') {
                    this.errmsg = 'Enter deed no and click to search';
                    return;
                } else if (s == 'mauja_no' && this.mauja_no == '') {
                    this.errmsg = 'Enter mauja no and click to search';
                    return;
                } else if (s == 'plot_no' && this.plot_no == '') {
                    this.errmsg = 'Enter plot no and click to search';
                    return;
                }
                this.searching = 1;
                api_call('search-mutation', {
                    search_by: this.search_by,
                    deed_no: this.deed_no,
                    case_no: this.case_no,
                }).then(resp => {
                    console.log(resp);
                    if (resp.success) {
                        this.rowItem = resp.data;
                        this.searching = 3;
                    } else {
                        this.searching = 2;
                    }
                })
            }
        }
    });
</script>