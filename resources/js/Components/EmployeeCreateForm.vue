<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="card-body">
                                <h4>
                                    {{
                                        id !== 0
                                            ? "Update Customer"
                                            : "Create Customer"
                                    }}
                                </h4>
                                <input
                                    v-if="id !== 0"
                                    id="id"
                                    name="id"
                                    v-model="id"
                                    placeholder="Employee ID"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    placeholder="Employee Name"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="email"
                                    v-model="form.email"
                                    placeholder="Employee Email"
                                    class="form-control"
                                    type="email"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="mobile"
                                    v-model="form.mobile"
                                    placeholder="Employee Mobile"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="password"
                                    name="password"
                                    v-model="form.password"
                                    placeholder="Employee Passowrd"
                                    class="form-control"
                                    type="password"
                                />
                                <br />
                                <button
                                    type="submit"
                                    class="btn w-100 btn-dark"
                                >
                                    {{
                                        id !== 0
                                            ? "Update Employee"
                                            : "Add Employee"
                                    }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster();

import { ref } from "vue";

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get("id") || 0));
const page = usePage();
let URL = "/employee-register";
let list = page.props.list;

if (id.value !== 0 && list !== null) {
    URL = "/employee-update";
}

const form = useForm({
    name: list?.name || "",
    mobile: list?.mobile || "",
    email: list?.email || "",
    password: "",
});

function submit() {
    form.post(URL, {
        headers: {
            "employee-id": id?.value,
        },
        onSuccess: () => {
            if (id.value !== 0) {
                toaster.success("Employee Updated");
                router.visit("/employee-page");
            } else {
                toaster.success("Employee Added");
                form.reset();
            }
        },
        onError: () => {
            toaster.error("Something Went Wrong");
        },
    });
}
</script>
