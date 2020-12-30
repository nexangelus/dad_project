<template>
    <div v-bind:class="{'jumbotron': !isNew}">
        <h4 v-if="!isNew">Change Details</h4>
        <form class="">
            <div class="row">
                <div class="col-md-2">
                    <img class="img-fluid" :src="user.photo_url" alt="">
                    <label v-if="isEditing" for="photo"><small>Select a Photo</small></label>
                    <input type="file" id="photo" v-on:change="fileChanged" accept="image/*" style="display: none;">
                </div>
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.name}" v-model="user.name">
                        <span v-if="errors.name" class="invalid-feedback">{{errors.name[0]}}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.email}" v-model="user.email">
                        <span v-if="errors.email" class="invalid-feedback">{{errors.email[0]}}</span>
                    </div>
                </div>
            </div>

            <div class="form-group" v-if="isNew && isEditing">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" v-bind:class="{'is-invalid': errors.password}" v-model="user.password">
                <span v-if="errors.password" class="invalid-feedback">{{errors.password[0]}}</span>
            </div>
            <template v-if="user.type === 'C'">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.address}" v-model="user.address">
                    <span v-if="errors.address" class="invalid-feedback">{{errors.address[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.phone}" v-model="user.phone">
                    <span v-if="errors.phone" class="invalid-feedback">{{errors.phone[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="nif">NIF:</label>
                    <input type="text" id="nif" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.nif}" v-model="user.nif">
                    <span v-if="errors.nif" class="invalid-feedback">{{errors.nif[0]}}</span>
                </div>
            </template>
            <div class="form-group" v-if="isEditing">
                <b-button @click="$router.go(-1)"><font-awesome-icon icon="arrow-left"/> Cancel</b-button>
                <b-button variant="primary" @click.prevent="save"><font-awesome-icon icon="save"/> {{isNew ? "Register" : "Save"}}</b-button>
            </div>
            <div v-else>
                <b-button @click="$router.go(-1)"><font-awesome-icon icon="arrow-left"/> Go Back</b-button>
                <b-button variant="primary" :to="{name: 'users-edit', params: {id: user.id}}"><font-awesome-icon icon="pen" /> Edit User</b-button>
                <b-button variant="danger" @click="actions(`block-${1 - user.blocked}`)"><font-awesome-icon icon="ban" />
                    {{ user.blocked ? "Unblock" : "Block" }} User</b-button>
                <b-button variant="danger" @click="actions('delete')" v-if="!user.deleted_at"><font-awesome-icon icon="trash"/> Delete User</b-button>
            </div>
        </form>
        <div v-if="!isNew && isEditing">
            <hr>
            <h4>Change Password</h4>
            <form class="">
                <div class="form-group">
                    <label for="oldpassword">Old Password:</label>
                    <input type="password" id="oldpassword" class="form-control" v-bind:class="{'is-invalid': errorsP.old}" v-model="changeP.old">
                    <span v-if="errorsP.old" class="invalid-feedback">{{errorsP.old[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="newpassword1">New Password:</label>
                    <input type="password" id="newpassword1" class="form-control" v-bind:class="{'is-invalid': errorsP.new}" v-model="changeP.new1">
                    <span v-if="errorsP.new" class="invalid-feedback">{{errorsP.new[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="newpassword2">New Password Confirmation:</label>
                    <input type="password" id="newpassword2" class="form-control" v-bind:class="{'is-invalid': errorsP.new}" v-model="changeP.new2">
                    <span v-if="errorsP.new" class="invalid-feedback">{{errorsP.new[0]}}</span>
                </div>
                <div class="form-group">
                    <a class="btn btn-primary" v-on:click.prevent="changePassword">Change Password</a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: "edit",
    data: function () {
        return {
            file: null,
            changeP: {
                old: "",
                new1: "",
                new2: "",
            },
            errorsP: {}
        }
    },
    props: {
        user: {
            type: Object,
            default: () => {
                return {type:'C', photo_url:''};
            }
        },
        errors: {
            type: Object,
            required: true,
            default: () => {
                return {};
            }
        },
        isEditing: {
            type: Boolean,
            default: true,
        }
    },
    computed: {
        fileName() {
            return this.file != null ? this.file.name : "Choose a file";
        },
        isNew() {
            return !this.user.id
        }
    },
    methods: {
        save() { // TODO client side form validation
            delete this.user.photo_url;
            this.$emit("finish")
        },
        fileChanged(e) {
            this.file = e.target.files[0];
            const reader = new FileReader();
            reader.onload = (ev => {
                this.user.photo_url = ev.target.result;
            })
            reader.readAsDataURL(this.file);
        },
        changePassword() {
            axios.put('/api/users/password', this.changeP).then(r => {
                Vue.toasted.success('Password changed successfully!')
                this.errorsP = {};
                this.changeP = {};
            }).catch(r => {
                this.errorsP = r.response.data.errors;
            })
        },
        actions(type) {
            alert(type);
        }
    }
}
</script>

<style scoped>

</style>
