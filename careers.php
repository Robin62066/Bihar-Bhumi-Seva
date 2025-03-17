<?php
include "./config/autoload.php";

include './common/header.php';
?>
<div id="origin" class="container py-5">
    <div class="page-header">
        <h5>Careers</h5>
    </div>
    <div class="card p-4 rounded-1 mb-2">
        <h5>Search Job</h5>
        <div class="d-flex gap-2">
            <input type="search" v-model="city" placeholder="e.g. Field Executive, Developer, Contractor etc" class="form-control">
            <button @click="doSearch" :disabled="loading" class="btn btn-primary">{{ loading ? 'Search...' : 'Search'}}</button>
        </div>
    </div>
    <div class="d-flex gap-2 text-white mb-2">
        <button @click="city='gaya'" class="btn bg-info">Gaya</button>
        <button @click="city='patna'" class="btn bg-info">Patna</button>
        <button @click="city='munger'" class="btn bg-info">Munger</button>
        <button @click="city='bhagalpur'" class="btn bg-info">Bhagalpur</button>
    </div>
    <div v-if="loading" class="card p-4 rounded-1">
        <p class="m-0">Loading...</p>
    </div>

    <div class=" bg-secondary-subtle text-center p-4 ">
        <h4>करियर - बिहार भूमि सेवा </h4>
        <p class="pb-2">यहाँ सभी श्रेणी के कर्मचारियों के लिए नौकरी के अवसर उपलब्ध ह। </p>
        <div class="bg-white p-2 border-2">

            <div class="container text-center">
                <h5>खाली पद </h5>
                <div class="p-5 mt-5">
                    <div class="row">
                        <div class="col">
                            <h5>1. Land Advocate</h5>
                            <p>भूमि अधिवक्ता</p>
                        </div>
                        <div class="col">
                            <h5>2. Data Entry Operator</h5>
                            <p>डेटा एन्ट्री ऑपरेटर</p>
                        </div>
                        <div class="col">
                            <h5>3. Project Coordinator</h5>
                            <p>प्रोजेक्ट कोऑर्डिनेटर</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <h5 class="p-2">अपना बायोडाटा अपलोड करें</h5>
        <button class="btn btn-success"><a href="upload-resume.php" style="text-decoration: none; color:white;">अभी आवेदन करे</a> </button>

    </div>
</div>
<?php
include './common/footer.php';
?>
<script>
    let vm = new Vue({
        el: '#origin',
        data: {
            city: '',
            loading: false,
            items: []
        },
        methods: {
            doSearch: function() {
                this.loading = true;
            }
        }
    })
</script>