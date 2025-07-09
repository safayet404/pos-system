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
                                            ? "Update Category"
                                            : "Create Customer"
                                    }}
                                </h4>
                                <input
                                    v-if="id !== 0"
                                    id="id"
                                    name="id"
                                    v-model="form.id"
                                    placeholder="Category ID"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="name"
                                    v-model="form.name"
                                    placeholder="Customer Name"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="email"
                                    v-model="form.email"
                                    placeholder="Customer Email"
                                    class="form-control"
                                    type="email"
                                />
                                <br />
                                <input
                                    id="name"
                                    name="mobile"
                                    v-model="form.mobile"
                                    placeholder="Customer Mobile"
                                    class="form-control"
                                    type="text"
                                />
                                <br />
                                <button
                                    type="submit"
                                    class="btn w-100 btn-success"
                                >
                                    {{
                                        id !== 0
                                            ? "Update Customer"
                                            : "Add Customer"
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
let URL = "/create-customer";
let list = page.props.list;

if (id.value !== 0 && list !== null) {
    URL = "/update-customer";
}

const form = useForm({
    name: list?.name || "",
    mobile: list?.mobile || "",
    email: list?.email || "",
    id: id,
});

function submit() {
    form.post(URL, {
        onSuccess: () => {
            if (id.value !== 0) {
                toaster.success("Customer Updated");

                router.visit("/CustomerPage");
            } else {
                toaster.success("Customer Added");
                form.reset();
            }
        },
    });
}
</script>
