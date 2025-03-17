<?php
include '../../config/autoload.php';
if (!is_admin_login()) redirect(admin_url('index.php'), 'You must login to continue');

$users = $db->select('ai_admin')->result();

$menu = 'settings';
include '../common/header.php';
?>
<div id="origin">
    <div class="page-header">
        <h5>Manage Users</h5>
        <button @click="addUser" class="btn btn-primary"> Add New User</button>
    </div>
    <div v-if="errmsg.length>0" class="alert" :class="errcls">{{errmsg}}</div>
    <div v-if="action=='add' || action == 'edit'" class="card mb-3">
        <div class="card-header">User Details</div>
        <div class="row">
            <div class="col-sm-9">
                <div class="p-3">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-6">
                            <input type="text" v-model="user.username" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-6">
                            <input type="text" v-model="user.first_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Email Id</label>
                        <div class="col-sm-6">
                            <input type="text" v-model="user.email_id" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-6">
                            <input type="password" v-model="user.password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Role</label>
                        <div class="col-sm-6">
                            <select v-model="user.role" class="form-select">
                                <option value="A">Super Admin</option>
                                <option value="E">Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-6">
                            <select v-model="user.status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">De-Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 control-label"> </label>
                        <div class="col-sm-6">
                            <button @click="saveUser" :disabled="saving" class="btn btn-primary">{{ saving ? 'Please wait...' : 'Save' }}</button>
                            <button @click="action='view'" class="btn btn-dark">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="action=='view'" class="card mb-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Full name</th>
                    <th>Email Id</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,sl) in items">
                    <td>{{ sl+1 }}</td>
                    <td>{{ item.first_name  }} </td>
                    <td>{{ item.email_id }} </td>
                    <td>{{ item.username }}</td>
                    <td>{{ item.password }}</td>
                    <td>{{ item.role }}</td>
                    <td>
                        <button @click="editUser(item)" class="btn btn-xs btn-primary">Edit</button>
                        <button @click="delUser(item.id)" class="btn btn-xs btn-danger">Delete</button>
                        <button v-if="item.id>1" @click="manageRole(item)" class="btn btn-xs btn-warning">Permissions</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="role_view" class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header"><b>{{ user.first_name }} </b></div>
                <table class="table m-0">
                    <tbody>
                        <tr>
                            <td>Login Id</td>
                            <td>{{ user.username }}</td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>{{ user.password }}</td>
                        </tr>
                        <tr>
                            <td>Role</td>
                            <td>{{ user.role == 'A' ? 'Super Admin' : 'Staff' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header"><b>Permissions</b></div>
                <table class="table m-0">
                    <thead>
                        <tr>
                            <th>Modules</th>
                            <th>Permissions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in user_modules">
                            <td>{{ item.label }}</td>
                            <td>
                                <div class="d-flex gap-3">
                                    <label v-for="ab in item.actions">
                                        <input type="checkbox" v-model="ab.is_selected" :checked="ab.is_selected" /> {{ ab.label }}
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-footer">
                    <button @click="updateRole" :disabled="updating" class="btn btn-primary">{{ updating ? 'Please Wait...' : 'Update' }}</button>
                    <span v-if="permission_updated" class="text-success">Permission updated !!</span>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include "../common/footer.php";
?>
<script>
    var appurl = '<?= admin_url('settings/api/') ?>';
    let vm = new Vue({
        el: '#origin',
        data: {
            items: [],
            users: [],
            loading: false,
            action: 'view',
            button: 'Save',
            user: {
                username: '',
                first_name: '',
                email_id: '',
                password: '',
                id: '',
                role: '',
                status: ''
            },
            errmsg: '',
            errcls: '',
            saving: false,
            role_view: false,
            modules: [{
                    module: 'users',
                    label: 'User'
                },
                {
                    module: 'sites',
                    label: 'Properties'
                },
                {
                    module: 'bhumi-locker',
                    label: 'Bhumi Locker'
                },
                {
                    module: 'payments',
                    label: 'Payments'
                },
                {
                    module: 'mutations',
                    label: 'Mutation Services'
                },
                {
                    module: 'media',
                    label: 'Media Manager'
                },
                {
                    module: 'reports',
                    label: 'Reports'
                },
                {
                    module: 'settings',
                    label: 'Settings'
                },

            ],
            permissions: [{
                    label: 'View',
                    action: 'view',
                    is_selected: false
                },
                {
                    label: 'Create',
                    action: 'add',
                    is_selected: false
                },
                {
                    label: 'Edit',
                    action: 'edit',
                    is_selected: false
                },
                {
                    label: 'Delete',
                    action: 'delete',
                    is_selected: false
                },
            ],
            updating: false,
            user_modules: [],
            permission_updated: false
        },
        methods: {
            loadUsers: function() {
                api_call('admin-users').then(resp => {
                    if (resp.success) {
                        this.items = resp.data;
                    }
                })
            },
            saveUser: function() {
                this.errmsg = '';
                if (this.user.username == '' || this.user.password == '' || this.user.role == '') {
                    this.errcls = 'alert-danger'
                    this.errmsg = 'Please fill required fields';
                    return;
                }
                this.saving = true;
                api_call('save-admin-user', {
                    username: this.user.username,
                    first_name: this.user.first_name,
                    email_id: this.user.email_id,
                    password: this.user.password,
                    action: this.action,
                    role: this.user.role,
                    status: this.user.status,
                    permissions: this.getPermission(),
                    id: this.user.id
                }).then(resp => {
                    this.errmsg = resp.message;
                    if (resp.success) {
                        this.errcls = 'alert-success'
                        this.action = 'view';
                        this.loadUsers();
                    } else {
                        this.errcls = 'alert-danger'
                    }
                    this.saving = false;
                })
            },
            editUser: function(item) {
                this.user = item;
                this.action = 'edit',
                    this.role_view = false
            },
            addUser: function(item) {
                this.user = {
                    username: '',
                    first_name: '',
                    email_id: '',
                    password: '',
                    id: '',
                    role: '',
                    status: ''
                };
                this.action = 'add',
                    this.role_view = false
            },
            delUser: function(id) {
                if (confirm('Are you sure to Delete?')) {
                    api_call('del-admin-user', {
                        id: id
                    }).then(resp => {
                        this.errmsg = resp.message;
                        if (resp.success) {
                            this.errcls = 'alert-success'
                            this.loadUsers();
                        } else {
                            this.errcls = 'alert-danger';
                        }
                    })
                }
            },
            manageRole: function(item) {
                this.user = item;
                this.role_view = true;
                this.updating = false;
                this.user_modules = JSON.parse(item.permissions);
            },
            updateRole: function() {
                this.permission_updated = false
                api_call('update-permission', {
                    id: this.user.id,
                    permissions: JSON.stringify(this.user_modules)
                }).then(resp => {
                    this.updating = false;
                    this.permission_updated = true;
                })
            },
            getPermission: function() {
                let actarr = [];
                this.modules.forEach(item => {
                    item.actions = this.permissions;
                    actarr.push(item);
                })
                return JSON.stringify(actarr);
            }
        },
        created: function() {
            this.loadUsers();

        }
    });
</script>