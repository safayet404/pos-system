<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();
const page = usePage();
const user = page.props.user || {};

const form = useForm({
    name: user?.name || "",
    email: user?.email || "",
    mobile: user?.mobile || "",
    role: user?.role || "",
    password: "",
});

let URL = "/user-update";

if (user?.role === "employee") {
    URL = "/employee-update";
}

function submit() {
    form.post(URL, {
        onSuccess: () => {
            toaster.success("Profile Updated");
        },
        onError: () => {
            toaster.error("Something Went Wrong");
        },
    });
}
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card animated fadeIn w-100 p-3">
                    <form @submit.prevent="submit">
                        <div class="card-body">
                            <h4>User Profile {{ user?.role }}</h4>
                            <hr />
                            <div class="container-fluid m-0 p-0">
                                <div class="row m-0 p-0">
                                    <div class="col-md-12 p-2">
                                        <label>Name</label>
                                        <input
                                            id="name"
                                            v-model="form.name"
                                            placeholder="Name"
                                            class="form-control"
                                            type="text"
                                        />
                                    </div>
                                    <div
                                        v-if="form.role === 'employee'"
                                        class="col-md-12 p-2"
                                    >
                                        <label>Email</label>
                                        <input
                                            id="name"
                                            v-model="form.email"
                                            placeholder="Name"
                                            class="form-control"
                                            type="text"
                                        />
                                    </div>

                                    <div class="col-md-6 p-2">
                                        <label>Mobile Number</label>
                                        <input
                                            id="mobile"
                                            v-model="form.mobile"
                                            placeholder="Mobile"
                                            class="form-control"
                                            type="mobile"
                                        />
                                    </div>
                                    <div class="col-md-12 p-2">
                                        <label>Password</label>
                                        <input
                                            id="password"
                                            v-model="form.password"
                                            placeholder="Leave blank to keep current password"
                                            class="form-control"
                                            type="password"
                                        />
                                    </div>
                                </div>
                                <div class="row m-0 p-0">
                                    <div class="col-md-4 p-2">
                                        <button
                                            type="submit"
                                            class="btn mt-3 text-white w-100 btn-dark"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.btn-bg {
    background-color: #001529;
}

.btn-bg:hover {
    background-color: #001529;
}
</style>
